<?php

namespace App\Models;

use App\Casts\AsImageUrl;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomeSection extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'subtitle', 'description', 'badge', 'button_text'];

    protected $fillable = [
        'key', 'title', 'subtitle', 'description', 'badge',
        'button_text', 'button_url', 'image', 'background_image',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'image' => AsImageUrl::class,
        'background_image' => AsImageUrl::class,
    ];

    public function scopeKey($query, string $key)
    {
        return $query->where('key', $key);
    }
}
