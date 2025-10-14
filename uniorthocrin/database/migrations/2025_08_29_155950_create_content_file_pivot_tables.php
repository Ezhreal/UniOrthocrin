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
        // Tabela pivot para produtos e arquivos (1 para muitos)
        if (!Schema::hasTable('product_files')) {
            Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            // Índices para performance
            $table->index(['product_id', 'file_id'], 'product_files_idx');
            });

        }

        // Tabela pivot para treinamentos e arquivos (1 para muitos)
        if (!Schema::hasTable('training_files')) {
            Schema::create('training_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['training_id', 'file_id'], 'training_files_idx');
            });
        }

        // Tabelas pivot para campanhas e arquivos (1 para muitos)
        // Campaign Miscellaneous
        if (!Schema::hasTable('campaign_miscellaneous_files')) {
            Schema::create('campaign_miscellaneous_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_miscellaneous_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('campaign_miscellaneous_id')->references('id')->on('campaign_miscellaneous')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['campaign_miscellaneous_id', 'file_id'], 'campaign_misc_files_idx');
            });
        }

        // Campaign Posts
        if (!Schema::hasTable('campaign_post_files')) {
            Schema::create('campaign_post_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_post_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('campaign_post_id')->references('id')->on('campaign_posts')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['campaign_post_id', 'file_id'], 'campaign_post_files_idx');
            });
        }

        // Campaign Folders
        if (!Schema::hasTable('campaign_folder_files')) {
            Schema::create('campaign_folder_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_folder_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('campaign_folder_id')->references('id')->on('campaign_folders')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['campaign_folder_id', 'file_id'], 'campaign_folder_files_idx');
            });
        }

        // Campaign Videos
        if (!Schema::hasTable('campaign_video_files')) {
            Schema::create('campaign_video_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_video_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('campaign_video_id')->references('id')->on('campaign_videos')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['campaign_video_id', 'file_id'], 'campaign_video_files_idx');
            });
        }

        // Tabela pivot para biblioteca e arquivos (1 para muitos)
        if (!Schema::hasTable('library_files')) {
            Schema::create('library_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            $table->foreign('library_id')->references('id')->on('library')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            
            $table->index(['library_id', 'file_id'], 'library_files_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_files');
        Schema::dropIfExists('campaign_video_files');
        Schema::dropIfExists('campaign_folder_files');
        Schema::dropIfExists('campaign_post_files');
        Schema::dropIfExists('campaign_miscellaneous_files');
        Schema::dropIfExists('training_files');
        Schema::dropIfExists('product_files');
    }
};
