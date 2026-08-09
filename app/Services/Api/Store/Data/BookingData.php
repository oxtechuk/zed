<?php

declare(strict_types=1);

namespace App\Services\Api\Store\Data;

use App\Services\Api\Store\Helpers\InstallmentCalculator;

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
        ];
    }
}
