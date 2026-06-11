<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Busca todos os usuários que têm um tipo definido
        $users = DB::table('users')->whereNotNull('user_type_id')->get();

        foreach ($users as $user) {
            // Garante que o perfil principal do usuário esteja inserido na tabela pivot
            DB::table('user_user_types')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'user_type_id' => $user->user_type_id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não é estritamente necessário reverter essa correção, pois ela apenas garante a integridade
    }
};
