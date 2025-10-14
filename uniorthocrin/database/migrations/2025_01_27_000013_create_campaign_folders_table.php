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
        Schema::create('campaign_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('state', ['MG/SP', 'DF/ES', 'RJ', 'RS', 'SC', 'PR', 'BA', 'CE', 'PE', 'GO', 'MT', 'MS', 'RO', 'AC', 'AP', 'AM', 'PA', 'RR', 'TO', 'PI', 'MA', 'RN', 'PB', 'AL', 'SE'])->default('MG/SP');
            $table->foreignId('campaign_folder_file_id')->nullable()->constrained('files')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_folders');
    }
};
