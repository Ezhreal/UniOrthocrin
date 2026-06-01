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
        if (!Schema::hasColumn('library', 'library_file_id')) {
            Schema::table('library', function (Blueprint $table) {
                $table->foreignId('library_file_id')->nullable()->constrained('files')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('library', 'library_file_id')) {
            Schema::table('library', function (Blueprint $table) {
                try {
                    $table->dropForeign(['library_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('library_file_id');
            });
        }
    }
};
