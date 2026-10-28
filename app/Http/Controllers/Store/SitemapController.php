<?php

declare(strict_types=1);

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://zad-capital.sa'), '/');
        if (! str_contains($baseUrl, 'localhost') && ! str_contains($baseUrl, '127.0.0.1')) {
            $baseUrl = 'https://zad-capital.sa';
        }

        $staticPages = [
            ['loc' => $baseUrl.'/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl.'/cars', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl.'/offers', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/finance-calculator', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/brands', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/blog', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/compare', 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/terms', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/privacy', 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        $cars = Car::where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->get();

        $blogs = BlogPost::published()
            ->select(['id', 'slug', 'updated_at', 'published_at'])
            ->get();

        $brands = Brand::where('is_active', true)
            ->whereHas('cars', fn ($q) => $q->where('is_active', true))
            ->select(['id', 'slug', 'updated_at'])
            ->get();

        $content = view('sitemap', [
            'baseUrl' => $baseUrl,
            'staticPages' => $staticPages,
            'cars' => $cars,
            'blogs' => $blogs,
            'brands' => $brands,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * /llms.txt: a plain-text summary of the business and its key pages for AI
     * assistants and search engines (https://llmstxt.org).
     */
    public function llms(): Response
    {
        $baseUrl = 'https://zad-capital.sa';
        $link = fn (string $path): string => $baseUrl.'/'.self::encodeSlug($path);

        $cars = Car::with(['brand', 'activeOffers'])
            ->where('is_active', true)
            ->get()
            ->sortByDesc(fn (Car $car) => [(int) $car->is_featured, (int) $car->views]);
        $minInstallment = (float) $cars->where('min_installment', '>', 0)->min('min_installment');
        $posts = BlogPost::published()->orderByDesc('published_at')->get();

        $lines = [
            '# زاد كابيتال (Zad Capital)',
            '',
            '> زاد كابيتال: بيع وتمويل السيارات الجديدة بالتقسيط في المملكة العربية السعودية، مع خيارات تمويل من البنوك والمصارف السعودية'
                .($minInstallment > 0 ? '، وأقساط شهرية تبدأ من '.number_format($minInstallment).' ريال.' : '.'),
            '',
            '- المقر: الرياض، حي اليرموك، طريق الصحابة',
            '- الهاتف الموحد: 920018657',
            '- عدد السيارات المتاحة: '.$cars->count().' سيارة من '.$cars->pluck('brand_id')->unique()->count().' ماركة',
            '- الأسعار والأقساط على الموقع محدثة من نظام المعرض مباشرة',
            '',
            '## الصفحات الأساسية',
            '',
            "- [كل السيارات بالتقسيط]({$baseUrl}/cars): السعر وأقل قسط شهري لكل سيارة",
            "- [عروض التمويل]({$baseUrl}/offers): العروض السارية",
            "- [حاسبة تمويل السيارات]({$baseUrl}/finance-calculator): احسب القسط حسب السعر والدفعة والمدة",
            "- [الماركات]({$baseUrl}/brands): السيارات حسب الماركة",
            "- [من نحن]({$baseUrl}/about)",
            "- [تواصل معنا]({$baseUrl}/contact)",
            '',
            '## أبرز السيارات',
            '',
        ];

        foreach ($cars->take(30) as $car) {
            $slug = (string) ($car->getTranslation('slug', 'ar', false) ?: $car->getTranslation('slug', 'en', false));
            $price = number_format((float) ($car->current_price ?? $car->cash_price));
            $installment = (float) $car->min_installment > 0 ? ' · قسط من '.number_format((float) $car->min_installment).' ريال' : '';
            $lines[] = "- [{$car->name}](".$link('cars/'.$slug)."): السعر {$price} ريال{$installment}";
        }

        if ($posts->isNotEmpty()) {
            array_push($lines, '', '## المقالات', '');
            foreach ($posts as $post) {
                $title = trim((string) ($post->getTranslation('title', 'ar', false) ?: $post->title));
                $excerpt = trim((string) ($post->getTranslation('excerpt', 'ar', false) ?: ''));
                $lines[] = "- [{$title}](".$link('blog/'.$post->slug).')'.($excerpt !== '' ? ": {$excerpt}" : '');
            }
        }

        array_push($lines, '', '## اختياري', '', "- [خريطة الموقع]({$baseUrl}/sitemap.xml)", '');

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    public static function encodeSlug(string $slug): string
    {
        $parts = explode('/', trim($slug, '/'));
        $encoded = array_map(static fn (string $part) => rawurlencode(urldecode($part)), $parts);

        return implode('/', $encoded);
    }
}
