<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Store;

use App\Http\Requests\Api\ApiBaseRequest;

final class BookingRequest extends ApiBaseRequest
{
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['required', 'string', 'max:20', 'regex:/^05\d{8}$/'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'duration_years' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'interest_rate' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'booking_type' => ['nullable', 'string', 'in:test_drive,purchase,inquiry'],
            'location' => ['nullable', 'string', 'max:500'],
            'calculator_bank_id' => ['nullable', 'integer', 'exists:calculator_banks,id'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'monthly_obligations' => ['nullable', 'numeric', 'min:0'],
            'employer_type' => ['nullable', 'string', 'max:100'],
            'years_of_service' => ['nullable', 'numeric', 'min:0'],
            'has_personal_loan' => ['nullable', 'boolean'],
            'has_mortgage_loan' => ['nullable', 'boolean'],
            'has_simah_default' => ['nullable', 'boolean'],
            'has_traffic_violations' => ['nullable', 'boolean'],
            'preferred_color' => ['nullable', 'string', 'max:100'],
            'monthly_installment' => ['nullable', 'numeric', 'min:0'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:191'],
            'utm_content' => ['nullable', 'string', 'max:191'],
            'utm_term' => ['nullable', 'string', 'max:191'],
            'referrer' => ['nullable', 'string', 'max:1000'],
            'click_id' => ['nullable', 'string', 'max:191'],
            'marketing_channel' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_phone.regex' => 'الرجاء إدخال رقم جوال سعودي صحيح (مثال: 05xxxxxxxx)',
        ];
    }
}
