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
        if (!Schema::hasColumn('trainings', 'training_file_id')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->foreignId('training_file_id')->nullable()->constrained('files')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('trainings', 'training_file_id')) {
            Schema::table('trainings', function (Blueprint $table) {
                try {
                    $table->dropForeign(['training_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('training_file_id');
            });
        }
    }
};
