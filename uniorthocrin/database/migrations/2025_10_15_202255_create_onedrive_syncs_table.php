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
        Schema::create('onedrive_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('syncable_type'); // Product, Training, Library, etc.
            $table->unsignedBigInteger('syncable_id'); // ID do item
            $table->string('file_path'); // Caminho do arquivo local
            $table->string('remote_path'); // Caminho no OneDrive
            $table->enum('status', ['pending', 'processing', 'synced', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->string('onedrive_url')->nullable(); // URL do arquivo no OneDrive
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->index(['syncable_type', 'syncable_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onedrive_syncs');
    }
};
