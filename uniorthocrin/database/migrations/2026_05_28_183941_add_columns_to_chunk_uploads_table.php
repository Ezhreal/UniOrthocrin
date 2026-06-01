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
        Schema::table('chunk_uploads', function (Blueprint $table) {
            if (!Schema::hasColumn('chunk_uploads', 'upload_status')) {
                $table->enum('upload_status', ['uploading', 'merging', 'completed', 'error'])->default('uploading')->after('status');
            }
            if (!Schema::hasColumn('chunk_uploads', 'upload_progress')) {
                $table->unsignedTinyInteger('upload_progress')->default(0)->after('upload_status');
            }
            if (!Schema::hasColumn('chunk_uploads', 'original_name')) {
                $table->string('original_name')->nullable()->after('filename');
            }
            if (!Schema::hasColumn('chunk_uploads', 'file_size')) {
                $table->unsignedBigInteger('file_size')->default(0)->after('total_size');
            }
            if (!Schema::hasColumn('chunk_uploads', 'attempts')) {
                $table->integer('attempts')->default(0)->after('uploaded_chunks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chunk_uploads', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('chunk_uploads', 'upload_status')) $colsToDrop[] = 'upload_status';
            if (Schema::hasColumn('chunk_uploads', 'upload_progress')) $colsToDrop[] = 'upload_progress';
            if (Schema::hasColumn('chunk_uploads', 'original_name')) $colsToDrop[] = 'original_name';
            if (Schema::hasColumn('chunk_uploads', 'file_size')) $colsToDrop[] = 'file_size';
            if (Schema::hasColumn('chunk_uploads', 'attempts')) $colsToDrop[] = 'attempts';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
