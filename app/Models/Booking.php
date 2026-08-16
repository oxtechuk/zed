<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'car_id', 'assigned_to', 'client_name', 'client_phone', 'client_email',
        'down_payment', 'duration_years', 'interest_rate', 'monthly_installment',
        'total_price', 'purchase_price', 'authorization_price', 'expenses', 'net_commission',
        'notes', 'status', 'source', 'last_contacted_at', 'delivered_at',
        'booking_type', 'location', 'calculator_bank_id', 'balloon_payment', 'offer_notes',
        'pending_reason', 'follow_up_at', 'proposed_status',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'follow_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'purchase_price' => 'decimal:2',
        'authorization_price' => 'decimal:2',
        'expenses' => 'decimal:2',
        'net_commission' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public const BOOKING_TYPES = ['test_drive', 'purchase', 'inquiry'];

    public const BOOKING_TYPES_LABELS = [
        'test_drive' => 'تجربة قيادة',
        'purchase' => 'شراء',
        'inquiry' => 'استفسار',
    ];

    public const STATUS_GROUPS = [
        'active' => [
            'label' => 'الحالات الأساسية (Active)',
            'color' => '#1877F2',
        ],
        'lost' => [
            'label' => 'الحالات الخاسرة (Closed - Lost)',
            'color' => '#ED5E5E',
        ],
    ];

    public const STATUSES = [
        // Active Group
        'new' => [
            'label' => 'جديد',
            'color' => 'primary',
            'group' => 'active',
        ],
        'contacted_no_answer' => [
            'label' => 'تم الاتصال ولم يتم الرد',
            'color' => 'info',
            'group' => 'active',
        ],
        'recontact_client' => [
            'label' => 'إعادة التواصل مع العميل',
            'color' => 'info',
            'group' => 'active',
        ],
        'waiting_documents' => [
            'label' => 'انتظار إرسال المستندات',
            'color' => 'warning',
            'group' => 'active',
        ],
        'bank_review' => [
            'label' => 'تحت الدراسة البنكية',
            'color' => 'warning',
            'group' => 'active',
        ],
        'approved' => [
            'label' => 'تمت الموافقة',
            'color' => 'success',
            'group' => 'active',
        ],
        'authorized' => [
            'label' => 'التعميد تم',
            'color' => 'success',
            'group' => 'active',
        ],
        'received' => [
            'label' => 'تم الاستلام',
            'color' => 'success',
            'group' => 'active',
            'is_close' => true,
            'is_won' => true,
        ],
        'pending' => [
            'label' => 'قيد الانتظار',
            'color' => 'secondary',
            'group' => 'active',
        ],
        'waiting_supervisor_approval' => [
            'label' => 'بانتظار اعتماد المشرف',
            'color' => 'secondary',
            'group' => 'active',
        ],

        // Lost Group
        'lost_no_answer' => [
            'label' => 'لم يتم الرد',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_no_response' => [
            'label' => 'عدم الاستجابة بعد التواصل',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_wrong_info' => [
            'label' => 'خطأ في بيانات التواصل',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_offer_not_suitable' => [
            'label' => 'العرض لا يناسب العميل',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_client_cancelled' => [
            'label' => 'إلغاء بناءً على رغبة العميل',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_cancelled_after_approval' => [
            'label' => 'كنسل بعد الموافقة',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_rejected_high_liabilities' => [
            'label' => 'مرفوض - الالتزامات مرتفعة',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_rejected_simah' => [
            'label' => 'مرفوض - تعثر في سمة',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
        'lost_rejected_finance_terms' => [
            'label' => 'مرفوض - عدم انطباق شروط التمويل',
            'color' => 'danger',
            'group' => 'lost',
            'is_close' => true,
            'is_lost' => true,
        ],
    ];

    protected static function booted(): void
    {
        static::created(function (Booking $booking) {
            try {
                $source = ContactSource::firstOrCreate(
                    ['name' => 'طلب حجز من المتجر'],
                    ['is_active' => true]
                );

                $existingLead = Lead::where('client_phone', $booking->client_phone)->first();

                if (! $existingLead) {
                    Lead::create([
                        'client_name' => $booking->client_name,
                        'client_phone' => $booking->client_phone,
                        'client_email' => $booking->client_email,
                        'contact_source_id' => $source->id,
                        'status' => 'new',
                        'started_at' => now(),
                        'car_id' => $booking->car_id,
                        'assigned_to' => $booking->assigned_to,
                        'status_details' => $booking->notes ?? 'طلب حجز تلقائي من المتجر',
                    ]);
                } else {
                    $updateData = [];
                    if (empty($existingLead->car_id)) {
                        $updateData['car_id'] = $booking->car_id;
                    }
                    if (empty($existingLead->assigned_to)) {
                        $updateData['assigned_to'] = $booking->assigned_to;
                    }
                    if (! empty($updateData)) {
                        $existingLead->update($updateData);
                    }
                }
            } catch (\Throwable $e) {
                logger()->warning('Lead auto-creation on booking failed: '.$e->getMessage());
            }
        });
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function calculatorLead(): HasOne
    {
        return $this->hasOne(CalculatorLead::class, 'phone', 'client_phone')->latest();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function notes_list(): HasMany
    {
        return $this->hasMany(BookingNote::class)->latest();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class)->latest();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->latest();
    }

    public function financingBank(): BelongsTo
    {
        return $this->belongsTo(CalculatorBank::class, 'calculator_bank_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'secondary';
    }

    public function getProposedStatusLabelAttribute(): ?string
    {
        return self::STATUSES[$this->proposed_status]['label'] ?? null;
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeInProgress($query)
    {
        return $query->whereNotIn('status', ['received', 'lost_no_answer', 'lost_no_response', 'lost_wrong_info', 'lost_offer_not_suitable', 'lost_client_cancelled', 'lost_cancelled_after_approval', 'lost_rejected_high_liabilities', 'lost_rejected_simah', 'lost_rejected_finance_terms']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'received');
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
