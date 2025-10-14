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
            $table->string('representante_nome')->nullable()->comment('Nome do Representante');
            $table->string('nome_fantasia')->nullable()->comment('Nome Fantasia');
            $table->string('razao_social')->nullable()->comment('Razão Social');
            $table->string('cpf_cnpj')->nullable()->comment('CPF ou CNPJ');
            
            // Adicionar índices para melhor performance
            $table->index('cpf_cnpj');
            $table->index('nome_fantasia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['cpf_cnpj']);
            $table->dropIndex(['nome_fantasia']);
            $table->dropColumn([
                'representante_nome',
                'nome_fantasia', 
                'razao_social',
                'cpf_cnpj'
            ]);
        });
    }
};
