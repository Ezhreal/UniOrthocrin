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
            // upload_status: enum('uploading','merging','completed','error') default 'uploading'
            $table->enum('upload_status', ['uploading', 'merging', 'completed', 'error'])->default('uploading')->after('status');
            // upload_progress: tinyint (0-100)
            $table->unsignedTinyInteger('upload_progress')->default(0)->after('upload_status');
            // original_name: string
            $table->string('original_name')->nullable()->after('filename');
            // file_size: bigint
            $table->unsignedBigInteger('file_size')->default(0)->after('total_size');
            // attempts: para controle do comando de sincronização
            $table->integer('attempts')->default(0)->after('uploaded_chunks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chunk_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'upload_status',
                'upload_progress',
                'original_name',
                'file_size',
                'attempts'
            ]);
        });
    }
};
