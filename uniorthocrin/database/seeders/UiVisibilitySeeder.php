<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UiVisibilitySeeder extends Seeder
{
    public function run(): void
    {
        // Admin vê tudo (não precisa explicitar, mas pode deixar para referência)
        $features = [
            'menu_marketing', 'menu_produtos', 'menu_biblioteca', 'menu_treinamentos', 'menu_news',
            'banner_marketing', 'banner_produtos', 'bloco_marketing',
        ];
        foreach ($features as $feature) {
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 1, // Admin
            ], [
                'can_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Franqueado vê tudo
        foreach ($features as $feature) {
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 2, // Franqueado
            ], [
                'can_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Lojista e Representante NÃO veem marketing
        $featuresLojista = [
            'menu_produtos', 'menu_biblioteca', 'menu_treinamentos', 'menu_news',
        ];
        foreach ($featuresLojista as $feature) {
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 3, // Lojista
            ], [
                'can_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 4, // Representante
            ], [
                'can_view' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Lojista e Representante NÃO veem marketing
        $featuresMarketing = ['menu_marketing', 'banner_marketing', 'bloco_marketing'];
        foreach ($featuresMarketing as $feature) {
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 3, // Lojista
            ], [
                'can_view' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('ui_visibilities')->updateOrInsert([
                'feature' => $feature,
                'user_type_id' => 4, // Representante
            ], [
                'can_view' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $blocosLiberados = ['bloco_produtos', 'bloco_biblioteca', 'bloco_treinamentos'];
        foreach ($blocosLiberados as $bloco) {
            foreach ([1,2,3,4] as $userTypeId) {
                DB::table('ui_visibilities')->updateOrInsert([
                    'feature' => $bloco,
                    'user_type_id' => $userTypeId
                ], [
                    'can_view' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 