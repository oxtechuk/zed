<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Store\CalculatorCalculateRequest;
use App\Http\Requests\Api\Store\CalculatorLeadRequest;
use App\Http\Requests\Api\Store\CalculatorOtpSendRequest;
use App\Http\Requests\Api\Store\CalculatorOtpVerifyRequest;
use App\Http\Resources\Store\CalculatorBankResource;
use App\Jobs\SendConversionTrackingJob;
use App\Services\Api\Store\CalculatorApiService;

final class CalculatorController extends ApiBaseController
{
    public function __construct(
        private readonly CalculatorApiService $calculatorService,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function banks()
    {
        $banks = $this->calculatorService->banks();

        return $this->respondSuccess(
            CalculatorBankResource::collection($banks),
        );
    }

    public function calculate(CalculatorCalculateRequest $request)
    {
        $bankId = $request->filled('bank_id') ? (int) $request->input('bank_id') : null;

        $result = $this->calculatorService->calculate(
            carId: (int) $request->input('car_id'),
            downPaymentPct: (float) $request->input('down_payment_percentage', 0),
            periodMonths: (int) $request->input('period_months', 60),
            bankId: $bankId,
        );

        return $this->respondSuccess($result);
    }

    public function saveLead(CalculatorLeadRequest $request)
    {
        $lead = $this->calculatorService->saveLead($request->validated());

        $eventId = $request->input('event_id') ?: ('calc_'.$lead->id);

        SendConversionTrackingJob::dispatch(
            type: 'calculator',
            userData: [
                'phone' => $lead->phone,
                'name' => $lead->name,
                'email' => $lead->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'fbp' => $request->cookie('_fbp'),
                'fbc' => $request->cookie('_fbc'),
                'ttclid' => $request->cookie('ttclid'),
                'scid' => $request->cookie('sc_clickid'),
            ],
            customData: [
                'currency' => 'SAR',
                'value' => (float) ($lead->monthly_installment ?? $lead->salary ?? 0),
                'content_name' => 'Finance Calculator Lead',
                'content_category' => 'Calculator',
            ],
            eventId: $eventId,
        );

        return $this->respondCreated(
            [
                'lead_id' => $lead->id,
                'event_id' => $eventId,
            ],
            'Lead saved successfully'
        );
    }

    public function sendOtp(CalculatorOtpSendRequest $request)
    {
        $result = $this->calculatorService->sendOtp(
            $request->input('phone')
        );

        if (! $result['success']) {
            return $this->respondError($result['message'], 422);
        }

        return $this->respondSuccess(null, $result['message']);
    }

    public function verifyOtp(CalculatorOtpVerifyRequest $request)
    {
        $verified = $this->calculatorService->verifyOtp(
            $request->input('phone'),
            $request->input('code')
        );

        if (! $verified) {
            return $this->respondError('Invalid or expired code', 422);
        }

        $lead = $this->calculatorService->createLeadFromVerified(
            $request->input('name'),
            $request->input('phone')
        );

        return $this->respondCreated(
            ['lead_id' => $lead->id],
            'OTP verified successfully'
        );
    }
}
