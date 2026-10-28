<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Brand;
use App\Models\CalculatorBank;
use App\Models\Car;
use App\Models\SeoEntry;
use App\Services\Api\Store\Helpers\SlugResolver;
use Illuminate\Support\Facades\Schema;

/**
 * Looks up the SEO copy for a storefront path and fills its live numbers
 * ({السعر}, {القسط}, {أقل_قسط}, {عدد}, …) so prices in the copy never go stale.
 */
final class SeoEntryResolver
{
    private ?bool $tableExists = null;

    private ?array $globals = null;

    /**
     * @return array{meta_title: ?string, meta_description: ?string, intro: ?string, paragraphs: list<string>, faq: list<array{q: string, a: string}>, image_alt: ?string}|null
     */
    public function resolve(string $path): ?array
    {
        $path = self::normalize($path);

        if (! ($this->tableExists ??= Schema::hasTable('seo_entries'))) {
            return null;
        }

        $entry = SeoEntry::where('path', $path)->first();
        if (! $entry) {
            return null;
        }

        $vars = $this->vars($path);
        $fill = fn (?string $text) => $text === null || $text === '' ? null : strtr($text, $vars);

        $faq = [];
        foreach ((array) $entry->faq as $item) {
            if (! empty($item['q']) && ! empty($item['a'])) {
                $faq[] = ['q' => $fill($item['q']), 'a' => $fill($item['a'])];
            }
        }

        $intro = $fill($entry->intro);

        return [
            'meta_title' => $fill($entry->meta_title),
            'meta_description' => $fill($entry->meta_description),
            'intro' => $intro,
            'paragraphs' => $intro ? self::plainParagraphs($intro) : [],
            'faq' => $faq,
            'image_alt' => $fill($entry->image_alt),
        ];
    }

    public static function normalize(string $path): string
    {
        $path = rawurldecode(parse_url($path, PHP_URL_PATH) ?: '/');
        $path = '/' . trim($path, '/');

        return $path;
    }

    /**
     * The intro uses a tiny markup (### heading, - item, 1. item, **bold**, [text](url));
     * crawlers get it as plain paragraphs.
     *
     * @return list<string>
     */
    public static function plainParagraphs(string $text): array
    {
        $lines = preg_split('/\R/u', $text) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $line = preg_replace('/^(#{1,6}\s+|-\s+|\d+\.\s+)/u', '', trim($line));
            $line = preg_replace('/\[([^\]]+)\]\([^)]+\)/u', '$1', (string) $line);
            $line = trim(str_replace('**', '', (string) $line));
            if ($line !== '') {
                $out[] = $line;
            }
        }

        return $out;
    }

    /** @return array<string, string> */
    private function vars(string $path): array
    {
        $vars = $this->globals();

        if (str_starts_with($path, '/cars/')) {
            $query = Car::where('is_active', true);
            SlugResolver::applyCarSlug($query, substr($path, 6));
            if ($car = $query->first()) {
                $vars['{السعر}'] = $this->money($car->current_price ?? $car->cash_price);
                $vars['{القسط}'] = $this->money($car->min_installment);
                $vars['{السنة}'] = (string) $car->year;
            }
        } elseif (str_starts_with($path, '/brands/')) {
            if ($brand = Brand::where('slug', substr($path, 8))->first()) {
                $cars = Car::where('is_active', true)->where('brand_id', $brand->id);
                $vars['{عدد}'] = (string) $cars->count();
                $vars['{أقل_قسط}'] = $this->money((clone $cars)->where('min_installment', '>', 0)->min('min_installment'));
                $vars['{أقل_سعر}'] = $this->money((clone $cars)->where('cash_price', '>', 0)->min('cash_price'));
            }
        }

        return $vars;
    }

    /** @return array<string, string> */
    private function globals(): array
    {
        if ($this->globals !== null) {
            return $this->globals;
        }

        $cars = Car::where('is_active', true);

        return $this->globals = [
            // Used as "أكثر من {عدد_السيارات} سيارة": rounded down to tens so the count reads correctly in Arabic.
            '{عدد_السيارات}' => (string) $this->tensBelow($cars->count()),
            '{عدد_الماركات}' => (string) $this->tensBelow((clone $cars)->distinct()->count('brand_id')),
            '{أقل_قسط}' => $this->money((clone $cars)->where('min_installment', '>', 0)->min('min_installment')),
            '{عدد_البنوك}' => (string) CalculatorBank::where('is_active', true)->count(),
        ];
    }

    private function tensBelow(int $count): int
    {
        return max(10, intdiv(max($count - 1, 0), 10) * 10);
    }

    private function money(mixed $amount): string
    {
        return number_format((float) $amount);
    }
}
