<?php

namespace Tests\Feature;

use App\Jobs\SendConversionTrackingJob;
use App\Models\Brand;
use App\Models\Car;
use App\Services\Tracking\ConversionTrackingService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ConversionTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    /** @test */
    public function test_conversion_tracking_service_sends_payloads_to_meta_tiktok_and_snap()
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response(['events_received' => 1], 200),
            'https://business-api.tiktok.com/*' => Http::response(['code' => 0, 'message' => 'OK'], 200),
            'https://tr.snapchat.com/*' => Http::response(['status' => 'SUCCESS'], 200),
        ]);

        config([
            'services.meta.pixel_id' => '27909085665438703',
            'services.meta.capi_token' => 'test_meta_token',
            'services.tiktok.pixel_id' => 'DA0MI2JC77UFIU519G0G',
            'services.tiktok.access_token' => 'test_tiktok_token',
            'services.snapchat.pixel_id' => 'bdbd26a3-c7a9-4250-b6a4-bcfe43476132',
            'services.snapchat.access_token' => 'test_snap_token',
        ]);

        $service = new ConversionTrackingService;
        $service->trackLead(
            user: [
                'phone' => '0566404863',
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@test.com',
                'ip' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 Test Agent',
            ],
            custom: [
                'content_name' => 'Toyota Camry 2025',
                'value' => 125000,
                'currency' => 'SAR',
            ],
            eventId: 'test_lead_123',
        );

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'graph.facebook.com')
                && $request['data'][0]['event_name'] === 'Lead'
                && $request['data'][0]['event_id'] === 'test_lead_123';
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'business-api.tiktok.com')
                && $request['data'][0]['event'] === 'SubmitForm'
                && $request['data'][0]['event_id'] === 'test_lead_123';
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'tr.snapchat.com')
                && $request['event_type'] === 'SIGN_UP';
        });
    }

    /** @test */
    public function test_booking_creation_dispatches_conversion_tracking_job()
    {
        Queue::fake();

        $brand = Brand::create([
            'name' => ['en' => 'Toyota', 'ar' => 'تويوتا'],
            'slug' => 'toyota',
            'is_active' => true,
        ]);

        $car = Car::create([
            'brand_id' => $brand->id,
            'name' => ['en' => 'Toyota Yaris', 'ar' => 'تويوتا يارس'],
            'slug' => ['en' => 'toyota-yaris', 'ar' => 'تويوتا-يارس'],
            'model' => 'Yaris',
            'year' => 2025,
            'type' => 'sedan',
            'cash_price' => 70000,
            'min_down_payment' => 10000,
            'min_installment' => 1200,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('store.api.booking.store'), [
            'car_id' => $car->id,
            'client_name' => 'Fahad Al-Harbi',
            'client_phone' => '0555555555',
            'duration_years' => 5,
            'booking_type' => 'purchase',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', ['client_phone' => '966555555555']);

        Queue::assertPushed(SendConversionTrackingJob::class, function ($job) {
            return $job->type === 'booking' && $job->userData['phone'] === '966555555555';
        });
    }
}
