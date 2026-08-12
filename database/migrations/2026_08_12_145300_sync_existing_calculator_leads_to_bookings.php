<?php

use App\Models\Booking;
use App\Models\CalculatorLead;
use App\Models\Car;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (CalculatorLead::all() as $lead) {
            // Check if booking already exists with same phone and source = 'calculator'
            $exists = Booking::where('client_phone', $lead->phone)
                ->where('source', 'calculator')
                ->exists();

            if (! $exists) {
                Booking::create([
                    'client_name' => $lead->name,
                    'client_phone' => $lead->phone,
                    'client_email' => $lead->details['email'] ?? null,
                    'car_id' => $lead->car_id,
                    'source' => 'calculator',
                    'status' => 'new',
                    'notes' => $lead->details['notes'] ?? null,
                    'monthly_installment' => $lead->details['monthly_installment'] ?? 0,
                    'down_payment' => 0,
                    'duration_years' => 5,
                    'total_price' => $lead->car_id ? (Car::find($lead->car_id)?->current_price ?? 0) : 0,
                    'created_at' => $lead->created_at,
                    'updated_at' => $lead->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down action needed for sync migration
    }
};
