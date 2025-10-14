<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar categorias
        $marketingCategory = DB::table('training_categories')->where('name', 'Marketing e Vendas')->first();
        $virtualCategory = DB::table('training_categories')->where('name', 'Treinamentos Virtuais')->first();

        $trainings = [
            [
                'name' => 'Técnicas Avançadas de Vendas Orthocrin',
                'description' => 'Curso completo sobre técnicas de vendas específicas para produtos Orthocrin, incluindo argumentação e fechamento.',
                'training_category_id' => $marketingCategory->id,
                'content_type' => 'video',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conhecendo a Linha de Produtos',
                'description' => 'Treinamento detalhado sobre todos os produtos da linha Orthocrin, suas características e benefícios.',
                'training_category_id' => $marketingCategory->id,
                'content_type' => 'pdf',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Atendimento ao Cliente Premium',
                'description' => 'Como oferecer um atendimento diferenciado e construir relacionamentos duradouros com clientes.',
                'training_category_id' => $virtualCategory->id,
                'content_type' => 'video',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gestão de Estoque e Logística',
                'description' => 'Aprenda a gerenciar eficientemente o estoque de produtos Orthocrin e otimizar a logística.',
                'training_category_id' => $virtualCategory->id,
                'content_type' => 'pdf',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($trainings as $index => $training) {
            // Criar arquivo principal para o treinamento
            $mainFile = 'training-sample01.mp4';
            $fileId = DB::table('files')->insertGetId([
                'name' => $mainFile,
                'path' => "trainings/" . ($index + 1) . "/" . $mainFile,
                'type' => 'video',
                'extension' => 'mp4',
                'mime_type' => 'video/mp4',
                'size' => 1024,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Adicionar file_id ao treinamento
            $training['training_file_id'] = $fileId;
            $trainingId = DB::table('trainings')->insertGetId($training);
            
            // Adicionar arquivos adicionais (sem relacionamento direto)
            $additionalFiles = [
                'training-sample02.pdf',
                'training-sample03.mp4',
                'training-sample04.pdf'
            ];
            
            foreach ($additionalFiles as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $mimeType = $extension === 'pdf' ? 'application/pdf' : 'video/mp4';
                $fileType = $extension === 'pdf' ? 'pdf' : 'video';
                
                DB::table('files')->insert([
                    'name' => $file,
                    'path' => "trainings/" . ($index + 1) . "/" . $file,
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
                DB::table('training_permissions')->insert([
                    'training_id' => $trainingId,
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
