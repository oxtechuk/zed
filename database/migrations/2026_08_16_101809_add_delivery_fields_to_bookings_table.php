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
            $table->decimal('purchase_price', 12, 2)->nullable()->after('total_price');
            $table->decimal('authorization_price', 12, 2)->nullable()->after('purchase_price');
            $table->decimal('expenses', 12, 2)->nullable()->after('authorization_price');
            $table->decimal('net_commission', 12, 2)->nullable()->after('expenses');
            $table->dateTime('delivered_at')->nullable()->after('last_contacted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_price',
                'authorization_price',
                'expenses',
                'net_commission',
                'delivered_at',
            ]);
        });
    }
};
