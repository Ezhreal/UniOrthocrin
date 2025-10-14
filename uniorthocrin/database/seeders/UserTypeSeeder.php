<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Criar cada tipo de usuário separadamente
        UserType::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acesso total ao sistema',
            'level' => 99,
            'status' => 'active',
        ]);

        UserType::create([
            'id' => 2,
            'name' => 'Franqueado',
            'description' => 'Para franqueados',
            'level' => 1,
            'status' => 'active',
        ]);

        UserType::create([
            'id' => 3,
            'name' => 'Lojista',
            'description' => 'Para lojistas',
            'level' => 1,
            'status' => 'active',
        ]);

        UserType::create([
            'id' => 4,
            'name' => 'Representante',
            'description' => 'Para representantes',
            'level' => 1,
            'status' => 'active',
        ]);

        // Permissões para blocos
        $blocos = ['bloco_produtos', 'bloco_treinamentos', 'bloco_biblioteca'];
        foreach ([1,2,3,4] as $userTypeId) {
            foreach ($blocos as $bloco) {
                DB::table('ui_visibility')->updateOrInsert([
                    'feature' => $bloco,
                    'user_type_id' => $userTypeId
                ], [
                    'can_view' => 1,
                    'updated_at' => now(),
                    'created_at' => now()
                ]);
            }
        }
        
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        foreach ([1, 2] as $userTypeId) {
            DB::table('ui_visibility')->updateOrInsert([
                'feature' => 'bloco_marketing',
                'user_type_id' => $userTypeId
            ], [
                'can_view' => 1,
                'updated_at' => now(),
                'created_at' => now()
            ]);
        }
        
        // Bloquear marketing para Lojistas (ID 3) e Representantes (ID 4)
        foreach ([3, 4] as $userTypeId) {
            DB::table('ui_visibility')->updateOrInsert([
                'feature' => 'bloco_marketing',
                'user_type_id' => $userTypeId
            ], [
                'can_view' => 0,
                'updated_at' => now(),
                'created_at' => now()
            ]);
        }
    }
}