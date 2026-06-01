<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'video_url')) {
                    $table->string('video_url', 2048)->nullable()->after('thumbnail_path');
                }
                if (!Schema::hasColumn('products', 'video_source')) {
                    $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
                }
            });
        }

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (!Schema::hasColumn('trainings', 'video_url')) {
                    $table->string('video_url', 2048)->nullable()->after('thumbnail_path');
                }
                if (!Schema::hasColumn('trainings', 'video_source')) {
                    $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
                }
            });
        }

        if (Schema::hasTable('campaign_videos')) {
            Schema::table('campaign_videos', function (Blueprint $table) {
                if (!Schema::hasColumn('campaign_videos', 'video_url')) {
                    $table->string('video_url', 2048)->nullable()->after('status');
                }
                if (!Schema::hasColumn('campaign_videos', 'video_source')) {
                    $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('products', 'video_url')) $cols[] = 'video_url';
                if (Schema::hasColumn('products', 'video_source')) $cols[] = 'video_source';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('trainings', 'video_url')) $cols[] = 'video_url';
                if (Schema::hasColumn('trainings', 'video_source')) $cols[] = 'video_source';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        if (Schema::hasTable('campaign_videos')) {
            Schema::table('campaign_videos', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('campaign_videos', 'video_url')) $cols[] = 'video_url';
                if (Schema::hasColumn('campaign_videos', 'video_source')) $cols[] = 'video_source';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }
    }
};
