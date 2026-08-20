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
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'is_active')) {
                $table->index('is_active', 'idx_brands_is_active');
            }
        });

        Schema::table('car_categories', function (Blueprint $table) {
            if (Schema::hasColumn('car_categories', 'is_active')) {
                $table->index('is_active', 'idx_car_categories_is_active');
            }
            if (Schema::hasColumn('car_categories', 'sort_order')) {
                $table->index('sort_order', 'idx_car_categories_sort_order');
            }
        });

        Schema::table('car_types', function (Blueprint $table) {
            if (Schema::hasColumn('car_types', 'is_active')) {
                $table->index('is_active', 'idx_car_types_is_active');
            }
            if (Schema::hasColumn('car_types', 'sort_order')) {
                $table->index('sort_order', 'idx_car_types_sort_order');
            }
        });

        Schema::table('brand_types', function (Blueprint $table) {
            if (Schema::hasColumn('brand_types', 'is_active')) {
                $table->index('is_active', 'idx_brand_types_is_active');
            }
            if (Schema::hasColumn('brand_types', 'sort_order')) {
                $table->index('sort_order', 'idx_brand_types_sort_order');
            }
        });

        Schema::table('partners', function (Blueprint $table) {
            if (Schema::hasColumn('partners', 'sort_order')) {
                $table->index('sort_order', 'idx_partners_sort_order');
            }
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->index(['is_active', 'brand_id'], 'idx_cars_active_brand');
            $table->index(['is_active', 'is_highlighted'], 'idx_cars_active_highlight');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('idx_brands_is_active');
        });

        Schema::table('car_categories', function (Blueprint $table) {
            $table->dropIndex('idx_car_categories_is_active');
            $table->dropIndex('idx_car_categories_sort_order');
        });

        Schema::table('car_types', function (Blueprint $table) {
            $table->dropIndex('idx_car_types_is_active');
            $table->dropIndex('idx_car_types_sort_order');
        });

        Schema::table('brand_types', function (Blueprint $table) {
            $table->dropIndex('idx_brand_types_is_active');
            $table->dropIndex('idx_brand_types_sort_order');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex('idx_partners_sort_order');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex('idx_cars_active_brand');
            $table->dropIndex('idx_cars_active_highlight');
        });
    }
};
