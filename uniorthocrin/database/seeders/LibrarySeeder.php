<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar categorias
        $manuaisCategory = DB::table('library_categories')->where('name', 'Manuais e Planilhas')->first();
        $papelariaCategory = DB::table('library_categories')->where('name', 'Papelaria')->first();
        $logomarcaCategory = DB::table('library_categories')->where('name', 'Logomarca')->first();
        $pdvCategory = DB::table('library_categories')->where('name', 'Peças de PDV')->first();

        $libraries = [
            [
                'name' => 'Manual de Vendas Orthocrin 2025',
                'description' => 'Guia completo com técnicas de vendas, argumentos e informações sobre produtos Orthocrin. Inclui scripts de vendas e respostas para objeções comuns.',
                'library_category_id' => $manuaisCategory->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Catálogo de Produtos Digital',
                'description' => 'Catálogo completo com imagens, especificações e preços de todos os produtos da linha Orthocrin. Material essencial para apresentações comerciais.',
                'library_category_id' => $papelariaCategory->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kit Logomarca Orthocrin',
                'description' => 'Pacote completo com logomarcas em diferentes formatos e cores para uso em materiais promocionais. Inclui versões para impressão e digital.',
                'library_category_id' => $logomarcaCategory->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Materiais de PDV - Stand Promocional',
                'description' => 'Arte final para impressão de stands promocionais, banners e displays para ponto de venda. Arquivos em alta resolução para impressão profissional.',
                'library_category_id' => $pdvCategory->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($libraries as $index => $library) {
            // Criar arquivo principal para o item da biblioteca
            $mainFile = 'library-sample01.pdf';
            $fileId = DB::table('files')->insertGetId([
                'name' => $mainFile,
                'path' => "libraries/" . ($index + 1) . "/" . $mainFile,
                'type' => 'pdf',
                'extension' => 'pdf',
                'mime_type' => 'application/pdf',
                'size' => 1024,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Adicionar file_id ao item da biblioteca
            $library['library_file_id'] = $fileId;
            $libraryId = DB::table('library')->insertGetId($library);
            
            // Adicionar arquivos adicionais (sem relacionamento direto)
            $additionalFiles = [
                'library-sample02.zip',
                'library-sample03.jpg',
                'library-sample04.pdf'
            ];
            
            foreach ($additionalFiles as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $mimeType = $extension === 'pdf' ? 'application/pdf' : 'image/jpeg';
                $fileType = $extension === 'pdf' ? 'pdf' : 'image';
                
                DB::table('files')->insert([
                    'name' => $file,
                    'path' => "libraries/" . ($index + 1) . "/" . $file,
                    'type' => $fileType,
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size' => 1024,
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Adicionar permissões para todos os tipos de usuário
            $userTypes = DB::table('user_types')->get();
            foreach ($userTypes as $userType) {
                DB::table('library_permissions')->insert([
                    'library_id' => $libraryId,
                    'user_type_id' => $userType->id,
                    'can_view' => 1,
                    'can_download' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
