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
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'optimized_path')) {
                $table->string('optimized_path')->nullable()->after('path');
            }
            if (!Schema::hasColumn('files', 'thumbnail_sm_path')) {
                $table->string('thumbnail_sm_path')->nullable()->after('optimized_path');
            }
            if (!Schema::hasColumn('files', 'thumbnail_md_path')) {
                $table->string('thumbnail_md_path')->nullable()->after('thumbnail_sm_path');
            }
            if (!Schema::hasColumn('files', 'thumbnail_lg_path')) {
                $table->string('thumbnail_lg_path')->nullable()->after('thumbnail_md_path');
            }
            if (!Schema::hasColumn('files', 'is_optimized')) {
                $table->boolean('is_optimized')->default(false)->after('thumbnail_lg_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('files', 'optimized_path')) $colsToDrop[] = 'optimized_path';
            if (Schema::hasColumn('files', 'thumbnail_sm_path')) $colsToDrop[] = 'thumbnail_sm_path';
            if (Schema::hasColumn('files', 'thumbnail_md_path')) $colsToDrop[] = 'thumbnail_md_path';
            if (Schema::hasColumn('files', 'thumbnail_lg_path')) $colsToDrop[] = 'thumbnail_lg_path';
            if (Schema::hasColumn('files', 'is_optimized')) $colsToDrop[] = 'is_optimized';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
