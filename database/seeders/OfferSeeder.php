<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        // Check if there are cars to link to offers
        $cars = Car::all();

        if ($cars->isEmpty()) {
            return;
        }

        // Clean existing offers to avoid duplicates
        Offer::query()->delete();

        // 1. Ramadan Offer (Limited)
        $ramadanOffer = Offer::create([
            'title' => [
                'ar' => 'عرض رمضان الاستثنائي',
                'en' => 'Exceptional Ramadan Offer',
            ],
            'description' => [
                'ar' => 'تمويل بدون أرباح لأول 6 أشهر',
                'en' => 'Zero-profit financing for the first 6 months',
            ],
            'discount_percent' => 15,
            'tag' => 'limited',
            'special_price' => 110000,
            'special_installment' => 1400,
            'starts_at' => now(),
            'ends_at' => now()->addDays(4)->addHours(23)->addMinutes(40)->addSeconds(3),
            'is_active' => true,
        ]);
        // Link to some cars
        $ramadanOffer->cars()->sync($cars->take(3)->pluck('id'));

        // 2. Exclusive Offer
        $exclusiveOffer = Offer::create([
            'title' => [
                'ar' => 'عرض الصيف الحصري',
                'en' => 'Exclusive Summer Offer',
            ],
            'description' => [
                'ar' => 'تأمين مجاني للسنة الأولى وصيانة مجانية لمدة عامين',
                'en' => 'Free insurance for the first year and 2 years free maintenance',
            ],
            'discount_percent' => 10,
            'tag' => 'exclusive',
            'special_price' => 135000,
            'special_installment' => 1800,
            'starts_at' => now(),
            'ends_at' => now()->addDays(7)->addHours(12)->addMinutes(30),
            'is_active' => true,
        ]);
        $exclusiveOffer->cars()->sync($cars->slice(2, 3)->pluck('id'));

        // 3. New Offer
        $newOffer = Offer::create([
            'title' => [
                'ar' => 'عرض الموديلات الجديدة 2026',
                'en' => 'New 2026 Models Offer',
            ],
            'description' => [
                'ar' => 'بدون دفعة أولى وبدون رسوم إدارية لسيارات 2026',
                'en' => '0% down payment and zero admin fees for 2026 models',
            ],
            'discount_percent' => 5,
            'tag' => 'new',
            'special_price' => 90000,
            'special_installment' => 1200,
            'starts_at' => now(),
            'ends_at' => now()->addDays(12)->addHours(6),
            'is_active' => true,
        ]);
        $newOffer->cars()->sync($cars->slice(4, 2)->pluck('id'));

        // 4. Popular Offer
        $popularOffer = Offer::create([
            'title' => [
                'ar' => 'عرض التقسيط الميسر الأكثر طلباً',
                'en' => 'Most Requested Easy Installment Offer',
            ],
            'description' => [
                'ar' => 'أقساط شهرية مخفضة بالتعاون مع مصرف الراجحي',
                'en' => 'Reduced monthly installments in partnership with Al Rajhi Bank',
            ],
            'discount_percent' => 8,
            'tag' => 'popular',
            'special_price' => 115000,
            'special_installment' => 1500,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'is_active' => true,
        ]);
        $popularOffer->cars()->sync($cars->slice(1, 4)->pluck('id'));
    }
}
