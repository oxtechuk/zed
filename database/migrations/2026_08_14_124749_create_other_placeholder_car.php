<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert custom placeholder brand with ID 9999 if it doesn't exist
        DB::table('brands')->insertOrIgnore([
            'id' => 9999,
            'name' => json_encode(['ar' => 'أخرى', 'en' => 'Other']),
            'slug' => 'other',
            'logo' => 'http://127.0.0.1:8000/images/brand.svg',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert custom placeholder car with ID 9999 if it doesn't exist
        DB::table('cars')->insertOrIgnore([
            'id' => 9999,
            'brand_id' => 9999,
            'name' => json_encode(['ar' => 'سيارة أخرى غير مدرجة', 'en' => 'Other unlisted car']),
            'slug' => json_encode(['ar' => 'سيارة-أخرى-غير-مدرجة', 'en' => 'other-unlisted-car']),
            'model' => 'Other',
            'year' => 2026,
            'type' => 'other',
            'cash_price' => 0,
            'min_down_payment' => 0,
            'min_installment' => 0,
            'is_active' => false,
            'is_featured' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('cars')->where('id', 9999)->delete();
        DB::table('brands')->where('id', 9999)->delete();
    }
};
