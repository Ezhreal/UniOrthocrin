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
            $table->string('optimized_path')->nullable()->after('path');
            $table->string('thumbnail_sm_path')->nullable()->after('optimized_path');
            $table->string('thumbnail_md_path')->nullable()->after('thumbnail_sm_path');
            $table->string('thumbnail_lg_path')->nullable()->after('thumbnail_md_path');
            $table->boolean('is_optimized')->default(false)->after('thumbnail_lg_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn([
                'optimized_path',
                'thumbnail_sm_path',
                'thumbnail_md_path',
                'thumbnail_lg_path',
                'is_optimized'
            ]);
        });
    }
};
