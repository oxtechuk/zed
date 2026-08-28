<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactSource;
use App\Models\Lead;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function bookings(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $base = Booking::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        // 1. Financial Performance
        $financial = [
            'total_sold' => (clone $base)->where('status', 'sold')->count(),
            'total_down_payment' => (clone $base)->where('status', 'sold')->sum('down_payment'),
            'total_remaining' => (clone $base)->where('status', 'sold')
                ->selectRaw('SUM(monthly_installment * (duration_years * 12)) as total')->value('total') ?? 0,
            'total_bookings' => (clone $base)->count(),
        ];

        // 2. Employee Performance
        $employees = (clone $base)->whereNotNull('assigned_to')
            ->selectRaw('assigned_to, count(*) as total_bookings, sum(case when status = \'sold\' then 1 else 0 end) as total_sold')
            ->groupBy('assigned_to')
            ->with('employee:id,name')
            ->get();

        // 3. Car Popularity
        $topCars = (clone $base)->whereNotNull('car_id')
            ->selectRaw('car_id, count(*) as total_bookings, sum(case when status = \'sold\' then 1 else 0 end) as total_sold')
            ->groupBy('car_id')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->with('car.brand')
            ->get();

        // 4. Installments Analysis (For sold or interested)
        $installments = [
            'avg_down_payment' => (clone $base)->where('down_payment', '>', 0)->avg('down_payment') ?? 0,
            'avg_duration' => (clone $base)->where('duration_years', '>', 0)->avg('duration_years') ?? 0,
            'avg_monthly' => (clone $base)->where('monthly_installment', '>', 0)->avg('monthly_installment') ?? 0,
        ];

        // 5. Sources Analysis
        $sourcesReport = (clone $base)->whereNotNull('source')
            ->selectRaw('source, count(*) as total_bookings, sum(case when status = \'sold\' then 1 else 0 end) as total_sold, sum(case when status = \'rejected\' then 1 else 0 end) as total_rejected')
            ->groupBy('source')
            ->orderByDesc('total_bookings')
            ->get();

        return view('crm.reports.bookings', compact(
            'from', 'to', 'financial', 'employees', 'topCars', 'installments', 'sourcesReport', 'settings'
        ));
    }

    /** تقرير مصادر وتتبع الحملات الإعلانية (Meta, Snapchat, TikTok, Google, Direct) */
    public function sources(Request $request)
    {
        $preset = $request->input('preset', 'this_month');
        $from = $request->input('from');
        $to = $request->input('to');

        if ($preset === 'today') {
            $from = today()->toDateString();
            $to = today()->toDateString();
        } elseif ($preset === 'last_7_days') {
            $from = now()->subDays(6)->toDateString();
            $to = today()->toDateString();
        } elseif ($preset === 'last_month') {
            $from = now()->subMonth()->startOfMonth()->toDateString();
            $to = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($preset === 'all') {
            $from = '2025-01-01';
            $to = today()->toDateString();
        } else {
            // this_month (default)
            $from = $from ?: now()->startOfMonth()->toDateString();
            $to = $to ?: today()->toDateString();
        }

        $bookingsQuery = Booking::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $leadsQuery = Lead::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $bookings = (clone $bookingsQuery)->with(['car.brand', 'employee'])->latest()->get();
        $leads = (clone $leadsQuery)->latest()->get();

        $platforms = [
            'Meta (Instagram / Facebook)' => [
                'name' => 'Meta (Instagram / Facebook)',
                'label' => 'إنستغرام وفيسبوك (Meta)',
                'icon' => 'bi-instagram',
                'bg' => '#FDF2F8',
                'color' => '#DB2777',
                'border' => '#FBCFE8',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'Snapchat' => [
                'name' => 'Snapchat',
                'label' => 'سناب شات (Snapchat Ads)',
                'icon' => 'bi-snapchat',
                'bg' => '#FEFCE8',
                'color' => '#CA8A04',
                'border' => '#FEF08A',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'TikTok' => [
                'name' => 'TikTok',
                'label' => 'تيك توك (TikTok Ads)',
                'icon' => 'bi-tiktok',
                'bg' => '#F8FAFC',
                'color' => '#0F172A',
                'border' => '#CBD5E1',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'Google Ads' => [
                'name' => 'Google Ads',
                'label' => 'إعلانات جوجل (Google Ads)',
                'icon' => 'bi-google',
                'bg' => '#EFF6FF',
                'color' => '#2563EB',
                'border' => '#BFDBFE',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'Google Search (Organic)' => [
                'name' => 'Google Search (Organic)',
                'label' => 'بحث جوجل الطبيعي (SEO)',
                'icon' => 'bi-search',
                'bg' => '#F0FDF4',
                'color' => '#16A34A',
                'border' => '#BBF7D0',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'مباشر (Direct Traffic)' => [
                'name' => 'مباشر (Direct Traffic)',
                'label' => 'زيارات مباشرة (Direct)',
                'icon' => 'bi-globe2',
                'bg' => '#F1F5F9',
                'color' => '#475569',
                'border' => '#E2E8F0',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
            'CRM الداخلي' => [
                'name' => 'CRM الداخلي',
                'label' => 'إدخال يدوي (CRM)',
                'icon' => 'bi-shield-check',
                'bg' => '#F3E8FF',
                'color' => '#7E22CE',
                'border' => '#E9D5FF',
                'leads_count' => 0,
                'bookings_count' => 0,
                'sold_count' => 0,
                'total_revenue' => 0,
            ],
        ];

        $otherChannels = [];
        $campaignsMap = [];
        $mediumsMap = [];

        foreach ($bookings as $b) {
            $channel = $b->marketing_channel ?: \App\Services\AttributionHelper::resolveChannel($b->utm_source, $b->utm_medium, $b->referrer, $b->click_id, $b->source);

            if (isset($platforms[$channel])) {
                $platforms[$channel]['bookings_count']++;
                if (in_array($b->status, ['sold', 'received'])) {
                    $platforms[$channel]['sold_count']++;
                    $platforms[$channel]['total_revenue'] += (float) ($b->total_price ?: 0);
                }
            } else {
                if (! isset($otherChannels[$channel])) {
                    $otherChannels[$channel] = [
                        'name' => $channel,
                        'label' => $channel,
                        'icon' => 'bi-link-45deg',
                        'bg' => '#F1F5F9',
                        'color' => '#475569',
                        'border' => '#E2E8F0',
                        'leads_count' => 0,
                        'bookings_count' => 0,
                        'sold_count' => 0,
                        'total_revenue' => 0,
                    ];
                }
                $otherChannels[$channel]['bookings_count']++;
                if (in_array($b->status, ['sold', 'received'])) {
                    $otherChannels[$channel]['sold_count']++;
                    $otherChannels[$channel]['total_revenue'] += (float) ($b->total_price ?: 0);
                }
            }

            $camp = $b->utm_campaign ?: 'بدون حملة محددة (Direct / Organic)';
            if (! isset($campaignsMap[$camp])) {
                $campaignsMap[$camp] = [
                    'name' => $camp,
                    'channel' => $channel,
                    'bookings' => 0,
                    'leads' => 0,
                    'sold' => 0,
                    'revenue' => 0,
                ];
            }
            $campaignsMap[$camp]['bookings']++;
            if (in_array($b->status, ['sold', 'received'])) {
                $campaignsMap[$camp]['sold']++;
                $campaignsMap[$camp]['revenue'] += (float) ($b->total_price ?: 0);
            }

            $med = $b->utm_medium ?: ($b->utm_source ? 'general' : 'direct');
            if (! isset($mediumsMap[$med])) {
                $mediumsMap[$med] = ['name' => $med, 'count' => 0];
            }
            $mediumsMap[$med]['count']++;
        }

        foreach ($leads as $l) {
            $channel = $l->marketing_channel ?: \App\Services\AttributionHelper::resolveChannel($l->utm_source, $l->utm_medium, $l->referrer, $l->click_id, 'Contact Form');

            if (isset($platforms[$channel])) {
                $platforms[$channel]['leads_count']++;
            } else {
                if (! isset($otherChannels[$channel])) {
                    $otherChannels[$channel] = [
                        'name' => $channel,
                        'label' => $channel,
                        'icon' => 'bi-link-45deg',
                        'bg' => '#F1F5F9',
                        'color' => '#475569',
                        'border' => '#E2E8F0',
                        'leads_count' => 0,
                        'bookings_count' => 0,
                        'sold_count' => 0,
                        'total_revenue' => 0,
                    ];
                }
                $otherChannels[$channel]['leads_count']++;
            }

            $camp = $l->utm_campaign ?: 'بدون حملة محددة (Direct / Organic)';
            if (! isset($campaignsMap[$camp])) {
                $campaignsMap[$camp] = [
                    'name' => $camp,
                    'channel' => $channel,
                    'bookings' => 0,
                    'leads' => 0,
                    'sold' => 0,
                    'revenue' => 0,
                ];
            }
            $campaignsMap[$camp]['leads']++;
        }

        $allChannels = array_merge($platforms, $otherChannels);

        uasort($campaignsMap, fn ($a, $b) => ($b['bookings'] + $b['leads']) <=> ($a['bookings'] + $a['leads']));
        uasort($mediumsMap, fn ($a, $b) => $b['count'] <=> $a['count']);

        $totalLeads = $leads->count();
        $totalBookings = $bookings->count();
        $totalInteractions = $totalLeads + $totalBookings;
        $totalSold = (clone $bookingsQuery)->whereIn('status', ['sold', 'received'])->count();
        $totalRevenue = (clone $bookingsQuery)->whereIn('status', ['sold', 'received'])->sum('total_price');

        $recentAttributed = $bookings->take(15);

        return view('crm.reports.sources', compact(
            'from', 'to', 'preset', 'allChannels', 'campaignsMap', 'mediumsMap',
            'totalLeads', 'totalBookings', 'totalInteractions', 'totalSold', 'totalRevenue',
            'recentAttributed'
        ));
    }

    /** تقرير شهري: نشاط الطلبات والعملاء المحتملين */
    public function monthly()
    {
        $start = now()->subMonths(11)->startOfMonth();

        $bookingRows = Booking::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $leadRows = Lead::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->copy()->subMonths($i)->startOfMonth();
            $key = $m->format('Y-m');
            $months[$key] = [
                'label' => $m->translatedFormat('M Y'),
                'bookings' => 0,
                'leads' => 0,
            ];
        }

        foreach ($bookingRows as $b) {
            $key = Carbon::parse($b->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['bookings']++;
            }
        }
        foreach ($leadRows as $l) {
            $key = Carbon::parse($l->created_at)->format('Y-m');
            if (isset($months[$key])) {
                $months[$key]['leads']++;
            }
        }

        return view('crm.reports.monthly', compact('months'));
    }
}
