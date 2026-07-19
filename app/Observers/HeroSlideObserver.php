<?php

namespace App\Observers;

use App\Models\HeroSlide;
use App\Services\Cache\HomeCacheService;

class HeroSlideObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
    ) {}

    public function saved(HeroSlide $heroSlide): void
    {
        $this->homeCache->forgetSection('hero');
        $this->homeCache->forgetHeroSlides();
        $this->homeCache->forgetHome();
    }

    public function deleted(HeroSlide $heroSlide): void
    {
        $this->homeCache->forgetSection('hero');
        $this->homeCache->forgetHeroSlides();
        $this->homeCache->forgetHome();
    }
}
