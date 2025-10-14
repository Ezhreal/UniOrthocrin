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
        Schema::table('sessions', function (Blueprint $table) {
            // Remover coluna last_activity antiga
            $table->dropColumn('last_activity');
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            // Adicionar colunas corretas
            $table->timestamp('last_activity')->nullable()->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['last_activity', 'created_at', 'updated_at']);
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('last_activity')->index();
        });
    }
};
