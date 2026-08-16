<?php

namespace App\Services\Tracking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConversionTrackingService
{
    /**
     * Dispatch conversion events across Meta CAPI, TikTok Events API, and Snap Conversions API.
     */
    public function trackLead(array $user, array $custom = [], ?string $eventId = null): void
    {
        $eventId = $eventId ?: 'lead_'.time().'_'.bin2hex(random_bytes(4));
        $eventTime = time();

        $this->sendMetaEvent('Lead', $user, $custom, $eventId, $eventTime);
        $this->sendTikTokEvent('SubmitForm', $user, $custom, $eventId, $eventTime);
        $this->sendSnapchatEvent('SIGN_UP', $user, $custom, $eventId, $eventTime);
    }

    /**
     * Dispatch finance calculator registration events.
     */
    public function trackCalculatorLead(array $user, array $custom = [], ?string $eventId = null): void
    {
        $eventId = $eventId ?: 'calc_'.time().'_'.bin2hex(random_bytes(4));
        $eventTime = time();

        $this->sendMetaEvent('CompleteRegistration', $user, $custom, $eventId, $eventTime);
        $this->sendTikTokEvent('CompleteRegistration', $user, $custom, $eventId, $eventTime);
        $this->sendSnapchatEvent('CUSTOM_EVENT_1', $user, $custom, $eventId, $eventTime);
    }

    /**
     * Send event to Meta Conversions API (CAPI)
     */
    public function sendMetaEvent(string $eventName, array $user, array $custom, string $eventId, int $eventTime): void
    {
        $pixelId = config('services.meta.pixel_id');
        $token = config('services.meta.capi_token');

        if (! $pixelId || ! $token) {
            return;
        }

        $userData = [
            'client_ip_address' => $user['ip'] ?? request()->ip(),
            'client_user_agent' => $user['user_agent'] ?? request()->userAgent(),
        ];

        if (! empty($user['phone'])) {
            $userData['ph'] = [$this->hashPhone($user['phone'])];
        }

        if (! empty($user['email'])) {
            $userData['em'] = [$this->hashEmail($user['email'])];
        }

        if (! empty($user['name'])) {
            $userData['fn'] = [$this->hashString($user['name'])];
        }

        if (! empty($user['fbp'])) {
            $userData['fbp'] = $user['fbp'];
        } elseif (request()->hasCookie('_fbp')) {
            $userData['fbp'] = request()->cookie('_fbp');
        }

        if (! empty($user['fbc'])) {
            $userData['fbc'] = $user['fbc'];
        } elseif (request()->hasCookie('_fbc')) {
            $userData['fbc'] = request()->cookie('_fbc');
        }

        $customData = [
            'currency' => $custom['currency'] ?? 'SAR',
            'value' => (float) ($custom['value'] ?? 0),
            'content_name' => $custom['content_name'] ?? null,
            'content_category' => $custom['content_category'] ?? null,
        ];

        $payload = [
            'data' => [
                [
                    'event_name' => $eventName,
                    'event_time' => $eventTime,
                    'event_id' => $eventId,
                    'event_source_url' => $user['url'] ?? request()->fullUrl(),
                    'action_source' => 'website',
                    'user_data' => array_filter($userData),
                    'custom_data' => array_filter($customData),
                ],
            ],
            'access_token' => $token,
        ];

        try {
            $response = Http::asJson()
                ->timeout(5)
                ->post("https://graph.facebook.com/v19.0/{$pixelId}/events", $payload);

            if ($response->failed()) {
                Log::warning('[Meta CAPI] Event dispatch warning: '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('[Meta CAPI] Error sending event: '.$e->getMessage());
        }
    }

    /**
     * Send event to TikTok Events API
     */
    public function sendTikTokEvent(string $eventName, array $user, array $custom, string $eventId, int $eventTime): void
    {
        $pixelId = config('services.tiktok.pixel_id');
        $token = config('services.tiktok.access_token');

        if (! $pixelId || ! $token) {
            return;
        }

        $userInfo = [
            'ip' => $user['ip'] ?? request()->ip(),
            'user_agent' => $user['user_agent'] ?? request()->userAgent(),
        ];

        if (! empty($user['phone'])) {
            $userInfo['phone_sha256'] = $this->hashPhone($user['phone']);
        }

        if (! empty($user['email'])) {
            $userInfo['email_sha256'] = $this->hashEmail($user['email']);
        }

        if (! empty($user['ttclid'])) {
            $userInfo['ttclid'] = $user['ttclid'];
        } elseif (request()->hasCookie('ttclid')) {
            $userInfo['ttclid'] = request()->cookie('ttclid');
        }

        $properties = [
            'currency' => $custom['currency'] ?? 'SAR',
            'value' => (float) ($custom['value'] ?? 0),
            'content_name' => $custom['content_name'] ?? null,
            'content_type' => 'product',
        ];

        $payload = [
            'event_source' => 'web',
            'event_source_id' => $pixelId,
            'data' => [
                [
                    'event' => $eventName,
                    'event_time' => $eventTime,
                    'event_id' => $eventId,
                    'user' => array_filter($userInfo),
                    'properties' => array_filter($properties),
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Access-Token' => $token,
                'Content-Type' => 'application/json',
            ])->timeout(5)->post('https://business-api.tiktok.com/open_api/v1.3/event/track/', $payload);

            if ($response->failed()) {
                Log::warning('[TikTok Events API] Event dispatch warning: '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('[TikTok Events API] Error sending event: '.$e->getMessage());
        }
    }

    /**
     * Send event to Snapchat Conversions API
     */
    public function sendSnapchatEvent(string $eventType, array $user, array $custom, string $eventId, int $eventTime): void
    {
        $pixelId = config('services.snapchat.pixel_id');
        $token = config('services.snapchat.access_token');

        if (! $pixelId || ! $token) {
            return;
        }

        $payload = [
            'version' => '2.0',
            'pixel_id' => $pixelId,
            'event_type' => $eventType,
            'event_conversion_type' => 'WEB',
            'event_tag' => $custom['content_name'] ?? 'car_lead',
            'timestamp' => (string) $eventTime,
            'user_agent' => $user['user_agent'] ?? request()->userAgent(),
            'ip_address' => $user['ip'] ?? request()->ip(),
            'price' => (float) ($custom['value'] ?? 0),
            'currency' => $custom['currency'] ?? 'SAR',
        ];

        if (! empty($user['phone'])) {
            $payload['hashed_phone_number'] = $this->hashPhone($user['phone']);
        }

        if (! empty($user['email'])) {
            $payload['hashed_email'] = $this->hashEmail($user['email']);
        }

        if (! empty($user['scid'])) {
            $payload['sc_click_id'] = $user['scid'];
        } elseif (request()->hasCookie('sc_clickid')) {
            $payload['sc_click_id'] = request()->cookie('sc_clickid');
        }

        try {
            $response = Http::withToken($token)
                ->asJson()
                ->timeout(5)
                ->post('https://tr.snapchat.com/v2/conversion', array_filter($payload));

            if ($response->failed()) {
                Log::warning('[Snap CAPI] Event dispatch warning: '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('[Snap CAPI] Error sending event: '.$e->getMessage());
        }
    }

    /**
     * Standardize and hash phone number (E.164 without leading +)
     */
    private function hashPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '05')) {
            $digits = '966'.substr($digits, 1);
        } elseif (str_starts_with($digits, '5')) {
            $digits = '966'.$digits;
        }

        return hash('sha256', $digits);
    }

    /**
     * Standardize and hash email
     */
    private function hashEmail(string $email): string
    {
        return hash('sha256', trim(strtolower($email)));
    }

    /**
     * Standardize and hash string
     */
    private function hashString(string $val): string
    {
        return hash('sha256', trim(strtolower($val)));
    }
}
