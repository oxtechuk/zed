<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculatorLead extends Model
{
    protected $fillable = ['name', 'phone', 'car_id', 'details'];

    protected $casts = [
        'details' => 'array',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'client_phone', 'phone')->where('source', 'calculator')->latest();
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->normalizeSaudiPhone($value);
    }

    protected function normalizeSaudiPhone($value): string
    {
        // Strip non-digits
        $phone = preg_replace('/\D/', '', $value);

        // If it starts with 009665, strip 00
        if (str_starts_with($phone, '009665')) {
            $phone = substr($phone, 2);
        }

        // If it starts with 9665 and is 12 digits long, it is already perfect
        if (str_starts_with($phone, '9665') && strlen($phone) === 12) {
            return $phone;
        }

        // If it starts with 05 and is 10 digits long, strip 0 and prepend 966
        if (str_starts_with($phone, '05') && strlen($phone) === 10) {
            return '9665'.substr($phone, 2);
        }

        // If it starts with 5 and is 9 digits long, prepend 966
        if (str_starts_with($phone, '5') && strlen($phone) === 9) {
            return '9665'.substr($phone, 1);
        }

        return $phone;
    }
}
