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
        // Adicionar colunas extras nas tabelas pivot para melhor controle
        Schema::table('product_files', function (Blueprint $table) {
            $table->string('file_type')->nullable(); // image, video, document
            $table->integer('sort_order')->default(0); // Para ordenação
            $table->boolean('is_primary')->default(false); // Arquivo principal
        });

        Schema::table('training_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });

        Schema::table('campaign_miscellaneous_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });

        Schema::table('campaign_post_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });

        Schema::table('campaign_folder_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });

        Schema::table('campaign_video_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });

        Schema::table('library_files', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('training_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('campaign_miscellaneous_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('campaign_post_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('campaign_folder_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('campaign_video_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });

        Schema::table('library_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'sort_order', 'is_primary']);
        });
    }
};
