<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_visibilities', function (Blueprint $table) {
            $table->id();
            $table->string('feature'); // Ex: menu_marketing, banner_marketing, bloco_marketing
            $table->unsignedBigInteger('user_type_id');
            $table->boolean('can_view')->default(true);
            $table->timestamps();

            $table->unique(['feature', 'user_type_id']);
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_visibilities');
    }
};