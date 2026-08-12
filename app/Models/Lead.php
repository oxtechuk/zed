<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'client_name',
        'client_phone',
        'client_email',
        'subject',
        'country',
        'contact_source_id',
        'status',
        'started_at',
        'status_details',
        'car_id',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
        ];
    }

    public const STATUSES = [
        'new' => ['label' => 'جديد', 'color' => 'primary'],
        'contacted' => ['label' => 'تم التواصل', 'color' => 'info'],
        'interested' => ['label' => 'مهتم', 'color' => 'warning'],
        'negotiation' => ['label' => 'قيد التفاوض', 'color' => 'secondary'],
        'converted' => ['label' => 'تم التحويل', 'color' => 'success'],
        'lost' => ['label' => 'غير مهتم / مغلق', 'color' => 'danger'],
    ];

    public function contactSource(): BelongsTo
    {
        return $this->belongsTo(ContactSource::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Booking::class, 'client_phone', 'client_phone');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'secondary';
    }

    public function setClientPhoneAttribute($value)
    {
        $this->attributes['client_phone'] = $this->normalizeSaudiPhone($value);
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
