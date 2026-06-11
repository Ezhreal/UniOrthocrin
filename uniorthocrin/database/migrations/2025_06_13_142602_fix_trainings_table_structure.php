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
        if (Schema::hasColumn('trainings', 'category_id') && !Schema::hasColumn('trainings', 'training_category_id')) {
            try {
                Schema::table('trainings', function (Blueprint $table) {
                    $table->dropForeign(['category_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('trainings', function (Blueprint $table) {
                $table->renameColumn('category_id', 'training_category_id');
            });
            
            try {
                Schema::table('trainings', function (Blueprint $table) {
                    $table->foreign('training_category_id')->references('id')->on('training_categories');
                });
            } catch (\Exception $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('trainings', 'training_category_id') && !Schema::hasColumn('trainings', 'category_id')) {
            try {
                Schema::table('trainings', function (Blueprint $table) {
                    $table->dropForeign(['training_category_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('trainings', function (Blueprint $table) {
                $table->renameColumn('training_category_id', 'category_id');
            });
            
            try {
                Schema::table('trainings', function (Blueprint $table) {
                    $table->foreign('category_id')->references('id')->on('training_categories');
                });
            } catch (\Exception $e) {}
        }
    }
};
