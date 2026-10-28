<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Http\Controllers\Store\SitemapController;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Car;
use App\Services\Api\Store\SettingApiService;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Collection;

/**
 * Builds the plain HTML version of each storefront page that is rendered inside
 * #root before the React app mounts (crawlers that don't run JavaScript, such as
 * AI search bots, only see this), plus the shared structured-data nodes.
 */
final class SeoContentBuilder
{
    private ?array $footer = null;

    private ?Collection $cars = null;

    private ?Collection $posts = null;

    public function __construct(
        private readonly SettingApiService $settings,
        private readonly string $baseUrl = 'https://zad-capital.sa',
    ) {}

    // ------------------------------------------------------------------
    // Pages
    // ------------------------------------------------------------------

    public function home(string $intro): array
    {
        $cars = $this->activeCars()
            ->sortByDesc(fn (Car $car) => [(int) $car->is_featured, (int) $car->views])
            ->take(12);

        return $this->page('تمويل سيارات بالتقسيط في السعودية', [$intro], sections: [
            $this->carSection('أبرز السيارات المتاحة بالتقسيط', $cars),
            [
                'title' => 'خدماتنا',
                'items' => [
                    ['text' => 'كل السيارات المتاحة بالتقسيط', 'url' => $this->url('/cars')],
                    ['text' => 'عروض تمويل السيارات', 'url' => $this->url('/offers')],
                    ['text' => 'حاسبة تمويل السيارات', 'url' => $this->url('/finance-calculator')],
                    ['text' => 'ماركات السيارات', 'url' => $this->url('/brands')],
                ],
            ],
            $this->postSection('أحدث المقالات', $this->publishedPosts()->take(6)),
        ]);
    }

    public function cars(): array
    {
        $cars = $this->activeCars()->sortBy(fn (Car $car) => (float) $car->min_installment);

        return $this->page(
            'سيارات بالتقسيط في السعودية',
            ["كل السيارات المتاحة للتمويل بالتقسيط من زاد كابيتال ({$cars->count()} سيارة)، مع السعر وأقل قسط شهري لكل سيارة."],
            sections: [$this->carSection('السيارات المتاحة', $cars)],
            breadcrumb: [['الرئيسية', '/'], ['السيارات', null]],
        );
    }

    public function car(Car $car, string $carTitle): array
    {
        $facts = array_filter([
            ['السعر', $this->money($car->current_price ?? $car->cash_price)],
            ['أقل قسط شهري', $this->money($car->min_installment)],
            ['الدفعة الأولى', (float) $car->min_down_payment > 0 ? $this->money($car->min_down_payment) : 'بدون دفعة أولى'],
            ['التوفر', $car->availability_status === 'available' ? 'متوفرة للاستلام' : 'متاحة بالطلب'],
            ['الماركة', $car->brand?->name],
            ['الموديل', $car->year ? (string) $car->year : null],
            ['القوة', $car->specs['hp'] ?? null],
            ['الوقود', $car->specs['fuel'] ?? null],
            ['ناقل الحركة', $car->specs['gearbox'] ?? null],
            ['عدد المقاعد', $car->specs['seats'] ?? null],
        ], fn (array $fact) => filled($fact[1]));

        $description = trim(strip_tags((string) ($car->getTranslation('description', 'ar', false) ?: '')));

        return $this->page(
            $carTitle,
            $description !== '' ? preg_split('/\R+/u', $description) : [],
            facts: array_values($facts),
            sections: [
                $this->postSection('مقالات عن هذه السيارة', $this->postsLinkingTo('/cars/' . $this->arSlug($car))),
                [
                    'title' => 'احسب قسطك',
                    'items' => [['text' => 'حاسبة تمويل السيارات', 'url' => $this->url('/finance-calculator')]],
                ],
            ],
            breadcrumb: [['الرئيسية', '/'], ['السيارات', '/cars'], [$car->name, null]],
        );
    }

    public function brand(Brand $brand, string $brandName): array
    {
        $cars = $this->activeCars()
            ->where('brand_id', $brand->id)
            ->sortBy(fn (Car $car) => (float) $car->min_installment);

        return $this->page(
            "سيارات {$brandName} بالتقسيط",
            ["سيارات {$brandName} المتاحة للتمويل بالتقسيط من زاد كابيتال ({$cars->count()} سيارة)، مع السعر وأقل قسط شهري."],
            sections: [$this->carSection("سيارات {$brandName}", $cars)],
            breadcrumb: [['الرئيسية', '/'], ['الماركات', '/brands'], [$brandName, null]],
        );
    }

    public function brands(): array
    {
        $brands = $this->activeCars()
            ->groupBy('brand_id')
            ->map(function (Collection $cars) {
                $brand = $cars->first()->brand;
                $slug = $brand?->slug ?: (string) $brand?->id;

                return [
                    'text' => 'سيارات ' . $brand?->name . ' بالتقسيط',
                    'url' => $this->url('/brands/' . SitemapController::encodeSlug($slug)),
                    'meta' => $cars->count() . ' سيارة · قسط من ' . $this->money($cars->min('min_installment')),
                ];
            })
            ->filter(fn (array $item) => $item['url'] !== $this->url('/brands/'))
            ->sortBy('text')
            ->values()
            ->all();

        return $this->page(
            'ماركات السيارات المتاحة بالتقسيط',
            sections: [['title' => 'الماركات', 'items' => $brands]],
            breadcrumb: [['الرئيسية', '/'], ['الماركات', null]],
        );
    }

    public function blogIndex(): array
    {
        return $this->page(
            'مدونة زاد كابيتال',
            ['نصائح وأسعار وأقساط السيارات وطرق التمويل في السعودية.'],
            sections: [$this->postSection('المقالات', $this->publishedPosts())],
            breadcrumb: [['الرئيسية', '/'], ['المدونة', null]],
        );
    }

    public function article(BlogPost $post): array
    {
        $date = optional($post->published_at ?? $post->created_at)->format('Y-m-d');

        return $this->page(
            $this->postTitle($post),
            $date ? ["تاريخ النشر: {$date}"] : [],
            html: (string) $post->getTranslation('content', 'ar', false),
            breadcrumb: [['الرئيسية', '/'], ['المدونة', '/blog'], [$this->postTitle($post), null]],
        );
    }

    /** Simple pages (offers, calculator, about, contact, ...). */
    public function simple(string $h1, string $intro, array $links = []): array
    {
        return $this->page($h1, [$intro], sections: $links ? [['title' => 'روابط', 'items' => $links]] : [], breadcrumb: [['الرئيسية', '/'], [$h1, null]]);
    }

    // ------------------------------------------------------------------
    // Structured data
    // ------------------------------------------------------------------

    public function organization(): array
    {
        $footer = $this->footer();
        $contact = $footer['contact'] ?? [];
        $branch = $footer['about_branches'][0] ?? [];

        return array_filter([
            '@type' => 'Organization',
            '@id' => $this->url('/#organization'),
            'name' => 'زاد كابيتال',
            'alternateName' => 'Zad Capital',
            'url' => $this->baseUrl,
            'logo' => ['@type' => 'ImageObject', 'url' => $this->logoUrl()],
            'email' => $contact['email'] ?? null,
            'telephone' => '+966-920018657',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'طريق الصحابة، حي اليرموك',
                'addressLocality' => 'الرياض',
                'addressCountry' => 'SA',
            ],
            'hasMap' => $branch['map_link'] ?? null,
            'areaServed' => ['@type' => 'Country', 'name' => 'المملكة العربية السعودية'],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+966-920018657',
                'contactType' => 'customer service',
                'areaServed' => 'SA',
                'availableLanguage' => ['Arabic', 'English'],
            ],
            'sameAs' => array_values(array_filter(array_column($footer['social_media'] ?? [], 'url'))) ?: null,
        ], fn ($value) => $value !== null);
    }

    public function logoUrl(): string
    {
        $footer = $this->footer();

        return $footer['logo'] ?: ($footer['header_logo'] ?: $this->url('/favicon.svg'));
    }

    /** schema.org vehicle properties for a car page. */
    public function vehicleProperties(Car $car): array
    {
        $seats = (int) preg_replace('/\D+/', '', (string) ($car->specs['seats'] ?? ''));

        return array_filter([
            'model' => $car->model,
            'vehicleModelDate' => $car->year ? (string) $car->year : null,
            'fuelType' => $car->specs['fuel'] ?? null,
            'vehicleTransmission' => $car->specs['gearbox'] ?? null,
            'seatingCapacity' => $seats > 0 ? $seats : null,
            'vehicleEngine' => filled($car->specs['hp'] ?? null) ? ['@type' => 'EngineSpecification', 'name' => $car->specs['hp']] : null,
        ], fn ($value) => $value !== null && $value !== '');
    }

    /**
     * Question/answer pairs from the article's FAQ block: the H2 that contains
     * "أسئلة شائعة", then each H3 (question) with the paragraphs that follow it.
     *
     * @return list<array{question: string, answer: string}>
     */
    public function faqFromHtml(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $faq = [];
        $inFaq = false;
        $current = null;

        foreach ($dom->getElementsByTagName('div')->item(0)?->childNodes ?? [] as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($node->tagName);
            $text = trim(preg_replace('/\s+/u', ' ', $node->textContent));

            if ($tag === 'h2') {
                if ($current) {
                    $faq[] = $current;
                    $current = null;
                }
                $inFaq = str_contains($text, 'أسئلة شائعة');

                continue;
            }
            if (! $inFaq) {
                continue;
            }
            if ($tag === 'h3') {
                if ($current) {
                    $faq[] = $current;
                }
                $current = ['question' => $text, 'answer' => ''];
            } elseif ($current && in_array($tag, ['p', 'ul', 'ol'], true)) {
                $current['answer'] = trim($current['answer'] . ' ' . $text);
            }
        }
        if ($current) {
            $faq[] = $current;
        }

        return array_values(array_filter($faq, fn (array $item) => $item['question'] !== '' && $item['answer'] !== ''));
    }

    public function postTitle(BlogPost $post): string
    {
        return trim((string) ($post->getTranslation('title', 'ar', false) ?: $post->title));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function page(string $h1, array $intro = [], array $facts = [], ?string $html = null, array $sections = [], array $breadcrumb = []): array
    {
        $footer = $this->footer();

        return [
            'h1' => $h1,
            'intro' => array_values(array_filter(array_map('trim', $intro))),
            'facts' => $facts,
            'html' => $html,
            'sections' => array_values(array_filter($sections, fn (array $section) => ! empty($section['items']))),
            'breadcrumb' => array_map(fn (array $crumb) => ['name' => $crumb[0], 'url' => $crumb[1] ? $this->url($crumb[1]) : null], $breadcrumb),
            'contact' => [
                'phone' => '920018657',
                'whatsapp' => trim((string) preg_replace('/[^\d+ ]/u', '', (string) ($footer['contact']['whatsapp'] ?? ''))),
                'email' => $footer['contact']['email'] ?? null,
                'address' => $footer['contact']['address'] ?? null,
            ],
        ];
    }

    private function carSection(string $title, Collection $cars): array
    {
        return [
            'title' => $title,
            'items' => $cars->map(fn (Car $car) => [
                'text' => $car->name,
                'url' => $this->url('/cars/' . SitemapController::encodeSlug($this->arSlug($car))),
                'meta' => trim(implode(' · ', array_filter([
                    (float) $car->min_installment > 0 ? 'قسط من ' . $this->money($car->min_installment) : null,
                    'السعر ' . $this->money($car->current_price ?? $car->cash_price),
                ]))),
            ])->values()->all(),
        ];
    }

    private function postSection(string $title, Collection $posts): array
    {
        return [
            'title' => $title,
            'items' => $posts->map(fn (BlogPost $post) => [
                'text' => $this->postTitle($post),
                'url' => $this->url('/blog/' . SitemapController::encodeSlug((string) $post->slug)),
                'meta' => trim((string) ($post->getTranslation('excerpt', 'ar', false) ?: '')),
            ])->values()->all(),
        ];
    }

    /** @return Collection<int, Car> */
    private function activeCars(): Collection
    {
        return $this->cars ??= Car::with(['brand', 'activeOffers'])->where('is_active', true)->get();
    }

    /** @return Collection<int, BlogPost> */
    private function publishedPosts(): Collection
    {
        return $this->posts ??= BlogPost::published()->orderByDesc('published_at')->get();
    }

    private function postsLinkingTo(string $path): Collection
    {
        $needles = [$path, SitemapController::encodeSlug(ltrim($path, '/'))];

        return $this->publishedPosts()->filter(function (BlogPost $post) use ($needles) {
            $content = (string) $post->getTranslation('content', 'ar', false);

            return str_contains($content, $needles[0]) || str_contains($content, $needles[1]);
        })->values();
    }

    private function arSlug(Car $car): string
    {
        return (string) ($car->getTranslation('slug', 'ar', false) ?: $car->getTranslation('slug', 'en', false));
    }

    private function money(mixed $amount): ?string
    {
        return (float) $amount > 0 ? number_format((float) $amount) . ' ريال' : null;
    }

    private function url(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    private function footer(): array
    {
        return $this->footer ??= $this->settings->footer();
    }
}
