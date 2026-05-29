<?php

namespace App\Console\Commands;

use App\Models\ChunkUpload;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class CleanChunkUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean-chunks {--dry-run : Apenas exibe o que seria deletado}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa chunks temporários e arquivos de remontagem com mais de 24 horas para liberar espaço em disco';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $expirationTime = Carbon::now()->subHours(24);
        
        $this->info("🧹 Iniciando a limpeza de arquivos temporários de upload (mais de 24 horas)...");
        if ($dryRun) {
            $this->warn("⚠️  Modo DRY-RUN ativado. Nenhuma exclusão real será executada.");
        }

        $chunksCount = 0;
        $tempFilesCount = 0;
        
        // 1. Limpar diretório storage/app/chunks/
        $chunksPath = storage_path('app/chunks');
        if (File::exists($chunksPath)) {
            $directories = File::directories($chunksPath);
            foreach ($directories as $dir) {
                // Obter a data da última modificação do diretório
                $lastModified = Carbon::createFromTimestamp(File::lastModified($dir));
                
                if ($lastModified->lessThan($expirationTime)) {
                    $chunksCount++;
                    $dirName = basename($dir);
                    
                    if ($dryRun) {
                        $this->line("🔍 [CHUNKS] Seria deletado: {$dirName} (Modificado em: {$lastModified->toDateTimeString()})");
                    } else {
                        $this->line("🗑️ [CHUNKS] Deletando pasta: {$dirName}");
                        File::deleteDirectory($dir);
                        
                        // Atualizar banco de dados correspondente se existir
                        ChunkUpload::where('uuid', $dirName)
                            ->where('status', 'uploading')
                            ->update([
                                'status' => 'failed',
                                'error_message' => 'Upload expirado ou abandonado (deletado pelo sistema de limpeza).'
                            ]);
                    }
                }
            }
        }

        // 2. Limpar diretório storage/app/temp/ (arquivos remontados mas não deletados por algum motivo)
        $tempPath = storage_path('app/temp');
        if (File::exists($tempPath)) {
            $files = File::files($tempPath);
            foreach ($files as $file) {
                $lastModified = Carbon::createFromTimestamp(File::lastModified($file->getPathname()));
                
                if ($lastModified->lessThan($expirationTime)) {
                    $tempFilesCount++;
                    $fileName = $file->getFilename();
                    
                    if ($dryRun) {
                        $this->line("🔍 [TEMP] Seria deletado: {$fileName} (Modificado em: {$lastModified->toDateTimeString()})");
                    } else {
                        $this->line("🗑️ [TEMP] Deletando arquivo: {$fileName}");
                        File::delete($file->getPathname());
                        
                        // Extrai o UUID do nome do arquivo se estiver no formato {uuid}_{filename}
                        $parts = explode('_', $fileName, 2);
                        if (count($parts) > 1 && strlen($parts[0]) === 36) {
                            $uuid = $parts[0];
                            ChunkUpload::where('uuid', $uuid)
                                ->where('status', 'completed')
                                ->update([
                                    'status' => 'failed',
                                    'error_message' => 'Arquivo temporário limpo antes da sincronização final.'
                                ]);
                        }
                    }
                }
            }
        }

        $this->info("✅ Limpeza concluída!");
        $this->info("📊 Total de pastas de chunks removidas/detectadas: {$chunksCount}");
        $this->info("📊 Total de arquivos temporários de remontagem removidos/detectados: {$tempFilesCount}");

        return 0;
    }
}
