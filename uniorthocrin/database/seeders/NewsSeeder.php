<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar um usuário admin para ser o autor
        $adminUser = DB::table('users')->where('user_type_id', 1)->first();
        
        // Buscar categorias
        $lancamentosCategory = DB::table('news_categories')->where('name', 'Lançamentos')->first();
        $marketingCategory = DB::table('news_categories')->where('name', 'Marketing')->first();
        $franquiasCategory = DB::table('news_categories')->where('name', 'Franquias')->first();
        $treinamentosCategory = DB::table('news_categories')->where('name', 'Treinamentos')->first();

        $news = [
            [
                'title' => 'Nova Linha de Colchões Orthocrin Premium',
                'content' => 'A Orthocrin lança sua nova linha de colchões premium com tecnologia avançada de molas e espumas viscoelásticas. Os novos produtos oferecem máximo conforto e suporte ortopédico, garantindo uma noite de sono perfeita para nossos clientes.',
                'excerpt' => 'Lançamento da nova linha premium com tecnologia avançada.',
                'author_id' => $adminUser->id,
                'news_category_id' => $lancamentosCategory->id,
                'published_at' => now(),
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Expansão da Rede de Franquias Orthocrin',
                'content' => 'A Orthocrin anuncia a expansão de sua rede de franquias em todo o Brasil. Novas oportunidades para empreendedores que desejam fazer parte da maior rede de colchões do país. Investimento inicial a partir de R$ 50.000.',
                'excerpt' => 'Oportunidades de franquia em todo o Brasil.',
                'author_id' => $adminUser->id,
                'news_category_id' => $franquiasCategory->id,
                'published_at' => now(),
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Campanha de Marketing Digital - Black Friday',
                'content' => 'Prepare-se para a maior campanha de vendas do ano! A Orthocrin preparou ofertas especiais e descontos exclusivos para a Black Friday 2025. Descontos de até 40% em toda a linha de produtos.',
                'excerpt' => 'Ofertas especiais na Black Friday 2025.',
                'author_id' => $adminUser->id,
                'news_category_id' => $marketingCategory->id,
                'published_at' => now(),
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Treinamento Online para Vendedores',
                'content' => 'Nova plataforma de treinamento online disponível para todos os vendedores da rede Orthocrin. Aprenda técnicas avançadas de vendas e conheça todos os produtos através de vídeos interativos e materiais exclusivos.',
                'excerpt' => 'Nova plataforma de treinamento online.',
                'author_id' => $adminUser->id,
                'news_category_id' => $treinamentosCategory->id,
                'published_at' => now(),
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($news as $index => $newsItem) {
            // Criar arquivo principal para a notícia
            $mainImage = 'news-sample01.jpg';
            $fileId = DB::table('files')->insertGetId([
                'name' => $mainImage,
                'path' => "news/" . $mainImage,
                'type' => 'image',
                'extension' => 'jpg',
                'mime_type' => 'image/jpeg',
                'size' => 1024,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Adicionar file_id à notícia
            $newsItem['news_file_id'] = $fileId;
            $newsId = DB::table('news')->insertGetId($newsItem);
            
            // Adicionar arquivos adicionais (sem relacionamento direto)
            $additionalFiles = [
                'news-sample02.jpg',
                'news-sample03.jpg',
                'news-sample04.jpg'
            ];
            
            foreach ($additionalFiles as $file) {
                DB::table('files')->insert([
                    'name' => $file,
                    'path' => "news/" . $file,
                    'type' => 'image',
                    'extension' => 'jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => 1024,
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Adicionar permissões para todos os tipos de usuário
            $userTypes = DB::table('user_types')->get();
            foreach ($userTypes as $userType) {
                DB::table('news_permissions')->insert([
                    'news_id' => $newsId,
                    'user_type_id' => $userType->id,
                    'can_view' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
