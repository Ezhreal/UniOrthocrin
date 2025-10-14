<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modifica a coluna last_access para permitir NULL e adicionar comentário
            $table->timestamp('last_access')->nullable()->comment('Último acesso do usuário ao sistema')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverte a modificação
            $table->timestamp('last_access')->nullable()->change();
        });
    }
};