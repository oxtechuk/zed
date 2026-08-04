<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;

final class TestimonialApiService
{
    public function list(): Collection
    {
        return Testimonial::where('is_visible', true)
            ->get();
    }
}
