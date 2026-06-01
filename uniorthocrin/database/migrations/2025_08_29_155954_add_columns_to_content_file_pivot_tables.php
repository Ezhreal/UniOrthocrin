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
        $tables = [
            'product_files',
            'training_files',
            'campaign_miscellaneous_files',
            'campaign_post_files',
            'campaign_folder_files',
            'campaign_video_files',
            'library_files'
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    if (!Schema::hasColumn($t, 'file_type')) {
                        $table->string('file_type')->nullable();
                    }
                    if (!Schema::hasColumn($t, 'sort_order')) {
                        $table->integer('sort_order')->default(0);
                    }
                    if (!Schema::hasColumn($t, 'is_primary')) {
                        $table->boolean('is_primary')->default(false);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'product_files',
            'training_files',
            'campaign_miscellaneous_files',
            'campaign_post_files',
            'campaign_folder_files',
            'campaign_video_files',
            'library_files'
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    $colsToDrop = [];
                    if (Schema::hasColumn($t, 'file_type')) $colsToDrop[] = 'file_type';
                    if (Schema::hasColumn($t, 'sort_order')) $colsToDrop[] = 'sort_order';
                    if (Schema::hasColumn($t, 'is_primary')) $colsToDrop[] = 'is_primary';
                    if (!empty($colsToDrop)) {
                        $table->dropColumn($colsToDrop);
                    }
                });
            }
        }
    }
};
