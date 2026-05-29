<?php

namespace App\Console\Commands;

use App\Models\ChunkUpload;
use App\Services\ChunkMergeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncChunkUploadsCommand extends Command
{
    /**
     * O nome e assinatura do comando console para execução no crontab.
     */
    protected $signature = 'uploads:sync';

    /**
     * A descrição legível do comando no terminal.
     */
    protected $description = 'Sincroniza uploads segmentados pendentes mesclando os blocos caso estejam todos no servidor';

    /**
     * Executa as ações principais do comando Artisan.
     */
    public function handle(ChunkMergeService $mergeService): int
    {
        // Obtém apenas uploads que ainda estão com o fluxo de envio ativo no banco
        $uploads = ChunkUpload::whereIn('upload_status', ['uploading', 'merging'])->get();

        if ($uploads->isEmpty()) {
            return 0;
        }

        foreach ($uploads as $upload) {
            $chunksDir = storage_path('app/chunks/' . $upload->uuid);
            
            $hasAllChunks = false;
            // Valida a existência física da pasta que armazena os blocos temporários
            if (File::exists($chunksDir)) {
                $files = File::files($chunksDir);
                // Compara a quantidade de arquivos físicos com o total exigido na requisição inicial
                $hasAllChunks = count($files) >= $upload->total_chunks;
            }

            if ($hasAllChunks) {
                try {
                    // Altera o estado do banco antes da operação demorada para evitar conflito com novas requests
                    $upload->update([
                        'status' => 'merging',
                        'upload_status' => 'merging',
                        'upload_progress' => 99
                    ]);

                    // Consolida os pedaços físicos em um arquivo unificado
                    $mergedPath = $mergeService->mergeChunks($upload->uuid, $upload->filename, $upload->total_chunks);

                    // Atualiza o estado local para finalizado para habilitar a rotina de anexo
                    $upload->update([
                        'status' => 'completed',
                        'upload_status' => 'completed',
                        'upload_progress' => 100,
                        'local_path' => $mergedPath
                    ]);

                    // Processa o anexo vinculando ao model do BD, salvando no disco privado e agendando o FTP
                    $upload->processAttachment();
                } catch (\Exception $e) {
                    // Registra o erro de processo para não interromper a fila de sincronização de outros registros
                    $upload->update([
                        'status' => 'failed',
                        'upload_status' => 'error',
                        'error_message' => $e->getMessage()
                    ]);
                }
            } else {
                // Incrementa o número de tentativas para controlar falhas por abandono ou timeouts do cliente
                $upload->increment('attempts');
                
                // Limita tentativas a 3 para evitar loop infinito em uploads cancelados pelo cliente
                if ($upload->attempts >= 3) {
                    $upload->update([
                        'status' => 'failed',
                        'upload_status' => 'error',
                        'error_message' => 'Sincronização falhou: chunks ausentes após 3 tentativas.'
                    ]);
                }
            }
        }

        return 0;
    }
}
