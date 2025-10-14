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
        Schema::table('products', function (Blueprint $table) {
            // Remover coluna serie (será substituída por product_series_id)
            $table->dropColumn('serie');
            
            // Adicionar coluna product_series_id
            $table->foreignId('product_series_id')->nullable()->constrained('product_series')->onDelete('set null');
            
            // Renomear category_id para product_category_id para manter consistência
            $table->renameColumn('category_id', 'product_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverter as mudanças
            $table->renameColumn('product_category_id', 'category_id');
            $table->dropForeign(['product_series_id']);
            $table->dropColumn('product_series_id');
            $table->string('serie')->nullable();
        });
    }
};
