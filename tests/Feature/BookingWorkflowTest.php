<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Employee;
use App\Services\BookingAssignmentService;
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

    /** @test */
    public function test_employee_cannot_reassign_booking()
    {
        $otherSales = Employee::create([
            'name' => 'Other Sales',
            'username' => 'other_sales',
            'email' => 'othersales@test.com',
            'password' => bcrypt('password'),
            'phone' => '0500000003',
            'role' => 'employee',
            'is_active' => true,
        ]);
        $otherSales->assignRole('employee');

        $booking = Booking::create([
            'client_name' => 'Reassign Test Client',
            'client_phone' => '0511111112',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.assign', $booking), [
                'employee_id' => $otherSales->id,
            ]);

        $response->assertStatus(403);
        $this->assertEquals($this->sales->id, $booking->fresh()->assigned_to);
    }

    /** @test */
    public function test_admin_can_reassign_booking()
    {
        $otherSales = Employee::create([
            'name' => 'Other Sales 2',
            'username' => 'other_sales_2',
            'email' => 'othersales2@test.com',
            'password' => bcrypt('password'),
            'phone' => '0500000004',
            'role' => 'employee',
            'is_active' => true,
        ]);
        $otherSales->assignRole('employee');

        $booking = Booking::create([
            'client_name' => 'Reassign Admin Test Client',
            'client_phone' => '0511111113',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->patch(route('crm.bookings.assign', $booking), [
                'employee_id' => $otherSales->id,
            ]);

        $response->assertRedirect();
        $this->assertEquals($otherSales->id, $booking->fresh()->assigned_to);
    }

    /** @test */
    public function test_employee_cannot_delete_booking()
    {
        $booking = Booking::create([
            'client_name' => 'Delete Test Client',
            'client_phone' => '0511111114',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->delete(route('crm.bookings.destroy', $booking));

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    /** @test */
    public function test_admin_can_delete_booking()
    {
        $booking = Booking::create([
            'client_name' => 'Admin Delete Test Client',
            'client_phone' => '0511111115',
            'car_id' => $this->car->id,
            'down_payment' => 20000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->admin, 'employee')
            ->delete(route('crm.bookings.destroy', $booking));

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    /** @test */
    public function test_calculator_lead_auto_assigns_to_sales_rep_and_appears_in_crm()
    {
        $booking = Booking::create([
            'client_name' => 'Calculator Client',
            'client_phone' => '0599999999',
            'car_id' => $this->car->id,
            'down_payment' => 15000,
            'duration_years' => 5,
            'monthly_installment' => 1800,
            'total_price' => 120000,
            'source' => 'calculator',
            'status' => 'new',
        ]);

        $service = app(BookingAssignmentService::class);
        $service->autoAssign($booking);

        $this->assertEquals($this->sales->id, $booking->fresh()->assigned_to);

        $response = $this->actingAs($this->admin, 'employee')
            ->get(route('crm.bookings.index', ['source' => 'calculator']));

        $response->assertStatus(200);
        $response->assertSee('Calculator Client');
    }

    /** @test */
    public function test_delivering_booking_saves_financial_details_and_displays_in_offer_details_tab()
    {
        $booking = Booking::create([
            'client_name' => 'Delivered Client',
            'client_phone' => '0598888888',
            'car_id' => $this->car->id,
            'down_payment' => 10000,
            'duration_years' => 5,
            'monthly_installment' => 2000,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.status', $booking), [
                'status' => 'received',
                'purchase_price' => 90000,
                'authorization_price' => 105000,
                'expenses' => 1500,
                'net_commission' => 13500,
                'down_payment' => 12000,
                'monthly_installment' => 2200,
                'note' => 'تم استلام السيارة وفحصها بنجاح',
            ]);

        $response->assertRedirect();
        $fresh = $booking->fresh();
        $this->assertEquals('received', $fresh->status);
        $this->assertEquals(90000, (float) $fresh->purchase_price);
        $this->assertEquals(105000, (float) $fresh->authorization_price);
        $this->assertEquals(1500, (float) $fresh->expenses);
        $this->assertEquals(13500, (float) $fresh->net_commission);
        $this->assertEquals(12000, (float) $fresh->down_payment);
        $this->assertEquals(2200, (float) $fresh->monthly_installment);
        $this->assertEquals('تم استلام السيارة وفحصها بنجاح', $fresh->delivery_note);

        // Verify that the show page renders the delivered financial data
        $showResponse = $this->actingAs($this->sales, 'employee')
            ->get(route('crm.bookings.show', $booking));

        $showResponse->assertStatus(200);
        $showResponse->assertSee('90,000.00');
        $showResponse->assertSee('105,000.00');
        $showResponse->assertSee('13,500.00');
        $showResponse->assertSee('2,200.00');
    }

    /** @test */
    public function test_updating_offer_and_financial_details_persists_and_renders()
    {
        $booking = Booking::create([
            'client_name' => 'Offer Client',
            'client_phone' => '0597777777',
            'car_id' => $this->car->id,
            'down_payment' => 10000,
            'duration_years' => 5,
            'monthly_installment' => 1500,
            'total_price' => 100000,
            'status' => 'new',
            'assigned_to' => $this->sales->id,
        ]);

        $response = $this->actingAs($this->sales, 'employee')
            ->patch(route('crm.bookings.offer', $booking), [
                'purchase_price' => 95000,
                'authorization_price' => 110000,
                'expenses' => 2000,
                'net_commission' => 13000,
                'total_price' => 110000,
                'down_payment' => 15000,
                'duration_years' => 4,
                'monthly_installment' => 2400,
                'balloon_payment' => 25000,
                'offer_notes' => 'عرض خاص مع تأمين شامل',
                'delivery_note' => 'توصيل لباب المنزل',
            ]);

        $response->assertRedirect();
        $fresh = $booking->fresh();
        $this->assertEquals(95000, (float) $fresh->purchase_price);
        $this->assertEquals(110000, (float) $fresh->authorization_price);
        $this->assertEquals(2000, (float) $fresh->expenses);
        $this->assertEquals(13000, (float) $fresh->net_commission);
        $this->assertEquals(15000, (float) $fresh->down_payment);
        $this->assertEquals(4, $fresh->duration_years);
        $this->assertEquals(2400, (float) $fresh->monthly_installment);
        $this->assertEquals(25000, (float) $fresh->balloon_payment);
        $this->assertEquals('عرض خاص مع تأمين شامل', $fresh->offer_notes);
        $this->assertEquals('توصيل لباب المنزل', $fresh->delivery_note);
    }
}
