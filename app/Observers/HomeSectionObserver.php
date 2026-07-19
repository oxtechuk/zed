<?php

namespace App\Observers;

use App\Models\HomeSection;
use App\Services\Cache\HomeCacheService;

class HomeSectionObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
    ) {}

    public function saved(HomeSection $homeSection): void
    {
        $this->homeCache->forgetSection($homeSection->key);
        $this->homeCache->forgetHome();
    }

    public function deleted(HomeSection $homeSection): void
    {
        $this->homeCache->forgetSection($homeSection->key);
        $this->homeCache->forgetHome();
    }
}
