<?php

namespace App\Models;

use App\Casts\AsImageUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PromoBanner extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'subtitle', 'description', 'button_text'];

    protected $fillable = [
        'image', 'title', 'subtitle', 'description',
        'button_text', 'button_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'image' => AsImageUrl::class,
    ];

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
