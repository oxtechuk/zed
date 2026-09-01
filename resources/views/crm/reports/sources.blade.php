@extends('partials.Layouts.crm-master')
@section('title', __('تحليل مصادر العملاء والحملات الإعلانية') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 d-print-none bg-white p-3 rounded-4 shadow-sm border">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-1.5 fw-bold" style="font-size: 12px;">
                    <i class="bi bi-bullseye me-1"></i> {{ __('تتبع الحملات والإعلانات') }}
                </span>
            </div>
            <h4 class="mb-1 fw-bold text-dark mt-1">{{ __('تحليل مصادر العملاء والحملات الإعلانية') }}</h4>
            <p class="text-muted mb-0 small">{{ __('متابعة دقيقة لمصادر العملاء والطلبات القادمة من Meta (Instagram & Facebook)، Snapchat، TikTok، Google Ads، والزيارات المباشرة') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary fw-bold rounded-3 shadow-xs d-flex align-items-center gap-2" onclick="window.print()">
                <i class="bi bi-printer"></i> {{ __('طباعة التقرير') }}
            </button>
            <a href="#urlBuilderSection" class="btn btn-primary fw-bold rounded-3 shadow-xs d-flex align-items-center gap-2">
                <i class="bi bi-link-45deg fs-5"></i> {{ __('منشئ روابط الحملات (UTM Builder)') }}
            </a>
        </div>
    </div>

    {{-- Date Filter & Presets Bar --}}
    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden d-print-none">
        <div class="card-body p-3 p-md-4 bg-light-subtle">
            <form method="GET" action="{{ route('crm.reports.sources') }}" class="row g-3 align-items-end" id="filterForm">
                {{-- Preset Shortcuts --}}
                <div class="col-12 mb-1">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="small fw-bold text-muted me-1">{{ __('فترات سريعة:') }}</span>
                        <a href="{{ route('crm.reports.sources', ['preset' => 'today']) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $preset === 'today' ? 'btn-primary' : 'btn-white border text-dark' }}">
                            {{ __('اليوم') }}
                        </a>
                        <a href="{{ route('crm.reports.sources', ['preset' => 'last_7_days']) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $preset === 'last_7_days' ? 'btn-primary' : 'btn-white border text-dark' }}">
                            {{ __('آخر 7 أيام') }}
                        </a>
                        <a href="{{ route('crm.reports.sources', ['preset' => 'this_month']) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $preset === 'this_month' ? 'btn-primary' : 'btn-white border text-dark' }}">
                            {{ __('هذا الشهر') }}
                        </a>
                        <a href="{{ route('crm.reports.sources', ['preset' => 'last_month']) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $preset === 'last_month' ? 'btn-primary' : 'btn-white border text-dark' }}">
                            {{ __('الشهر الماضي') }}
                        </a>
                        <a href="{{ route('crm.reports.sources', ['preset' => 'all']) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $preset === 'all' ? 'btn-primary' : 'btn-white border text-dark' }}">
                            {{ __('كافة الفترات') }}
                        </a>
                    </div>
                </div>

                {{-- Custom Date Inputs --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">{{ __('من تاريخ') }}</label>
                    <input type="date" name="from" class="form-control border-0 bg-white shadow-xs" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">{{ __('إلى تاريخ') }}</label>
                    <input type="date" name="to" class="form-control border-0 bg-white shadow-xs" value="{{ $to }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3 py-2 shadow-xs">
                        <i class="bi bi-funnel me-1"></i> {{ __('تحديث البيانات') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Executive Summary Counters --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #1E293B, #0F172A); color:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold opacity-75">{{ __('إجمالي التفاعلات والطلبات') }}</span>
                    <i class="bi bi-people-fill fs-5 opacity-50"></i>
                </div>
                <div class="fs-2 fw-black">{{ number_format($totalInteractions) }}</div>
                <div class="small opacity-75 mt-1" style="font-size:11px;">
                    <span>{{ $totalBookings }} {{ __('طلب سيارة') }}</span> • <span>{{ $totalLeads }} {{ __('استفسار') }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #059669, #047857); color:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold opacity-75">{{ __('الصفقات المغلقة والمباعة') }}</span>
                    <i class="bi bi-check2-circle fs-5 opacity-50"></i>
                </div>
                <div class="fs-2 fw-black">{{ number_format($totalSold) }}</div>
                <div class="small opacity-75 mt-1" style="font-size:11px;">
                    {{ __('معدل التحويل:') }} <strong>{{ $totalBookings > 0 ? round(($totalSold / $totalBookings) * 100, 1) : 0 }}%</strong>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); color:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold opacity-75">{{ __('إجمالي صافي العمولات (الأرباح)') }}</span>
                    <i class="bi bi-wallet2 fs-5 opacity-50"></i>
                </div>
                <div class="fs-3 fw-black">{{ number_format($totalRevenue) }} <span class="fs-6 fw-normal">ر.س</span></div>
                <div class="small opacity-75 mt-1" style="font-size:11px;">
                    {{ __('صافي الربح الفعلي المحقق') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #7C3AED, #6D28D9); color:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold opacity-75">{{ __('الحملات النشطة المسجلة') }}</span>
                    <i class="bi bi-tags-fill fs-5 opacity-50"></i>
                </div>
                <div class="fs-2 fw-black">{{ count($campaignsMap) }}</div>
                <div class="small opacity-75 mt-1" style="font-size:11px;">
                    {{ __('حملة إعلانية متبعة') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Marketing Platforms Cards Grid --}}
    <h5 class="fw-bold text-dark mb-3">
        <i class="bi bi-grid-fill me-2 text-primary"></i> {{ __('توزيع أداء القنوات والمنصات الإعلانية') }}
    </h5>

    <div class="row g-3 mb-5">
        @foreach($allChannels as $channelKey => $p)
        @php
            $chanTotal = $p['bookings_count'] + $p['leads_count'];
            $chanPct = $totalInteractions > 0 ? round(($chanTotal / $totalInteractions) * 100, 1) : 0;
            $convPct = $p['bookings_count'] > 0 ? round(($p['sold_count'] / $p['bookings_count']) * 100, 1) : 0;
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border: 1px solid {{ $p['border'] }} !important;">
                {{-- Platform Header --}}
                <div class="p-3.5 d-flex align-items-center justify-content-between" style="background: {{ $p['bg'] }}; border-bottom: 1px solid {{ $p['border'] }};">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 d-flex align-items-center justify-content-center shadow-xs"
                             style="width: 38px; height: 38px; background: #fff; color: {{ $p['color'] }}; font-size: 20px;">
                            <i class="bi {{ $p['icon'] }}"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: {{ $p['color'] }}; font-size: 14px;">
                                {{ $p['label'] }}
                            </h6>
                            <span class="text-muted" style="font-size: 11px;">{{ $p['name'] }}</span>
                        </div>
                    </div>
                    <span class="badge rounded-pill fw-bold px-2.5 py-1" style="background: {{ $p['color'] }}; color:#fff; font-size: 11px;">
                        {{ $chanPct }}%
                    </span>
                </div>

                {{-- Platform Body Stats --}}
                <div class="card-body p-3.5">
                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded-3">
                                <span class="text-muted d-block small" style="font-size:11px;">{{ __('الطلبات') }}</span>
                                <strong class="fs-5 text-dark">{{ $p['bookings_count'] }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded-3">
                                <span class="text-muted d-block small" style="font-size:11px;">{{ __('الاستفسارات') }}</span>
                                <strong class="fs-5 text-dark">{{ $p['leads_count'] }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded-3" style="background: #ECFDF5; color: #047857;">
                                <span class="d-block small" style="font-size:11px;">{{ __('المبيعات') }}</span>
                                <strong class="fs-5">{{ $p['sold_count'] }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Value --}}
                    <div class="d-flex justify-content-between align-items-center py-2 px-2.5 bg-light rounded-3 mb-2" style="font-size: 12px;">
                        <span class="text-muted">{{ __('صافي العمولات المحققة:') }}</span>
                        <strong class="text-success">{{ number_format($p['total_revenue']) }} ر.س</strong>
                    </div>

                    {{-- Progress Bar for Channel Share --}}
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 11px;">
                            <span>{{ __('حصة القناة من إجمالي الزوار') }}</span>
                            <span class="fw-bold">{{ $chanPct }}%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 7px; background:#F1F5F9;">
                            <div class="progress-bar rounded-pill" role="progressbar"
                                 style="width: {{ $chanPct }}%; background: {{ $p['color'] }};"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4 mb-5">
        {{-- Campaigns Performance Table --}}
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-tag-fill me-2 text-primary"></i> {{ __('أداء الحملات الإعلانية (Campaigns)') }}
                    </h6>
                    <span class="badge bg-light text-secondary border small">{{ count($campaignsMap) }} {{ __('حملة') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#F8F9FC; font-size:12px;">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-bold">{{ __('اسم الحملة (utm_campaign)') }}</th>
                                <th class="py-3 text-muted fw-bold">{{ __('المنصة') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('الطلبات') }}</th>
                                <th class="py-3 text-muted fw-bold text-center">{{ __('المبيعات') }}</th>
                                <th class="py-3 text-muted fw-bold text-end px-4">{{ __('صافي العمولات المحققة') }}</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px;">
                            @forelse($campaignsMap as $camp)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    <i class="bi bi-megaphone me-1 text-muted"></i>
                                    {{ $camp['name'] }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                        {{ $camp['channel'] }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold">{{ $camp['bookings'] + $camp['leads'] }}</td>
                                <td class="text-center">
                                    @if($camp['sold'] > 0)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5">{{ $camp['sold'] }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end px-4 fw-bold text-dark">
                                    {{ $camp['revenue'] > 0 ? number_format($camp['revenue']) . ' ر.س' : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                    {{ __('لا توجد حملات مسجلة خلال الفترة المحددة') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Mediums & Ad Formats Table --}}
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-sliders me-2 text-primary"></i> {{ __('المواضع وأنواع الإعلانات (Mediums)') }}
                    </h6>
                    <span class="badge bg-light text-secondary border small">{{ count($mediumsMap) }} {{ __('موضع') }}</span>
                </div>
                <div class="card-body p-4">
                    @php $maxMed = max(collect($mediumsMap)->max('count') ?: 1, 1); @endphp
                    @forelse($mediumsMap as $med)
                    @php $medPct = round(($med['count'] / $maxMed) * 100); @endphp
                    <div class="mb-3.5">
                        <div class="d-flex justify-content-between align-items-center mb-1.5" style="font-size:13px;">
                            <span class="fw-bold text-dark">
                                <i class="bi bi-dot text-primary fs-5 align-middle"></i>
                                {{ $med['name'] }}
                            </span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5">{{ $med['count'] }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px; background: #F1F5F9;">
                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $medPct }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4 opacity-50">
                        <i class="bi bi-funnel fs-1 d-block mb-1"></i>
                        {{ __('لا توجد مواضع مسجلة') }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Attributed Leads & Bookings Feed --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-activity me-2 text-primary"></i> {{ __('سجل آخر الطلبات مع تفاصيل التتبع والمصدر') }}
                </h6>
                <p class="text-muted small mb-0 mt-0.5">{{ __('أحدث العملاء المسجلين مع المنصة والحملة ومعرف النقر') }}</p>
            </div>
            <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm btn-light border fw-bold rounded-2">
                {{ __('عرض كافة الطلبات') }}
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC; font-size:12px;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold">#</th>
                        <th class="py-3 text-muted fw-bold">{{ __('العميل') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('المنصة والمصدر') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحملة (Campaign)') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('السيارة') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحالة') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('التاريخ') }}</th>
                        <th class="py-3 text-muted fw-bold px-4">{{ __('إجراء') }}</th>
                    </tr>
                </thead>
                <tbody style="font-size:13px;">
                    @forelse($recentAttributed as $b)
                    @php
                        $chan = $b->marketing_channel ?: \App\Services\AttributionHelper::resolveChannel($b->utm_source, $b->utm_medium, $b->referrer, $b->click_id, $b->source);
                        $meta = \App\Services\AttributionHelper::getChannelMeta($chan);
                    @endphp
                    <tr>
                        <td class="px-4 py-3 fw-bold">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="text-decoration-none" style="color:var(--crm-red);">#{{ $b->id }}</a>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $b->client_name }}</div>
                            <div class="small text-muted" dir="ltr">{{ $b->client_phone }}</div>
                        </td>
                        <td>
                            <span class="badge d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2 fw-bold"
                                  style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; border: 1px solid {{ $meta['border'] }}; font-size: 11px;">
                                <i class="bi {{ $meta['icon'] }}"></i>
                                {{ $chan }}
                            </span>
                            @if($b->click_id)
                                <div class="mt-1 small text-muted" style="font-size: 10px;" title="Click ID: {{ $b->click_id }}">
                                    <i class="bi bi-fingerprint"></i> {{ Str::limit($b->click_id, 16) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($b->utm_campaign)
                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 11px;">
                                    <i class="bi bi-tag me-1"></i>{{ $b->utm_campaign }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $b->car?->brand?->name }} {{ $b->car?->name ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold" style="font-size: 11px;">
                                {{ $b->status_label ?? $b->status }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $b->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-4">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light border rounded-2" title="{{ __('عرض تفاصيل الطلب') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            {{ __('لا توجد طلبات مسجلة مؤخراً') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Interactive UTM URL Builder Tool --}}
    <div id="urlBuilderSection" class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5" style="border: 2px solid #E2E8F0 !important;">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center p-2">
                    <i class="bi bi-link-45deg fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">{{ __('أداة توليد روابط الحملات الإعلانية (UTM Campaign URL Builder)') }}</h5>
                    <p class="text-muted small mb-0">{{ __('استخدم هذه الأداة لإنشاء روابط مخصصة لحملاتك على سناب شات، تيك توك، ميتا (إنستغرام/فيسبوك)، وجوجل مع تتبع دقيق') }}</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                {{-- Preset Platform Selector --}}
                <div class="col-12 mb-2">
                    <label class="form-label fw-bold small text-muted">{{ __('اختر المنصة الإعلانية لتعبئة الإعدادات تلقائياً:') }}</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="setBuilderPreset('meta')">
                            <i class="bi bi-instagram me-1"></i> Meta (Instagram / Facebook)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3" onclick="setBuilderPreset('snapchat')">
                            <i class="bi bi-snapchat me-1"></i> Snapchat Ads
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-dark fw-bold rounded-pill px-3" onclick="setBuilderPreset('tiktok')">
                            <i class="bi bi-tiktok me-1"></i> TikTok Ads
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3" onclick="setBuilderPreset('google')">
                            <i class="bi bi-google me-1"></i> Google Ads
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary fw-bold rounded-pill px-3" onclick="setBuilderPreset('x')">
                            <i class="bi bi-twitter-x me-1"></i> Twitter / X
                        </button>
                    </div>
                </div>

                {{-- Page URL --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">{{ __('رابط الصفحة المقصودة (Landing Page)') }} <span class="text-danger">*</span></label>
                    <select id="builderBaseUrl" class="form-select bg-light border-0" onchange="generateUtmUrl()">
                        <option value="{{ url('/') }}">{{ __('الصفحة الرئيسية') }} ({{ url('/') }})</option>
                        <option value="{{ url('/cars') }}">{{ __('صفحة السيارات') }} (/cars)</option>
                        <option value="{{ url('/finance-calculator') }}">{{ __('حاسبة التمويل') }} (/finance-calculator)</option>
                        <option value="{{ url('/offers') }}">{{ __('العروض الخاصة') }} (/offers)</option>
                        <option value="{{ url('/about') }}">{{ __('من نحن') }} (/about)</option>
                        <option value="{{ url('/contact') }}">{{ __('تواصل معنا') }} (/contact)</option>
                    </select>
                </div>

                {{-- Campaign Source --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted">{{ __('مصدر الحملة (utm_source)') }} <span class="text-danger">*</span></label>
                    <input type="text" id="builderSource" class="form-control bg-light border-0" placeholder="e.g. snapchat, meta, tiktok, google" oninput="generateUtmUrl()">
                </div>

                {{-- Campaign Medium --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">{{ __('نوع الإعلان / الموضع (utm_medium)') }}</label>
                    <input type="text" id="builderMedium" class="form-control bg-light border-0" placeholder="e.g. story, reels, feed, cpc" oninput="generateUtmUrl()">
                </div>

                {{-- Campaign Name --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">{{ __('اسم الحملة (utm_campaign)') }} <span class="text-danger">*</span></label>
                    <input type="text" id="builderCampaign" class="form-control bg-light border-0" placeholder="e.g. summer_offers_2026, toyota_deal" oninput="generateUtmUrl()">
                </div>

                {{-- Campaign Content --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">{{ __('محتوى الإعلان (utm_content)') }}</label>
                    <input type="text" id="builderContent" class="form-control bg-light border-0" placeholder="e.g. video_v1, banner_blue" oninput="generateUtmUrl()">
                </div>

                {{-- Output Result Box --}}
                <div class="col-12 mt-4">
                    <label class="form-label fw-bold small text-dark">{{ __('الرابط الجاهز للاستخدام في الحملة:') }}</label>
                    <div class="input-group">
                        <input type="text" id="builderOutput" class="form-control bg-white border fw-bold text-primary font-monospace py-2.5" readonly dir="ltr">
                        <button type="button" class="btn btn-primary px-4 fw-bold" onclick="copyUtmUrl()">
                            <i class="bi bi-clipboard-check me-1"></i> <span id="copyBtnText">{{ __('نسخ الرابط') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function setBuilderPreset(platform) {
    const srcInput = document.getElementById('builderSource');
    const medInput = document.getElementById('builderMedium');

    if (platform === 'meta') {
        srcInput.value = 'meta';
        medInput.value = 'reels';
    } else if (platform === 'snapchat') {
        srcInput.value = 'snapchat';
        medInput.value = 'story';
    } else if (platform === 'tiktok') {
        srcInput.value = 'tiktok';
        medInput.value = 'feed_video';
    } else if (platform === 'google') {
        srcInput.value = 'google';
        medInput.value = 'cpc';
    } else if (platform === 'x') {
        srcInput.value = 'twitter';
        medInput.value = 'post';
    }
    generateUtmUrl();
}

function generateUtmUrl() {
    const base = document.getElementById('builderBaseUrl').value.trim();
    const source = encodeURIComponent(document.getElementById('builderSource').value.trim());
    const medium = encodeURIComponent(document.getElementById('builderMedium').value.trim());
    const campaign = encodeURIComponent(document.getElementById('builderCampaign').value.trim());
    const content = encodeURIComponent(document.getElementById('builderContent').value.trim());

    if (!base) return;

    let url = base;
    const params = [];

    if (source) params.push('utm_source=' + source);
    if (medium) params.push('utm_medium=' + medium);
    if (campaign) params.push('utm_campaign=' + campaign);
    if (content) params.push('utm_content=' + content);

    if (params.length > 0) {
        url += (url.includes('?') ? '&' : '?') + params.join('&');
    }

    document.getElementById('builderOutput').value = url;
}

function copyUtmUrl() {
    const out = document.getElementById('builderOutput');
    if (!out.value) return;

    out.select();
    navigator.clipboard.writeText(out.value).then(() => {
        const btnText = document.getElementById('copyBtnText');
        const orig = btnText.innerText;
        btnText.innerText = 'تم النسخ بنجاح!';
        setTimeout(() => {
            btnText.innerText = orig;
        }, 2000);
    });
}

// Initial generate
document.addEventListener('DOMContentLoaded', function() {
    setBuilderPreset('meta');
});
</script>

<style>
    .fw-black { font-weight: 900; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .btn-white { background: #fff; }
    .bg-primary-subtle { background: #e7f1ff; }
    .bg-success-subtle { background: #e1f7e3; }
</style>
@endsection
