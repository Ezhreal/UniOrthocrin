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
        // Adicionar colunas de thumbnail nas tabelas principais (1 por registro)
        if (!Schema::hasColumn('products', 'thumbnail_path')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable(); // Caminho para o thumbnail do card
            });
        }

        if (!Schema::hasColumn('trainings', 'thumbnail_path')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        if (!Schema::hasColumn('news', 'thumbnail_path')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        if (!Schema::hasColumn('library', 'thumbnail_path')) {
            Schema::table('library', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        // Tabelas auxiliares de campaigns
        if (!Schema::hasColumn('campaign_miscellaneous', 'thumbnail_path')) {
            Schema::table('campaign_miscellaneous', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        if (!Schema::hasColumn('campaign_posts', 'thumbnail_path')) {
            Schema::table('campaign_posts', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        if (!Schema::hasColumn('campaign_folders', 'thumbnail_path')) {
            Schema::table('campaign_folders', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }

        if (!Schema::hasColumn('campaign_videos', 'thumbnail_path')) {
            Schema::table('campaign_videos', function (Blueprint $table) {
                $table->string('thumbnail_path')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('library', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('campaign_miscellaneous', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('campaign_posts', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('campaign_folders', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });

        Schema::table('campaign_videos', function (Blueprint $table) {
            $table->dropColumn('thumbnail_path');
        });
    }
};
