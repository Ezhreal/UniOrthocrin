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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['image', 'video', 'pdf', 'audio']);
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedBigInteger('size'); // bytes
            $table->string('extension', 10);
            $table->string('mime_type')->nullable();
            $table->integer('order')->default(0);
            
            // Relacionamento polimórfico
            $table->morphs('fileable');
            
            $table->timestamps();
            
            $table->index(['type', 'fileable_type']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
}; 