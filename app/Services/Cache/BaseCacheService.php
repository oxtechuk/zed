<?php

namespace App\Services\Cache;

use App\Models\HeroSlide;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BaseCacheService
{
    protected const TTL_DEFAULT = 3600;

    protected const TTL_LONG = 86400;

    protected static array $runtimeCache = [];

    public function remember(string $key, callable $callback, int $ttl = self::TTL_DEFAULT): mixed
    {
        if (array_key_exists($key, self::$runtimeCache)) {
            return self::$runtimeCache[$key];
        }

        $value = Cache::remember($key, $ttl, $callback);
        self::$runtimeCache[$key] = $value;

        return $value;
    }

    public static function flushRuntimeCache(): void
    {
        self::$runtimeCache = [];
    }

    public function rememberSettings(): mixed
    {
        return $this->remember('settings.all', function () {
            return Setting::all()->pluck('value', 'key');
        }, self::TTL_LONG);
    }

    public function rememberSetting(string $key, mixed $default = null): mixed
    {
        $settings = $this->rememberSettings();

        return $settings[$key] ?? $default;
    }

    public function rememberHeroSetting(string $key): array
    {
        $hero = $this->remember("settings.hero.{$key}", function () use ($key) {
            $heroSetting = Setting::where('key', $key)->first();

            return $heroSetting ? $heroSetting->value : [
                'title' => '',
                'subtitle' => '',
                'image' => null,
            ];
        }, self::TTL_LONG);

        $locale = app()->getLocale();

        return [
            'title' => $this->localizeHeroField($hero['title'] ?? '', $locale),
            'subtitle' => $this->localizeHeroField($hero['subtitle'] ?? '', $locale),
            'image' => $this->resolveHeroImage($hero['image'] ?? null),
        ];
    }

    private function localizeHeroField(mixed $value, string $locale): string
    {
        if (! is_array($value)) {
            return (string) $value;
        }

        return $value[$locale] ?? $value['en'] ?? $value['ar'] ?? '';
    }

    private function resolveHeroImage(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function rememberHeroSlides(): array
    {
        $slides = $this->remember('settings.hero_slides', function () {
            return HeroSlide::query()->activeOrdered()->get()->map(fn (HeroSlide $slide): array => [
                'id' => $slide->id,
                'title' => $slide->getTranslations('title'),
                'subtitle' => $slide->getTranslations('subtitle'),
                'description' => $slide->getTranslations('description'),
                'image' => $slide->getRawOriginal('image_desktop'),
                'image_mobile' => $slide->getRawOriginal('image_mobile'),
                'button_url' => $slide->button_url,
                'button_text' => $slide->getTranslations('button_text'),
                'badge' => $slide->getTranslations('badge'),
            ])->values()->all();
        }, self::TTL_LONG);

        $locale = app()->getLocale();

        return array_map(fn (array $slide): array => [
            'id' => $slide['id'],
            'title' => $this->localizeHeroField($slide['title'], $locale),
            'subtitle' => $this->localizeHeroField($slide['subtitle'], $locale),
            'description' => $this->localizeHeroField($slide['description'], $locale),
            'image' => $this->resolveHeroImage($slide['image']),
            'image_mobile' => $this->resolveHeroImage($slide['image_mobile']),
            'link' => $slide['button_url'], // kept for backward compatibility with existing consumers
            'button_url' => $slide['button_url'],
            'button_text' => $this->localizeHeroField($slide['button_text'], $locale),
            'badge' => $this->localizeHeroField($slide['badge'], $locale),
        ], $slides);
    }

    public function forgetHeroSlides(): void
    {
        Cache::forget('settings.hero_slides');
    }

    public function forgetSettings(): void
    {
        Cache::forget('settings.all');
    }

    public function flushAll(): void
    {
        Cache::flush();
    }
}
