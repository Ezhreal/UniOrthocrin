<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('chunk_uploads', 'status')) {
            DB::statement("ALTER TABLE chunk_uploads MODIFY status varchar(255) not null default 'uploading'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('chunk_uploads', 'status')) {
            DB::statement("ALTER TABLE chunk_uploads MODIFY status enum('uploading', 'merging', 'completed', 'failed') not null default 'uploading'");
        }
    }
};
