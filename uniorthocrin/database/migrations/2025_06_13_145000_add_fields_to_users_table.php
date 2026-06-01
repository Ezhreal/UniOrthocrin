<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type_id')) {
                $table->foreignId('user_type_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'last_access')) {
                $table->timestamp('last_access')->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });

        $hasIdx1 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_user_type_id_status_index'"))->isNotEmpty();
        if (!$hasIdx1 && Schema::hasColumn('users', 'user_type_id') && Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['user_type_id', 'status']);
            });
        }

        $hasIdx2 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_last_access_index'"))->isNotEmpty();
        if (!$hasIdx2 && Schema::hasColumn('users', 'last_access')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('last_access');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_type_id']);
            } catch (\Exception $e) {}

            $colsToDrop = [];
            if (Schema::hasColumn('users', 'user_type_id')) $colsToDrop[] = 'user_type_id';
            if (Schema::hasColumn('users', 'last_access')) $colsToDrop[] = 'last_access';
            if (Schema::hasColumn('users', 'status')) $colsToDrop[] = 'status';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
