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
        if (Schema::hasColumn('products', 'product_file_id')) {
            Schema::table('products', function (Blueprint $table) {
                try {
                    $table->dropForeign(['product_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('product_file_id');
            });
        }

        if (Schema::hasColumn('trainings', 'training_file_id')) {
            Schema::table('trainings', function (Blueprint $table) {
                try {
                    $table->dropForeign(['training_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('training_file_id');
            });
        }

        if (Schema::hasColumn('campaigns', 'campaign_file_id')) {
            Schema::table('campaigns', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('campaign_file_id');
            });
        }

        if (Schema::hasColumn('campaign_miscellaneous', 'campaign_miscellaneous_file_id')) {
            Schema::table('campaign_miscellaneous', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_miscellaneous_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('campaign_miscellaneous_file_id');
            });
        }

        if (Schema::hasColumn('campaign_posts', 'campaign_post_file_id')) {
            Schema::table('campaign_posts', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_post_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('campaign_post_file_id');
            });
        }

        if (Schema::hasColumn('campaign_folders', 'campaign_folder_file_id')) {
            Schema::table('campaign_folders', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_folder_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('campaign_folder_file_id');
            });
        }

        if (Schema::hasColumn('campaign_videos', 'campaign_video_file_id')) {
            Schema::table('campaign_videos', function (Blueprint $table) {
                try {
                    $table->dropForeign(['campaign_video_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('campaign_video_file_id');
            });
        }

        if (Schema::hasColumn('library', 'library_file_id')) {
            Schema::table('library', function (Blueprint $table) {
                try {
                    $table->dropForeign(['library_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('library_file_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_file_id')) {
                $table->unsignedBigInteger('product_file_id')->nullable();
                $table->foreign('product_file_id')->references('id')->on('files')->onDelete('set null');
            }
        });

        Schema::table('trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('trainings', 'training_file_id')) {
                $table->unsignedBigInteger('training_file_id')->nullable();
                $table->foreign('training_file_id')->references('id')->on('files')->onDelete('set null');
            }
        });

        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'campaign_file_id')) {
                $table->unsignedBigInteger('campaign_file_id')->nullable();
                $table->foreign('campaign_file_id')->references('id')->on('files')->onDelete('set null');
            }
        });

        Schema::table('library', function (Blueprint $table) {
            if (!Schema::hasColumn('library', 'library_file_id')) {
                $table->unsignedBigInteger('library_file_id')->nullable();
                $table->foreign('library_file_id')->references('id')->on('files')->onDelete('set null');
            }
        });
    }
};
