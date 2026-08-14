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
        // Truncate and seed the calculator_banks table with the exact list requested
        DB::table('calculator_banks')->delete();

        $banks = [
            ['name' => 'مصرف الراجحي', 'annual_rate' => 3.8, 'sort_order' => 1, 'is_active' => true],
            ['name' => 'البنك الأهلي السعودي', 'annual_rate' => 3.5, 'sort_order' => 2, 'is_active' => true],
            ['name' => 'بنك الرياض', 'annual_rate' => 4.5, 'sort_order' => 3, 'is_active' => true],
            ['name' => 'مصرف الإنماء', 'annual_rate' => 4.0, 'sort_order' => 4, 'is_active' => true],
            ['name' => 'بنك البلاد', 'annual_rate' => 4.2, 'sort_order' => 5, 'is_active' => true],
            ['name' => 'البنك السعودي الفرنسي', 'annual_rate' => 4.1, 'sort_order' => 6, 'is_active' => true],
            ['name' => 'البنك العربي الوطني', 'annual_rate' => 4.3, 'sort_order' => 7, 'is_active' => true],
            ['name' => 'بنك الجزيرة', 'annual_rate' => 4.4, 'sort_order' => 8, 'is_active' => true],
            ['name' => 'بنك الأول ( ساب )', 'annual_rate' => 4.0, 'sort_order' => 9, 'is_active' => true],
            ['name' => 'بنك الخليج الدولي', 'annual_rate' => 4.6, 'sort_order' => 10, 'is_active' => true],
            ['name' => 'بنك الإمارات دبي الوطني', 'annual_rate' => 4.5, 'sort_order' => 11, 'is_active' => true],
        ];

        foreach ($banks as $index => $bank) {
            DB::table('calculator_banks')->insert(array_merge($bank, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('calculator_banks')->delete();

        // Restore original default banks
        $banks = [
            ['name' => 'البنك الأهلي', 'annual_rate' => 3.5, 'sort_order' => 1, 'is_active' => true],
            ['name' => 'البنك الراجحي', 'annual_rate' => 3.8, 'sort_order' => 2, 'is_active' => true],
            ['name' => 'بنك الرياض', 'annual_rate' => 4.5, 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($banks as $bank) {
            DB::table('calculator_banks')->insert(array_merge($bank, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
