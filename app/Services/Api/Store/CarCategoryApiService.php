<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\CarCategory;
use Illuminate\Database\Eloquent\Collection;

final class CarCategoryApiService
{
    public function list(): Collection
    {
        return CarCategory::activeOrdered()
            ->withCount('cars')
            ->get();
    }
}
