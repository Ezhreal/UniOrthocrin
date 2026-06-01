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
        if (Schema::hasColumn('sessions', 'id')) {
            DB::statement('ALTER TABLE sessions MODIFY id varchar(255) not null');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sessions', 'id')) {
            DB::statement('ALTER TABLE sessions MODIFY id varchar(191) not null');
        }
    }
};
