<?php

namespace App\Models;

use App\Casts\AsImageUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HeroSlide extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'subtitle', 'description', 'button_text', 'badge'];

    protected $fillable = [
        'title', 'subtitle', 'description', 'image_desktop', 'image_mobile',
        'button_text', 'button_url', 'badge', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'image_desktop' => AsImageUrl::class,
        'image_mobile' => AsImageUrl::class,
    ];

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
