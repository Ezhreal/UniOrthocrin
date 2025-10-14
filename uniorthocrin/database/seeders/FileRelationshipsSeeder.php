<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== CRIANDO RELACIONAMENTOS DE ARQUIVOS ===\n\n";

        // Primeiro, recriar os registros na tabela files baseado nos arquivos físicos
        $this->recreateFilesFromPhysicalStorage();
        
        // Depois, criar os relacionamentos nas tabelas pivot
        $this->createFileRelationships();
        
        echo "\n=== RELACIONAMENTOS CRIADOS COM SUCESSO ===\n";
    }

    private function recreateFilesFromPhysicalStorage()
    {
        echo "Recriando registros na tabela files...\n";
        
        // Limpar tabela files
        \App\Models\File::query()->delete();
        
        // Escanear produtos (IDs 1-4)
        for ($i = 1; $i <= 4; $i++) {
            $this->createProductFiles($i);
        }
        
        // Escanear treinamentos (IDs 1-4)
        for ($i = 1; $i <= 4; $i++) {
            $this->createTrainingFiles($i);
        }
        
        // Escanear notícias (IDs 1-4)
        for ($i = 1; $i <= 4; $i++) {
            $this->createNewsFiles($i);
        }
        
        // Escanear campanhas (IDs 1-4)
        for ($i = 1; $i <= 4; $i++) {
            $this->createCampaignFiles($i);
        }
        
        // Escanear biblioteca (IDs 1-4)
        for ($i = 1; $i <= 4; $i++) {
            $this->createLibraryFiles($i);
        }
    }

    private function createProductFiles($productId)
    {
        echo "  - Escaneando pasta do produto {$productId}...\n";
        
        $folder = "private/products/{$productId}";
        $fullPath = storage_path("app/{$folder}");
        if (is_dir($fullPath)) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files($folder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Pular arquivos .zone.identifier
                if (strpos($filename, '.zone.identifier') !== false) {
                    continue;
                }
                
                // Determinar tipo baseado na extensão
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $type = 'other';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
                    $type = 'video';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $type = 'document';
                }
                
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => $filePath,
                    'type' => $type,
                    'size' => \Illuminate\Support\Facades\Storage::disk('local')->size($filePath),
                    'extension' => $extension,
                    'mime_type' => mime_content_type(\Illuminate\Support\Facades\Storage::disk('local')->path($filePath)),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "    -> Criado: {$filename} ({$type})\n";
            }
        } else {
            echo "    -> Pasta não existe: {$fullPath}\n";
        }
    }

    private function createTrainingFiles($trainingId)
    {
        echo "  - Escaneando pasta do treinamento {$trainingId}...\n";
        
        $folder = "private/trainings/{$trainingId}";
        $fullPath = storage_path("app/{$folder}");
        if (is_dir($fullPath)) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files($folder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Pular arquivos .zone.identifier
                if (strpos($filename, '.zone.identifier') !== false) {
                    continue;
                }
                
                // Determinar tipo baseado na extensão
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $type = 'other';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
                    $type = 'video';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $type = 'document';
                }
                
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => $filePath,
                    'type' => $type,
                    'size' => \Illuminate\Support\Facades\Storage::disk('local')->size($filePath),
                    'extension' => $extension,
                    'mime_type' => mime_content_type(\Illuminate\Support\Facades\Storage::disk('local')->path($filePath)),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "    -> Criado: {$filename} ({$type})\n";
            }
        } else {
            echo "    -> Pasta não existe: {$fullPath}\n";
        }
    }

    private function createNewsFiles($newsId)
    {
        echo "  - Escaneando pasta da notícia {$newsId}...\n";
        
        $folder = "private/news/{$newsId}";
        $fullPath = storage_path("app/{$folder}");
        if (is_dir($fullPath)) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files($folder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Pular arquivos .zone.identifier
                if (strpos($filename, '.zone.identifier') !== false) {
                    continue;
                }
                
                // Determinar tipo baseado na extensão
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $type = 'other';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
                    $type = 'video';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $type = 'document';
                }
                
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => $filePath,
                    'type' => $type,
                    'size' => \Illuminate\Support\Facades\Storage::disk('local')->size($filePath),
                    'extension' => $extension,
                    'mime_type' => mime_content_type(\Illuminate\Support\Facades\Storage::disk('local')->path($filePath)),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "    -> Criado: {$filename} ({$type})\n";
            }
        } else {
            echo "    -> Pasta não existe: {$fullPath}\n";
        }
    }

    private function createCampaignFiles($campaignId)
    {
        echo "  - Escaneando pasta da campanha {$campaignId}...\n";
        
        $folder = "private/campaing/{$campaignId}";
        $fullPath = storage_path("app/{$folder}");
        if (is_dir($fullPath)) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files($folder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Pular arquivos .zone.identifier
                if (strpos($filename, '.zone.identifier') !== false) {
                    continue;
                }
                
                // Determinar tipo baseado na extensão
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $type = 'other';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
                    $type = 'video';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $type = 'document';
                }
                
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => $filePath,
                    'type' => $type,
                    'size' => \Illuminate\Support\Facades\Storage::disk('local')->size($filePath),
                    'extension' => $extension,
                    'mime_type' => mime_content_type(\Illuminate\Support\Facades\Storage::disk('local')->path($filePath)),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "    -> Criado: {$filename} ({$type})\n";
            }
        } else {
            echo "    -> Pasta não existe: {$fullPath}\n";
        }
    }

    private function createLibraryFiles($libraryId)
    {
        echo "  - Escaneando pasta da biblioteca {$libraryId}...\n";
        
        $folder = "private/libraries/{$libraryId}";
        $fullPath = storage_path("app/{$folder}");
        if (is_dir($fullPath)) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files($folder);
            
            foreach ($files as $filePath) {
                $filename = basename($filePath);
                
                // Pular arquivos .zone.identifier
                if (strpos($filename, '.zone.identifier') !== false) {
                    continue;
                }
                
                // Determinar tipo baseado na extensão
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $type = 'other';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
                    $type = 'video';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $type = 'document';
                }
                
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => $filePath,
                    'type' => $type,
                    'size' => \Illuminate\Support\Facades\Storage::disk('local')->size($filePath),
                    'extension' => $extension,
                    'mime_type' => mime_content_type(\Illuminate\Support\Facades\Storage::disk('local')->path($filePath)),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "    -> Criado: {$filename} ({$type})\n";
            }
        } else {
            echo "    -> Pasta não existe: {$fullPath}\n";
        }
    }

    private function createFileRelationships()
    {
        echo "\nCriando relacionamentos nas tabelas pivot...\n";
        
        // Relacionar produtos com arquivos
        $this->createProductRelationships();
        
        // Relacionar treinamentos com arquivos
        $this->createTrainingRelationships();
        
        // Relacionar notícias com arquivos (1 para 1)
        $this->createNewsRelationships();
        
        // Relacionar campanhas com arquivos
        $this->createCampaignRelationships();
        
        // Relacionar biblioteca com arquivos
        $this->createLibraryRelationships();
    }

    private function createProductRelationships()
    {
        echo "Relacionando produtos...\n";
        
        for ($productId = 1; $productId <= 4; $productId++) {
            $files = \App\Models\File::where('path', 'like', "private/products/{$productId}/%")->get();
            
            foreach ($files as $index => $file) {
                \Illuminate\Support\Facades\DB::table('product_files')->insert([
                    'product_id' => $productId,
                    'file_id' => $file->id,
                    'file_type' => $file->type,
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // Primeiro arquivo é o principal
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            echo "  - Produto {$productId}: " . $files->count() . " arquivos relacionados\n";
        }
    }

    private function createTrainingRelationships()
    {
        echo "Relacionando treinamentos...\n";
        
        for ($trainingId = 1; $trainingId <= 4; $trainingId++) {
            $files = \App\Models\File::where('path', 'like', "private/trainings/{$trainingId}/%")->get();
            
            foreach ($files as $index => $file) {
                \Illuminate\Support\Facades\DB::table('training_files')->insert([
                    'training_id' => $trainingId,
                    'file_id' => $file->id,
                    'file_type' => $file->type,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            echo "  - Treinamento {$trainingId}: " . $files->count() . " arquivos relacionados\n";
        }
    }

    private function createNewsRelationships()
    {
        echo "Relacionando notícias...\n";
        
        for ($newsId = 1; $newsId <= 4; $newsId++) {
            $file = \App\Models\File::where('path', 'like', "private/news/{$newsId}/%")->first();
            
            if ($file) {
                \Illuminate\Support\Facades\DB::table('news')->where('id', $newsId)->update([
                    'news_file_id' => $file->id
                ]);
                
                echo "  - Notícia {$newsId}: arquivo relacionado\n";
            }
        }
    }

    private function createCampaignRelationships()
    {
        echo "Relacionando campanhas...\n";
        
        for ($campaignId = 1; $campaignId <= 4; $campaignId++) {
            $files = \App\Models\File::where('path', 'like', "private/campaing/{$campaignId}/%")->get();
            
            foreach ($files as $index => $file) {
                \Illuminate\Support\Facades\DB::table('campaign_miscellaneous_files')->insert([
                    'campaign_miscellaneous_id' => $campaignId,
                    'file_id' => $file->id,
                    'file_type' => $file->type,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            echo "  - Campanha {$campaignId}: " . $files->count() . " arquivos relacionados\n";
        }
    }

    private function createLibraryRelationships()
    {
        echo "Relacionando biblioteca...\n";
        
        for ($libraryId = 1; $libraryId <= 4; $libraryId++) {
            $files = \App\Models\File::where('path', 'like', "private/libraries/{$libraryId}/%")->get();
            
            foreach ($files as $index => $file) {
                \Illuminate\Support\Facades\DB::table('library_files')->insert([
                    'library_id' => $libraryId,
                    'file_id' => $file->id,
                    'file_type' => $file->type,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            echo "  - Biblioteca {$libraryId}: " . $files->count() . " arquivos relacionados\n";
        }
    }
}
