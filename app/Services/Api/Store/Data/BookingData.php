<?php

declare(strict_types=1);

namespace App\Services\Api\Store\Data;

use App\Services\Api\Store\Helpers\InstallmentCalculator;
use App\Services\AttributionHelper;

final class BookingData
{
    public function __construct(
        public readonly int $car_id,
        public readonly string $client_name,
        public readonly string $client_phone,
        public readonly float $down_payment,
        public readonly int $duration_years,
        public readonly float $interest_rate,
        public readonly int $monthly_installment,
        public readonly int $total_price,
        public readonly string $status = 'new',
        public readonly string $source = 'api',
        public readonly ?string $booking_type = null,
        public readonly ?string $location = null,
        public readonly ?string $client_email = null,
        public readonly ?string $notes = null,
        public readonly ?int $calculator_bank_id = null,
        public readonly ?string $utm_source = null,
        public readonly ?string $utm_medium = null,
        public readonly ?string $utm_campaign = null,
        public readonly ?string $utm_content = null,
        public readonly ?string $utm_term = null,
        public readonly ?string $referrer = null,
        public readonly ?string $click_id = null,
        public readonly ?string $marketing_channel = null,
    ) {}

    public static function fromRequest(array $validated, float $cashPrice): self
    {
        $interestRate = isset($validated['interest_rate']) && $validated['interest_rate'] > 0
            ? (float) $validated['interest_rate']
            : (float) config('store-api.booking.default_interest_rate', 4.0);

        $downPayment = (float) ($validated['down_payment'] ?? 0);
        $durationYears = max(1, (int) ($validated['duration_years'] ?? 5));

        $principal = max(0, $cashPrice - $downPayment);
        $totalMonths = $durationYears * 12;

        $calculator = new InstallmentCalculator;
        $monthly = $calculator->calculate($principal, $totalMonths, $interestRate);

        $utmSource = $validated['utm_source'] ?? null;
        $utmMedium = $validated['utm_medium'] ?? null;
        $referrer = $validated['referrer'] ?? null;
        $clickId = $validated['click_id'] ?? null;
        $source = $validated['source'] ?? 'api';

        $channel = $validated['marketing_channel']
            ?? AttributionHelper::resolveChannel($utmSource, $utmMedium, $referrer, $clickId, $source);

        return new self(
            car_id: (int) $validated['car_id'],
            client_name: $validated['client_name'],
            client_phone: $validated['client_phone'],
            down_payment: $downPayment,
            duration_years: $durationYears,
            interest_rate: $interestRate,
            monthly_installment: (int) round($monthly),
            total_price: (int) round($monthly * $totalMonths + $downPayment),
            booking_type: $validated['booking_type'] ?? null,
            location: $validated['location'] ?? null,
            client_email: $validated['client_email'] ?? null,
            notes: $validated['notes'] ?? null,
            calculator_bank_id: isset($validated['calculator_bank_id']) ? (int) $validated['calculator_bank_id'] : null,
            utm_source: $utmSource,
            utm_medium: $utmMedium,
            utm_campaign: $validated['utm_campaign'] ?? null,
            utm_content: $validated['utm_content'] ?? null,
            utm_term: $validated['utm_term'] ?? null,
            referrer: $referrer,
            click_id: $clickId,
            marketing_channel: $channel,
        );
    }

    public function toDatabase(): array
    {
        return [
            'car_id' => $this->car_id,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'down_payment' => $this->down_payment,
            'duration_years' => $this->duration_years,
            'interest_rate' => $this->interest_rate,
            'monthly_installment' => $this->monthly_installment,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'source' => $this->source,
            'booking_type' => $this->booking_type,
            'location' => $this->location,
            'client_email' => $this->client_email,
            'notes' => $this->notes,
            'calculator_bank_id' => $this->calculator_bank_id,
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_content' => $this->utm_content,
            'utm_term' => $this->utm_term,
            'referrer' => $this->referrer,
            'click_id' => $this->click_id,
            'marketing_channel' => $this->marketing_channel,
        ];
    }
}
