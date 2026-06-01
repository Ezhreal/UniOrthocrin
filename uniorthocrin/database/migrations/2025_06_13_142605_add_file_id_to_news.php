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
        if (!Schema::hasColumn('news', 'news_file_id')) {
            Schema::table('news', function (Blueprint $table) {
                $table->foreignId('news_file_id')->nullable()->constrained('files')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('news', 'news_file_id')) {
            Schema::table('news', function (Blueprint $table) {
                try {
                    $table->dropForeign(['news_file_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('news_file_id');
            });
        }
    }
};
