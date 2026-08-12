<?php

namespace App\Services\Cache;

use App\Models\Brand;
use App\Models\BrandType;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\CarType;
use Illuminate\Support\Facades\Cache;

class CarCacheService extends BaseCacheService
{
    public function rememberCarFilters(): array
    {
        $result = $this->remember('cars.filters', function () {
            $brands = Brand::whereHas('cars', fn ($q) => $q->where('is_active', true))
                ->withCount(['cars' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->get();

            $years = Car::where('is_active', true)->distinct()->orderByDesc('year')->pluck('year');

            $types = CarType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            $categories = CarCategory::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            $brandTypes = BrandType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            // SQL aggregation for price brackets (0 memory overhead compared to plucking all prices)
            $priceCounts = Car::where('is_active', true)
                ->selectRaw('
                    COUNT(CASE WHEN cash_price BETWEEN 0 AND 150000 THEN 1 END) as b1,
                    COUNT(CASE WHEN cash_price BETWEEN 150001 AND 250000 THEN 1 END) as b2,
                    COUNT(CASE WHEN cash_price BETWEEN 250001 AND 350000 THEN 1 END) as b3,
                    COUNT(CASE WHEN cash_price >= 350001 THEN 1 END) as b4
                ')
                ->first();

            $prices = [
                ['min' => 0, 'max' => 150000, 'count' => (int) ($priceCounts->b1 ?? 0)],
                ['min' => 150001, 'max' => 250000, 'count' => (int) ($priceCounts->b2 ?? 0)],
                ['min' => 250001, 'max' => 350000, 'count' => (int) ($priceCounts->b3 ?? 0)],
                ['min' => 350001, 'max' => null, 'count' => (int) ($priceCounts->b4 ?? 0)],
            ];

            // Lightweight column selection for specs & horsepower facet calculations
            $activeCars = Car::where('is_active', true)->get(['id', 'specs']);

            $fuels = $activeCars->pluck('specs.fuel')
                ->filter()
                ->countBy()
                ->map(fn (int $count, string $fuel): array => ['value' => $fuel, 'count' => $count])
                ->values()
                ->all();

            $horsepowers = $activeCars->pluck('horsepower')->filter();
            $hpBrackets = [
                ['min' => 0, 'max' => 150],
                ['min' => 151, 'max' => 250],
                ['min' => 251, 'max' => 350],
                ['min' => 351, 'max' => null],
            ];
            $horsepowerBrackets = collect($hpBrackets)->map(function (array $bracket) use ($horsepowers): array {
                $count = $horsepowers->filter(
                    fn (int $hp): bool => $hp >= $bracket['min']
                        && ($bracket['max'] === null || $hp <= $bracket['max'])
                )->count();

                return [
                    'min' => $bracket['min'],
                    'max' => $bracket['max'],
                    'count' => $count,
                ];
            })->values()->all();

            // SQL aggregation for highlight counts
            $highlightCounts = Car::where('is_active', true)
                ->where('is_highlighted', '!=', 'none')
                ->selectRaw('is_highlighted, COUNT(*) as count')
                ->groupBy('is_highlighted')
                ->pluck('count', 'is_highlighted')
                ->all();

            return compact('brands', 'years', 'types', 'categories', 'brandTypes', 'prices', 'fuels', 'horsepowerBrackets', 'highlightCounts');
        }, self::TTL_LONG);

        $locale = app()->getLocale();
        $result['highlights'] = collect(Car::HIGHLIGHT_OPTIONS)->map(fn (array $labels, string $value): array => [
            'value' => $value,
            'label' => $labels[$locale] ?? $labels['en'],
            'count' => $result['highlightCounts'][$value] ?? 0,
        ])->values()->all();

        return $result;
    }

    public function forgetCars(): void
    {
        Cache::forget('cars.filters');
    }
}
