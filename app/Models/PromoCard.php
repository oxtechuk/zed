<?php

namespace App\Models;

use App\Casts\AsImageUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PromoCard extends Model
{
    use HasTranslations;

    public const TYPES = ['large', 'medium', 'small'];

    public $translatable = ['title', 'subtitle', 'button_text', 'badge'];

    protected $fillable = [
        'type', 'title', 'subtitle', 'image', 'button_text', 'button_url',
        'badge', 'sort_order', 'is_active',
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
