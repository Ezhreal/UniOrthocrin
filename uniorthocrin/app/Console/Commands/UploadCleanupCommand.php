<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pastas de chunks órfãos com mais de 24 horas em storage/app/chunks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $basePath = storage_path('app/chunks');

        if (!is_dir($basePath)) {
            $this->info('Diretório de chunks não existe. Nada para limpar.');
            return self::SUCCESS;
        }

        $now = time();
        $deletedCount = 0;

        foreach (scandir($basePath) as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $fullPath = $basePath . DIRECTORY_SEPARATOR . $dir;

            if (!is_dir($fullPath)) {
                continue;
            }

            $lastModified = @filemtime($fullPath);
            if ($lastModified === false) {
                continue;
            }

            // 24 horas = 86400 segundos
            if (($now - $lastModified) > 86400) {
                $this->deleteDirectory($fullPath);
                $deletedCount++;
            }
        }

        $this->info("Limpeza de chunks concluída. Pastas removidas: {$deletedCount}");

        return self::SUCCESS;
    }

    protected function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }
}

