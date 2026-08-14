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
        ];
    }

    public function messages(): array
    {
        return [
            'client_phone.regex' => 'الرجاء إدخال رقم جوال سعودي صحيح (مثال: 05xxxxxxxx)',
        ];
    }
}
