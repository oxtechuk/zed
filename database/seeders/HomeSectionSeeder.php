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
    use LocalImagesTrait;

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
                'title' => ['ar' => 'ابحث عن سيارتك المثالية', 'en' => 'Find your perfect car'],
                'subtitle' => ['ar' => 'ابحث عن سيارة بالاسم، العلامة التجارية، الموديل، أو المواصفات...', 'en' => 'Search by name, brand, model, or specs...'], // used as the search input placeholder
                'button_text' => ['ar' => 'بحث', 'en' => 'Search'],
            ],
            [
                'key' => 'brands', 'sort_order' => 4,
                'title' => ['ar' => 'العلامات التجارية', 'en' => 'Brands'],
                'subtitle' => ['ar' => 'تسوق حسب العلامة التجارية المفضلة لديك', 'en' => 'Shop by your favorite brand'],
            ],
            [
                'key' => 'featured_cars', 'sort_order' => 5,
                'badge' => ['ar' => 'وصل حديثاً', 'en' => 'Just arrived'],
                'title' => ['ar' => 'أحدث السيارات واصلة حديثاً', 'en' => 'Latest cars just arrived'],
                'subtitle' => ['ar' => 'آخر ما وصل إلى المعرض', 'en' => 'Recently added to the showroom'],
                'button_text' => ['ar' => 'عرض الكل', 'en' => 'View all'],
                'button_url' => '/cars',
            ],
            [
                'key' => 'offers', 'sort_order' => 55,
                'badge' => ['ar' => 'عرض', 'en' => 'Offer'],
                'title' => ['ar' => 'ايضاً من عروضنا', 'en' => 'Also from our offers'],
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
                'background_image' => $this->localImage('offer.png'),
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
                'badge' => ['ar' => 'حسب الراتب', 'en' => 'By salary'],
                'title' => ['ar' => 'سيارات حسب راتبك', 'en' => 'Cars by your salary'],
                'description' => ['ar' => 'اختر نطاق راتبك لرؤية السيارات المناسبة لك', 'en' => 'Select your salary range to see suitable cars'],
                'button_text' => ['ar' => 'عرض الكل', 'en' => 'View all'],
            ],
            [
                'key' => 'finance', 'sort_order' => 9,
                'title' => ['ar' => 'استلم سيارتك في 4 خطوات', 'en' => 'Receive your car in 4 steps'],
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
        $slides = [
            [
                'title' => ['ar' => 'المنصة الأولى لتمويل السيارات الفاخرة', 'en' => 'The leading luxury car financing platform'],
                'subtitle' => ['ar' => 'زاد كابيتال', 'en' => 'Zad Capital'],
                'description' => ['ar' => 'نجمع بين الخبرة المالية والشغف بالسيارات', 'en' => 'Financial expertise meets a passion for cars'],
                'image_desktop' => $this->localImage('home_hero.png'),
                'image_mobile' => $this->localImage('home_hero.png'),
                'button_text' => ['ar' => 'تصفح السيارات', 'en' => 'Browse cars'],
                'button_url' => '/cars',
                'badge' => ['ar' => 'الأكثر ثقة', 'en' => 'Most trusted'],
                'sort_order' => 1,
            ],
            [
                'title' => ['ar' => 'عروض العيد على أحدث الموديلات', 'en' => 'Eid offers on the latest models'],
                'subtitle' => ['ar' => 'عروض محدودة', 'en' => 'Limited-time offers'],
                'description' => ['ar' => 'احتفل بالعيد بسيارتك الجديدة بأقساط ميسرة', 'en' => 'Celebrate Eid with your new car and easy installments'],
                'image_desktop' => $this->localImage('eid.png'),
                'image_mobile' => $this->localImage('eid.png'),
                'button_text' => ['ar' => 'احسب قسطك الآن', 'en' => 'Calculate now'],
                'button_url' => '/calculator',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::query()->updateOrCreate(['sort_order' => $slide['sort_order']], $slide + ['is_active' => true]);
        }
    }

    private function seedPromoCards(): void
    {
        $cards = [
            [
                'type' => 'large',
                'title' => ['ar' => 'تمويل السيارات', 'en' => 'Car Financing'],
                'subtitle' => ['ar' => 'خطط تمويل مرنة تصل حتى 84 شهراً', 'en' => 'Flexible plans up to 84 months'],
                'image' => $this->localImage('car1.png'),
                'button_text' => ['ar' => 'ابدأ الآن', 'en' => 'Get started'],
                'button_url' => '/calculator',
                'sort_order' => 1,
            ],
            [
                'type' => 'medium',
                'title' => ['ar' => 'شراء نقدي', 'en' => 'Cash Purchase'],
                'subtitle' => ['ar' => 'وفر أكثر بالدفع الكامل', 'en' => 'Save more paying in full'],
                'image' => $this->localImage('g1.png'),
                'button_text' => ['ar' => 'تصفح', 'en' => 'Browse'],
                'button_url' => '/cars',
                'sort_order' => 2,
            ],
            [
                'type' => 'small',
                'title' => ['ar' => 'عروض العيد', 'en' => 'Eid Offers'],
                'subtitle' => ['ar' => 'خصومات خاصة بمناسبة العيد', 'en' => 'Special Eid discounts'],
                'image' => $this->localImage('eid.png'),
                'button_text' => ['ar' => 'اطلب الآن', 'en' => 'Request now'],
                'button_url' => '/offers',
                'sort_order' => 3,
            ],
        ];

        foreach ($cards as $card) {
            PromoCard::query()->updateOrCreate(['sort_order' => $card['sort_order']], $card + ['is_active' => true]);
        }
    }

    private function seedFinanceSteps(): void
    {
        $steps = [
            ['number' => 1, 'title' => ['ar' => 'اختر سيارتك', 'en' => 'Choose your car'], 'description' => ['ar' => 'قمت بإختيار سيارتك من بين مئات السيارات على حسب احتياجك', 'en' => 'You picked your car from hundreds based on your needs'], 'icon' => 'car'],
            ['number' => 2, 'title' => ['ar' => 'قدّم طلبك', 'en' => 'Submit your request'], 'description' => ['ar' => 'أكمل نموذج التمويل الإلكتروني في دقائق معدودة', 'en' => 'Complete the online financing form in minutes'], 'icon' => 'file-text'],
            ['number' => 3, 'title' => ['ar' => 'الموافقة السريعة', 'en' => 'Fast approval'], 'description' => ['ar' => 'يتواصل معك فريقنا خلال 24 ساعة لاستكمال الإجراءات', 'en' => 'Our team contacts you within 24 hours to complete the process'], 'icon' => 'calculator'],
            ['number' => 4, 'title' => ['ar' => 'استلم سيارتك', 'en' => 'Receive your car'], 'description' => ['ar' => 'استلم مفاتيح سيارتك من المعرض أو بالتوصيل لباب بيتك', 'en' => 'Receive your keys at the showroom or delivered to your door'], 'icon' => 'key'],
        ];

        foreach ($steps as $index => $step) {
            FinanceStep::query()->updateOrCreate(['number' => $step['number']], $step + ['sort_order' => $index + 1, 'is_active' => true]);
        }
    }

    private function seedBudgetRanges(): void
    {
        // Delete existing budget ranges to avoid conflict with min values
        BudgetRange::query()->delete();

        $ranges = [
            ['label' => ['ar' => 'من 3,000 إلى 5,000 ريال', 'en' => 'From 3,000 to 5,000 SAR'], 'min' => 3000, 'max' => 5000],
            ['label' => ['ar' => 'من 5,000 إلى 7,000 ريال', 'en' => 'From 5,000 to 7,000 SAR'], 'min' => 5000, 'max' => 7000],
            ['label' => ['ar' => 'من 7,000 إلى 10,000 ريال', 'en' => 'From 7,000 to 10,000 SAR'], 'min' => 7000, 'max' => 10000],
            ['label' => ['ar' => 'أكثر من 10,000 ريال', 'en' => 'Over 10,000 SAR'], 'min' => 10001, 'max' => null],
        ];

        foreach ($ranges as $index => $range) {
            BudgetRange::query()->create($range + ['sort_order' => $index + 1]);
        }
    }

    /**
     * quick_links / service_links live alongside the rest of the footer's contact
     * data in the `settings` table (Setting::key = footer_quick_links / footer_service_links),
     * managed from the existing General Settings > Contact tab — not a dedicated model.
     */
    private function seedFooterLinks(): void
    {
        Setting::updateOrCreate(
            ['key' => 'footer_quick_links'],
            ['value' => [
                ['title' => ['ar' => 'من نحن', 'en' => 'About Us'], 'url' => '/about'],
                ['title' => ['ar' => 'المدونة', 'en' => 'Blog'], 'url' => '/blog'],
                ['title' => ['ar' => 'العروض', 'en' => 'Offers'], 'url' => '/offers'],
                ['title' => ['ar' => 'تواصل معنا', 'en' => 'Contact Us'], 'url' => '/contact'],
            ]]
        );

        Setting::updateOrCreate(
            ['key' => 'footer_service_links'],
            ['value' => [
                ['title' => ['ar' => 'تمويل السيارات', 'en' => 'Car Financing'], 'url' => '/calculator'],
                ['title' => ['ar' => 'شراء نقدي', 'en' => 'Cash Purchase'], 'url' => '/cars'],
                ['title' => ['ar' => 'حجز السيارات', 'en' => 'Car Booking'], 'url' => '/booking'],
                ['title' => ['ar' => 'طلب مخصص', 'en' => 'Custom Order'], 'url' => '/contact'],
            ]]
        );

        $contactSettings = [
            'footer_text' => ['ar' => '© 2026 زاد كابيتال. جميع الحقوق محفوظة.', 'en' => '© 2026 Zad Capital. All rights reserved.'],
            'contact_phone' => '+966 55 000 0000',
            'contact_email' => 'info@zadcapital.sa',
            'contact_address' => ['ar' => 'الرياض، المملكة العربية السعودية', 'en' => 'Riyadh, Saudi Arabia'],
        ];

        foreach ($contactSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
