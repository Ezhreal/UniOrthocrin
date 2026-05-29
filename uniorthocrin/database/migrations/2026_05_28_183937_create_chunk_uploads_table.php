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
        Schema::create('chunk_uploads', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('total_size');
            $table->integer('total_chunks');
            $table->integer('uploaded_chunks')->default(0);
            $table->string('status')->default('uploading');
            $table->string('local_path')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chunk_uploads');
    }
};
