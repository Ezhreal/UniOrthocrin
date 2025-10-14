<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StorageMigrateCommand extends Command
{
    protected $signature = 'storage:migrate {--from=local} {--to=s3} {--dry-run}';
    protected $description = 'Migra arquivos entre diferentes drivers de storage';

    public function handle()
    {
        $from = $this->option('from');
        $to = $this->option('to');
        $dryRun = $this->option('dry-run');

        $this->info("🔄 Migrando arquivos de '$from' para '$to'...");

        if ($dryRun) {
            $this->warn("🔍 Modo DRY-RUN ativado - nenhum arquivo será movido");
        }

        $directories = [
            'campaigns',
            'products', 
            'training',
            'library',
            'news',
            'users'
        ];

        $totalFiles = 0;
        $totalSize = 0;

        foreach ($directories as $dir) {
            $this->info("📁 Processando diretório: $dir");
            
            $files = Storage::disk($from)->allFiles("private/$dir");
            
            if (empty($files)) {
                $this->line("  ⚠️  Nenhum arquivo encontrado");
                continue;
            }

            $dirFiles = 0;
            $dirSize = 0;

            foreach ($files as $file) {
                if ($dryRun) {
                    $size = Storage::disk($from)->size($file);
                    $dirSize += $size;
                    $dirFiles++;
                    $this->line("  📄 $file (" . $this->formatBytes($size) . ")");
                } else {
                    try {
                        $content = Storage::disk($from)->get($file);
                        $size = strlen($content);
                        
                        Storage::disk($to)->put($file, $content);
                        
                        $dirSize += $size;
                        $dirFiles++;
                        
                        $this->line("  ✅ $file (" . $this->formatBytes($size) . ")");
                    } catch (\Exception $e) {
                        $this->error("  ❌ Erro ao migrar $file: " . $e->getMessage());
                    }
                }
            }

            $totalFiles += $dirFiles;
            $totalSize += $dirSize;

            $this->line("  📊 $dir: $dirFiles arquivos (" . $this->formatBytes($dirSize) . ")");
        }

        $this->info("📊 Resumo da migração:");
        $this->line("  Total de arquivos: $totalFiles");
        $this->line("  Tamanho total: " . $this->formatBytes($totalSize));

        if (!$dryRun) {
            $this->info("✅ Migração concluída com sucesso!");
            $this->warn("⚠️  Lembre-se de atualizar o arquivo .env para usar o novo driver de storage");
        } else {
            $this->info("🔍 Execute sem --dry-run para realizar a migração real");
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}