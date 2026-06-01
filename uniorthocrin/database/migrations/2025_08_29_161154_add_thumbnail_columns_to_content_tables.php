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
            'products',
            'trainings',
            'news',
            'library',
            'campaign_miscellaneous',
            'campaign_posts',
            'campaign_folders',
            'campaign_videos'
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                if (!Schema::hasColumn($t, 'thumbnail_path')) {
                    Schema::table($t, function (Blueprint $table) {
                        $table->string('thumbnail_path')->nullable();
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'trainings',
            'news',
            'library',
            'campaign_miscellaneous',
            'campaign_posts',
            'campaign_folders',
            'campaign_videos'
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                if (Schema::hasColumn($t, 'thumbnail_path')) {
                    Schema::table($t, function (Blueprint $table) {
                        $table->dropColumn('thumbnail_path');
                    });
                }
            }
        }
    }
};
