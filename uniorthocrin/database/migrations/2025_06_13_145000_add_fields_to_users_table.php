<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_type_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('last_access')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->index(['user_type_id', 'status']);
            $table->index('last_access');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropColumn(['user_type_id', 'last_access', 'status']);
        });
    }
}; 
