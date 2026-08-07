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
    public function test_sales_employee_cannot_directly_close_a_booking_instead_routed_for_approval()
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

        // "received" is a closing status (won)
        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'received',
                'note' => 'Car delivered successfully',
            ]);

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('waiting_supervisor_approval', $booking->status);
        $this->assertEquals('received', $booking->proposed_status);
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
                'status' => 'received',
                'note' => 'Direct close by admin',
            ]);

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('received', $booking->status);
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
            'proposed_status' => 'received',
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->patch(route('crm.bookings.approve', $booking));

        $response->assertSessionHas('success');
        $booking = $booking->fresh();
        $this->assertEquals('received', $booking->status);
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
            'proposed_status' => 'received',
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
    public function test_non_supervisor_cannot_approve_or_reject_close_requests()
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
            'proposed_status' => 'received',
        ]);

        // Attempt approve as sales
        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.approve', $booking));
        $response->assertStatus(403);

        // Attempt reject as sales
        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.reject', $booking), [
                'status' => 'recontact_client',
                'note' => 'hacky reject',
            ]);
        $response->assertStatus(403);

        $booking = $booking->fresh();
        $this->assertEquals('waiting_supervisor_approval', $booking->status);
    }
}
