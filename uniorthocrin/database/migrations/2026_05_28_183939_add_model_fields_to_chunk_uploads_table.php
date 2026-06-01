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
            if (!Schema::hasColumn('chunk_uploads', 'model_type')) {
                $table->string('model_type')->nullable()->after('local_path');
            }
            if (!Schema::hasColumn('chunk_uploads', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            }
            if (!Schema::hasColumn('chunk_uploads', 'property')) {
                $table->string('property')->nullable()->after('model_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chunk_uploads', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('chunk_uploads', 'model_type')) $colsToDrop[] = 'model_type';
            if (Schema::hasColumn('chunk_uploads', 'model_id')) $colsToDrop[] = 'model_id';
            if (Schema::hasColumn('chunk_uploads', 'property')) $colsToDrop[] = 'property';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
