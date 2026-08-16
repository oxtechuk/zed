<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Store\BookingRequest;
use App\Jobs\SendConversionTrackingJob;
use App\Models\Car;
use App\Services\Api\Store\BookingApiService;
use App\Services\Api\Store\Data\BookingData;
use App\Services\Cache\BookingCacheService;

final class BookingController extends ApiBaseController
{
    public function __construct(
        private readonly BookingApiService $bookingService,
        private readonly BookingCacheService $bookingCache,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    /**
     * Return booking page layout metadata (hero, steps, sections).
     */
    public function meta()
    {
        return $this->respondSuccess([
            'hero' => $this->bookingCache->rememberBookingHero(),
            'steps' => $this->bookingCache->rememberBookingSteps(),
            'sections' => $this->bookingCache->rememberBookingSections(),
        ], 'Booking page metadata retrieved successfully');
    }

    /**
     * Submit a new booking request.
     */
    public function store(BookingRequest $request)
    {
        try {
            $car = Car::findOrFail($request->input('car_id'));

            $cashPrice = (float) ($car->current_price ?? $car->cash_price ?? 0);

            $data = BookingData::fromRequest(
                $request->validated(),
                $cashPrice,
            );

            $booking = $this->bookingService->create($data);

            $eventId = $request->input('event_id') ?: ('booking_'.$booking->id);

            SendConversionTrackingJob::dispatch(
                type: 'booking',
                userData: [
                    'phone' => $booking->client_phone,
                    'name' => $booking->client_name,
                    'email' => $booking->client_email,
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
                    'value' => (float) ($booking->total_price ?: $cashPrice),
                    'content_name' => $car->name,
                    'content_category' => 'Car Request',
                ],
                eventId: $eventId,
            );

            return $this->respondCreated([
                'booking_id' => $booking->id,
                'event_id' => $eventId,
                'client_name' => $booking->client_name,
                'client_phone' => $booking->client_phone,
                'car_id' => $booking->car_id,
                'booking_type' => $booking->booking_type,
                'monthly_installment' => $booking->monthly_installment,
                'total_price' => $booking->total_price,
                'down_payment' => $booking->down_payment,
                'duration_years' => $booking->duration_years,
                'status' => $booking->status,
            ], 'Booking created successfully');
        } catch (\Throwable $e) {
            logger()->error('Booking creation failed: '.$e->getMessage(), [
                'exception' => $e,
                'input' => $request->all(),
            ]);

            return $this->respondError('Failed to process booking request: '.$e->getMessage(), 422);
        }
    }
}
