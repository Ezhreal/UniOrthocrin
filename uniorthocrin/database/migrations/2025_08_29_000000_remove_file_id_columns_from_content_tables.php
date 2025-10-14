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
        // Remover colunas file_id das tabelas principais (exceto news que é 1 para 1)
        // Verificar se as colunas existem antes de tentar removê-las

        if (Schema::hasColumn('products', 'product_file_id')) {
            Schema::table('products', function (Blueprint $table) {
                try {
                    $table->dropForeign(['product_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('product_file_id');
            });
        }

        if (Schema::hasColumn('trainings', 'training_file_id')) {
            Schema::table('trainings', function (Blueprint $table) {
                try {
                    $table->dropForeign(['training_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('training_file_id');
            });
        }

        if (Schema::hasColumn('campaigns', 'campaign_file_id')) {
            Schema::table('campaigns', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('campaign_file_id');
            });
        }

        // Campaigns não tem file_id direto, mas suas tabelas auxiliares têm
        if (Schema::hasColumn('campaign_miscellaneous', 'campaign_miscellaneous_file_id')) {
            Schema::table('campaign_miscellaneous', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_miscellaneous_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('campaign_miscellaneous_file_id');
            });
        }

        if (Schema::hasColumn('campaign_posts', 'campaign_post_file_id')) {
            Schema::table('campaign_posts', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_post_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('campaign_post_file_id');
            });
        }

        if (Schema::hasColumn('campaign_folders', 'campaign_folder_file_id')) {
            Schema::table('campaign_folders', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_folder_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('campaign_folder_file_id');
            });
        }

        if (Schema::hasColumn('campaign_videos', 'campaign_video_file_id')) {
            Schema::table('campaign_videos', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_video_file_id']);
                } catch (Exception $e) {
                    // Foreign key não existe, continuar
                }
                $table->dropColumn('campaign_video_file_id');
            });
        }

        // Correção para a tabela 'library'
        if (Schema::hasColumn('library', 'library_file_id')) {
            Schema::table('library', function (Blueprint $table) {
                // Remove a foreign key primeiro
                $table->dropForeign(['library_file_id']);
                // Remove a coluna em seguida
                $table->dropColumn('library_file_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar as colunas file_id
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_file_id')->nullable();
            $table->foreign('product_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('training_file_id')->nullable();
            $table->foreign('training_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_file_id')->nullable();
            $table->foreign('campaign_file_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::table('library', function (Blueprint $table) {
            $table->unsignedBigInteger('library_file_id')->nullable();
            $table->foreign('library_file_id')->references('id')->on('files')->onDelete('set null');
        });
    }
};