<?php

namespace App\Services\Cache;

use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\BrandType;
use App\Models\BudgetRange;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\CarType;
use App\Models\FinanceStep;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\Offer;
use App\Models\Partner;
use App\Models\PromoCard;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class HomeCacheService extends BaseCacheService
{
    public function rememberHomeData(): array
    {
        $result = $this->remember('home.data', function () {
            $featuredCars = Car::with(['brand', 'images'])
                ->where('is_featured', true)
                ->where('is_active', true)
                ->latest()
                ->limit(8)
                ->get();

            $activeOffers = Offer::active()
                ->with(['cars.brand', 'cars.images', 'cars.activeOffers'])
                ->limit(4)
                ->get();

            $brands = Brand::where('is_active', true)
                ->withCount('cars')
                ->get();

            $latestPosts = BlogPost::published()
                ->latest('published_at')
                ->limit(3)
                ->get();

            $stats = [
                'cars' => Car::where('is_active', true)->count(),
                'brands' => Brand::where('is_active', true)->count(),
            ];

            $testimonials = Testimonial::where('is_visible', true)->get();
            $partners = Partner::orderBy('sort_order')->get();

            $filterBrands = Brand::whereHas('cars', fn ($q) => $q->where('is_active', true))
                ->withCount(['cars' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->get();
            $filterCategories = CarCategory::orderBy('name')->get();
            $filterTypes = CarType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
            $filterYears = Car::where('is_active', true)
                ->select('year')
                ->distinct()
                ->orderByDesc('year')
                ->get()
                ->map(fn ($car) => ['year' => $car->year])
                ->values();

            $filterBrandTypes = BrandType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            $carPrices = Car::where('is_active', true)->pluck('cash_price');
            $priceBrackets = [
                ['min' => 0, 'max' => 150000],
                ['min' => 150001, 'max' => 250000],
                ['min' => 250001, 'max' => 350000],
                ['min' => 350001, 'max' => null],
            ];
            $filterPrices = collect($priceBrackets)->map(function (array $bracket) use ($carPrices): array {
                $count = $carPrices->filter(
                    fn (int $price): bool => $price >= $bracket['min']
                        && ($bracket['max'] === null || $price <= $bracket['max'])
                )->count();

                return [
                    'min' => $bracket['min'],
                    'max' => $bracket['max'],
                    'count' => $count,
                ];
            })->values();

            $activeCars = Car::where('is_active', true)->get(['id', 'specs']);

            $filterFuels = $activeCars->pluck('specs.fuel')
                ->filter()
                ->countBy()
                ->map(fn (int $count, string $fuel): array => ['value' => $fuel, 'count' => $count])
                ->values();

            $horsepowers = $activeCars->pluck('horsepower')->filter();
            $hpBrackets = [
                ['min' => 0, 'max' => 150],
                ['min' => 151, 'max' => 250],
                ['min' => 251, 'max' => 350],
                ['min' => 351, 'max' => null],
            ];
            $filterHorsepowers = collect($hpBrackets)->map(function (array $bracket) use ($horsepowers): array {
                $count = $horsepowers->filter(
                    fn (int $hp): bool => $hp >= $bracket['min']
                        && ($bracket['max'] === null || $hp <= $bracket['max'])
                )->count();

                return [
                    'min' => $bracket['min'],
                    'max' => $bracket['max'],
                    'count' => $count,
                ];
            })->values();

            $homeSections = HomeSection::query()->get()->keyBy('key');
            $promoCards = PromoCard::query()->activeOrdered()->get();
            $financeSteps = FinanceStep::query()->activeOrdered()->get();

            $budgetRanges = BudgetRange::query()->activeOrdered()->get()->map(function (BudgetRange $range): array {
                return [
                    'range' => $range,
                    'cars' => Car::with(['brand', 'images'])
                        ->where('is_active', true)
                        ->where('cash_price', '>=', $range->min)
                        ->when($range->max !== null, fn ($q) => $q->where('cash_price', '<=', $range->max))
                        ->latest()
                        ->limit(8)
                        ->get(),
                ];
            });

            $highlightedCars = Car::with(['brand', 'images'])
                ->where('is_highlighted', '!=', 'none')
                ->where('is_active', true)
                ->latest()
                ->get();

            $highlightCounts = Car::where('is_active', true)
                ->where('is_highlighted', '!=', 'none')
                ->pluck('is_highlighted')
                ->countBy()
                ->all();

            // Cached as raw models (not $this->rememberHeroSlides()) so locale resolves fresh
            // on every request instead of being baked into this hour-long cache entry.
            $heroSlides = HeroSlide::query()->activeOrdered()->get();

            return compact(
                'featuredCars', 'activeOffers', 'brands', 'latestPosts', 'stats',
                'testimonials', 'partners',
                'filterBrands', 'filterCategories', 'filterTypes', 'filterYears',
                'filterBrandTypes', 'filterPrices', 'filterFuels', 'filterHorsepowers', 'highlightCounts',
                'highlightedCars', 'heroSlides',
                'homeSections', 'promoCards', 'financeSteps', 'budgetRanges',
            );
        });

        $locale = app()->getLocale();
        $result['filterHighlights'] = collect(Car::HIGHLIGHT_OPTIONS)->map(fn (array $labels, string $value): array => [
            'value' => $value,
            'label' => $labels[$locale] ?? $labels['en'],
            'count' => $result['highlightCounts'][$value] ?? 0,
        ])->values();

        return $result;
    }

    public function forgetHome(): void
    {
        Cache::forget('home.data');
    }

    /**
     * Granular per-section cache, used by the new CMS-backed homepage models
     * (HomeSection, HeroSlide, PromoCard, FinanceStep, BudgetRange, FooterLink).
     * Kept separate from home.data until the Home API itself is migrated onto them.
     */
    public function forgetSection(string $key): void
    {
        Cache::forget("home.section.{$key}");
    }
}
