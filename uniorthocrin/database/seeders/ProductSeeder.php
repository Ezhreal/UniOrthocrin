<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar categorias e séries
        $molaCategory = DB::table('product_categories')->where('name', 'Molas')->first();
        $travesseiroCategory = DB::table('product_categories')->where('name', 'Travesseiros')->first();
        $espumaCategory = DB::table('product_categories')->where('name', 'Espumas')->first();
        
        $serie900 = DB::table('product_series')->where('name', 'Série 900')->first();
        $serie700 = DB::table('product_series')->where('name', 'Série 700')->first();

        $products = [
            [
                'name' => 'Colchão Orthocrin Série 900 Premium',
                'description' => 'Colchão de molas com tecnologia avançada, oferecendo máximo conforto e suporte ortopédico.',
                'product_category_id' => $molaCategory->id,
                'product_series_id' => $serie900->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Colchão Orthocrin Série 700 Comfort',
                'description' => 'Colchão de molas com excelente relação custo-benefício, ideal para quem busca qualidade e conforto.',
                'product_category_id' => $molaCategory->id,
                'product_series_id' => $serie700->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travesseiro Orthocrin Memory Foam',
                'description' => 'Travesseiro com espuma viscoelástica que se adapta ao formato da cabeça, proporcionando sono tranquilo.',
                'product_category_id' => $travesseiroCategory->id,
                'product_series_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Colchão Orthocrin Espuma Densa',
                'description' => 'Colchão de espuma de alta densidade, durável e confortável para todos os tipos de colchão.',
                'product_category_id' => $espumaCategory->id,
                'product_series_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $index => $product) {
            // Criar o produto primeiro
            $productId = DB::table('products')->insertGetId($product);
            
            // Criar arquivo principal para o produto
            $mainImage = 'product-sample01.jpg';
            $fileId = DB::table('files')->insertGetId([
                'name' => $mainImage,
                'path' => "products/" . str_pad($productId, 2, '0', STR_PAD_LEFT) . "/" . $mainImage,
                'type' => 'image',
                'extension' => 'jpg',
                'mime_type' => 'image/jpeg',
                'size' => 1024,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Atualizar o produto com o file_id
            DB::table('products')->where('id', $productId)->update(['product_file_id' => $fileId]);
            
            // Adicionar arquivos adicionais (sem relacionamento direto)
            $additionalFiles = [
                'product-sample02.jpg',
                'product-sample03.jpg',
                'product-sample04.jpg',
                'product-video01.mp4',
                'product-video02.mp4',
                'product-video03.mp4'
            ];
            
            foreach ($additionalFiles as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $mimeType = $extension === 'mp4' ? 'video/mp4' : 'image/jpeg';
                $fileType = $extension === 'mp4' ? 'video' : 'image';
                
                DB::table('files')->insert([
                    'name' => $file,
                    'path' => "products/" . str_pad($productId, 2, '0', STR_PAD_LEFT) . "/" . $file,
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
                DB::table('product_permissions')->insert([
                    'product_id' => $productId,
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
