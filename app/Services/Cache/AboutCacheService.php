<?php

namespace App\Services\Cache;

use App\Models\Partner;
use App\Models\Testimonial;

class AboutCacheService extends BaseCacheService
{
    public function rememberMainGallery(): array
    {
        $settings = $this->rememberSettings();

        if (! isset($settings['main_gallery'])) {
            return [];
        }

        return is_array($settings['main_gallery'])
            ? $settings['main_gallery']
            : (json_decode($settings['main_gallery'], true) ?: []);
    }

    public function rememberAboutSections(): array
    {
        $raw = $this->rememberSetting('about_sections', []);

        return is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
    }

    public function rememberAboutStats(): array
    {
        $raw = $this->rememberSetting('about_stats', []);

        return is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
    }

    public function rememberAboutBranches(): array
    {
        $raw = $this->rememberSetting('about_branches', []);

        return is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
    }

    public function rememberTestimonials(): array
    {
        return $this->remember('about.testimonials', function () {
            return Testimonial::where('is_visible', true)->get()->toArray();
        }, self::TTL_LONG);
    }

    public function rememberPartners(): array
    {
        return $this->remember('about.partners', function () {
            return Partner::orderBy('sort_order')->get()->toArray();
        }, self::TTL_LONG);
    }
}
