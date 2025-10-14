<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\File;
use ZipArchive;

class TestZipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:zip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ZIP creation with product images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando criação de ZIP...');

        // Buscar arquivos de imagem do produto 4
        $files = File::where('path', 'like', 'private/products/4/%')
                     ->where('type', 'image')
                     ->get();

        $this->info("Encontrados " . $files->count() . " arquivos de imagem");

        foreach ($files as $file) {
            $this->line("Arquivo: " . $file->path . " - " . $file->name);
        }

        // Criar ZIP
        $zip = new ZipArchive();
        $zipPath = storage_path('app/downloads/test_command.zip');

        $this->info("Criando ZIP em: $zipPath");

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            $this->error("Erro ao criar ZIP");
            return 1;
        }

        $addedFiles = 0;
        foreach ($files as $file) {
            $filePath = storage_path('app/' . $file->path);
            $exists = file_exists($filePath);
            $this->line("Verificando: $filePath - " . ($exists ? 'EXISTE' : 'NÃO EXISTE'));
            
            if ($exists) {
                $result = $zip->addFile($filePath, $file->name);
                if ($result) {
                    $addedFiles++;
                    $this->info("Adicionado: " . $file->name);
                } else {
                    $this->error("Erro ao adicionar: " . $file->name);
                }
            }
        }

        $result = $zip->close();
        $this->info("ZIP fechado: " . ($result ? 'SUCESSO' : 'FALHA'));
        $this->info("Arquivos adicionados: $addedFiles");
        
        if (file_exists($zipPath)) {
            $this->info("Tamanho do ZIP: " . filesize($zipPath) . " bytes");
        } else {
            $this->error("ZIP não foi criado");
        }

        return 0;
    }
}
