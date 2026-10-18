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

    public static function encodeSlug(string $slug): string
    {
        $parts = explode('/', trim($slug, '/'));
        $encoded = array_map(static fn (string $part) => rawurlencode(urldecode($part)), $parts);

        return implode('/', $encoded);
    }
}
