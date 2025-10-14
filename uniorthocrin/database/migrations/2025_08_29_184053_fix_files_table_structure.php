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
        Schema::table('files', function (Blueprint $table) {
            // Remover coluna thumbnail_path (deve estar nas tabelas de conteúdo)
            if (Schema::hasColumn('files', 'thumbnail_path')) {
                $table->dropColumn('thumbnail_path');
            }
            
            // Remover colunas do morphs (fileable_type e fileable_id)
            if (Schema::hasColumn('files', 'fileable_type')) {
                $table->dropColumn('fileable_type');
            }
            
            if (Schema::hasColumn('files', 'fileable_id')) {
                $table->dropColumn('fileable_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('thumbnail_path')->nullable();
            $table->morphs('fileable');
        });
    }
};
