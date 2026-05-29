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
        Schema::create('ftp_syncs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('syncable');
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('local_path');
            $table->string('remote_path');
            $table->enum('status', ['pending', 'processing', 'synced', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ftp_syncs');
    }
};
