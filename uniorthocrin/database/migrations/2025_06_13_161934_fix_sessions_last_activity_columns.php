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
        $hasIndex = collect(DB::select("SHOW INDEXES FROM sessions WHERE Key_name = 'sessions_last_activity_index'"))->isNotEmpty();
        if ($hasIndex) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('sessions_last_activity_index');
            });
        }

        if (Schema::hasColumn('sessions', 'last_activity')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropColumn('last_activity');
            });
        }
        
        Schema::table('sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('sessions', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->index();
            }
            if (!Schema::hasColumn('sessions', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('sessions', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('sessions', 'last_activity')) $colsToDrop[] = 'last_activity';
            if (Schema::hasColumn('sessions', 'created_at')) $colsToDrop[] = 'created_at';
            if (Schema::hasColumn('sessions', 'updated_at')) $colsToDrop[] = 'updated_at';
            
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('sessions', 'last_activity')) {
                $table->integer('last_activity')->index();
            }
        });
    }
};
