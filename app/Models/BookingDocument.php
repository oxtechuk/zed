<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDocument extends Model
{
    protected $fillable = [
        'booking_id',
        'employee_id',
        'title',
        'file_path',
        'file_type',
    ];

    protected $touches = ['booking'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
