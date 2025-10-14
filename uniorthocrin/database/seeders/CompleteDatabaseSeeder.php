<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\File;
use App\Models\Product;
use App\Models\Training;
use App\Models\News;
use App\Models\CampaignMiscellaneous;
use App\Models\CampaignPost;
use App\Models\CampaignFolder;
use App\Models\CampaignVideo;
use App\Models\Library;
use App\Models\ProductCategory;
use App\Models\TrainingCategory;
use App\Models\LibraryCategory;
use App\Models\NewsCategory;
use App\Models\UserType;
use App\Models\UiVisibility;
use App\Models\ProductPermission;
use App\Models\TrainingPermission;
use App\Models\LibraryPermission;
use App\Models\NewsPermission;

class CompleteDatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "=== LIMPANDO TODAS AS TABELAS ===\n";
        
        // Desabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpar tabelas de relacionamentos primeiro
        $pivotTables = [
            'product_files',
            'training_files', 
            'library_files',
            'campaign_miscellaneous_files',
            'campaign_post_files',
            'campaign_folder_files',
            'campaign_video_files'
        ];
        
        foreach ($pivotTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                echo "  - Limpou tabela: {$table}\n";
            }
        }
        
        // Limpar tabelas de permissões
        $permissionTables = [
            'product_permissions',
            'training_permissions',
            'library_permissions', 
            'news_permissions'
        ];
        
        foreach ($permissionTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                echo "  - Limpou tabela: {$table}\n";
            }
        }
        
        // Limpar tabelas de visibilidade (não vamos usar)
        // if (Schema::hasTable('ui_visibilities')) {
        //     DB::table('ui_visibilities')->truncate();
        //     echo "  - Limpou tabela: ui_visibilities\n";
        // }
        
        // Limpar tabela files
        if (Schema::hasTable('files')) {
            DB::table('files')->truncate();
            echo "  - Limpou tabela: files\n";
        }
        
        // Limpar tabelas de conteúdo (manter apenas categorias e tipos de usuário)
        $contentTables = [
            'products',
            'trainings',
            'news',
            'campaign_miscellaneous',
            'campaign_posts', 
            'campaign_folders',
            'campaign_videos',
            'library'
        ];
        
        foreach ($contentTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                echo "  - Limpou tabela: {$table}\n";
            }
        }
        
        // Reabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "\n=== RECRIANDO DADOS BASEADO NAS PASTAS FÍSICAS ===\n";

        // Recriar arquivos baseado nas pastas físicas
        $this->recreateFilesFromPhysicalStorage();
        
        // Recriar relacionamentos
        $this->createFileRelationships();
        
        // Recriar permissões específicas para cada registro
        $this->createSpecificPermissions();
        
        echo "\n=== SEEDER COMPLETO FINALIZADO ===\n";
    }
    
    private function recreateFilesFromPhysicalStorage()
    {
        echo "\n--- Recriando arquivos das pastas físicas ---\n";
        
        // Escanear pastas de produtos
        $this->scanAndCreateFiles('private/products', 'product');
        
        // Escanear pastas de treinamentos  
        $this->scanAndCreateFiles('private/trainings', 'training');
        
        // Escanear pastas de notícias
        $this->scanAndCreateFiles('private/news', 'news');
        
        // Escanear pastas de campanhas
        $this->scanAndCreateFiles('private/campaing', 'campaign');
        
        // Escanear pastas de biblioteca
        $this->scanAndCreateFiles('private/libraries', 'library');
    }
    
    private function scanAndCreateFiles($baseFolder, $contentType)
    {
        echo "  Escaneando pasta: {$baseFolder}\n";
        
        $fullPath = storage_path("app/{$baseFolder}");
        if (!is_dir($fullPath)) {
            echo "    -> Pasta não existe: {$fullPath}\n";
            return;
        }
        
        // Usar scandir diretamente
        $items = scandir($fullPath);
        
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $itemPath = $fullPath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $contentId = $item;
                    echo "    Processando {$contentType} ID: {$contentId}\n";
                    
                    $files = scandir($itemPath);
                    
                    foreach ($files as $filename) {
                        if ($filename !== '.' && $filename !== '..') {
                            // Pular arquivos .zone.identifier
                            if (strpos($filename, '.zone.identifier') !== false) {
                                continue;
                            }
                            
                            $filePath = $baseFolder . '/' . $contentId . '/' . $filename;
                            
                            // Determinar tipo baseado na extensão
                            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                            $type = $this->getFileType($extension);
                            
                            $file = File::create([
                                'name' => $filename,
                                'path' => $filePath,
                                'type' => $type,
                                'size' => filesize(storage_path("app/{$filePath}")),
                                'extension' => $extension,
                                'mime_type' => mime_content_type(storage_path("app/{$filePath}")),
                                'order' => 0,
                            ]);
                            
                            echo "      -> Criado arquivo: {$filename} ({$type})\n";
                        }
                    }
                }
            }
        }
    }
    
    private function getFileType($extension)
    {
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return 'image';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv'])) {
            return 'video';
        } elseif (in_array($extension, ['pdf'])) {
            return 'pdf';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        }
        return 'image'; // fallback para outros tipos
    }
    
    private function createFileRelationships()
    {
        echo "\n--- Criando relacionamentos de arquivos ---\n";
        
        // Relacionar arquivos de produtos
        $this->createProductRelationships();
        
        // Relacionar arquivos de treinamentos
        $this->createTrainingRelationships();
        
        // Relacionar arquivos de notícias (1:1)
        $this->createNewsRelationships();
        
        // Relacionar arquivos de campanhas
        $this->createCampaignRelationships();
        
        // Relacionar arquivos de biblioteca
        $this->createLibraryRelationships();
    }
    
    private function createProductRelationships()
    {
        echo "  Criando relacionamentos de produtos...\n";
        
        $files = File::where('path', 'like', 'private/products/%')->get();
        
        foreach ($files as $file) {
            // Extrair ID do produto do caminho
            $pathParts = explode('/', $file->path);
            $productId = $pathParts[2]; // private/products/ID/arquivo
            
            // Verificar se o produto existe, se não, criar
            $product = Product::firstOrCreate(
                ['id' => $productId],
                [
                    'name' => "Produto {$productId}",
                    'description' => "Descrição do produto {$productId}",
                    'status' => 'active',
                    'product_category_id' => 1, // Categoria padrão
                ]
            );
            
            // Criar relacionamento na pivot table
            DB::table('product_files')->insert([
                'product_id' => $productId,
                'file_id' => $file->id,
                'file_type' => $file->type,
                'sort_order' => 0,
                'is_primary' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Definir thumbnail se for imagem
            if ($file->type === 'image' && !$product->thumbnail_path) {
                $product->update(['thumbnail_path' => $file->path]);
            }
        }
    }
    
    private function createTrainingRelationships()
    {
        echo "  Criando relacionamentos de treinamentos...\n";
        
        $files = File::where('path', 'like', 'private/trainings/%')->get();
        
        foreach ($files as $file) {
            $pathParts = explode('/', $file->path);
            $trainingId = $pathParts[2];
            
            $training = Training::firstOrCreate(
                ['id' => $trainingId],
                [
                    'name' => "Treinamento {$trainingId}",
                    'description' => "Descrição do treinamento {$trainingId}",
                    'status' => 'active',
                    'training_category_id' => 1, // Categoria padrão
                    'thumbnail_path' => null
                ]
            );
            
            DB::table('training_files')->insert([
                'training_id' => $trainingId,
                'file_id' => $file->id,
                'file_type' => $file->type,
                'sort_order' => 0,
                'is_primary' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if ($file->type === 'image' && !$training->thumbnail_path) {
                $training->update(['thumbnail_path' => $file->path]);
            }
        }
    }
    
    private function createNewsRelationships()
    {
        echo "  Criando relacionamentos de notícias (1:1)...\n";
        
        $files = File::where('path', 'like', 'private/news/%')->get();
        
        foreach ($files as $file) {
            $pathParts = explode('/', $file->path);
            $newsId = $pathParts[2];
            
            $news = News::firstOrCreate(
                ['id' => $newsId],
                [
                    'title' => "Notícia {$newsId}",
                    'content' => "Conteúdo completo da notícia {$newsId}",
                    'excerpt' => "Resumo da notícia {$newsId}",
                    'status' => 'published',
                    'author_id' => 1, // Usuário padrão
                    'news_file_id' => $file->id,
                    'thumbnail_path' => null,
                    'news_category_id' => 1, // Categoria padrão
                ]
            );
            
            // Atualizar news_file_id se não estiver definido
            if (!$news->news_file_id) {
                $news->update(['news_file_id' => $file->id]);
            }
            
            if ($file->type === 'image' && !$news->thumbnail_path) {
                $news->update(['thumbnail_path' => $file->path]);
            }
        }
    }
    
    private function createCampaignRelationships()
    {
        echo "  Criando relacionamentos de campanhas...\n";
        
        $files = File::where('path', 'like', 'private/campaing/%')->get();
        
        foreach ($files as $file) {
            $pathParts = explode('/', $file->path);
            $campaignId = $pathParts[2];
            
            // Determinar tipo de campanha baseado no caminho
            $campaignType = $this->determineCampaignType($file->path);
            
            if ($campaignType) {
                $this->createCampaignRelationship($campaignType, $campaignId, $file);
            }
        }
    }
    
    private function determineCampaignType($path)
    {
        if (strpos($path, '/miscellaneous/') !== false) return 'miscellaneous';
        if (strpos($path, '/posts/') !== false) return 'post';
        if (strpos($path, '/folders/') !== false) return 'folder';
        if (strpos($path, '/videos/') !== false) return 'video';
        return null;
    }
    
    private function createCampaignRelationship($type, $campaignId, $file)
    {
        $tableMap = [
            'miscellaneous' => 'campaign_miscellaneous',
            'post' => 'campaign_posts',
            'folder' => 'campaign_folders',
            'video' => 'campaign_videos'
        ];
        
        $pivotMap = [
            'miscellaneous' => 'campaign_miscellaneous_files',
            'post' => 'campaign_post_files',
            'folder' => 'campaign_folder_files',
            'video' => 'campaign_video_files'
        ];
        
        $table = $tableMap[$type];
        $pivotTable = $pivotMap[$type];
        $foreignKey = "campaign_{$type}_id";
        
        $campaign = DB::table($table)->firstOrInsert(
            ['id' => $campaignId],
            [
                'name' => "Campanha {$type} {$campaignId}",
                'description' => "Descrição da campanha {$type} {$campaignId}",
                'status' => 'active',
                'thumbnail_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table($pivotTable)->insert([
            $foreignKey => $campaignId,
            'file_id' => $file->id,
            'file_type' => $file->type,
            'sort_order' => 0,
            'is_primary' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        if ($file->type === 'image') {
            DB::table($table)->where('id', $campaignId)->update(['thumbnail_path' => $file->path]);
        }
    }
    
    private function createLibraryRelationships()
    {
        echo "  Criando relacionamentos de biblioteca...\n";
        
        $files = File::where('path', 'like', 'private/libraries/%')->get();
        
        foreach ($files as $file) {
            $pathParts = explode('/', $file->path);
            $libraryId = $pathParts[2];
            
            $library = Library::firstOrCreate(
                ['id' => $libraryId],
                [
                    'name' => "Biblioteca {$libraryId}",
                    'description' => "Descrição da biblioteca {$libraryId}",
                    'status' => 'active',
                    'library_category_id' => 1, // Categoria padrão
                    'thumbnail_path' => null
                ]
            );
            
            DB::table('library_files')->insert([
                'library_id' => $libraryId,
                'file_id' => $file->id,
                'file_type' => $file->type,
                'sort_order' => 0,
                'is_primary' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if ($file->type === 'image' && !$library->thumbnail_path) {
                $library->update(['thumbnail_path' => $file->path]);
            }
        }
    }
    
    private function createSpecificPermissions()
    {
        echo "\n--- Criando permissões específicas para cada registro ---\n";
        
        // Criar tipos de usuário se não existirem
        $userTypes = [
            ['id' => 1, 'name' => 'Admin', 'description' => 'Administrador'],
            ['id' => 2, 'name' => 'Cliente', 'description' => 'Cliente'],
            ['id' => 3, 'name' => 'Visitante', 'description' => 'Visitante']
        ];
        
        foreach ($userTypes as $userType) {
            UserType::firstOrCreate(['id' => $userType['id']], $userType);
        }
        
        // Criar permissões para produtos
        $this->createProductPermissions();
        
        // Criar permissões para treinamentos
        $this->createTrainingPermissions();
        
        // Criar permissões para notícias
        $this->createNewsPermissions();
        
        // Criar permissões para biblioteca
        $this->createLibraryPermissions();
        
        echo "  Permissões específicas criadas!\n";
    }
    
    private function createProductPermissions()
    {
        echo "  Criando permissões de produtos...\n";
        
        $products = Product::all();
        $userTypes = [1, 2, 3, 4]; // Admin, Cliente, Visitante
        
        foreach ($products as $product) {
            foreach ($userTypes as $userTypeId) {
                $canView = $userTypeId == 3 ? false : true; // Visitante não pode ver
                $canDownload = $userTypeId == 1 ? true : false; // Apenas Admin pode baixar
                
                DB::table('product_permissions')->insert([
                    'product_id' => $product->id,
                    'user_type_id' => $userTypeId,
                    'can_view' => $canView,
                    'can_download' => $canDownload,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
    
    private function createTrainingPermissions()
    {
        echo "  Criando permissões de treinamentos...\n";
        
        $trainings = Training::all();
        $userTypes = [1, 2, 3, 4];
        
        foreach ($trainings as $training) {
            foreach ($userTypes as $userTypeId) {
                $canView = $userTypeId == 3 ? false : true;
                $canDownload = $userTypeId == 1 ? true : false;
                
                DB::table('training_permissions')->insert([
                    'training_id' => $training->id,
                    'user_type_id' => $userTypeId,
                    'can_view' => $canView,
                    'can_download' => $canDownload,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
    
    private function createNewsPermissions()
    {
        echo "  Criando permissões de notícias...\n";
        
        $news = News::all();
        $userTypes = [1, 2, 3, 4];
        
        foreach ($news as $newsItem) {
            foreach ($userTypes as $userTypeId) {
                $canView = true; // Todos podem ver notícias
                
                DB::table('news_permissions')->insert([
                    'news_id' => $newsItem->id,
                    'user_type_id' => $userTypeId,
                    'can_view' => $canView,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
    
    private function createLibraryPermissions()
    {
        echo "  Criando permissões de biblioteca...\n";
        
        $libraries = Library::all();
        $userTypes = [1, 2, 3, 4];
        
        foreach ($libraries as $library) {
            foreach ($userTypes as $userTypeId) {
                $canView = $userTypeId == 3 ? false : true;
                $canDownload = $userTypeId == 1 ? true : false;
                
                DB::table('library_permissions')->insert([
                    'library_id' => $library->id,
                    'user_type_id' => $userTypeId,
                    'can_view' => $canView,
                    'can_download' => $canDownload,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
