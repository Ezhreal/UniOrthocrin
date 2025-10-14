<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Molas',
                'description' => 'Colchões com molas',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travesseiros',
                'description' => 'Travesseiros e almofadas',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Espumas',
                'description' => 'Colchões de espuma',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Acessórios e Complementos',
                'description' => 'Acessórios para colchões',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Box',
                'description' => 'Box para colchões',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ortopédicos',
                'description' => 'Colchões ortopédicos',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vibroterapia',
                'description' => 'Colchões com vibroterapia',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cabeceiras',
                'description' => 'Cabeceiras para cama',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }

        // Criar séries para a categoria Molas
        $molaCategory = DB::table('product_categories')->where('name', 'Molas')->first();
        
        if ($molaCategory) {
            $series = [
                'Série 900',
                'Série 700', 
                'Série 500',
                'Série 300',
                'Série 200',
                'Série 100'
            ];

            foreach ($series as $serie) {
                DB::table('product_series')->updateOrInsert(
                    ['name' => $serie],
                    [
                        'name' => $serie,
                        'product_category_id' => $molaCategory->id,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
