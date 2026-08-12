<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class CarModel extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['brand_id', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the brand that owns the car model.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the cars for this model.
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_model_id');
    }
}
