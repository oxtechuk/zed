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
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (! Schema::hasColumn('bookings', 'utm_source')) {
                    $table->string('utm_source', 100)->nullable()->index()->after('source');
                }
                if (! Schema::hasColumn('bookings', 'utm_medium')) {
                    $table->string('utm_medium', 100)->nullable()->after('utm_source');
                }
                if (! Schema::hasColumn('bookings', 'utm_campaign')) {
                    $table->string('utm_campaign', 191)->nullable()->index()->after('utm_medium');
                }
                if (! Schema::hasColumn('bookings', 'utm_content')) {
                    $table->string('utm_content', 191)->nullable()->after('utm_campaign');
                }
                if (! Schema::hasColumn('bookings', 'utm_term')) {
                    $table->string('utm_term', 191)->nullable()->after('utm_content');
                }
                if (! Schema::hasColumn('bookings', 'referrer')) {
                    $table->text('referrer')->nullable()->after('utm_term');
                }
                if (! Schema::hasColumn('bookings', 'click_id')) {
                    $table->string('click_id', 191)->nullable()->index()->after('referrer');
                }
                if (! Schema::hasColumn('bookings', 'marketing_channel')) {
                    $table->string('marketing_channel', 100)->nullable()->index()->after('click_id');
                }
            });
        }

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (! Schema::hasColumn('leads', 'utm_source')) {
                    $table->string('utm_source', 100)->nullable()->index()->after('status_details');
                }
                if (! Schema::hasColumn('leads', 'utm_medium')) {
                    $table->string('utm_medium', 100)->nullable()->after('utm_source');
                }
                if (! Schema::hasColumn('leads', 'utm_campaign')) {
                    $table->string('utm_campaign', 191)->nullable()->index()->after('utm_medium');
                }
                if (! Schema::hasColumn('leads', 'utm_content')) {
                    $table->string('utm_content', 191)->nullable()->after('utm_campaign');
                }
                if (! Schema::hasColumn('leads', 'utm_term')) {
                    $table->string('utm_term', 191)->nullable()->after('utm_content');
                }
                if (! Schema::hasColumn('leads', 'referrer')) {
                    $table->text('referrer')->nullable()->after('utm_term');
                }
                if (! Schema::hasColumn('leads', 'click_id')) {
                    $table->string('click_id', 191)->nullable()->index()->after('referrer');
                }
                if (! Schema::hasColumn('leads', 'marketing_channel')) {
                    $table->string('marketing_channel', 100)->nullable()->index()->after('click_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn([
                    'utm_source', 'utm_medium', 'utm_campaign', 'utm_content',
                    'utm_term', 'referrer', 'click_id', 'marketing_channel',
                ]);
            });
        }

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn([
                    'utm_source', 'utm_medium', 'utm_campaign', 'utm_content',
                    'utm_term', 'referrer', 'click_id', 'marketing_channel',
                ]);
            });
        }
    }
};
