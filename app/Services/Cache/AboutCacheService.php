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

    public function rememberTestimonials()
    {
        return Testimonial::where('is_visible', true)->latest()->get();
    }

    public function rememberPartners()
    {
        return Partner::orderBy('sort_order')->get();
    }

    public function forgetAbout(): void
    {
        $this->forget('about.testimonials');
        $this->forget('about.partners');
    }
}
