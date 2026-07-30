<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get first employee or create a default one
        $employee = Employee::first();
        if (! $employee) {
            $employee = Employee::create([
                'name' => 'أدمن زاد',
                'email' => 'admin@knoz.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
        }

        // 2. Create Categories
        $categoriesData = [
            [
                'name' => ['ar' => 'اتجاهات', 'en' => 'Trends'],
                'slug' => 'trends',
                'icon' => 'graph-up',
                'sort_order' => 1,
            ],
            [
                'name' => ['ar' => 'نصائح تمويلية', 'en' => 'Finance Tips'],
                'slug' => 'finance',
                'icon' => 'wallet2',
                'sort_order' => 2,
            ],
            [
                'name' => ['ar' => 'صيانة وسيارات', 'en' => 'Maintenance'],
                'slug' => 'maintenance',
                'icon' => 'wrench-adjustable',
                'sort_order' => 3,
            ],
            [
                'name' => ['ar' => 'أخبار السيارات', 'en' => 'News'],
                'slug' => 'news',
                'icon' => 'newspaper',
                'sort_order' => 4,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = BlogCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'icon' => $catData['icon'],
                    'sort_order' => $catData['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Clear existing blog posts to avoid duplicate seeded content
        BlogPost::truncate();
        \Illuminate\Support\Facades\DB::table('blog_post_category')->truncate();

        // 4. Create Blog Posts
        $postsData = [
            [
                'title' => [
                    'ar' => 'مستقبل السيارات الكهربائية في المملكة العربية السعودية',
                    'en' => 'The Future of Electric Cars in Saudi Arabia',
                ],
                'excerpt' => [
                    'ar' => 'مع تسارع وتيرة التحول نحو الطاقة الكهربائية، كيف يتشكل مستقبل السيارات الكهربائية في السوق السعودي؟',
                    'en' => 'With the accelerating transition towards clean energy, how is the future of electric cars shaping up in the Saudi market?',
                ],
                'content' => [
                    'ar' => "تتسارع وتيرة اعتماد السيارات الكهربائية في المملكة تماشياً مع رؤية 2030.\n\nتعد البنية التحتية لمحطات الشحن السريع في تطور مستمر، وتوفر الدولة حوافز ممتازة لاقتناء المركبات الكهربائية الصديقة للبيئة.\n\nمن المتوقع أن تشهد السنوات القادمة زيادة كبيرة في أعداد السيارات الكهربائية وتنوع الطرازات المتاحة.",
                    'en' => "The pace of electric vehicle adoption in the Kingdom is accelerating in line with Vision 2030.\n\nThe infrastructure for fast-charging stations is constantly developing, and the state provides excellent incentives for purchasing eco-friendly electric vehicles.\n\nOver the coming years, we expect a major increase in the number of electric cars and the variety of available models.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?q=80&w=800&auto=format&fit=crop',
                'tag' => 'limited',
                'is_featured' => true,
                'categories' => ['trends', 'news'],
            ],
            [
                'title' => [
                    'ar' => 'دليلك الشامل لتمويل السيارات بدون دفعة أولى',
                    'en' => 'Your Ultimate Guide to Car Financing with Zero Down Payment',
                ],
                'excerpt' => [
                    'ar' => 'كيف يمكنك الحصول على أفضل عروض تمويل السيارات بدون دفعة أولى؟ إليك الشروط والمستندات المطلوبة.',
                    'en' => 'How can you get the best car financing offers with zero down payment? Here are the required terms and documents.',
                ],
                'content' => [
                    'ar' => "يبحث الكثير من المشترين عن تمويل سيارة بدون سداد دفعة أولى لتقليل الأعباء المالية الأولية.\n\nنوضح في هذا المقال الشروط الواجب توفرها، مثل الحد الأدنى للراتب، سجل ائتماني ممتاز، والجهات التمويلية والبنوك التي تقدم هذه المزايا الخاصة في المملكة.\n\nتأكد دائماً من مقارنة معدل النسبة السنوي (APR) قبل اتخاذ قرارك التمويلي.",
                    'en' => "Many buyers look for car financing without paying a down payment to reduce initial financial burdens.\n\nIn this article, we explain the requirements, such as minimum salary, excellent credit score, and financial institutions and banks offering these special advantages in the Kingdom.\n\nAlways make sure to compare the Annual Percentage Rate (APR) before making your financing decision.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=800&auto=format&fit=crop',
                'tag' => 'exclusive',
                'is_featured' => false,
                'categories' => ['finance'],
            ],
            [
                'title' => [
                    'ar' => 'نصائح هامة للحفاظ على محرك سيارتك في حرارة الصيف',
                    'en' => 'Essential Tips to Maintain Your Car Engine in Summer Heat',
                ],
                'excerpt' => [
                    'ar' => 'تجنب الأعطال المفاجئة للسيارة خلال درجات الحرارة المرتفعة مع هذه النصائح البسيطة والعملية.',
                    'en' => 'Avoid sudden car breakdowns during high temperatures with these simple and practical tips.',
                ],
                'content' => [
                    'ar' => "يتعرض محرك السيارة لضغط هائل خلال فصل الصيف في منطقة الخليج بسبب درجات الحرارة المرتفعة.\n\nأهم النصائح تشمل: التحقق من مستوى سائل التبريد (الرديتر)، فحص صلاحية الإطارات وضغط الهواء، وتغيير زيت المحرك ولزوجته المناسبة للصيف في الموعد المحدد.\n\nالصيانة الوقائية تحميك من تعطل المكيف ومشاكل ارتفاع حرارة المحرك.",
                    'en' => "The car engine faces massive pressure during summer in the Gulf region due to high temperatures.\n\nTop tips include: checking coolant levels, inspecting tire condition and air pressure, and changing engine oil at the scheduled interval.\n\nPreventive maintenance protects you from AC failure and engine overheating problems.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?q=80&w=800&auto=format&fit=crop',
                'tag' => 'popular',
                'is_featured' => false,
                'categories' => ['maintenance'],
            ],
            [
                'title' => [
                    'ar' => 'أفضل السيارات العائلية مبيعاً في الأسواق لعام 2026',
                    'en' => 'Top-Selling Family Cars in the Market for 2026',
                ],
                'excerpt' => [
                    'ar' => 'مراجعة شاملة لأبرز السيارات العائلية التي تجمع بين الأمان، المساحة، والرفاهية لعام 2026.',
                    'en' => 'A comprehensive review of the top family cars combining safety, space, and luxury for 2026.',
                ],
                'content' => [
                    'ar' => "تعتبر السلامة والراحة هما الأولوية القصوى للعائلات عند اختيار سيارتهم الجديدة.\n\nنستعرض في هذا المقال طرازات الدفع الرباعي والـ SUV الأوسع والأنسب للعائلات هذا العام، بما في ذلك ميزات الترفيه والاتصال بالمقاعد الخلفية وحجم صندوق الأمتعة الواسع.\n\nشاهد مقارنتنا المفصلة للأسعار واستهلاك الوقود.",
                    'en' => "Safety and comfort are the top priorities for families when choosing their new car.\n\nIn this article, we review the roomiest SUV models best suited for families this year, including rear-seat entertainment features and luggage capacity.\n\nCheck out our detailed comparison of pricing and fuel consumption.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=800&auto=format&fit=crop',
                'tag' => 'new',
                'is_featured' => false,
                'categories' => ['trends', 'news'],
            ],
            [
                'title' => [
                    'ar' => 'كيف تختار التأمين المناسب لسيارتك الجديدة؟',
                    'en' => 'How to Choose the Right Insurance for Your New Car?',
                ],
                'excerpt' => [
                    'ar' => 'تعرف على الفروقات بين التأمين ضد الغير والتأمين الشامل وكيف تختار العرض الأوفر والأكثر أماناً.',
                    'en' => 'Learn the differences between third-party and comprehensive insurance and how to choose the most cost-effective and secure offer.',
                ],
                'content' => [
                    'ar' => "يعد التأمين على السيارة خطوة إلزامية وهامة لحماية استثمارك المالي وممتلكات الآخرين.\n\nنقارن هنا بين التأمين الإلزامي (ضد الغير) والتأمين الشامل الذي يغطي سيارتك حتى في حال ارتكابك للحادث أو الكوارث الطبيعية والسرقة.\n\nإليك كيف تحصل على خصومات عدم وجود مطالبات عند تجديد وثيقتك.",
                    'en' => "Car insurance is a mandatory and important step to protect your financial investment and the property of others.\n\nHere we compare third-party insurance and comprehensive insurance that covers your car even in case of fault, natural disasters, or theft.\n\nLearn how to get no-claims discounts when renewing your policy.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?q=80&w=800&auto=format&fit=crop',
                'tag' => 'new',
                'is_featured' => false,
                'categories' => ['finance'],
            ],
            [
                'title' => [
                    'ar' => 'أهم الميزات التي يجب البحث عنها في سيارتك القادمة',
                    'en' => 'Top Features to Look For in Your Next Car',
                ],
                'excerpt' => [
                    'ar' => 'من أنظمة الأمان المتطورة إلى التكنولوجيا الذكية، ما هي الميزات التي تضيف قيمة حقيقية لسيارتك؟',
                    'en' => 'From advanced safety systems to smart technology, what features add real value to your car?',
                ],
                'content' => [
                    'ar' => "مع تطور التكنولوجيا، أصبحت السيارات الحديثة مليئة بالميزات الذكية وأنظمة الأمان المعقدة.\n\nأهم الأنظمة التي نوصي بها تشمل: الكبح التلقائي في حالات الطوارئ، التنبيه عند الخروج من المسار، كاميرات الرؤية المحيطية 360 درجة، ودعم Apple CarPlay و Android Auto.\n\nتأكد من اختيار الميزات التي تناسب استخدامك اليومي وتساهم في رفع قيمة إعادة البيع.",
                    'en' => "With technology evolution, modern cars are packed with smart features and complex safety systems.\n\nTop systems we recommend include: Automatic Emergency Braking, Lane Departure Warning, 360-degree cameras, and support for Apple CarPlay & Android Auto.\n\nMake sure to choose features that fit your daily use and contribute to a better resale value.",
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop',
                'tag' => 'exclusive',
                'is_featured' => false,
                'categories' => ['maintenance', 'trends'],
            ],
        ];

        foreach ($postsData as $pData) {
            $post = BlogPost::create([
                'employee_id' => $employee->id,
                'title' => $pData['title'],
                'slug' => Str::slug($pData['title']['en']) . '-' . rand(100, 999),
                'excerpt' => $pData['excerpt'],
                'content' => $pData['content'],
                'thumbnail' => $pData['thumbnail'],
                'tag' => $pData['tag'],
                'is_featured' => $pData['is_featured'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 10)),
            ]);

            // Sync categories
            $syncIds = [];
            foreach ($pData['categories'] as $catSlug) {
                if (isset($categories[$catSlug])) {
                    $syncIds[] = $categories[$catSlug]->id;
                }
            }
            $post->categories()->sync($syncIds);
        }
    }
}
