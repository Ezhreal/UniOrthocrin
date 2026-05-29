<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChunkUpload;
use App\Services\ChunkMergeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use App\Traits\HandlesChunkUploads;

class ChunkUploadController extends Controller
{
    use HandlesChunkUploads;

    protected $mergeService;

    // Injeta o serviço de mesclagem para desacoplar a lógica física do fluxo do controller
    public function __construct(ChunkMergeService $mergeService)
    {
        $this->mergeService = $mergeService;
    }

    /**
     * Recebe um chunk individual do frontend Uppy e salva temporariamente.
     */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        // Uppy envia os cabeçalhos de identificação do chunk
        $uuid = $request->header('X-Unique-Upload-Id');
        $chunkIndex = $request->header('X-Chunk-Index');
        $totalChunks = $request->header('X-Total-Chunks');
        
        // Metadados do arquivo original (geralmente enviados na primeira requisição ou injetados)
        $filename = $request->input('filename') ?? $request->header('X-File-Name') ?? 'file';
        $totalSize = $request->input('total_size') ?? $request->header('X-Total-Size') ?? 0;
        
        if (!$uuid || $chunkIndex === null || !$totalChunks) {
            return response()->json([
                'success' => false,
                'message' => 'Parâmetros de chunking ausentes nos cabeçalhos (X-Unique-Upload-Id, X-Chunk-Index, X-Total-Chunks).'
            ], 400);
        }

        $chunkIndex = (int) $chunkIndex;
        $totalChunks = (int) $totalChunks;

        // Se o arquivo original não tiver nome no header, tenta pegar do arquivo enviado
        $uploadedFile = $request->file('file');
        if ($filename === 'file') {
            $filename = $uploadedFile->getClientOriginalName();
        }
        if ($totalSize == 0) {
            $totalSize = $uploadedFile->getSize();
        }

        // Diretório temporário dos chunks deste arquivo
        $chunksDir = storage_path('app/chunks/' . $uuid);
        if (!File::exists($chunksDir)) {
            File::makeDirectory($chunksDir, 0755, true);
        }

        // Mover o chunk enviado para o destino temporário
        $uploadedFile->move($chunksDir, $chunkIndex);

        $mimeType = $request->header('X-File-Type') ?? $uploadedFile->getClientMimeType();
        
        // Se o mime_type for genérico ou vazio, tenta determinar pela extensão do arquivo
        if (!$mimeType || $mimeType === 'application/octet-stream' || $mimeType === 'binary/oct-stream') {
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $mimeTypes = [
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'ogg' => 'video/ogg',
                'webm' => 'video/webm',
                'avi' => 'video/x-msvideo',
                'mkv' => 'video/x-matroska',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'mp3' => 'audio/mpeg',
                'wav' => 'audio/wav',
            ];
            $mimeType = $mimeTypes[$extension] ?? $mimeType;
        }

        $modelType = $request->header('X-Model-Type');
        $modelId = $request->header('X-Model-Id');
        $property = $request->header('X-Property');

        // Registra o upload imediatamente no banco de dados para rastreamento prévio do formulário
        $chunkUpload = ChunkUpload::firstOrCreate(
            ['uuid' => $uuid],
            [
                'filename' => $filename,
                'mime_type' => $mimeType,
                'total_size' => (int) $totalSize,
                'total_chunks' => $totalChunks,
                'uploaded_chunks' => 0,
                'status' => 'uploading',
                'upload_status' => 'uploading',
                'upload_progress' => 0,
                'original_name' => $filename,
                'file_size' => (int) $totalSize,
                'model_type' => $modelType ?: null,
                'model_id' => $modelId ?: null,
                'property' => $property ?: null,
            ]
        );

        if ($modelType && $modelId && $property) {
            $chunkUpload->update([
                'model_type' => $modelType,
                'model_id' => $modelId,
                'property' => $property,
            ]);

            try {
                $modelClass = $modelType;
                if (class_exists($modelClass)) {
                    $model = $modelClass::find($modelId);
                    if ($model) {
                        $cleanProperty = str_ends_with($property, '[]') ? substr($property, 0, -2) : $property;
                        $this->createPendingFileAttachment($chunkUpload, $model, $cleanProperty);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao registrar anexo temporario/pendente: ' . $e->getMessage());
            }
        }

        // Conta os chunks reais salvos no disco
        $actualChunksCount = 0;
        $hasAllChunks = true;
        if (File::exists($chunksDir)) {
            for ($i = 0; $i < $totalChunks; $i++) {
                if (File::exists($chunksDir . '/' . $i)) {
                    $actualChunksCount++;
                } else {
                    $hasAllChunks = false;
                }
            }
        } else {
            $hasAllChunks = false;
        }

        // Atualiza a coluna de progresso e contagem de chunks de forma confiável
        $progress = min(100, (int) round(($actualChunksCount / $totalChunks) * 100));
        $chunkUpload->update([
            'uploaded_chunks' => $actualChunksCount,
            'upload_progress' => $progress
        ]);

        // Se todos os chunks foram recebidos e gravados no disco, inicia a remontagem (merge)
        if ($hasAllChunks) {
            try {
                // Atualiza status para merging a fim de prevenir disparos redundantes
                $chunkUpload->update([
                    'status' => 'merging',
                    'upload_status' => 'merging',
                    'upload_progress' => 99
                ]);
                
                // Delega ao serviço a mesclagem de chunks e limpeza de arquivos antigos
                $mergedPath = $this->mergeService->mergeChunks($uuid, $filename, $totalChunks);
                
                // Completa o upload de chunks e define o local físico do arquivo finalizado
                $chunkUpload->update([
                    'status' => 'completed',
                    'upload_status' => 'completed',
                    'upload_progress' => 100,
                    'local_path' => $mergedPath
                ]);

                // Tenta processar o anexo se o modelo já tiver sido associado na request
                $chunkUpload->processAttachment();

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'upload_status' => 'completed',
                    'uuid' => $uuid,
                    'filename' => $filename,
                    'local_path' => $mergedPath,
                    'size' => $totalSize,
                    'upload_progress' => 100
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao remontar chunks: ' . $e->getMessage(), [
                    'uuid' => $uuid,
                    'exception' => $e
                ]);

                // Atualiza o registro com o erro correspondente para exibição no painel
                $chunkUpload->update([
                    'status' => 'failed',
                    'upload_status' => 'error',
                    'error_message' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'status' => 'failed',
                    'upload_status' => 'error',
                    'message' => 'Falha ao remontar o arquivo: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'status' => 'uploading',
            'upload_status' => 'uploading',
            'uuid' => $uuid,
            'chunk_index' => $chunkIndex,
            'uploaded_chunks' => $chunkUpload->uploaded_chunks,
            'upload_progress' => $progress
        ]);
    }

    /**
     * Exibe a interface do Gerenciador de Uploads (Upload Manager) com a tabela dos arquivos.
     */
    public function showUploadManager()
    {
        // Pagina os uploads de chunks em ordem cronológica reversa para auditoria fácil
        $uploads = ChunkUpload::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.upload-manager', compact('uploads'));
    }

    /**
     * Retorna o status atual de um upload para atualização de badge no frontend.
     */
    public function getUploadStatus($uuid)
    {
        // Busca o registro ou retorna erro se não existir
        $upload = ChunkUpload::where('uuid', $uuid)->first();
        
        if (!$upload) {
            // Se o chunk_upload foi apagado, verifica se o registro de File correspondente já virou ready
            $file = \App\Models\File::where('chunk_upload_uuid', $uuid)->first();
            if ($file && $file->status === 'ready') {
                return response()->json([
                    'success' => true,
                    'uuid' => $uuid,
                    'status' => 'completed',
                    'upload_status' => 'completed',
                    'upload_progress' => 100,
                    'original_name' => $file->name,
                    'file_size' => $file->size
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Upload não encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'uuid' => $uuid,
            'status' => $upload->status,
            'upload_status' => $upload->upload_status,
            'upload_progress' => $upload->upload_progress,
            'original_name' => $upload->original_name ?? $upload->filename,
            'file_size' => $upload->file_size ?? $upload->total_size,
            'error_message' => $upload->error_message
        ]);
    }

    /**
     * Executa a sincronização manual de um upload específico.
     *
     * Lida com três cenários:
     *  1. Arquivo já mergeado (local_path existe no disco) mas ainda não vinculado ao modelo.
     *  2. Todos os chunks no disco → executa o merge e vincula ao modelo.
     *  3. Chunks ausentes e sem arquivo mergeado → upload corrompido, incrementa tentativas.
     *
     * O sync manual aceita também status 'error'/'failed' para permitir re-tentativa via painel.
     */
    public function sync($uuid)
    {
        // Carrega o registro para processamento individual
        $upload = ChunkUpload::where('uuid', $uuid)->first();

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload não encontrado.',
            ], 404);
        }

        // Sync manual aceita uploading, merging e também error/failed (permite retry pelo painel)
        $allowedStatuses = ['uploading', 'merging', 'error', 'failed'];
        if (!in_array($upload->upload_status, $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => "Este upload está com status '{$upload->upload_status}' e não pode ser sincronizado.",
            ]);
        }

        $chunksDir = storage_path('app/chunks/' . $upload->uuid);

        // Conta e verifica os chunks físicos no disco para diagnóstico
        $hasAllChunks   = false;
        $foundChunks    = 0;
        $missingChunks  = [];
        $chunksExist    = File::exists($chunksDir);

        if ($chunksExist) {
            for ($i = 0; $i < $upload->total_chunks; $i++) {
                if (File::exists($chunksDir . '/' . $i)) {
                    $foundChunks++;
                } else {
                    $missingChunks[] = $i;
                }
            }
            $hasAllChunks = empty($missingChunks);
        }

        // Log de diagnóstico completo — sempre registrado para facilitar debug
        Log::info('[ChunkUpload] Sync iniciado', [
            'uuid'          => $uuid,
            'upload_status' => $upload->upload_status,
            'local_path'    => $upload->local_path,
            'local_exists'  => $upload->local_path ? file_exists($upload->local_path) : false,
            'chunks_dir'    => $chunksDir,
            'chunks_dir_exists' => $chunksExist,
            'total_chunks'  => $upload->total_chunks,
            'found_chunks'  => $foundChunks,
            'missing_chunks' => $missingChunks,
            'has_all_chunks' => $hasAllChunks,
            'model_type'    => $upload->model_type,
            'model_id'      => $upload->model_id,
            'property'      => $upload->property,
            'attempts'      => $upload->attempts,
        ]);

        try {
            // ── Cenário 1: arquivo já mergeado mas processAttachment não concluiu ──
            // Acontece quando local_path existe no disco mas chunks já foram limpos.
            // Ex: processAttachment falhou silenciosamente ou model_id não estava disponível.
            if (!$hasAllChunks && $upload->local_path && file_exists($upload->local_path)) {
                Log::info('[ChunkUpload] Sync Cenário 1: arquivo mergeado encontrado, tentando vincular ao modelo.', [
                    'uuid'       => $uuid,
                    'local_path' => $upload->local_path,
                ]);

                // Garante status completed para que processAttachment aceite o registro
                $upload->update([
                    'status'          => 'completed',
                    'upload_status'   => 'completed',
                    'upload_progress' => 100,
                    'attempts'        => 0,
                    'error_message'   => null,
                ]);

                $attached = $upload->processAttachment();

                return response()->json([
                    'success' => true,
                    'message' => 'Arquivo já mergeado.' . ($attached
                        ? ' Vinculado ao modelo com sucesso.'
                        : ' Vínculo ao modelo falhou ou não havia modelo associado — verifique os logs.'),
                ]);
            }

            // ── Cenário 2: todos os chunks estão no disco → fazer o merge agora ──
            if ($hasAllChunks) {
                Log::info('[ChunkUpload] Sync Cenário 2: todos os chunks presentes, iniciando merge.', [
                    'uuid'         => $uuid,
                    'total_chunks' => $upload->total_chunks,
                ]);

                $upload->update([
                    'status'          => 'merging',
                    'upload_status'   => 'merging',
                    'upload_progress' => 99,
                    'attempts'        => 0,
                    'error_message'   => null,
                ]);

                $mergedPath = $this->mergeService->mergeChunks($upload->uuid, $upload->filename, $upload->total_chunks);

                $upload->update([
                    'status'          => 'completed',
                    'upload_status'   => 'completed',
                    'upload_progress' => 100,
                    'local_path'      => $mergedPath,
                ]);

                $attached = $upload->processAttachment();

                return response()->json([
                    'success' => true,
                    'message' => 'Upload sincronizado e mesclado com sucesso.' . ($attached ? ' Vinculado ao modelo.' : ''),
                ]);
            }

            // ── Cenário 3: chunks ausentes e arquivo não mergeado → upload corrompido ──
            Log::warning('[ChunkUpload] Sync Cenário 3: chunks ausentes e local_path não encontrado.', [
                'uuid'          => $uuid,
                'local_path'    => $upload->local_path,
                'local_exists'  => $upload->local_path ? file_exists($upload->local_path) : false,
                'missing_chunks' => $missingChunks,
                'found_chunks'  => $foundChunks,
                'total_chunks'  => $upload->total_chunks,
            ]);

            $upload->increment('attempts');

            if ($upload->attempts >= 3) {
                $upload->update([
                    'status'        => 'failed',
                    'upload_status' => 'error',
                    'error_message' => "Chunks ausentes ({$foundChunks}/{$upload->total_chunks}) e arquivo mergeado não localizado. Limite de tentativas atingido.",
                ]);

                return response()->json([
                    'success' => false,
                    'message' => "Sincronização falhou: chunks ausentes ({$foundChunks}/{$upload->total_chunks}) e arquivo mergeado não encontrado. Verifique os logs.",
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Chunks ausentes ({$foundChunks}/{$upload->total_chunks}) e arquivo mergeado não localizado. Tentativa {$upload->attempts} de 3.",
            ]);

        } catch (\Exception $e) {
            Log::error("[ChunkUpload] Sync erro inesperado no upload {$uuid}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $upload->update([
                'status'        => 'failed',
                'upload_status' => 'error',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Falha ao processar sincronização: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Executa a sincronização de todos os uploads pendentes de uma vez.
     */
    public function syncAll()
    {
        // Busca todos os registros ativos que podem ser sincronizados
        $pendingUploads = ChunkUpload::whereIn('upload_status', ['uploading', 'merging'])->get();
        
        if ($pendingUploads->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Nenhum upload pendente para sincronizar.'
            ]);
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($pendingUploads as $upload) {
            $chunksDir = storage_path('app/chunks/' . $upload->uuid);
            
            $hasAllChunks = true;
            if (File::exists($chunksDir)) {
                for ($i = 0; $i < $upload->total_chunks; $i++) {
                    if (!File::exists($chunksDir . '/' . $i)) {
                        $hasAllChunks = false;
                        break;
                    }
                }
            } else {
                $hasAllChunks = false;
            }

            if ($hasAllChunks) {
                try {
                    $upload->update([
                        'status' => 'merging',
                        'upload_status' => 'merging',
                        'upload_progress' => 99
                    ]);

                    $mergedPath = $this->mergeService->mergeChunks($upload->uuid, $upload->filename, $upload->total_chunks);

                    $upload->update([
                        'status' => 'completed',
                        'upload_status' => 'completed',
                        'upload_progress' => 100,
                        'local_path' => $mergedPath
                    ]);

                    $upload->processAttachment();
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Erro na sincronização automática em lote do upload {$upload->uuid}: " . $e->getMessage());
                    
                    $upload->update([
                        'status' => 'failed',
                        'upload_status' => 'error',
                        'error_message' => $e->getMessage()
                    ]);
                    $failedCount++;
                }
            } else {
                $upload->increment('attempts');
                if ($upload->attempts >= 3) {
                    $upload->update([
                        'status' => 'failed',
                        'upload_status' => 'error',
                        'error_message' => 'Sincronização falhou: chunks ausentes após 3 tentativas.'
                    ]);
                    $failedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Sincronização em lote finalizada. Sucessos: {$successCount}, Falhas: {$failedCount}."
        ]);
    }

    /**
     * Limpa todos os registros de upload e remove os diretórios temporários do disco.
     */
    public function clearAll()
    {
        try {
            // Remove os diretórios físicos
            $chunksDir = storage_path('app/chunks');
            if (File::exists($chunksDir)) {
                File::deleteDirectory($chunksDir);
                File::makeDirectory($chunksDir, 0755, true);
            }

            $tempDir = storage_path('app/temp');
            if (File::exists($tempDir)) {
                $files = File::files($tempDir);
                foreach ($files as $file) {
                    @unlink($file->getRealPath());
                }
            }
            
            $ftpEmulatorDir = storage_path('app/emulator/ftp');
            if (File::exists($ftpEmulatorDir)) {
                File::deleteDirectory($ftpEmulatorDir);
                File::makeDirectory($ftpEmulatorDir, 0755, true);
            }

            // Limpa as tabelas do banco de dados
            ChunkUpload::query()->delete();
            \App\Models\FtpSync::query()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todos os registros de uploads, sincronizações e arquivos temporários foram excluídos com sucesso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar uploads: ' . $e->getMessage()
            ], 500);
        }
    }
}
