<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_types', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });

        // Popular slugs baseados no nome
        $types = DB::table('user_types')->get();
        foreach ($types as $type) {
            $slug = Str::slug($type->name);
            // Ajuste manual para slugs específicos se necessário
            if ($type->id == 1) $slug = 'admin';
            
            DB::table('user_types')->where('id', $type->id)->update(['slug' => $slug]);
        }

        Schema::table('user_types', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_types', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
