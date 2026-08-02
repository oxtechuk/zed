<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('calculator_bank_id')->nullable()->after('assigned_to')->constrained('calculator_banks')->nullOnDelete();
            $table->unsignedBigInteger('balloon_payment')->nullable()->after('monthly_installment');
            $table->text('offer_notes')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('calculator_bank_id');
            $table->dropColumn(['balloon_payment', 'offer_notes']);
        });
    }
};
