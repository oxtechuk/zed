<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BudgetRange extends Model
{
    use HasTranslations;

    public $translatable = ['label'];

    protected $fillable = [
        'label', 'min', 'max', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min' => 'integer',
        'max' => 'integer',
    ];

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
