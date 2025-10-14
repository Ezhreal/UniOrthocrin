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
        Schema::create('download_options', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // Product, Campaign, etc.
            $table->unsignedBigInteger('resource_id');
            $table->string('option_name'); // 'all_images', 'all_videos', 'complete'
            $table->text('description')->nullable();
            $table->unsignedBigInteger('estimated_size')->nullable(); // bytes
            $table->timestamps();
            
            $table->index(['resource_type', 'resource_id']);
            $table->unique(['resource_type', 'resource_id', 'option_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_options');
    }
}; 