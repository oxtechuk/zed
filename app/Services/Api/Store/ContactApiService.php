<?php

declare(strict_types=1);

namespace App\Services\Api\Store;

use App\Models\ContactSource;
use App\Models\Lead;
use App\Services\AttributionHelper;
use App\Services\BookingAssignmentService;

final class ContactApiService
{
    public function submitContactForm(array $data): Lead
    {
        $source = ContactSource::firstOrCreate(
            ['name' => 'Contact Us Form'],
            ['is_active' => true]
        );

        $utmSource = $data['utm_source'] ?? null;
        $utmMedium = $data['utm_medium'] ?? null;
        $referrer = $data['referrer'] ?? null;
        $clickId = $data['click_id'] ?? null;

        $channel = $data['marketing_channel']
            ?? AttributionHelper::resolveChannel($utmSource, $utmMedium, $referrer, $clickId, 'Contact Form');

        $lead = Lead::create([
            'client_name' => $data['name'],
            'client_phone' => $data['phone'],
            'client_email' => $data['email'] ?? null,
            'subject' => $data['subject'] ?? null,
            'country' => $data['country'] ?? null,
            'status_details' => $data['message'] ?? null,
            'contact_source_id' => $source->id,
            'status' => 'new',
            'started_at' => now(),
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'referrer' => $referrer,
            'click_id' => $clickId,
            'marketing_channel' => $channel,
        ]);

        app(BookingAssignmentService::class)->autoAssignLead($lead);

        return $lead;
    }
}
