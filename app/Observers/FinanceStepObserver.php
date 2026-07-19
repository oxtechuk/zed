<?php

namespace App\Observers;

use App\Models\FinanceStep;
use App\Services\Cache\HomeCacheService;

class FinanceStepObserver
{
    public function __construct(
        private HomeCacheService $homeCache,
    ) {}

    public function saved(FinanceStep $financeStep): void
    {
        $this->homeCache->forgetSection('finance');
        $this->homeCache->forgetHome();
    }

    public function deleted(FinanceStep $financeStep): void
    {
        $this->homeCache->forgetSection('finance');
        $this->homeCache->forgetHome();
    }
}
