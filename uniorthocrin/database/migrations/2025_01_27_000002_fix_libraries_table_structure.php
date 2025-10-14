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
        Schema::table('library', function (Blueprint $table) {
            // Renomear category_id para library_category_id
            $table->renameColumn('category_id', 'library_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library', function (Blueprint $table) {
            // Reverter as mudanças
            $table->renameColumn('library_category_id', 'category_id');
        });
    }
};
