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
        if (Schema::hasColumn('library', 'category_id') && !Schema::hasColumn('library', 'library_category_id')) {
            try {
                Schema::table('library', function (Blueprint $table) {
                    $table->dropForeign(['category_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('library', function (Blueprint $table) {
                $table->renameColumn('category_id', 'library_category_id');
            });
            
            try {
                Schema::table('library', function (Blueprint $table) {
                    $table->foreign('library_category_id')->references('id')->on('library_categories');
                });
            } catch (\Exception $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('library', 'library_category_id') && !Schema::hasColumn('library', 'category_id')) {
            try {
                Schema::table('library', function (Blueprint $table) {
                    $table->dropForeign(['library_category_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('library', function (Blueprint $table) {
                $table->renameColumn('library_category_id', 'category_id');
            });
            
            try {
                Schema::table('library', function (Blueprint $table) {
                    $table->foreign('category_id')->references('id')->on('library_categories');
                });
            } catch (\Exception $e) {}
        }
    }
};
