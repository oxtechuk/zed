<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;

final class PartnerApiService
{
    public function list(): Collection
    {
        return Partner::orderBy('sort_order')
            ->get();
    }
}
