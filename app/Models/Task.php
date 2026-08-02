<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'status', 'priority',
        'assigned_to', 'due_date', 'created_by', 'booking_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'عالية',
            'medium' => 'متوسطة',
            'low' => 'منخفضة',
            default => $this->priority,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'جديدة',
            'in_progress' => 'قيد التنفيذ',
            'done' => 'مكتملة',
            default => $this->status,
        };
    }

    public function getIsLateAttribute(): bool
    {
        return $this->status !== 'done' && $this->due_date !== null && $this->due_date->isPast();
    }

    public function getDisplayStatusLabelAttribute(): string
    {
        if ($this->status === 'done') {
            return 'مكتملة';
        }

        return $this->is_late ? 'متأخرة' : 'قيد التنفيذ';
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary',
        };
    }
}
