@extends('partials.Layouts.crm-master')
@section('title', __('الطلبات النشطة') . ' | Zad Capital')

@section('css')
<style>
    .crm-custom-booking-table {
        border-collapse: collapse;
        width: 100%;
    }
    .crm-custom-booking-table tr.crm-booking-row {
        border-bottom: 1px solid #ECEEF2;
        transition: background-color 0.15s ease;
    }
    .crm-custom-booking-table tr.crm-booking-row:hover {
        background-color: #FAFAFC;
    }
    .booking-id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #E6F4EA;
        color: #137333;
        font-weight: 700;
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    .booking-id-badge:hover {
        background-color: #CEEAD6;
        color: #0D652D;
    }
    .booking-meta-box {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 12.5px;
        line-height: 1.45;
    }
    .booking-meta-item {
        display: flex;
        align-items: baseline;
        gap: 6px;
        white-space: nowrap;
    }
    .booking-meta-key {
        color: #1E293B;
        font-weight: 700;
        font-size: 12.5px;
    }
    .booking-meta-val {
        color: #64748B;
        font-size: 12.5px;
    }
    .badge-payment {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-payment.badge-unpaid {
        background-color: #FEE2E2;
        color: #DC2626;
    }
    .badge-payment.badge-paid {
        background-color: #DCFCE7;
        color: #16A34A;
    }
    .badge-payment .dot {
        font-size: 8px;
    }
    .btn-action-square {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease;
        padding: 0;
    }
    .btn-action-square:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
    }
    /* Pagination styling matching the orange circular style in screenshot */
    .crm-pagination-wrapper .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .crm-pagination-wrapper .page-item .page-link {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
        border: 1px solid #E2E8F0;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        background: #FFFFFF;
        text-decoration: none;
        transition: all 0.2s;
        margin: 0;
        padding: 0;
    }
    .crm-pagination-wrapper .page-item.active .page-link {
        background-color: #EA580C !important;
        border-color: #EA580C !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.35);
    }
    .crm-pagination-wrapper .page-item .page-link:hover:not(.active) {
        background-color: #F8FAFC;
        border-color: #CBD5E1;
        color: #0F172A;
    }
    .crm-pagination-wrapper .page-item.disabled .page-link {
        background-color: #F8FAFC;
        border-color: #ECEEF2;
        color: #CBD5E1;
    }
    /* Sleek Professional Stat Cards */
    .stat-card-clean {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 16px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        text-decoration: none;
    }
    .stat-card-clean:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }
    .stat-card-clean.active {
        border-color: #2563EB;
        background: #F8FAFC;
        box-shadow: 0 0 0 1px #2563EB;
    }
    .stat-card-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-card-icon-box.blue {
        background: #EFF6FF;
        color: #2563EB;
    }
    .stat-card-icon-box.sky {
        background: #F0F9FF;
        color: #0284C7;
    }
    .stat-card-icon-box.purple {
        background: #FAF5FF;
        color: #9333EA;
    }
    .stat-card-icon-box.amber {
        background: #FFFBEB;
        color: #D97706;
    }
    .stat-card-icon-box.emerald {
        background: #F0FDF4;
        color: #16A34A;
    }
    .stat-card-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    .stat-card-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748B;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stat-card-value {
        font-size: 22px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('الطلبات النشطة') }}</span>
    </nav>

    {{-- Title Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#EBF5FF;color:#2563EB;">
                    <i class="bi bi-lightning-charge"></i>
                </span>
                {{ __('الطلبات النشطة (Active)') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('مسار المبيعات الفعلي ومتابعة الطلبات الجارية مع العملاء والبنوك') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('crm.bookings.pending') }}" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-3 px-3">
                <i class="bi bi-hourglass-split me-1 text-warning"></i> {{ __('قيد الانتظار') }}
            </a>
            <a href="{{ route('crm.bookings.delivered') }}" class="btn btn-sm btn-outline-success fw-bold rounded-3 px-3">
                <i class="bi bi-check2-circle me-1"></i> {{ __('تم التسليم') }}
            </a>
            @can('manage-bookings')
            <button class="btn btn-crm-primary btn-sm rounded-3 fw-bold px-3" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة طلب جديد') }}
            </button>
            @endcan
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        {{-- 1. All Active --}}
        <div class="col-6 col-xl-2 col-md-4">
            <a href="{{ route('crm.bookings.index') }}" class="text-decoration-none d-block h-100">
                <div class="stat-card-clean {{ !request('source') ? 'active' : '' }}">
                    <div class="stat-card-info">
                        <span class="stat-card-label">{{ __('إجمالي الطلبات النشطة') }}</span>
                        <span class="stat-card-value">{{ number_format($stats['total']) }}</span>
                    </div>
                    <div class="stat-card-icon-box blue">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- 2. Car Requests --}}
        <div class="col-6 col-xl-2 col-md-4">
            <a href="{{ route('crm.bookings.index', array_merge(request()->query(), ['source' => 'cars'])) }}" class="text-decoration-none d-block h-100">
                <div class="stat-card-clean {{ request('source') === 'cars' ? 'active' : '' }}">
                    <div class="stat-card-info">
                        <span class="stat-card-label">{{ __('طلبات السيارات') }}</span>
                        <span class="stat-card-value" style="color: #0284C7;">{{ number_format($stats['car_requests']) }}</span>
                    </div>
                    <div class="stat-card-icon-box sky">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- 3. Calculator Leads --}}
        <div class="col-6 col-xl-3 col-md-4">
            <a href="{{ route('crm.bookings.index', array_merge(request()->query(), ['source' => 'calculator'])) }}" class="text-decoration-none d-block h-100">
                <div class="stat-card-clean {{ request('source') === 'calculator' ? 'active' : '' }}">
                    <div class="stat-card-info">
                        <span class="stat-card-label">{{ __('عملاء حاسبة التمويل') }}</span>
                        <span class="stat-card-value" style="color: #7C3AED;">{{ number_format($stats['calculator_leads']) }}</span>
                    </div>
                    <div class="stat-card-icon-box purple">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- 4. Pending Review --}}
        <div class="col-6 col-xl-2 col-md-6">
            <div class="stat-card-clean">
                <div class="stat-card-info">
                    <span class="stat-card-label">{{ __('بانتظار التواصل والمراجعة') }}</span>
                    <span class="stat-card-value text-warning">{{ number_format($stats['pending_review']) }}</span>
                </div>
                <div class="stat-card-icon-box amber">
                    <i class="bi bi-telephone-inbound-fill"></i>
                </div>
            </div>
        </div>

        {{-- 5. Under Bank --}}
        <div class="col-12 col-xl-3 col-md-6">
            <div class="stat-card-clean">
                <div class="stat-card-info">
                    <span class="stat-card-label">{{ __('تحت الدراسة والتعميد') }}</span>
                    <span class="stat-card-value text-success">{{ number_format($stats['under_bank']) }}</span>
                </div>
                <div class="stat-card-icon-box emerald">
                    <i class="bi bi-bank2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                    {{-- فلتر الموظف (للإدارة يظهر كافة الموظفين المسجلين) --}}
                    @if($isAdmin)
                    <div style="min-width:180px;">
                        <select name="employee_id" class="form-select form-select-sm" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;" onchange="this.form.submit()">
                            <option value="">{{ __('الموظف — جميع الموظفين') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- فلتر الشهر --}}
                    <div style="position:relative;">
                        <input type="month" name="month" value="{{ request('month') }}"
                                style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; min-width: 150px;"
                                onchange="this.form.submit()" title="{{ __('تصفية بالشهر') }}">
                    </div>

                    {{-- مصدر ونوع الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value="">{{ __('المصدر والنوع — الكل') }}</option>
                        <option value="cars" {{ request('source')==='cars'?'selected':'' }}>{{ __('طلبات السيارات (حجز وشراء)') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>{{ __('عملاء حاسبة التمويل') }}</option>
                        <option value="crm_manual" {{ request('source')==='crm_manual'?'selected':'' }}>{{ __('طلبات داخلية (CRM)') }}</option>
                    </select>

                    {{-- الحالة النشطة --}}
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;" onchange="this.form.submit()">
                        <option value="">{{ __('الحالة — جميع الحالات النشطة') }}</option>
                        @foreach($statuses as $key => $s)
                        <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $s['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort','newest')==='newest'?'selected':'' }}>{{ __('الأحدث أولاً') }}</option>
                        <option value="oldest" {{ request('sort','newest')==='oldest'?'selected':'' }}>{{ __('الأقدم أولاً') }}</option>
                    </select>

                    {{-- Search --}}
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('بحث بالاسم أو الهاتف...') }}"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;">{{ __('تصفية') }}</button>
                    <a href="{{ route('crm.bookings.index') }}" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- ====== قسم بانتظار اعتماد المشرف (الأدمن فقط) ====== --}}
    @if($isAdmin && $pendingApprovals->isNotEmpty())
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border: 2px solid #FCA5A5 !important; background: #FFF5F5;">
        <div class="card-header border-0 px-4 py-3 d-flex justify-content-between align-items-center"
             style="background: linear-gradient(135deg, #FEE2E2, #FFF5F5); border-bottom: 1px solid #FCA5A5 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-hourglass-split" style="color: #DC2626; font-size: 18px;"></i>
                <h6 class="fw-bold mb-0" style="color: #DC2626;">{{ __('بانتظار اعتماد المشرف') }}</h6>
                <span class="badge rounded-pill" style="background:#DC2626; color:#fff; font-size:12px;">{{ $pendingApprovals->count() }}</span>
            </div>
            <span style="font-size:12px;color:#7f1d1d;">{{ __('هذه الطلبات تحتاج موافقتك على الإغلاق أو إعادتها للمندوب') }}</span>
        </div>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#FEF2F2;">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;">#</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('العميل') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('النوع / المصدر') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('السيارة') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('المندوب') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('سبب الإغلاق المقترح') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('الإجراء') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $pa)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('crm.bookings.show', $pa) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);">#{{ $pa->id }}</a>
                        </td>
                        <td class="px-3 py-3">
                            <a href="{{ route('crm.bookings.show', $pa) }}" class="fw-bold text-decoration-none text-dark d-block hover-primary" style="font-size:13px;">{{ $pa->client_name }}</a>
                            <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $pa->client_phone }}</div>
                        </td>
                        <td class="px-3 py-3">
                            @if($pa->source === 'calculator' || $pa->calculator_bank_id)
                                <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 11px; padding: 3px 7px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-calculator me-1"></i>{{ __('حاسبة تمويل') }}
                                </span>
                            @else
                                <span class="badge" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11px; padding: 3px 7px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-car-front me-1"></i>{{ __('طلب سيارة') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            {{ $pa->car?->brand?->name }} {{ $pa->car?->name ?? '—' }}
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            {{ $pa->employee?->name ?? '—' }}
                        </td>
                        <td class="px-3 py-3">
                            @if($pa->proposed_status && isset(\App\Models\Booking::STATUSES[$pa->proposed_status]))
                                <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:700;border:1px solid #FCA5A5;">
                                    {{ \App\Models\Booking::STATUSES[$pa->proposed_status]['label'] }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:12px;">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                {{-- عرض تفاصيل الطلب --}}
                                <a href="{{ route('crm.bookings.show', $pa) }}" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;padding:5px 12px;color:var(--crm-text);" title="{{ __('عرض وتعديل تفاصيل الطلب') }}">
                                    <i class="bi bi-eye me-1 text-primary"></i>{{ __('عرض الطلب') }}
                                </a>
                                {{-- موافقة --}}
                                <form action="{{ route('crm.bookings.approve', $pa) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد الموافقة على إغلاق هذا الطلب؟') }}')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success fw-bold rounded-2" style="font-size:12px;padding:5px 12px;">
                                        <i class="bi bi-check-lg me-1"></i>{{ __('موافقة') }}
                                    </button>
                                </form>
                                {{-- رفض وإعادة --}}
                                <button type="button" class="btn btn-sm fw-bold rounded-2"
                                        style="font-size:12px;padding:5px 12px;background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pa->id }}">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>{{ __('إعادة للمندوب') }}
                                </button>
                                {{-- حذف للأدمن فقط --}}
                                @if($isAdmin)
                                <form action="{{ route('crm.bookings.destroy', $pa) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:5px 10px;" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash" style="font-size:13px;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    {{-- Reject Modal --}}
                    <div class="modal fade" id="rejectModal{{ $pa->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="modal-title fw-bold">{{ __('إعادة الطلب #') }}{{ $pa->id }} {{ __('للمندوب') }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('crm.bookings.reject', $pa) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                             <label class="form-label fw-bold" style="font-size:13px;">{{ __('إعادة إلى مرحلة') }}</label>
                                            <select name="status" class="form-select form-select-sm" required>
                                                @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                                    @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval')
                                                        <option value="{{ $key }}">{{ $s['label'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold" style="font-size:13px;">{{ __('سبب الإعادة') }} <span class="text-danger">*</span></label>
                                            <textarea name="note" class="form-control form-control-sm" rows="3"
                                                      placeholder="{{ __('اكتب سبب إعادة الطلب للمندوب...') }}" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('إعادة للمندوب') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards for Pending Approvals --}}
        <div class="d-md-none p-3">
            @foreach($pendingApprovals as $pa)
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:#fff; border-color: #FCA5A5 !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="{{ route('crm.bookings.show', $pa) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#{{ $pa->id }}</a>
                            @if($pa->source === 'calculator' || $pa->calculator_bank_id)
                                <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-calculator me-1"></i>{{ __('حاسبة تمويل') }}
                                </span>
                            @else
                                <span class="badge" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-car-front me-1"></i>{{ __('طلب سيارة') }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('crm.bookings.show', $pa) }}" class="fw-bold text-dark text-decoration-none d-block mt-1" style="font-size:14px;">{{ $pa->client_name }}</a>
                        <div class="small text-muted" dir="ltr">{{ $pa->client_phone }}</div>
                    </div>
                </div>

                <div class="p-2.5 rounded-2 mb-2.5" style="background:#FEF2F2; font-size:12px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ __('السيارة') }}:</span>
                        <span class="fw-semibold">{{ $pa->car?->brand?->name }} {{ $pa->car?->name ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ __('المندوب') }}:</span>
                        <span class="fw-semibold">{{ $pa->employee?->name ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ __('السبب المقترح') }}:</span>
                        <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:700;border:1px solid #FCA5A5;">
                            {{ \App\Models\Booking::STATUSES[$pa->proposed_status]['label'] ?? '—' }}
                        </span>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap align-items-center pt-2 border-top">
                    <a href="{{ route('crm.bookings.show', $pa) }}" class="btn btn-sm btn-light border fw-bold rounded-2 flex-grow-1" style="font-size:12px;padding:6px 12px;">
                        <i class="bi bi-eye me-1 text-primary"></i>{{ __('عرض الطلب') }}
                    </a>
                    <form action="{{ route('crm.bookings.approve', $pa) }}" method="POST" class="m-0"
                          onsubmit="return confirm('{{ __('هل تريد الموافقة على إغلاق هذا الطلب؟') }}')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success fw-bold rounded-2" style="font-size:12px;padding:6px 12px;">
                            <i class="bi bi-check-lg me-1"></i>{{ __('موافقة') }}
                        </button>
                    </form>
                    <button type="button" class="btn btn-sm fw-bold rounded-2"
                            style="font-size:12px;padding:6px 12px;background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;"
                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pa->id }}">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>{{ __('إعادة') }}
                    </button>
                    @if($isAdmin)
                    <form action="{{ route('crm.bookings.destroy', $pa) }}" method="POST" class="m-0"
                          onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:6px 10px;" title="{{ __('حذف') }}">
                            <i class="bi bi-trash" style="font-size:13px;"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Active Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill me-1 text-primary"></i> {{ __('قائمة كافة الطلبات النشطة') }}</h6>
            <span style="font-size:12px;color:var(--crm-text-muted);">{{ __('إجمالي الطلبات') }}: <strong>{{ $bookings->total() }}</strong></span>
        </div>

        {{-- Desktop Table --}}
        <div class="table-responsive d-none d-lg-block">
            <table class="table align-middle mb-0 crm-custom-booking-table">
                <tbody class="border-top-0">
                    @forelse($bookings as $index => $b)
                    @php
                        // Calculate relative update time
                        $updatedDiff = '—';
                        if ($b->updated_at) {
                            if ($b->updated_at->diffInHours(now()) < 24) {
                                $updatedDiff = __('اقل من 24 ساعة');
                            } else {
                                $updatedDiff = $b->updated_at->diffForHumans();
                            }
                        }

                        $createdDiff = $b->created_at ? $b->created_at->diffForHumans() : '—';
                        $employeeName = $b->employee?->name ?? __('لايوجد');
                        $sourceName = $b->source ?: ($b->calculator_bank_id ? __('حاسبة تمويل') : __('لايوجد'));
                        $brandName = $b->car?->brand?->name ?? __('لايوجد');
                        $modelName = $b->car?->carModel?->name ?? ($b->car?->model ?? ($b->car?->name ?? __('لايوجد')));
                        $categoryName = $b->car?->category?->name ?? ($b->car?->type ?? __('لايوجد'));
                        $yearVal = $b->car?->year ?? __('لايوجد');
                        $statusLabel = $statuses[$b->status]['label'] ?? ($b->status_label ?? '—');
                        $mainStatusGroup = match(\App\Models\Booking::STATUSES[$b->status]['group'] ?? 'active') {
                            'active' => __('مفتوح'),
                            'lost' => __('مغلق'),
                            'received' => __('مكتمل'),
                            default => __('مفتوح')
                        };
                        $isPaid = $b->down_payment > 0 || in_array($b->status, ['authorized', 'received']);
                    @endphp
                    <tr class="crm-booking-row">
                        {{-- 1. Index & ID Badge (Far Right) --}}
                        <td class="px-4 py-3" style="width: 140px;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted fw-bold" style="font-size:14px; min-width: 16px;">
                                    {{ $bookings->firstItem() + $index }}
                                </span>
                                <a href="{{ route('crm.bookings.show', $b) }}" class="booking-id-badge" title="{{ __('عرض تفاصيل الطلب') }}">
                                    {{ $b->id }}
                                </a>
                            </div>
                        </td>

                        {{-- 2. Meta Info (انشاء / التعديل / الموظف / المصدر) + Client Contact (Left) --}}
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                {{-- Client Info (Left) --}}
                                <div class="text-start" style="min-width: 130px;">
                                    <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none text-dark d-block hover-primary" style="font-size: 14px;">
                                        {{ $b->client_name }}
                                    </a>
                                    <div class="d-flex align-items-center gap-1.5 mt-1">
                                        <a href="tel:{{ $b->client_phone }}" class="fw-bold text-decoration-none" style="color: #2563EB; font-size: 13px;" dir="ltr">
                                            {{ $b->client_phone }}
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $b->client_phone) }}" target="_blank" class="text-success small" title="{{ __('واتساب') }}">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Meta Key-Values (Right) --}}
                                <div class="booking-meta-box">
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key">{{ __('انشاء :') }}</span>
                                        <span class="booking-meta-val" title="{{ $b->created_at?->format('Y-m-d H:i') }}">{{ $createdDiff }}</span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key">{{ __('التعديل :') }}</span>
                                        <span class="booking-meta-val fw-bold text-dark" title="{{ $b->updated_at?->format('Y-m-d H:i') }}">{{ $updatedDiff }}</span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key">{{ __('الموظف :') }}</span>
                                        <span class="booking-meta-val">{{ $employeeName }}</span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key">{{ __('المصدر :') }}</span>
                                        <span class="booking-meta-val">{{ $sourceName }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 3. Car Specifications --}}
                        <td class="px-3 py-3" style="min-width: 200px;">
                            <div class="booking-meta-box">
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key">{{ __('الماركة :') }}</span>
                                    <span class="booking-meta-val">{{ $brandName }}</span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key">{{ __('موديل :') }}</span>
                                    <span class="booking-meta-val">{{ $modelName }}</span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key">{{ __('الفئة :') }}</span>
                                    <span class="booking-meta-val">{{ $categoryName }}</span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key">{{ __('سنة الصنع :') }}</span>
                                    <span class="booking-meta-val">{{ $yearVal }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- 4. Status & Payment --}}
                        <td class="px-3 py-3" style="min-width: 250px;">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                {{-- Payment Pill --}}
                                <div>
                                    @if($isPaid)
                                        <span class="badge-payment badge-paid">
                                            <span class="dot">●</span> {{ __('مدفوع') }}
                                        </span>
                                    @else
                                        <span class="badge-payment badge-unpaid">
                                            <span class="dot">●</span> {{ __('غير مدفوع') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Status Info --}}
                                <div class="booking-meta-box text-end">
                                    <div class="booking-meta-item justify-content-end">
                                        <span class="booking-meta-key">{{ __('حالة الطلب :') }}</span>
                                        <span class="booking-meta-val fw-semibold text-dark">{{ $statusLabel }}</span>
                                    </div>
                                    <div class="booking-meta-item justify-content-end">
                                        <span class="booking-meta-key">{{ __('الحالة الرئيسية :') }}</span>
                                        <span class="booking-meta-val text-muted">{{ $mainStatusGroup }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 5. Actions (Far Left) --}}
                        <td class="px-4 py-3 text-start" style="width: 120px;">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn-action-square" title="{{ __('عرض تفاصيل الطلب') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn-action-square" title="{{ __('تعديل الطلب') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if($isAdmin)
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0 d-inline"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-square text-danger" title="{{ __('حذف الطلب') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold">{{ __('لا توجد طلبات نشطة حالياً مطابقة للشروط') }}</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile / Tablet Cards --}}
        <div class="d-lg-none p-3">
            @forelse($bookings as $index => $b)
            @php
                $updatedDiff = '—';
                if ($b->updated_at) {
                    if ($b->updated_at->diffInHours(now()) < 24) {
                        $updatedDiff = __('اقل من 24 ساعة');
                    } else {
                        $updatedDiff = $b->updated_at->diffForHumans();
                    }
                }
                $createdDiff = $b->created_at ? $b->created_at->diffForHumans() : '—';
                $employeeName = $b->employee?->name ?? __('لايوجد');
                $sourceName = $b->source ?: ($b->calculator_bank_id ? __('حاسبة تمويل') : __('لايوجد'));
                $brandName = $b->car?->brand?->name ?? __('لايوجد');
                $modelName = $b->car?->carModel?->name ?? ($b->car?->model ?? ($b->car?->name ?? __('لايوجد')));
                $categoryName = $b->car?->category?->name ?? ($b->car?->type ?? __('لايوجد'));
                $yearVal = $b->car?->year ?? __('لايوجد');
                $statusLabel = $statuses[$b->status]['label'] ?? ($b->status_label ?? '—');
                $mainStatusGroup = match(\App\Models\Booking::STATUSES[$b->status]['group'] ?? 'active') {
                    'active' => __('مفتوح'),
                    'lost' => __('مغلق'),
                    'received' => __('مكتمل'),
                    default => __('مفتوح')
                };
                $isPaid = $b->down_payment > 0 || in_array($b->status, ['authorized', 'received']);
            @endphp
            <div class="mb-3 p-3 rounded-4 shadow-sm border bg-white" style="border: 1px solid #ECEEF2 !important;">
                {{-- Header: ID + Payment + Index --}}
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-bold">#{{ $bookings->firstItem() + $index }}</span>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="booking-id-badge">
                            {{ $b->id }}
                        </a>
                    </div>
                    <div>
                        @if($isPaid)
                            <span class="badge-payment badge-paid">
                                <span class="dot">●</span> {{ __('مدفوع') }}
                            </span>
                        @else
                            <span class="badge-payment badge-unpaid">
                                <span class="dot">●</span> {{ __('غير مدفوع') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Client Info --}}
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-dark text-decoration-none" style="font-size: 14px;">
                            {{ $b->client_name }}
                        </a>
                        <div class="d-flex align-items-center gap-1.5 mt-0.5">
                            <a href="tel:{{ $b->client_phone }}" class="fw-bold text-decoration-none" style="color: #2563EB; font-size: 13px;" dir="ltr">
                                {{ $b->client_phone }}
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $b->client_phone) }}" target="_blank" class="text-success small" title="{{ __('واتساب') }}">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Metadata Grid --}}
                <div class="p-2.5 rounded-3 mb-2" style="background:#F8FAFC; font-size:12px;">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted">{{ __('انشاء:') }}</span>
                            <span class="fw-semibold">{{ $createdDiff }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('التعديل:') }}</span>
                            <span class="fw-bold text-dark">{{ $updatedDiff }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('الموظف:') }}</span>
                            <span class="fw-semibold">{{ $employeeName }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('المصدر:') }}</span>
                            <span class="fw-semibold">{{ $sourceName }}</span>
                        </div>
                    </div>
                </div>

                {{-- Car Specs --}}
                <div class="p-2.5 rounded-3 mb-2" style="background:#FFF9F5; font-size:12px; border:1px solid #FFEDD5;">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted">{{ __('الماركة:') }}</span>
                            <span class="fw-bold text-dark">{{ $brandName }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('موديل:') }}</span>
                            <span class="fw-bold text-dark">{{ $modelName }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('الفئة:') }}</span>
                            <span class="fw-semibold">{{ $categoryName }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ __('سنة الصنع:') }}</span>
                            <span class="fw-semibold">{{ $yearVal }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status & Actions --}}
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div style="font-size:12px;">
                        <span class="text-muted">{{ __('حالة الطلب:') }}</span>
                        <span class="fw-bold text-dark">{{ $statusLabel }}</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('crm.bookings.show', $b) }}" class="btn-action-square" title="{{ __('عرض') }}">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="btn-action-square" title="{{ __('تعديل') }}">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        @if($isAdmin)
                        <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                              onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب؟') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action-square text-danger" title="{{ __('حذف') }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                <div>{{ __('لا توجد طلبات نشطة حالياً') }}</div>
            </div>
            @endforelse
        </div>

        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center" style="border-top:1px solid var(--crm-border)!important;">
            <div class="crm-pagination-wrapper">
                {{ $bookings->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Create Booking Modal --}}
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FAF9F6;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" style="color: var(--crm-text);">{{ __('إضافة عميل / طلب جديد') }}</h5>
                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('crm.bookings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('اسم العميل') }} <span class="text-danger">*</span></label>
                                <input type="text" name="client_name" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" required placeholder="{{ __('اسم العميل') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('رقم الهاتف') }} <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-0 text-muted" dir="ltr" style="font-size: 14px;">+966 🇸🇦</span>
                                    <input type="text" name="client_phone" class="form-control border-0" style="font-size: 14px;" required placeholder="5X XXX XXXX" dir="ltr">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('البريد الإلكتروني') }}</label>
                                <input type="email" name="client_email" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" placeholder="{{ __('البريد الإلكتروني (اختياري)') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">{{ __('نوع الطلب') }}</label>
                                <select name="type" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;">
                                    <option value="booking">{{ __('حجز سيارة') }}</option>
                                    <option value="loan">{{ __('تمويل') }}</option>
                                    <option value="test">{{ __('تجربة قيادة') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4 mb-4" style="background: #F4EFF0; border-radius: 16px;">
                            <h6 class="fw-bold mb-3" style="color: var(--crm-text);">{{ __('تفاصيل السيارة') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small">{{ __('السيارة المطلوبة') }}</label>
                                    <select name="car_id" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" id="bookingCarSelect">
                                        <option value="">{{ __('اختر سيارة (أو اتركها فارغة)') }}</option>
                                        @foreach($cars as $car)
                                            <option value="{{ $car->id }}" data-price="{{ $car->cash_price }}" data-installment="{{ $car->min_installment }}">{{ $car->brand->name ?? '' }} {{ $car->name }} ({{ $car->year }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('سعر السيارة الإجمالي') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="total_price" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('الدفعة الأولى (إن وجدت)') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="down_payment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('القسط الشهري المتوقع') }}</label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="monthly_installment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">{{ __('مدة التمويل (سنوات)') }}</label>
                                    <input type="number" name="duration_years" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" value="5">
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted small">{{ __('ملاحظات إضافية') }}</label>
                            <textarea name="notes" class="form-control bg-white border-0 shadow-sm" rows="2" style="border-radius: 12px; font-size: 14px;" placeholder="{{ __('إضافة ملاحظة...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 flex-nowrap">
                        @can('manage-bookings')
                        <button type="submit" class="btn flex-fill fw-bold py-3 text-white" style="background: #16254F; border-radius: 12px;">{{ __('حفظ بيانات العميل') }}</button>
                        @endcan
                        <button type="button" class="btn btn-outline-secondary flex-fill fw-bold py-3" data-bs-dismiss="modal" style="border-radius: 12px; background: white;">{{ __('إلغاء') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@include('crm.bookings.partials.status-modals')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carSelect = document.getElementById('bookingCarSelect');
        const priceInput = document.querySelector('input[name="total_price"]');
        const installmentInput = document.querySelector('input[name="monthly_installment"]');

        if(carSelect) {
            carSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if(selectedOption.value) {
                    const price = selectedOption.getAttribute('data-price');
                    const installment = selectedOption.getAttribute('data-installment');

                    if(priceInput) priceInput.value = price || '';
                    if(installmentInput) installmentInput.value = installment || '';
                } else {
                    if(priceInput) priceInput.value = '';
                    if(installmentInput) installmentInput.value = '';
                }
            });
        }
    });
</script>
@endsection
