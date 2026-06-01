<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'serie')) {
                $table->dropColumn('serie');
            }
            if (!Schema::hasColumn('products', 'product_series_id')) {
                $table->foreignId('product_series_id')->nullable()->constrained('product_series')->onDelete('set null');
            }
        });

        if (Schema::hasColumn('products', 'category_id') && !Schema::hasColumn('products', 'product_category_id')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropForeign(['category_id']);
                });
            } catch (\Exception $e) {}

            DB::statement('ALTER TABLE products CHANGE category_id product_category_id bigint unsigned null');

            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->foreign('product_category_id')->references('id')->on('product_categories');
                });
            } catch (\Exception $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'product_category_id') && !Schema::hasColumn('products', 'category_id')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropForeign(['product_category_id']);
                });
            } catch (\Exception $e) {}

            DB::statement('ALTER TABLE products CHANGE product_category_id category_id bigint unsigned null');

            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->foreign('category_id')->references('id')->on('product_categories');
                });
            } catch (\Exception $e) {}
        }

        Schema::table('products', function (Blueprint $table) {
            try {
                $table->dropForeign(['product_series_id']);
            } catch (\Exception $e) {}
            if (Schema::hasColumn('products', 'product_series_id')) {
                $table->dropColumn('product_series_id');
            }
            if (!Schema::hasColumn('products', 'serie')) {
                $table->string('serie')->nullable();
            }
        });
    }
};
