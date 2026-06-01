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
            $hasIndex1 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_fileable_type_fileable_id_index'"))->isNotEmpty();
            if ($hasIndex1) {
                $table->dropIndex('files_fileable_type_fileable_id_index');
            }
            $hasIndex2 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_type_fileable_type_index'"))->isNotEmpty();
            if ($hasIndex2) {
                $table->dropIndex('files_type_fileable_type_index');
            }
            
            if (Schema::hasColumn('files', 'fileable_type')) {
                $table->dropColumn('fileable_type');
            }
            if (Schema::hasColumn('files', 'fileable_id')) {
                $table->dropColumn('fileable_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'fileable_type')) {
                $table->string('fileable_type')->nullable();
            }
            if (!Schema::hasColumn('files', 'fileable_id')) {
                $table->unsignedBigInteger('fileable_id')->nullable();
            }
        });

        $hasIndex1 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_fileable_type_fileable_id_index'"))->isNotEmpty();
        if (!$hasIndex1 && Schema::hasColumn('files', 'fileable_type') && Schema::hasColumn('files', 'fileable_id')) {
            Schema::table('files', function (Blueprint $table) {
                $table->index(['fileable_type', 'fileable_id'], 'files_fileable_type_fileable_id_index');
            });
        }

        $hasIndex2 = collect(DB::select("SHOW INDEXES FROM files WHERE Key_name = 'files_type_fileable_type_index'"))->isNotEmpty();
        if (!$hasIndex2 && Schema::hasColumn('files', 'type') && Schema::hasColumn('files', 'fileable_type')) {
            Schema::table('files', function (Blueprint $table) {
                $table->index(['type', 'fileable_type'], 'files_type_fileable_type_index');
            });
        }
    }
};
