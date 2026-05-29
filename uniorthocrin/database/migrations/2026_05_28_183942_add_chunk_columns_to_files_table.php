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
        Schema::table('files', function (Blueprint $table) {
            // chunk_upload_uuid: usado para rastrear o upload temporário associado
            $table->string('chunk_upload_uuid')->nullable()->after('id')->index();
            // status: indica se o arquivo está pronto ('ready') ou ainda sendo carregado ('pending')
            $table->string('status')->default('ready')->after('chunk_upload_uuid')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['chunk_upload_uuid', 'status']);
        });
    }
};
