<?php

namespace App\Observers;

use App\Models\BudgetRange;
use App\Services\Cache\HomeCacheService;

class BudgetRangeObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
    ) {}

    public function saved(BudgetRange $budgetRange): void
    {
        $this->homeCache->forgetSection('budget');
        $this->homeCache->forgetHome();
    }

    public function deleted(BudgetRange $budgetRange): void
    {
        $this->homeCache->forgetSection('budget');
        $this->homeCache->forgetHome();
    }
}
