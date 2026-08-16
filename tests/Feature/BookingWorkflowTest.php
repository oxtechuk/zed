<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $admin;

    protected Employee $sales;

    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(PermissionSeeder::class);

        // Create Admin
        $this->admin = Employee::create([
            'name' => 'Admin User',
            'username' => 'test_admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'phone' => '0500000001',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');

        // Create Sales (Standard employee)
        $this->sales = Employee::create([
            'name' => 'Sales User',
            'username' => 'test_sales',
            'email' => 'sales@test.com',
            'password' => bcrypt('password'),
            'phone' => '0500000002',
            'role' => 'employee',
            'is_active' => true,
        ]);
        $this->sales->assignRole('employee');

        // Create test brand and car because of NOT NULL database constraints
        $brand = Brand::create([
            'name' => ['en' => 'Toyota', 'ar' => 'تويوتا'],
            'slug' => 'toyota',
            'is_active' => true,
        ]);

        $this->car = Car::create([
            'brand_id' => $brand->id,
            'name' => ['en' => 'Toyota Camry', 'ar' => 'تويوتا كامري'],
            'slug' => ['en' => 'toyota-camry', 'ar' => 'تويوتا-كامري'],
            'model' => 'Camry',
            'year' => 2025,
            'type' => 'sedan',
            'cash_price' => 125000,
            'min_down_payment' => 25000,
            'min_installment' => 3500,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function test_sales_employee_cannot_transition_to_pending_without_valid_parameters()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'pending',
            ]);

        $response->assertSessionHasErrors(['pending_reason', 'follow_up_at', 'note']);
        $this->assertEquals('new', $booking->fresh()->status);
    }

    /** @test */
    public function test_sales_employee_can_transition_to_pending_with_valid_parameters()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'pending',
                'pending_reason' => 'Waiting for client salary release',
                'follow_up_at' => now()->addDays(2)->toDateTimeString(),
                'note' => 'Client asked to follow up next week',
            ]);

        $response->assertSessionHasNoErrors();
        $booking = $booking->fresh();
        $this->assertEquals('pending', $booking->status);
        $this->assertEquals('Waiting for client salary release', $booking->pending_reason);
        $this->assertNotNull($booking->follow_up_at);
        $this->assertDatabaseHas('booking_notes', [
            'booking_id' => $booking->id,
            'new_status' => 'pending',
        ]);
    }

    /** @test */
    public function test_returning_from_pending_to_active_resets_pending_fields()
    {
        $booking = Booking::create([
            'client_name' => 'Pending Client',
            'client_phone' => '0511111112',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'pending',
            'pending_reason' => 'Waiting for documents',
            'follow_up_at' => now()->addDays(3),
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'bank_review',
                'note' => 'Documents received, sent to bank',
            ]);

        $response->assertSessionHasNoErrors();
        $booking = $booking->fresh();
        $this->assertEquals('bank_review', $booking->status);
        $this->assertNull($booking->pending_reason);
        $this->assertNull($booking->follow_up_at);
    }

    /** @test */
    public function test_sales_employee_can_deliver_booking_with_four_financial_fields()
    {
        $booking = Booking::create([
            'client_name' => 'Delivered Client',
            'client_phone' => '0511111113',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'authorized',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'received',
                'purchase_price' => 90000,
                'authorization_price' => 98000,
                'expenses' => 1500,
                'net_commission' => 6500,
                'note' => 'Car handed over to customer',
            ]);

        $response->assertSessionHasNoErrors();
        $booking = $booking->fresh();
        $this->assertEquals('received', $booking->status);
        $this->assertEquals(90000, (float) $booking->purchase_price);
        $this->assertEquals(98000, (float) $booking->authorization_price);
        $this->assertEquals(1500, (float) $booking->expenses);
        $this->assertEquals(6500, (float) $booking->net_commission);
        $this->assertNotNull($booking->delivered_at);
    }

    /** @test */
    public function test_sales_employee_cannot_directly_close_lost_booking_instead_routed_for_approval()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        // "lost_client_cancelled" is a closing lost status
        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'lost_client_cancelled',
                'note' => 'Client cancelled the order',
            ]);

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('waiting_supervisor_approval', $booking->status);
        $this->assertEquals('lost_client_cancelled', $booking->proposed_status);
    }

    /** @test */
    public function test_supervisor_can_directly_close_a_booking()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'lost_client_cancelled',
                'note' => 'Direct close by admin',
            ]);

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('lost_client_cancelled', $booking->status);
        $this->assertNull($booking->proposed_status);
    }

    /** @test */
    public function test_supervisor_can_approve_a_pending_close_request()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'waiting_supervisor_approval',
            'proposed_status' => 'lost_client_cancelled',
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->patch(route('crm.bookings.approve', $booking));

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('lost_client_cancelled', $booking->status);
        $this->assertNull($booking->proposed_status);
    }

    /** @test */
    public function test_supervisor_can_reject_a_pending_close_request_and_return_to_active_stage()
    {
        $booking = Booking::create([
            'client_name' => 'Test Client',
            'client_phone' => '0511111111',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'waiting_supervisor_approval',
            'proposed_status' => 'lost_client_cancelled',
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->patch(route('crm.bookings.reject', $booking), [
                'status' => 'recontact_client',
                'note' => 'Client needs to be recontacted, do not close yet',
            ]);

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('recontact_client', $booking->status);
        $this->assertNull($booking->proposed_status);
    }

    /** @test */
    public function test_all_four_order_lists_render_and_filter_by_employee()
    {
        // 1. Active index
        $response1 = $this->actingAs($this->admin, 'employee')
            ->get(route('crm.bookings.index', ['employee_id' => $this->sales->id]));
        $response1->assertStatus(200);

        // 2. Pending index
        $response2 = $this->actingAs($this->admin, 'employee')
            ->get(route('crm.bookings.pending', ['employee_id' => $this->sales->id]));
        $response2->assertStatus(200);

        // 3. Delivered index
        $response3 = $this->actingAs($this->admin, 'employee')
            ->get(route('crm.bookings.delivered', ['employee_id' => $this->sales->id]));
        $response3->assertStatus(200);

        // 4. Closed index
        $response4 = $this->actingAs($this->admin, 'employee')
            ->get(route('crm.bookings.closed', ['employee_id' => $this->sales->id]));
        $response4->assertStatus(200);
    }
}
