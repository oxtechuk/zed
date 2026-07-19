<?php

namespace Database\Seeders;

use App\Models\BudgetRange;
use App\Models\FinanceStep;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\PromoCard;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSections();
        $this->seedHeroSlides();
        $this->seedPromoCards();
        $this->seedFinanceSteps();
        $this->seedBudgetRanges();
        $this->seedFooterLinks();
    }

    private function seedSections(): void
    {
        $sections = [
            ['key' => 'hero', 'sort_order' => 1],
            ['key' => 'promo', 'sort_order' => 2],
            [
                'key' => 'search', 'sort_order' => 3,
                'title' => ['ar' => 'ابحث عن سيارتك', 'en' => 'Find your car'],
                'subtitle' => ['ar' => 'ابحث عن سيارة...', 'en' => 'Search for a car...'], // used as the search input placeholder
                'button_text' => ['ar' => 'بحث', 'en' => 'Search'],
            ],
            [
                'key' => 'brands', 'sort_order' => 4,
                'title' => ['ar' => 'العلامات التجارية', 'en' => 'Brands'],
                'subtitle' => ['ar' => 'تسوق حسب العلامة التجارية المفضلة لديك', 'en' => 'Shop by your favorite brand'],
            ],
            [
                'key' => 'featured_cars', 'sort_order' => 5,
                'badge' => ['ar' => 'مميز', 'en' => 'Featured'],
                'title' => ['ar' => 'سيارات مميزة', 'en' => 'Featured Cars'],
                'subtitle' => ['ar' => 'أفضل السيارات المختارة لك', 'en' => 'Hand-picked cars for you'],
                'button_text' => ['ar' => 'عرض الكل', 'en' => 'View all'],
                'button_url' => '/cars',
            ],
            [
                'key' => 'offers', 'sort_order' => 55,
                'badge' => ['ar' => 'عرض', 'en' => 'Offer'],
                'title' => ['ar' => 'العروض الحالية', 'en' => 'Current Offers'],
                'button_text' => ['ar' => 'عرض كل العروض', 'en' => 'View all offers'],
                'button_url' => '/offers',
            ],
            [
                'key' => 'featured_banner', 'sort_order' => 6,
                'badge' => ['ar' => 'عرض خاص', 'en' => 'Special offer'],
                'title' => ['ar' => 'تمويل بدون أرباح لأول 6 أشهر', 'en' => 'Zero-profit financing for the first 6 months'],
                'subtitle' => ['ar' => 'العرض ينتهي قريباً', 'en' => 'Offer ends soon'],
                'description' => ['ar' => 'استفد من عرضنا الحصري على تمويل السيارات الفاخرة.', 'en' => 'Take advantage of our exclusive luxury car financing offer.'],
                'button_text' => ['ar' => 'اطلع على العرض', 'en' => 'See the offer'],
                'button_url' => '/offers',
            ],
            [
                'key' => 'latest_cars', 'sort_order' => 7,
                'badge' => ['ar' => 'جديد', 'en' => 'New'],
                'title' => ['ar' => 'أحدث السيارات', 'en' => 'Latest Cars'],
                'subtitle' => ['ar' => 'آخر ما أضيف إلى المعرض', 'en' => 'Recently added to the showroom'],
                'button_text' => ['ar' => 'عرض الكل', 'en' => 'View all'],
                'button_url' => '/cars?sort=latest',
            ],
            [
                'key' => 'budget', 'sort_order' => 8,
                'badge' => ['ar' => 'حسب الميزانية', 'en' => 'By budget'],
                'title' => ['ar' => 'سيارات حسب ميزانيتك', 'en' => 'Cars by your budget'],
                'description' => ['ar' => 'اختر النطاق السعري المناسب لك', 'en' => 'Pick the price range that suits you'],
                'button_text' => ['ar' => 'عرض الكل', 'en' => 'View all'],
            ],
            [
                'key' => 'finance', 'sort_order' => 9,
                'title' => ['ar' => 'كيف يعمل التمويل؟', 'en' => 'How financing works'],
                'subtitle' => ['ar' => 'أربع خطوات بسيطة تفصلك عن سيارتك القادمة', 'en' => 'Four simple steps to your next car'],
                'button_text' => ['ar' => 'احسب قسطك الآن', 'en' => 'Calculate your installment'],
                'button_url' => '/calculator',
            ],
            ['key' => 'footer', 'sort_order' => 10],
        ];

        foreach ($sections as $section) {
            HomeSection::query()->updateOrCreate(['key' => $section['key']], $section + ['is_active' => true]);
        }
    }

    private function seedHeroSlides(): void
    {
        if (HeroSlide::query()->exists()) {
            return;
        }

        HeroSlide::create([
            'title' => ['ar' => 'المنصة الأولى لتمويل السيارات الفاخرة', 'en' => 'The leading luxury car financing platform'],
            'subtitle' => ['ar' => 'زاد كابيتال', 'en' => 'Zad Capital'],
            'description' => ['ar' => 'نجمع بين الخبرة المالية والشغف بالسيارات', 'en' => 'Financial expertise meets a passion for cars'],
            'image_desktop' => 'hero/slide-1-desktop.jpg',
            'image_mobile' => 'hero/slide-1-mobile.jpg',
            'button_text' => ['ar' => 'تصفح السيارات', 'en' => 'Browse cars'],
            'button_url' => '/cars',
            'badge' => ['ar' => 'الأكثر ثقة', 'en' => 'Most trusted'],
            'sort_order' => 1,
        ]);

        HeroSlide::create([
            'title' => ['ar' => 'احصل على تمويلك خلال دقائق', 'en' => 'Get financed in minutes'],
            'subtitle' => ['ar' => 'حاسبة التمويل', 'en' => 'Financing calculator'],
            'description' => ['ar' => 'احسب قسطك الشهري بدون التزام', 'en' => 'Estimate your monthly installment, no commitment'],
            'image_desktop' => 'hero/slide-2-desktop.jpg',
            'image_mobile' => 'hero/slide-2-mobile.jpg',
            'button_text' => ['ar' => 'احسب الآن', 'en' => 'Calculate now'],
            'button_url' => '/calculator',
            'sort_order' => 2,
        ]);
    }

    private function seedPromoCards(): void
    {
        if (PromoCard::query()->exists()) {
            return;
        }

        PromoCard::create([
            'type' => 'large',
            'title' => ['ar' => 'تمويل السيارات', 'en' => 'Car Financing'],
            'subtitle' => ['ar' => 'خطط تمويل مرنة تصل حتى 84 شهراً', 'en' => 'Flexible plans up to 84 months'],
            'image' => 'promo/financing.jpg',
            'button_text' => ['ar' => 'ابدأ الآن', 'en' => 'Get started'],
            'button_url' => '/calculator',
            'sort_order' => 1,
        ]);

        PromoCard::create([
            'type' => 'medium',
            'title' => ['ar' => 'شراء نقدي', 'en' => 'Cash Purchase'],
            'subtitle' => ['ar' => 'وفر أكثر بالدفع الكامل', 'en' => 'Save more paying in full'],
            'image' => 'promo/cash.jpg',
            'button_text' => ['ar' => 'تصفح', 'en' => 'Browse'],
            'button_url' => '/cars',
            'sort_order' => 2,
        ]);

        PromoCard::create([
            'type' => 'small',
            'title' => ['ar' => 'طلب مخصص', 'en' => 'Custom Order'],
            'subtitle' => ['ar' => 'لم تجد سيارتك؟ اطلبها', 'en' => "Can't find your car? Request it"],
            'image' => 'promo/custom-order.jpg',
            'button_text' => ['ar' => 'اطلب الآن', 'en' => 'Request now'],
            'button_url' => '/contact',
            'sort_order' => 3,
        ]);
    }

    private function seedFinanceSteps(): void
    {
        if (FinanceStep::query()->exists()) {
            return;
        }

        $steps = [
            ['number' => 1, 'title' => ['ar' => 'اختر سيارتك', 'en' => 'Choose your car'], 'description' => ['ar' => 'تصفح مجموعتنا الواسعة من السيارات الفاخرة', 'en' => 'Browse our wide range of luxury cars'], 'icon' => 'car'],
            ['number' => 2, 'title' => ['ar' => 'احسب قسطك', 'en' => 'Calculate your installment'], 'description' => ['ar' => 'استخدم الحاسبة لمعرفة قسطك الشهري', 'en' => 'Use the calculator to see your monthly payment'], 'icon' => 'calculator'],
            ['number' => 3, 'title' => ['ar' => 'قدّم طلبك', 'en' => 'Submit your request'], 'description' => ['ar' => 'أكمل بياناتك الشخصية والمالية', 'en' => 'Complete your personal and financial details'], 'icon' => 'file-text'],
            ['number' => 4, 'title' => ['ar' => 'استلم سيارتك', 'en' => 'Get your car'], 'description' => ['ar' => 'بعد الموافقة، استلم سيارتك خلال أيام', 'en' => 'Once approved, receive your car within days'], 'icon' => 'key'],
        ];

        foreach ($steps as $index => $step) {
            FinanceStep::create($step + ['sort_order' => $index + 1]);
        }
    }

    private function seedBudgetRanges(): void
    {
        if (BudgetRange::query()->exists()) {
            return;
        }

        $ranges = [
            ['label' => ['ar' => 'أقل من 300 ألف', 'en' => 'Under 300k'], 'min' => 0, 'max' => 300000],
            ['label' => ['ar' => '300 - 500 ألف', 'en' => '300k - 500k'], 'min' => 300001, 'max' => 500000],
            ['label' => ['ar' => '500 - 800 ألف', 'en' => '500k - 800k'], 'min' => 500001, 'max' => 800000],
            ['label' => ['ar' => 'أكثر من 800 ألف', 'en' => 'Over 800k'], 'min' => 800001, 'max' => null],
        ];

        foreach ($ranges as $index => $range) {
            BudgetRange::create($range + ['sort_order' => $index + 1]);
        }
    }

    /**
     * quick_links / service_links live alongside the rest of the footer's contact
     * data in the `settings` table (Setting::key = footer_quick_links / footer_service_links),
     * managed from the existing General Settings > Contact tab — not a dedicated model.
     */
    private function seedFooterLinks(): void
    {
        if (Setting::where('key', 'footer_quick_links')->exists()) {
            return;
        }

        Setting::create(['key' => 'footer_quick_links', 'value' => [
            ['title' => ['ar' => 'من نحن', 'en' => 'About Us'], 'url' => '/about'],
            ['title' => ['ar' => 'المدونة', 'en' => 'Blog'], 'url' => '/blog'],
            ['title' => ['ar' => 'العروض', 'en' => 'Offers'], 'url' => '/offers'],
            ['title' => ['ar' => 'تواصل معنا', 'en' => 'Contact Us'], 'url' => '/contact'],
        ]]);

        Setting::create(['key' => 'footer_service_links', 'value' => [
            ['title' => ['ar' => 'تمويل السيارات', 'en' => 'Car Financing'], 'url' => '/calculator'],
            ['title' => ['ar' => 'شراء نقدي', 'en' => 'Cash Purchase'], 'url' => '/cars'],
            ['title' => ['ar' => 'حجز السيارات', 'en' => 'Car Booking'], 'url' => '/booking'],
            ['title' => ['ar' => 'طلب مخصص', 'en' => 'Custom Order'], 'url' => '/contact'],
        ]]);
    }
}
