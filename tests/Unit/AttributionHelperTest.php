<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\AttributionHelper;
use PHPUnit\Framework\TestCase;

final class AttributionHelperTest extends TestCase
{
    public function test_google_ads_with_cpc(): void
    {
        $channel = AttributionHelper::resolveChannel(
            utmSource: 'google',
            utmMedium: 'cpc',
            referrer: 'https://google.com'
        );

        $this->assertSame('Google Ads', $channel);
    }

    public function test_google_ads_with_gclid(): void
    {
        $channel = AttributionHelper::resolveChannel(
            utmSource: null,
            utmMedium: null,
            clickId: 'gclid_example_123'
        );

        $this->assertSame('Google Ads', $channel);
    }

    public function test_google_search_organic(): void
    {
        $channel = AttributionHelper::resolveChannel(
            utmSource: 'google',
            utmMedium: 'organic'
        );

        $this->assertSame('Google Search (Organic)', $channel);
    }

    public function test_google_search_from_referrer(): void
    {
        $channel = AttributionHelper::resolveChannel(
            referrer: 'https://www.google.com.sa/'
        );

        $this->assertSame('Google Search (Organic)', $channel);
    }

    public function test_snapchat_with_utm(): void
    {
        $channel = AttributionHelper::resolveChannel(
            utmSource: 'snapchat',
            utmMedium: 'cpc'
        );

        $this->assertSame('Snapchat', $channel);
    }

    public function test_snapchat_with_click_id(): void
    {
        $channel = AttributionHelper::resolveChannel(
            clickId: 'sc_clickid_xyz'
        );

        $this->assertSame('Snapchat', $channel);
    }

    public function test_google_not_misclassified_as_snapchat(): void
    {
        // Even if some random string or generic parameter is present, google source must resolve to Google Ads
        $channel = AttributionHelper::resolveChannel(
            utmSource: 'google',
            utmMedium: 'cpc',
            referrer: 'https://www.google.com'
        );

        $this->assertNotSame('Snapchat', $channel);
        $this->assertSame('Google Ads', $channel);
    }

    public function test_meta_instagram_and_facebook(): void
    {
        $ig = AttributionHelper::resolveChannel(utmSource: 'instagram', utmMedium: 'cpc');
        $this->assertSame('Meta (Instagram / Facebook)', $ig);

        $fb = AttributionHelper::resolveChannel(clickId: 'fbclid_test');
        $this->assertSame('Meta (Instagram / Facebook)', $fb);
    }

    public function test_tiktok(): void
    {
        $channel = AttributionHelper::resolveChannel(utmSource: 'tiktok', utmMedium: 'cpc');
        $this->assertSame('TikTok', $channel);
    }

    public function test_twitter_x(): void
    {
        $channel = AttributionHelper::resolveChannel(utmSource: 'twitter');
        $this->assertSame('Twitter / X', $channel);
    }

    public function test_youtube(): void
    {
        $channel = AttributionHelper::resolveChannel(utmSource: 'youtube');
        $this->assertSame('YouTube', $channel);
    }

    public function test_direct_traffic(): void
    {
        $channel = AttributionHelper::resolveChannel();
        $this->assertSame('مباشر (Direct Traffic)', $channel);
    }
}
