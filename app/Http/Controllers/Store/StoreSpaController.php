<?php

declare(strict_types=1);

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarSlugRedirect;
use App\Models\Setting;
use App\Services\Api\Store\Helpers\SlugResolver;
use App\Services\Api\Store\SettingApiService;
use App\Services\Seo\SeoContentBuilder;
use App\Services\Seo\SeoEntryResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class StoreSpaController extends Controller
{
    protected string $baseUrl;
    protected string $siteName;
    protected string $defaultOgImage;
    protected SeoContentBuilder $seo;
    protected SeoEntryResolver $seoEntries;

    public function __construct()
    {
        $appUrl = rtrim(config('app.url', 'https://zad-capital.sa'), '/');
        if (! str_contains($appUrl, 'localhost') && ! str_contains($appUrl, '127.0.0.1')) {
            $this->baseUrl = 'https://zad-capital.sa';
        } else {
            $this->baseUrl = $appUrl;
        }

        $this->siteName = 'زاد كابيتال';
        $this->seo = new SeoContentBuilder(app(SettingApiService::class), $this->baseUrl);
        $this->defaultOgImage = $this->seo->logoUrl();
        $this->seoEntries = app(SeoEntryResolver::class);
    }

    public function home(): Response
    {
        $metaTitle = Setting::where('key', 'meta_title')->value('value');
        $metaDesc = Setting::where('key', 'meta_description')->value('value');
        if (! $metaTitle || ! $metaDesc) {
            $seoSettings = Setting::where('key', 'seo')->value('value') ?? [];
            $metaTitle = $metaTitle ?: ($seoSettings['meta_title'] ?? null);
            $metaDesc = $metaDesc ?: ($seoSettings['meta_description'] ?? null);
        }

        $title = $metaTitle ?: 'زاد كابيتال | تمويل سيارات بالتقسيط في السعودية';
        $description = $metaDesc ?: 'احصل على أفضل عروض تمويل وتقسيط السيارات في السعودية من زاد كابيتال. أقل قسط شهري وبدون دفعة أولى لجميع الماركات.';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                $this->seo->organization(),
                [
                    '@type' => 'WebSite',
                    '@id' => $this->baseUrl . '/#website',
                    'url' => $this->baseUrl,
                    'name' => 'زاد كابيتال',
                    'publisher' => ['@id' => $this->baseUrl . '/#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => $this->baseUrl . '/cars?search={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];

        return $this->renderSpa([
            'metaTitle' => $title,
            'metaDescription' => $description,
            'canonicalUrl' => $this->baseUrl . '/',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'jsonLd' => $jsonLd,
            'seoContent' => $this->seo->home($description),
        ]);
    }

    public function cars(Request $request): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'سيارات بالتقسيط في السعودية | زاد كابيتال',
            'metaDescription' => 'تصفح أحدث موديلات السيارات المتاحة للتمويل والتقسيط في المملكة من زاد كابيتال. احسب قسطك واختر سيارتك المفضلة.',
            'canonicalUrl' => $this->baseUrl . '/cars',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->cars(),
        ]);
    }

    public function car(string $slug): Response|RedirectResponse
    {
        $query = Car::with(['brand', 'images', 'activeOffers'])->where('is_active', true);
        SlugResolver::applyCarSlug($query, $slug);
        $car = $query->first();

        if (! $car) {
            try {
                if (Schema::hasTable('car_slug_redirects')) {
                    $decodedSlug = urldecode($slug);
                    $redirect = CarSlugRedirect::where('old_slug', $slug)
                        ->orWhere('old_slug', $decodedSlug)
                        ->first();

                    if ($redirect && ! empty($redirect->new_slug)) {
                        return redirect($this->baseUrl . '/cars/' . SitemapController::encodeSlug($redirect->new_slug), 301);
                    }
                }
            } catch (\Throwable) {
                // Table might not exist yet, continue to 404
            }

            return $this->notFound();
        }

        $carName = trim((string) ($car->getTranslation('name', 'ar') ?: $car->name));
        $carYear = ($car->year && ! str_contains($carName, (string) $car->year)) ? " {$car->year}" : '';
        $installmentPart = $car->min_installment
            ? ' · قسط من ' . number_format((float) $car->min_installment) . ' ريال'
            : '';

        $title = "{$carName}{$carYear} بالتقسيط{$installmentPart}";
        if (mb_strlen($title) > 65) {
            $title = "{$carName}{$carYear} بالتقسيط";
        }

        $priceFormatted = number_format((float) ($car->current_price ?? $car->cash_price ?? 0));
        $instFormatted = number_format((float) ($car->min_installment ?? 0));
        $description = "اشترِ {$carName} بالتقسيط من زاد كابيتال. السعر {$priceFormatted} ريال والقسط الشهري يبدأ من {$instFormatted} ريال. اطّلع على المواصفات واحسب قسطك.";

        $carImage = $car->main_image ?: $this->defaultOgImage;
        $canonicalUrl = $this->baseUrl . '/cars/' . SitemapController::encodeSlug($slug);

        $brandName = $car->brand?->name ?? 'زاد كابيتال';
        $availability = ($car->availability_status === 'available')
            ? 'https://schema.org/InStock'
            : 'https://schema.org/PreOrder';

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => ['Product', 'Car'],
                    'name' => "{$carName}{$carYear}",
                    'image' => $carImage,
                    'description' => $description,
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $brandName,
                    ],
                    ...$this->seo->vehicleProperties($car),
                    'offers' => [
                        '@type' => 'Offer',
                        'url' => $canonicalUrl,
                        'priceCurrency' => 'SAR',
                        'price' => (float) ($car->current_price ?? $car->cash_price ?? 0),
                        'itemCondition' => 'https://schema.org/NewCondition',
                        'availability' => $availability,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'الرئيسية',
                            'item' => $this->baseUrl . '/',
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'السيارات',
                            'item' => $this->baseUrl . '/cars',
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $carName,
                            'item' => $canonicalUrl,
                        ],
                    ],
                ],
            ],
        ];

        return $this->renderSpa([
            'metaTitle' => $title,
            'metaDescription' => $description,
            'canonicalUrl' => $canonicalUrl,
            'ogImage' => $carImage,
            'ogType' => 'product',
            'jsonLd' => $jsonLd,
            'seoContent' => $this->seo->car($car, "{$carName}{$carYear}"),
        ]);
    }

    public function blogIndex(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'مدونة زاد كابيتال | نصائح وأخبار تمويل وتقسيط السيارات',
            'metaDescription' => 'دليلك الشامل لمعرفة أحدث مستجدات سوق السيارات وحلول التمويل الذكية في المملكة العربية السعودية.',
            'canonicalUrl' => $this->baseUrl . '/blog',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'blog',
            'seoContent' => $this->seo->blogIndex(),
        ]);
    }

    public function blogShow(string $slug): Response
    {
        $post = BlogPost::published()
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->first();

        if (! $post) {
            return $this->notFound();
        }

        $title = $post->meta_title ?: ($post->getTranslation('title', 'ar') ?: $post->title);
        $title = "{$title} | زاد كابيتال";
        $description = $post->meta_description ?: ($post->getTranslation('excerpt', 'ar') ?: strip_tags((string) $post->content));
        $canonicalUrl = $this->baseUrl . '/blog/' . SitemapController::encodeSlug($slug);
        $postImage = $post->thumbnail ?: $this->defaultOgImage;

        $headline = $this->seo->postTitle($post);
        $content = (string) $post->getTranslation('content', 'ar', false);
        $faq = $this->seo->faqFromHtml($content);
        $category = $post->categories()->value('name');

        $graph = [
            array_filter([
                '@type' => 'Article',
                'headline' => $headline,
                'description' => $description,
                'image' => $postImage,
                'datePublished' => optional($post->published_at ?? $post->created_at)->toAtomString(),
                'dateModified' => optional($post->updated_at)->toAtomString(),
                'inLanguage' => 'ar',
                'articleSection' => is_array($category) ? ($category['ar'] ?? reset($category)) : $category,
                'wordCount' => count(preg_split('/\s+/u', trim(strip_tags($content)), -1, PREG_SPLIT_NO_EMPTY)),
                'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonicalUrl],
                'author' => ['@id' => $this->baseUrl . '/#organization'],
                'publisher' => ['@id' => $this->baseUrl . '/#organization'],
            ], fn ($value) => $value !== null && $value !== ''),
            $this->seo->organization(),
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => $this->baseUrl . '/'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'المدونة', 'item' => $this->baseUrl . '/blog'],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $headline, 'item' => $canonicalUrl],
                ],
            ],
        ];

        if ($faq) {
            $graph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn (array $item) => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                ], $faq),
            ];
        }

        $jsonLd = ['@context' => 'https://schema.org', '@graph' => $graph];

        return $this->renderSpa([
            'metaTitle' => $title,
            'metaDescription' => $description,
            'canonicalUrl' => $canonicalUrl,
            'ogImage' => $postImage,
            'ogType' => 'article',
            'jsonLd' => $jsonLd,
            'seoContent' => $this->seo->article($post),
        ]);
    }

    public function offers(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'عروض تمويل السيارات | زاد كابيتال',
            'metaDescription' => 'اكتشف أحدث العروض والتخفيضات الاستثنائية على تمويل وتقسيط السيارات في السعودية مع زاد كابيتال.',
            'canonicalUrl' => $this->baseUrl . '/offers',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('عروض تمويل السيارات', '', [['text' => 'كل السيارات المتاحة بالتقسيط', 'url' => $this->baseUrl . '/cars'], ['text' => 'حاسبة تمويل السيارات', 'url' => $this->baseUrl . '/finance-calculator']]),
        ]);
    }

    public function about(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'من نحن | زاد كابيتال لتمويل السيارات',
            'metaDescription' => 'تعرف على شركة زاد كابيتال، شريكك الأول في تمويل السيارات بأقساط ميسرة وأعلى معايير الشفافية بالمملكة.',
            'canonicalUrl' => $this->baseUrl . '/about',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('من نحن', '', [['text' => 'تواصل معنا', 'url' => $this->baseUrl . '/contact'], ['text' => 'السيارات المتاحة بالتقسيط', 'url' => $this->baseUrl . '/cars']]),
        ]);
    }

    public function contact(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'تواصل معنا | زاد كابيتال',
            'metaDescription' => 'تواصل مع مستشاري تمويل السيارات في زاد كابيتال عبر الهاتف الموحد أو واتساب، ويسعدنا خدمتكم على مدار الساعة.',
            'canonicalUrl' => $this->baseUrl . '/contact',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('تواصل معنا', '', [['text' => 'حاسبة تمويل السيارات', 'url' => $this->baseUrl . '/finance-calculator'], ['text' => 'طلب تمويل سيارة', 'url' => $this->baseUrl . '/request-car']]),
        ]);
    }

    public function financeCalculator(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'حاسبة تمويل السيارات الفورية | زاد كابيتال',
            'metaDescription' => 'احسب قسطك الشهري بدقة خلال ثوانٍ، وقارن بين خيارات البنوك وشركات التمويل المعتمدة في السعودية.',
            'canonicalUrl' => $this->baseUrl . '/finance-calculator',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('حاسبة تمويل السيارات', '', [['text' => 'كل السيارات المتاحة بالتقسيط', 'url' => $this->baseUrl . '/cars'], ['text' => 'عروض تمويل السيارات', 'url' => $this->baseUrl . '/offers'], ['text' => 'كيف تحسب قسط السيارة الشهري قبل الشراء', 'url' => $this->baseUrl . '/blog/كيف-تحسب-قسط-السيارة']]),
        ]);
    }

    public function brands(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'ماركات السيارات المتاحة بالتقسيط | زاد كابيتال',
            'metaDescription' => 'تصفح قائمة ماركات السيارات المتوفرة للتمويل الفوري، تويوتا، نيسان، هيونداي، كيا وغيرها بأفضل الأسعار.',
            'canonicalUrl' => $this->baseUrl . '/brands',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->brands(),
        ]);
    }

    public function brand(string $brandSlug): Response|RedirectResponse
    {
        $decoded = urldecode($brandSlug);
        $brand = Brand::where('is_active', true)
            ->where(function ($q) use ($brandSlug, $decoded) {
                $q->where('slug', $brandSlug)
                    ->orWhere('slug', $decoded)
                    ->orWhere('id', $brandSlug)
                    ->orWhere('name->ar', $decoded)
                    ->orWhere('name->en', $decoded);
            })
            ->first();

        if (! $brand) {
            return $this->notFound();
        }

        if ($brand->slug && $decoded !== $brand->slug) {
            return redirect($this->baseUrl . '/brands/' . SitemapController::encodeSlug($brand->slug), 301);
        }

        $brandName = trim((string) ($brand->getTranslation('name', 'ar') ?: $brand->name));
        $minInstallment = Car::where('brand_id', $brand->id)
            ->where('is_active', true)
            ->whereNotNull('min_installment')
            ->where('min_installment', '>', 0)
            ->min('min_installment');

        $minPart = $minInstallment
            ? ' · أقساط تبدأ من ' . number_format((float) $minInstallment) . ' ريال'
            : '';

        $title = "سيارات {$brandName} بالتقسيط 2026{$minPart} | زاد كابيتال";
        if (mb_strlen($title) > 65) {
            $title = "سيارات {$brandName} بالتقسيط 2026 | زاد كابيتال";
        }

        $description = "تصفح أحدث عروض وموديلات سيارات {$brandName} بالتقسيط في السعودية من زاد كابيتال. احسب قسطك الشهري واستفد من عروض التمويل الميسرة بدون دفعة أولى.";
        $canonicalSlug = $brand->slug ?: (string) $brand->id;
        $canonicalUrl = $this->baseUrl . '/brands/' . SitemapController::encodeSlug($canonicalSlug);
        $brandLogo = $brand->logo ?: $this->defaultOgImage;

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Brand',
                    'name' => $brandName,
                    'url' => $canonicalUrl,
                    'logo' => $brandLogo,
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'الرئيسية',
                            'item' => $this->baseUrl . '/',
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'الماركات',
                            'item' => $this->baseUrl . '/brands',
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $brandName,
                            'item' => $canonicalUrl,
                        ],
                    ],
                ],
            ],
        ];

        return $this->renderSpa([
            'metaTitle' => $title,
            'metaDescription' => $description,
            'canonicalUrl' => $canonicalUrl,
            'ogImage' => $brandLogo,
            'ogType' => 'website',
            'jsonLd' => $jsonLd,
            'seoContent' => $this->seo->brand($brand, $brandName),
        ]);
    }

    public function compare(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'مقارنة السيارات: السعر والمواصفات والقسط | زاد كابيتال',
            'metaDescription' => 'قارن بين مواصفات وأسعار وأقساط السيارات جنباً إلى جنب لتختار السيارة الأنسب لاحتياجاتك وميزانيتك.',
            'canonicalUrl' => $this->baseUrl . '/compare',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('مقارنة السيارات: السعر والمواصفات والقسط', '', [['text' => 'كل السيارات المتاحة بالتقسيط', 'url' => $this->baseUrl . '/cars']]),
        ]);
    }

    public function terms(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'الشروط والأحكام | زاد كابيتال',
            'metaDescription' => 'الشروط والأحكام الخاصة بخدمات الوساطة التمويلية وشراء وتمويل السيارات عبر شركة زاد كابيتال.',
            'canonicalUrl' => $this->baseUrl . '/terms',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('الشروط والأحكام', '', []),
        ]);
    }

    public function privacy(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'سياسة الخصوصية | زاد كابيتال',
            'metaDescription' => 'سياسة الخصوصية وحماية البيانات الشخصية لعملاء زاد كابيتال لتمويل السيارات في المملكة العربية السعودية.',
            'canonicalUrl' => $this->baseUrl . '/privacy',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'seoContent' => $this->seo->simple('سياسة الخصوصية', '', []),
        ]);
    }

    public function requestCar(): Response
    {
        return $this->renderSpa([
            'metaTitle' => 'طلب تمويل سيارة | زاد كابيتال',
            'metaDescription' => 'قدّم طلب تمويل سيارتك الآن بخطوات بسيطة وسيقوم فريقنا بمتابعة طلبك مع أفضل جهات التمويل.',
            'canonicalUrl' => $this->baseUrl . '/request-car',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'metaRobots' => 'noindex, follow',
        ]);
    }

    public function fallback(Request $request, string $any = ''): Response
    {
        return $this->notFound();
    }

    public function notFound(): Response
    {
        return response()->view('app', [
            'metaTitle' => 'الصفحة غير موجودة | زاد كابيتال',
            'metaDescription' => 'عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.',
            'ogImage' => $this->defaultOgImage,
            'ogType' => 'website',
            'metaRobots' => 'noindex, nofollow',
            'is404' => true,
        ], 404);
    }

    /**
     * Helper to render the SPA shell view with SEO payload.
     *
     * @param array<string, mixed> $seo
     */
    protected function renderSpa(array $seo, int $status = 200): Response
    {
        if ($status === 200 && ! empty($seo['canonicalUrl'])) {
            $seo = $this->applySeoEntry($seo);
        }

        if (isset($seo['seoContent']) && empty($seo['seoContent']['intro']) && ! empty($seo['metaDescription'])) {
            $seo['seoContent']['intro'] = [$seo['metaDescription']];
        }

        return response()->view('app', $seo, $status);
    }

    /**
     * Overlay the SEO team's copy for this page (seo_entries): title, meta description,
     * extra text and FAQ (also as FAQPage structured data).
     *
     * @param array<string, mixed> $seo
     * @return array<string, mixed>
     */
    private function applySeoEntry(array $seo): array
    {
        $entry = $this->seoEntries->resolve((string) parse_url((string) $seo['canonicalUrl'], PHP_URL_PATH));
        if (! $entry) {
            return $seo;
        }

        $seo['metaTitle'] = $entry['meta_title'] ?: ($seo['metaTitle'] ?? null);
        $seo['metaDescription'] = $entry['meta_description'] ?: ($seo['metaDescription'] ?? null);

        if (isset($seo['seoContent'])) {
            $seo['seoContent']['intro'] = array_merge($seo['seoContent']['intro'] ?? [], $entry['paragraphs']);
            $seo['seoContent']['faq'] = $entry['faq'];
        }

        if ($entry['faq'] !== []) {
            $faqPage = [
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn (array $item) => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ], $entry['faq']),
            ];

            $jsonLd = $seo['jsonLd'] ?? null;
            if (is_array($jsonLd) && isset($jsonLd['@graph'])) {
                $jsonLd['@graph'][] = $faqPage;
            } elseif (is_array($jsonLd) && $jsonLd !== []) {
                $context = $jsonLd['@context'] ?? 'https://schema.org';
                unset($jsonLd['@context']);
                $jsonLd = ['@context' => $context, '@graph' => [$jsonLd, $faqPage]];
            } else {
                $jsonLd = ['@context' => 'https://schema.org', '@graph' => [$faqPage]];
            }
            $seo['jsonLd'] = $jsonLd;
        }

        return $seo;
    }
}
