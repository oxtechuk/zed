<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SEO copy for one storefront page (title, meta description, extra text, FAQ),
 * imported from the SEO team's sheet with `php artisan seo:import`.
 */
class SeoEntry extends Model
{
    protected $fillable = [
        'path',
        'type',
        'focus_keyword',
        'meta_title',
        'meta_description',
        'intro',
        'faq',
        'image_alt',
    ];

    protected $casts = [
        'faq' => 'array',
    ];
}
