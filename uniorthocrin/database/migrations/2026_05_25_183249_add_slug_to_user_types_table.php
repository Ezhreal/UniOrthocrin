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
        if (!Schema::hasColumn('user_types', 'slug')) {
            Schema::table('user_types', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });

            // Popular slugs baseados no nome
            $types = DB::table('user_types')->get();
            foreach ($types as $type) {
                $slug = Str::slug($type->name);
                if ($type->id == 1) $slug = 'admin';
                
                DB::table('user_types')->where('id', $type->id)->update(['slug' => $slug]);
            }

            DB::statement('ALTER TABLE user_types MODIFY slug varchar(255) not null');

            $hasUnique = collect(DB::select("SHOW INDEXES FROM user_types WHERE Key_name = 'user_types_slug_unique'"))->isNotEmpty();
            if (!$hasUnique) {
                Schema::table('user_types', function (Blueprint $table) {
                    $table->unique('slug');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('user_types', 'slug')) {
            Schema::table('user_types', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
