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
            // Drop indexes first to avoid SQLite index column reference errors
            $table->dropIndex('files_fileable_type_fileable_id_index');
            $table->dropIndex('files_type_fileable_type_index');
            $table->dropColumn(['fileable_type', 'fileable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('fileable_type')->nullable();
            $table->unsignedBigInteger('fileable_id')->nullable();
            $table->index(['fileable_type', 'fileable_id'], 'files_fileable_type_fileable_id_index');
            $table->index(['type', 'fileable_type'], 'files_type_fileable_type_index');
        });
    }
};
