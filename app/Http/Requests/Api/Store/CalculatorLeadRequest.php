<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Store;

use App\Http\Requests\Api\ApiBaseRequest;

final class CalculatorLeadRequest extends ApiBaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^05\d{8}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
            'monthly_obligations' => ['required', 'numeric', 'min:0'],
            'employer_type' => ['nullable', 'string', 'in:government,private,company,institution,military,retired,freelance'],
            'employer_name' => ['nullable', 'string', 'max:255'],
            'years_of_service' => ['nullable', 'numeric', 'min:0', 'max:60'],
            'has_mortgage_loan' => ['nullable', 'boolean'],
            'has_personal_loan' => ['nullable', 'boolean'],
            'has_traffic_violations' => ['nullable', 'boolean'],
            'has_simah_default' => ['nullable', 'boolean'],
            'preferred_bank_id' => ['nullable', 'integer', 'exists:calculator_banks,id'],
            'monthly_installment' => ['nullable', 'numeric', 'min:0'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'period_months' => ['nullable', 'integer'],
            'car_ids' => ['nullable', 'array'],
            'car_ids.*' => ['integer', 'exists:cars,id'],
            'preferred_color' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
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
            'phone.regex' => 'الرجاء إدخال رقم جوال سعودي صحيح (مثال: 05xxxxxxxx)',
        ];
    }
}
