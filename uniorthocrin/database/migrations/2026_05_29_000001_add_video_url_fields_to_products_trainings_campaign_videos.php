<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona suporte a vídeos via URL externa (YouTube, Vimeo, etc.)
 * nas tabelas products, trainings e campaign_videos.
 *
 * video_url    → a URL original fornecida pelo admin
 * video_source → 'upload' | 'url'  — distingue upload de arquivo vs link externo
 *                Nullable para não quebrar registros existentes (NULL == 'upload')
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('video_url', 2048)->nullable()->after('thumbnail_path');
            $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->string('video_url', 2048)->nullable()->after('thumbnail_path');
            $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
        });

        Schema::table('campaign_videos', function (Blueprint $table) {
            $table->string('video_url', 2048)->nullable()->after('status');
            $table->enum('video_source', ['upload', 'url'])->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_source']);
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_source']);
        });

        Schema::table('campaign_videos', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_source']);
        });
    }
};
