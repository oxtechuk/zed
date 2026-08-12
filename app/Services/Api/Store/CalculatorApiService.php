<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\Booking;
use App\Models\CalculatorBank;
use App\Models\CalculatorLead;
use App\Models\Car;
use App\Models\Employee;
use App\Notifications\NewBookingNotification;
use App\Services\BookingAssignmentService;
use App\Services\TwilioOtpService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

final class CalculatorApiService
{
    public function saveLead(array $data): CalculatorLead
    {
        $carIds = $data['car_ids'] ?? [];
        $primaryCarId = $carIds[0] ?? null;

        $lead = CalculatorLead::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'car_id' => $primaryCarId,
            'details' => [
                'email' => $data['email'] ?? null,
                'city' => $data['city'],
                'salary' => $data['salary'],
                'monthly_obligations' => $data['monthly_obligations'],
                'employer_type' => $data['employer_type'] ?? null,
                'employer_name' => $data['employer_name'] ?? null,
                'years_of_service' => $data['years_of_service'] ?? null,
                'has_mortgage_loan' => $data['has_mortgage_loan'] ?? false,
                'has_personal_loan' => $data['has_personal_loan'] ?? false,
                'has_traffic_violations' => $data['has_traffic_violations'] ?? false,
                'has_simah_default' => $data['has_simah_default'] ?? false,
                'preferred_bank_id' => $data['preferred_bank_id'] ?? null,
                'car_ids' => $carIds,
                'preferred_color' => $data['preferred_color'] ?? null,
                'notes' => $data['notes'] ?? null,
            ],
        ]);

        $this->createBookingForLead($lead, $primaryCarId, $data['email'] ?? null, $data['notes'] ?? null);

        return $lead;
    }

    public function sendOtp(string $phone): array
    {
        $otpService = app(TwilioOtpService::class);

        $result = $otpService->sendOtp($phone);

        return [
            'success' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'OTP sent',
        ];
    }

    public function verifyOtp(string $phone, string $code): bool
    {
        $otpService = app(TwilioOtpService::class);

        $result = $otpService->verifyOtp($phone, $code);

        return $result['success'] ?? false;
    }

    public function createLeadFromVerified(string $name, string $phone): CalculatorLead
    {
        $lead = CalculatorLead::create([
            'name' => $name,
            'phone' => $phone,
            'details' => [
                'page' => 'calculator_page',
                'otp_verified_at' => now()->toISOString(),
            ],
        ]);

        $this->createBookingForLead($lead);

        return $lead;
    }

    private function createBookingForLead(CalculatorLead $lead, ?int $carId = null, ?string $email = null, ?string $notes = null): void
    {
        $booking = Booking::create([
            'client_name' => $lead->name,
            'client_phone' => $lead->phone,
            'client_email' => $email,
            'car_id' => $carId,
            'source' => 'calculator',
            'status' => 'new',
            'notes' => $notes,
            'total_price' => $carId ? (Car::find($carId)?->current_price ?? 0) : 0,
            'down_payment' => 0,
            'duration_years' => 5,
            'monthly_installment' => $lead->details['monthly_installment'] ?? 0,
        ]);

        try {
            $assignmentService = app(BookingAssignmentService::class);
            $assignmentService->autoAssign($booking);
        } catch (\Throwable $e) {
            logger()->warning('Auto assignment failed for calculator booking '.$booking->id.': '.$e->getMessage());
        }

        try {
            $admins = Employee::where('role', 'admin')->orWhere('id', 1)->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewBookingNotification($booking));
            }
        } catch (\Throwable $e) {
            logger()->warning('Admin notification failed for calculator booking '.$booking->id.': '.$e->getMessage());
        }
    }

    /** @return Collection<int, CalculatorBank> */
    public function banks(): Collection
    {
        return CalculatorBank::query()->activeOrdered()->get();
    }

    public function calculate(int $carId, float $downPaymentPct, int $periodMonths, int $bankId): array
    {
        $car = Car::findOrFail($carId);
        $bank = CalculatorBank::findOrFail($bankId);

        $carPrice = (float) ($car->current_price ?? $car->cash_price);
        $downPaymentAmount = round($carPrice * $downPaymentPct / 100);
        $loanAmount = $carPrice - $downPaymentAmount;
        $annualRate = $bank->annual_rate;
        $monthlyRate = $annualRate / 12 / 100;

        if ($monthlyRate > 0 && $periodMonths > 0) {
            $compounded = pow(1 + $monthlyRate, $periodMonths);
            $denom = $compounded - 1;
            $monthlyPayment = $denom > 0 ? round($loanAmount * ($monthlyRate * $compounded) / $denom) : round($loanAmount / $periodMonths);
        } elseif ($periodMonths > 0) {
            $monthlyPayment = round($loanAmount / $periodMonths);
        } else {
            $monthlyPayment = 0;
        }

        $totalPayment = $monthlyPayment * $periodMonths;
        $totalInterest = $totalPayment - $loanAmount;

        return [
            'car_price' => $carPrice,
            'down_payment_amount' => $downPaymentAmount,
            'down_payment_percentage' => $downPaymentPct,
            'loan_amount' => $loanAmount,
            'monthly_payment' => $monthlyPayment,
            'period_months' => $periodMonths,
            'total_payment' => $totalPayment,
            'total_interest' => max(0, $totalInterest),
            'annual_rate' => $annualRate,
            'bank' => [
                'id' => $bank->id,
                'name' => $bank->name,
            ],
        ];
    }
}
