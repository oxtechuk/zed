<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Lead;
use App\Services\AttributionHelper;
use Illuminate\Console\Command;

final class FixAttributionsCommand extends Command
{
    protected $signature = 'crm:fix-attributions
                            {--dry-run : Only show what will be changed without persisting}';

    protected $description = 'Re-evaluate and fix misattributed marketing channels for bookings and leads';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info('🔍 جاري فحص الحجوزات والطلبات لإعادة تقييم قنوات التسويق...');
        if ($dryRun) {
            $this->warn('⚠️ وضع التجربة مفعل (Dry Run): لن يتم تعديل البيانات الفعلية.');
        }

        $fixedBookings = 0;
        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            $resolved = AttributionHelper::resolveChannel(
                $booking->utm_source,
                $booking->utm_medium,
                $booking->referrer,
                $booking->click_id,
                $booking->source
            );

            if ($booking->marketing_channel !== $resolved) {
                $this->line(sprintf(
                    'Booking #%d: [%s] ➔ [%s] (Source: %s, Medium: %s, ClickID: %s)',
                    $booking->id,
                    $booking->marketing_channel ?: 'None',
                    $resolved,
                    $booking->utm_source ?: '-',
                    $booking->utm_medium ?: '-',
                    $booking->click_id ? substr($booking->click_id, 0, 15) : '-'
                ));

                if (! $dryRun) {
                    $booking->update(['marketing_channel' => $resolved]);
                }

                $fixedBookings++;
            }
        }

        $fixedLeads = 0;
        $leads = Lead::all();

        foreach ($leads as $lead) {
            $resolved = AttributionHelper::resolveChannel(
                $lead->utm_source,
                $lead->utm_medium,
                $lead->referrer,
                $lead->click_id,
                'Contact Form'
            );

            if ($lead->marketing_channel !== $resolved) {
                $this->line(sprintf(
                    'Lead #%d: [%s] ➔ [%s] (Source: %s, Medium: %s)',
                    $lead->id,
                    $lead->marketing_channel ?: 'None',
                    $resolved,
                    $lead->utm_source ?: '-',
                    $lead->utm_medium ?: '-'
                ));

                if (! $dryRun) {
                    $lead->update(['marketing_channel' => $resolved]);
                }

                $fixedLeads++;
            }
        }

        $this->info(sprintf(
            '✅ اكتمل الفحص: تم تحديث %d حجز و %d ليد.',
            $fixedBookings,
            $fixedLeads
        ));

        return self::SUCCESS;
    }
}
