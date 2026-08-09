<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Http\Resources\Store\CarMiniResource;
use App\Models\Car;
use App\Models\FinanceStep;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\Offer;
use App\Models\PromoCard;
use App\Services\Cache\HomeCacheService;
use Illuminate\Support\Collection;

final class HomeApiService
{
    /** @var string[] page_sections keys that are still backed by a HomeSection CMS row */
    private const TEXT_SECTION_KEYS = ['filter', 'featured_cars', 'offers', 'brands', 'budget', 'finance'];

    public function __construct(
        private readonly HomeCacheService $cache,
    ) {}

    public function home(): array
    {
        $data = $this->cache->rememberHomeData();

        $homepageStats = $this->cache->rememberSetting('homepage_stats', []);
        if (! is_array($homepageStats)) {
            $homepageStats = json_decode((string) $homepageStats, true) ?: [];
        }

        /** @var Collection<string, HomeSection> $homeSections */
        $homeSections = $data['homeSections'] ?? collect();

        return [
            'hero' => $this->sectionMeta($homeSections->get('hero')),
            'featured_cars' => ($data['featuredCars'] ?? collect())->values(),
            'active_offers' => ($data['activeOffers'] ?? collect())->values(),
            'offer_cars' => $this->offerCars($data['activeOffers'] ?? collect()),
            'brands' => ($data['brands'] ?? collect())->values(),
            'latest_posts' => ($data['latestPosts'] ?? collect())->values(),
            'stats' => $data['stats'] ?? [],
            'testimonials' => ($data['testimonials'] ?? collect())->values(),
            'partners' => ($data['partners'] ?? collect())->values(),
            'filter_brands' => ($data['filterBrands'] ?? collect())->values(),
            'filter_categories' => ($data['filterCategories'] ?? collect())->values(),
            'filter_types' => ($data['filterTypes'] ?? collect())->values(),
            'filter_years' => ($data['filterYears'] ?? collect())->values(),
            'filter_prices' => ($data['filterPrices'] ?? collect())->values(),
            'filter_fuels' => ($data['filterFuels'] ?? collect())->values(),
            'filter_horsepowers' => ($data['filterHorsepowers'] ?? collect())->values(),
            'filter_highlights' => ($data['filterHighlights'] ?? collect())->values(),
            'filter_brand_types' => ($data['filterBrandTypes'] ?? collect())->values(),
            'promo_cards' => $this->promoCards($data['promoCards'] ?? collect()),
            'highlighted_cars' => ($data['highlightedCars'] ?? collect())->values(),
            'hero_slides' => $this->heroSlides($data['heroSlides'] ?? collect()),
            'featured_section' => $this->featuredBanner($homeSections->get('featured_banner')),
            'homepage_stats' => $homepageStats,
            'page_sections' => $this->pageSections($homeSections),
            'finance_steps' => $this->financeSteps($data['financeSteps'] ?? collect()),
            'budget_ranges' => $this->budgetRanges($data['budgetRanges'] ?? collect()),
        ];
    }

    /** @return array<int, Car> */
    private function offerCars(Collection $offers): array
    {
        return $offers
            ->flatMap(fn (Offer $offer) => $offer->cars)
            ->unique('id')
            ->values()
            ->all();
    }

    private function heroSlides(Collection $slides): array
    {
        return $slides->map(fn (HeroSlide $slide): array => [
            'id' => $slide->id,
            'title' => $slide->title,
            'subtitle' => $slide->subtitle,
            'description' => $slide->description,
            'image' => $slide->image_desktop,
            'image_mobile' => $slide->image_mobile,
            'link' => $slide->button_url, // kept for backward compatibility with existing consumers
            'button_url' => $slide->button_url,
            'button_text' => $slide->button_text,
            'badge' => $slide->badge,
        ])->values()->all();
    }

    private function promoCards(Collection $cards): array
    {
        return $cards->map(fn (PromoCard $card): array => [
            'type' => $card->type,
            'title' => $card->title,
            'subtitle' => $card->subtitle,
            'image' => $card->image,
            'button' => ['text' => $card->button_text, 'url' => $card->button_url],
            'badge' => $card->badge,
        ])->values()->all();
    }

    private function financeSteps(Collection $steps): array
    {
        return $steps->map(fn (FinanceStep $step): array => [
            'number' => $step->number,
            'title' => $step->title,
            'description' => $step->description,
            'icon' => $step->icon,
        ])->values()->all();
    }

    private function sectionMeta(?HomeSection $section): array
    {
        if (! $section) {
            return ['title' => '', 'subtitle' => '', 'image' => null];
        }

        return [
            'title' => $section->title,
            'subtitle' => $section->subtitle,
            'image' => $section->image,
        ];
    }

    private function featuredBanner(?HomeSection $section): ?array
    {
        if (! $section || ! $section->is_active) {
            return null;
        }

        $locale = app()->getLocale();
        $image = $locale === 'en' && $section->background_image
            ? $section->background_image
            : ($section->image ?: $section->background_image);

        return [
            'title' => $section->title,
            'subtitle' => $section->subtitle,
            'description' => $section->description,
            'badge' => $section->badge,
            'button' => ['text' => $section->button_text, 'url' => $section->button_url],
            'image' => $image,
            'background_image' => $section->background_image,
        ];
    }

    /** @param Collection<string, HomeSection> $homeSections */
    private function pageSections(Collection $homeSections): array
    {
        // 'filter' maps to the 'search' CMS row; every other key matches its HomeSection row directly.
        $keyMap = ['filter' => 'search'] + array_combine(self::TEXT_SECTION_KEYS, self::TEXT_SECTION_KEYS);

        return collect(self::TEXT_SECTION_KEYS)->mapWithKeys(function (string $outputKey) use ($homeSections, $keyMap): array {
            $section = $homeSections->get($keyMap[$outputKey]);

            if (! $section) {
                return [$outputKey => null];
            }

            return [$outputKey => [
                'badge' => $section->badge,
                'title' => $section->title,
                'subtitle' => $section->subtitle,
                'description' => $section->description,
                'button_text' => $section->button_text,
                'button_url' => $section->button_url,
            ]];
        })->all();
    }

    private function budgetRanges(Collection $ranges): array
    {
        return $ranges->map(function (array $entry): array {
            $range = $entry['range'];

            return [
                'label' => $range->label,
                'min' => $range->min,
                'max' => $range->max,
                'cars' => CarMiniResource::collection($entry['cars'])->resolve(),
            ];
        })->values()->all();
    }
}
