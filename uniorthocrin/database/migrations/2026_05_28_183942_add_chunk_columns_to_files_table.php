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
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'chunk_upload_uuid')) {
                $table->string('chunk_upload_uuid')->nullable()->after('id');
            }
            if (!Schema::hasColumn('files', 'status')) {
                $table->string('status')->default('ready')->after('chunk_upload_uuid');
            }
        });

        $hasIdx1 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_chunk_upload_uuid_index'"))->isNotEmpty();
        if (!$hasIdx1 && Schema::hasColumn('files', 'chunk_upload_uuid')) {
            Schema::table('files', function (Blueprint $table) {
                $table->index('chunk_upload_uuid');
            });
        }

        $hasIdx2 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_status_index'"))->isNotEmpty();
        if (!$hasIdx2 && Schema::hasColumn('files', 'status')) {
            Schema::table('files', function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $hasIdx1 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_chunk_upload_uuid_index'"))->isNotEmpty();
            if ($hasIdx1) {
                $table->dropIndex(['chunk_upload_uuid']);
            }
            $hasIdx2 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_status_index'"))->isNotEmpty();
            if ($hasIdx2) {
                $table->dropIndex(['status']);
            }

            $colsToDrop = [];
            if (Schema::hasColumn('files', 'chunk_upload_uuid')) $colsToDrop[] = 'chunk_upload_uuid';
            if (Schema::hasColumn('files', 'status')) $colsToDrop[] = 'status';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
