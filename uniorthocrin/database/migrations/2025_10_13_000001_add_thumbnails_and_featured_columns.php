<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('status');
            }
            if (!Schema::hasColumn('campaigns', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('thumbnail_path');
            }
            if (!Schema::hasColumn('campaigns', 'banner_path')) {
                $table->string('banner_path')->nullable()->after('is_featured');
            }
        });

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'thumbnail_path')) {
                    $table->string('thumbnail_path')->nullable()->after('status');
                }
            });
        }

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (!Schema::hasColumn('trainings', 'thumbnail_path')) {
                    $table->string('thumbnail_path')->nullable()->after('status');
                }
            });
        }

        if (Schema::hasTable('libraries')) {
            Schema::table('libraries', function (Blueprint $table) {
                if (!Schema::hasColumn('libraries', 'thumbnail_path')) {
                    $table->string('thumbnail_path')->nullable()->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'banner_path')) {
                $table->dropColumn('banner_path');
            }
            if (Schema::hasColumn('campaigns', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('campaigns', 'thumbnail_path')) {
                $table->dropColumn('thumbnail_path');
            }
        });

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'thumbnail_path')) {
                    $table->dropColumn('thumbnail_path');
                }
            });
        }

        if (Schema::hasTable('trainings')) {
            Schema::table('trainings', function (Blueprint $table) {
                if (Schema::hasColumn('trainings', 'thumbnail_path')) {
                    $table->dropColumn('thumbnail_path');
                }
            });
        }

        if (Schema::hasTable('libraries')) {
            Schema::table('libraries', function (Blueprint $table) {
                if (Schema::hasColumn('libraries', 'thumbnail_path')) {
                    $table->dropColumn('thumbnail_path');
                }
            });
        }
    }
};


