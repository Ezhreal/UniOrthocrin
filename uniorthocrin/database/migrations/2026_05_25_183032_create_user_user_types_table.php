<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_user_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_type_id')->constrained('user_types')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'user_type_id']);
        });

        // Migrar dados existentes: cada usuário ganha o perfil que já possui no user_type_id
        $users = DB::table('users')->whereNotNull('user_type_id')->get();
        foreach ($users as $user) {
            DB::table('user_user_types')->insert([
                'user_id' => $user->id,
                'user_type_id' => $user->user_type_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_user_types');
    }
};
