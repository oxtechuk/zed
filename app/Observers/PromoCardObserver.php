<?php

namespace App\Observers;

use App\Models\PromoCard;
use App\Services\Cache\HomeCacheService;

class PromoCardObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
    ) {}

    public function saved(PromoCard $promoCard): void
    {
        $this->homeCache->forgetSection('promo');
        $this->homeCache->forgetHome();
    }

    public function deleted(PromoCard $promoCard): void
    {
        $this->homeCache->forgetSection('promo');
        $this->homeCache->forgetHome();
    }
}
