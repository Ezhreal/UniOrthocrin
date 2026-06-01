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
        if (Schema::hasColumn('users', 'last_access')) {
            DB::statement("ALTER TABLE users MODIFY last_access timestamp NULL COMMENT 'Último acesso do usuário ao sistema'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'last_access')) {
            DB::statement("ALTER TABLE users MODIFY last_access timestamp NULL");
        }
    }
};
