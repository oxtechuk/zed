<?php

use App\Models\BudgetRange;
use App\Models\HomeSection;
use App\Services\Cache\HomeCacheService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Truncate and insert new salary ranges in budget_ranges
        BudgetRange::query()->truncate();

        $ranges = [
            [
                'label' => ['ar' => 'من 3,000 إلى 5,000 ريال', 'en' => 'From 3,000 to 5,000 SAR'],
                'min' => 3000,
                'max' => 5000,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => ['ar' => 'من 5,000 إلى 7,000 ريال', 'en' => 'From 5,000 to 7,000 SAR'],
                'min' => 5000,
                'max' => 7000,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => ['ar' => 'من 7,000 إلى 10,000 ريال', 'en' => 'From 7,000 to 10,000 SAR'],
                'min' => 7000,
                'max' => 10000,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => ['ar' => 'أكثر من 10,000 ريال', 'en' => 'Over 10,000 SAR'],
                'min' => 10001,
                'max' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($ranges as $range) {
            BudgetRange::create($range);
        }

        // 2. Update home_sections key = 'budget'
        $budgetSection = HomeSection::where('key', 'budget')->first();
        if ($budgetSection) {
            $budgetSection->update([
                'title' => [
                    'ar' => 'سيارات حسب راتبك',
                    'en' => 'Cars by your salary',
                ],
                'description' => [
                    'ar' => 'اختر نطاق راتبك لرؤية السيارات المناسبة لك',
                    'en' => 'Select your salary range to see suitable cars',
                ],
                'badge' => [
                    'ar' => 'حسب الراتب',
                    'en' => 'By salary',
                ],
            ]);
        }

        // 3. Clear cache
        try {
            $cacheService = resolve(HomeCacheService::class);
            $cacheService->forgetHome();
            $cacheService->forgetSection('budget');
        } catch (Throwable $e) {
            // Silence if running in environment where service is not bound yet
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original budget ranges
        BudgetRange::query()->truncate();

        $ranges = [
            ['label' => ['ar' => 'أقل من 300 ألف', 'en' => 'Under 300k'], 'min' => 0, 'max' => 300000, 'sort_order' => 1, 'is_active' => true],
            ['label' => ['ar' => '300 - 500 ألف', 'en' => '300k - 500k'], 'min' => 300001, 'max' => 500000, 'sort_order' => 2, 'is_active' => true],
            ['label' => ['ar' => '500 - 800 ألف', 'en' => '500k - 800k'], 'min' => 500001, 'max' => 800000, 'sort_order' => 3, 'is_active' => true],
            ['label' => ['ar' => 'أكثر من 800 ألف', 'en' => 'Over 800k'], 'min' => 800001, 'max' => null, 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($ranges as $range) {
            BudgetRange::create($range);
        }

        $budgetSection = HomeSection::where('key', 'budget')->first();
        if ($budgetSection) {
            $budgetSection->update([
                'title' => [
                    'ar' => 'سيارات حسب ميزانيتك',
                    'en' => 'Cars by your budget',
                ],
                'description' => [
                    'ar' => 'اختر النطاق السعري المناسب لك',
                    'en' => 'Pick the price range that suits you',
                ],
                'badge' => [
                    'ar' => 'حسب الميزانية',
                    'en' => 'By budget',
                ],
            ]);
        }

        try {
            $cacheService = resolve(HomeCacheService::class);
            $cacheService->forgetHome();
            $cacheService->forgetSection('budget');
        } catch (Throwable $e) {
            // Silence
        }
    }
};
