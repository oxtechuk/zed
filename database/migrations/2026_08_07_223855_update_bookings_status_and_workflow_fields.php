<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
            $table->string('pending_reason')->nullable()->after('status');
            $table->dateTime('follow_up_at')->nullable()->after('pending_reason');
            $table->string('proposed_status')->nullable()->after('follow_up_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['pending_reason', 'follow_up_at', 'proposed_status']);
            // Change it back to enum
            $table->enum('status', [
                'new',
                'contacted',
                'interested',
                'rejected',
                'sold',
            ])->default('new')->change();
        });
    }
};
