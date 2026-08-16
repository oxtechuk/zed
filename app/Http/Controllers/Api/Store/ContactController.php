<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Api\Response\Builder\ApiResponseBuilder;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Store\ContactRequest;
use App\Jobs\SendConversionTrackingJob;
use App\Services\Api\Store\ContactApiService;

final class ContactController extends ApiBaseController
{
    public function __construct(
        private readonly ContactApiService $contactService,
    ) {
        parent::__construct(app(ApiResponseBuilder::class));
    }

    public function store(ContactRequest $request)
    {
        $lead = $this->contactService->submitContactForm($request->validated());

        $eventId = $request->input('event_id') ?: ('contact_'.$lead->id);

        SendConversionTrackingJob::dispatch(
            type: 'contact',
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
                'content_name' => 'Contact Form Submission',
                'content_category' => 'Contact',
            ],
            eventId: $eventId,
        );

        return $this->respondCreated(
            [
                'lead_id' => $lead->id,
                'event_id' => $eventId,
            ],
            'Message submitted successfully'
        );
    }
}
