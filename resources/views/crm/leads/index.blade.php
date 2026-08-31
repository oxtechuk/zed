@extends('partials.Layouts.crm-master')
@section('title', __('العملاء') . ' | ' . (\App\Models\Setting::where('key', 'site_name')->first()?->value['ar'] ?? 'زد كابيتال'))

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <span>{{ __('إدارة العملاء') }}</span>
        <span class="sep">›</span>
        <span class="current">{{ __('العملاء') }}</span>
    </nav>

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">{{ __('قائمة العملاء') }}</h5>
            <p class="text-muted small mb-0">{{ __('استهداف ومتابعة العملاء وإدارة حملات رسائل الواتساب بناءً على مسار الطلبات') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1.5 fw-bold rounded-3 shadow-xs" id="btnOpenCampaignFiltered" style="font-size:13px;padding:8px 16px;">
                <i class="bi bi-whatsapp"></i>
                <span>{{ __('إرسال حملة واتساب للفلتر') }} ({{ $leads->total() }})</span>
            </button>
            @can('manage-leads')
            <a href="{{ route('crm.leads.create') }}" class="btn-crm-primary">
                <i class="bi bi-person-plus"></i> {{ __('إضافة عميل') }}
            </a>
            @endcan
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        {{-- 1. إجمالي العملاء --}}
        <div class="col-6 col-lg-3">
            <a href="{{ route('crm.leads.index') }}" class="text-decoration-none">
                <div class="crm-stat-new {{ !request('booking_status_group') ? 'border-primary' : '' }}">
                    <span class="stat-badge purple">100%</span>
                    <div class="stat-icon purple"><i class="bi bi-people"></i></div>
                    <div class="stat-lbl">{{ __('إجمالي العملاء') }}</div>
                    <div class="stat-val">{{ number_format($totalLeadsAllCount) }}</div>
                </div>
            </a>
        </div>

        {{-- 2. طلبات نشطة --}}
        <div class="col-6 col-lg-3">
            <a href="{{ route('crm.leads.index', ['booking_status_group' => 'active']) }}" class="text-decoration-none">
                <div class="crm-stat-new {{ request('booking_status_group') === 'active' ? 'border-primary' : '' }}">
                    <span class="stat-badge blue">{{ $totalLeadsAllCount > 0 ? round(($activeOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0 }}%</span>
                    <div class="stat-icon blue"><i class="bi bi-lightning-charge"></i></div>
                    <div class="stat-lbl">{{ __('عملاء بطلبات نشطة') }}</div>
                    <div class="stat-val text-primary">{{ number_format($activeOrdersLeadsCount) }}</div>
                </div>
            </a>
        </div>

        {{-- 3. طلبات مستلمة --}}
        <div class="col-6 col-lg-3">
            <a href="{{ route('crm.leads.index', ['booking_status_group' => 'received']) }}" class="text-decoration-none">
                <div class="crm-stat-new {{ request('booking_status_group') === 'received' ? 'border-success' : '' }}">
                    <span class="stat-badge green">{{ $totalLeadsAllCount > 0 ? round(($receivedOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0 }}%</span>
                    <div class="stat-icon green"><i class="bi bi-patch-check"></i></div>
                    <div class="stat-lbl">{{ __('عملاء بطلبات مستلمة') }}</div>
                    <div class="stat-val text-success">{{ number_format($receivedOrdersLeadsCount) }}</div>
                </div>
            </a>
        </div>

        {{-- 4. طلبات مغلقة --}}
        <div class="col-6 col-lg-3">
            <a href="{{ route('crm.leads.index', ['booking_status_group' => 'closed']) }}" class="text-decoration-none">
                <div class="crm-stat-new {{ request('booking_status_group') === 'closed' ? 'border-danger' : '' }}">
                    <span class="stat-badge red">{{ $totalLeadsAllCount > 0 ? round(($closedOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0 }}%</span>
                    <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                    <div class="stat-lbl">{{ __('عملاء بطلبات مغلقة') }}</div>
                    <div class="stat-val text-danger">{{ number_format($closedOrdersLeadsCount) }}</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Main Filter Tabs (حسب مسار الطلبات) --}}
    @php
        $currentGroup = request('booking_status_group');
    @endphp
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="crm-filter-tabs mb-0">
            <a href="{{ route('crm.leads.index', request()->except('booking_status_group', 'page')) }}"
               class="crm-filter-tab {{ empty($currentGroup) ? 'active' : '' }}">
                <i class="bi bi-grid me-1"></i> {{ __('جميع العملاء') }}
                <span class="badge rounded-pill bg-light text-dark ms-1">{{ number_format($totalLeadsAllCount) }}</span>
            </a>
            <a href="{{ route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'active'])) }}"
               class="crm-filter-tab {{ $currentGroup === 'active' ? 'active' : '' }}">
                <i class="bi bi-lightning-charge me-1 text-primary"></i> {{ __('طلبات نشطة') }}
                <span class="badge rounded-pill {{ $currentGroup === 'active' ? 'bg-white text-primary' : 'bg-primary-subtle text-primary' }} ms-1">{{ number_format($activeOrdersLeadsCount) }}</span>
            </a>
            <a href="{{ route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'received'])) }}"
               class="crm-filter-tab {{ $currentGroup === 'received' ? 'active' : '' }}">
                <i class="bi bi-patch-check me-1 text-success"></i> {{ __('طلبات مستلمة') }}
                <span class="badge rounded-pill {{ $currentGroup === 'received' ? 'bg-white text-success' : 'bg-success-subtle text-success' }} ms-1">{{ number_format($receivedOrdersLeadsCount) }}</span>
            </a>
            <a href="{{ route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'closed'])) }}"
               class="crm-filter-tab {{ $currentGroup === 'closed' ? 'active' : '' }}">
                <i class="bi bi-x-circle me-1 text-danger"></i> {{ __('طلبات مغلقة') }}
                <span class="badge rounded-pill {{ $currentGroup === 'closed' ? 'bg-white text-danger' : 'bg-danger-subtle text-danger' }} ms-1">{{ number_format($closedOrdersLeadsCount) }}</span>
            </a>
            <a href="{{ route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'no_orders'])) }}"
               class="crm-filter-tab {{ $currentGroup === 'no_orders' ? 'active' : '' }}">
                <i class="bi bi-dash-circle me-1 text-muted"></i> {{ __('بدون طلبات') }}
                <span class="badge rounded-pill bg-light text-muted ms-1">{{ number_format($noOrdersLeadsCount) }}</span>
            </a>
        </div>
    </div>

    {{-- Filter Bar (مطابق تماماً لفلتر الطلبات) --}}
    <form method="GET" action="{{ route('crm.leads.index') }}" id="leadFilterForm">
        @if(request('booking_status_group'))
            <input type="hidden" name="booking_status_group" value="{{ request('booking_status_group') }}">
        @endif
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
                        <option value="cars" {{ request('source')==='cars'?'selected':'' }}>🚗 {{ __('طلبات السيارات (حجز وشراء)') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>🧮 {{ __('عملاء حاسبة التمويل') }}</option>
                        <option value="crm_manual" {{ request('source')==='crm_manual'?'selected':'' }}>📋 {{ __('طلبات داخلية (CRM)') }}</option>
                    </select>

                    {{-- فلتر الحالة (يتكيف تلقائياً مع التبويب المختار: مغلقة / نشطة / مستلمة / الكل) --}}
                    @php
                        $group = request('booking_status_group');
                    @endphp
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        @if($group === 'closed')
                            <option value="">{{ __('الحالة — جميع الحالات المغلقة') }}</option>
                            @foreach($closedBookingStatuses as $key => $s)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                            @endforeach
                        @elseif($group === 'received')
                            <option value="">{{ __('الحالة — جميع الحالات المستلمة') }}</option>
                            @foreach($receivedBookingStatuses as $key => $s)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                            @endforeach
                        @elseif($group === 'active')
                            <option value="">{{ __('الحالة — جميع الحالات النشطة') }}</option>
                            @foreach($activeBookingStatuses as $key => $s)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                            @endforeach
                        @else
                            <option value="">{{ __('الحالة — جميع الحالات') }}</option>
                            <optgroup label="{{ __('الحالات الأساسية والنشطة') }}">
                                @foreach($activeBookingStatuses as $key => $s)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('الحالات المستلمة والناجحة') }}">
                                @foreach($receivedBookingStatuses as $key => $s)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('الحالات المغلقة والخاسرة') }}">
                                @foreach($closedBookingStatuses as $key => $s)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                @endforeach
                            </optgroup>
                        @endif
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
                    <a href="{{ route('crm.leads.index') }}" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Results Counter & Selection Helper Banner --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 px-1">
        <div style="font-size:12.5px;color:var(--crm-text-muted);">
            {{ __('نتائج البحث:') }} <strong>{{ number_format($leads->total()) }}</strong> {{ __('عميل') }}
            @if(request('booking_status_group'))
                <span class="badge bg-light text-dark border ms-1">
                    {{ match(request('booking_status_group')) {
                        'active' => __('فلتر: طلبات نشطة'),
                        'received' => __('فلتر: طلبات مستلمة'),
                        'closed' => __('فلتر: طلبات مغلقة'),
                        'no_orders' => __('فلتر: بدون طلبات'),
                        default => ''
                    } }}
                </span>
            @endif
        </div>
        <div id="selectionStatusText" class="text-success fw-bold d-none" style="font-size:12.5px;">
            <i class="bi bi-check-all me-1"></i> <span id="selectedCountText">0</span> {{ __('عميل محدد') }}
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark">{{ __('جدول العملاء') }}</h6>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border rounded-2 d-none" id="btnSelectAllOnPage">
                    {{ __('تحديد كل الصفحة') }}
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;width:40px;">
                            <input type="checkbox" id="selectAllLeads" class="form-check-input" title="{{ __('تحديد الكل') }}">
                        </th>
                        <th class="px-3 py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('اسم العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الهاتف') }}</th>
                        <th class="py-3 text-muted fw-bold text-center" style="font-size:12px;">{{ __('الطلبات ومسارها') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('السيارة المطلوبة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('مصدر العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('تاريخ الإضافة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('حالة العميل') }}</th>
                        <th class="py-3 text-muted fw-bold text-end px-4" style="font-size:12px;">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($leads as $lead)
                    <tr>
                        <td class="px-4">
                            <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"
                                   class="form-check-input lead-checkbox"
                                   data-name="{{ $lead->client_name }}"
                                   data-phone="{{ $lead->client_phone }}"
                                   {{ $lead->client_phone ? '' : 'disabled' }}>
                        </td>
                        <td class="px-3 fw-bold" style="font-size:13px;">
                            <a href="{{ route('crm.leads.show', $lead) }}" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#{{ $lead->id }}</a>
                        </td>
                        <td>
                            <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $lead->client_name }}</div>
                            @if($lead->client_email)
                                <div class="text-muted small" style="font-size:11px;">{{ $lead->client_email }}</div>
                            @endif
                        </td>
                        <td style="font-size:13px;">
                            @if($lead->client_phone)
                                <div class="d-flex align-items-center gap-1">
                                    <span dir="ltr" class="fw-bold">{{ $lead->client_phone }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->client_phone) }}" target="_blank" class="badge text-white text-decoration-none" style="background:#25D366;font-size:10px;padding:3px 6px;" title="{{ __('مراسلة واتساب') }}">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $ordersCount = $lead->orders_count ?? $lead->orders->count();
                                $hasActiveOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::ACTIVE_BOOKING_STATUSES)->isNotEmpty();
                                $hasReceivedOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::RECEIVED_BOOKING_STATUSES)->isNotEmpty();
                                $hasClosedOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::CLOSED_BOOKING_STATUSES)->isNotEmpty();
                            @endphp
                            @if($ordersCount > 0)
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-bold" style="font-size:12px;">
                                        {{ $ordersCount }} {{ __('طلب') }}
                                    </span>
                                    <div class="d-flex gap-1">
                                        @if($hasReceivedOrder)
                                            <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:10px;" title="{{ __('يوجد طلب مستلم') }}">
                                                <i class="bi bi-patch-check-fill"></i> {{ __('مستلم') }}
                                            </span>
                                        @endif
                                        @if($hasActiveOrder)
                                            <span class="badge" style="background:#EFF6FF;color:#2563EB;font-size:10px;" title="{{ __('يوجد طلب نشط') }}">
                                                <i class="bi bi-lightning-charge-fill"></i> {{ __('نشط') }}
                                            </span>
                                        @endif
                                        @if($hasClosedOrder && !$hasReceivedOrder && !$hasActiveOrder)
                                            <span class="badge" style="background:#FEF2F2;color:#DC2626;font-size:10px;" title="{{ __('طلب مغلق') }}">
                                                <i class="bi bi-x-circle-fill"></i> {{ __('مغلق') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:11px;">{{ __('بدون طلبات') }}</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">
                            @if($lead->car)
                                <div class="fw-bold text-dark">{{ $lead->car->brand?->name }} {{ $lead->car->name }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $lead->contactSource?->name ?? __('مباشر') }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">
                            <div>{{ $lead->created_at?->format('d/m/Y') ?? ($lead->started_at?->format('d/m/Y') ?? '—') }}</div>
                            <div style="font-size:11px;">{{ $lead->created_at?->diffForHumans() }}</div>
                        </td>
                        <td>
                            @php
                                $dotClass = match($lead->status) {
                                    'new'         => 'confirmed',
                                    'contacted'   => 'waiting',
                                    'interested'  => 'planned',
                                    'negotiation' => 'waiting',
                                    'converted'   => 'done',
                                    'lost'        => 'late',
                                    default       => 'cancelled',
                                };
                            @endphp
                            <span class="status-dot {{ $dotClass }}">{{ $lead->status_label }}</span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-1 justify-content-end align-items-center">
                                <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض التفاصيل') }}">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                @can('manage-leads')
                                <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('تعديل') }}">
                                    <i class="bi bi-pencil" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <form action="{{ route('crm.leads.destroy', $lead) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا العميل؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-2 border" title="{{ __('حذف') }}"
                                            style="color:var(--crm-red);">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-person-x fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold">{{ __('لا يوجد عملاء يطابقون خيارات الفلتر الحالية') }}</div>
                            <small class="text-muted">{{ __('جرب تغيير خيارات البحث أو الفلاتر بالأعلى') }}</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--crm-border)!important;">
            <div class="small text-muted">
                {{ __('عرض') }} <strong>{{ $leads->firstItem() ?? 0 }}</strong> - <strong>{{ $leads->lastItem() ?? 0 }}</strong> {{ __('من أصل') }} <strong>{{ $leads->total() }}</strong>
            </div>
            <div>
                {{ $leads->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- WhatsApp Campaign Floating Button --}}
    <button type="button" id="btnWhatsappCampaign"
            class="btn btn-success rounded-pill shadow-lg d-none align-items-center gap-2"
            style="position:fixed;bottom:30px;{{ app()->getLocale()=='ar'?'left':'right' }}:30px;z-index:1050;padding:12px 24px;font-size:14px;font-weight:700;box-shadow:0 8px 24px rgba(22,163,74,0.35)!important;">
        <i class="bi bi-whatsapp" style="font-size:20px;"></i>
        <span>{{ __('إرسال واتساب للمحددين') }} (<span id="selectedCount">0</span>)</span>
    </button>

    {{-- WhatsApp Campaign Modal --}}
    <div class="modal fade" id="whatsappCampaignModal" tabindex="-1" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#F0FDF4;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#DCFCE7;color:#16A34A;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold mb-0 text-success">{{ __('حملة رسائل واتساب الجماعية') }}</h6>
                            <span class="text-muted" style="font-size:12px;">{{ __('استهداف العملاء برسائل مخصصة وتلقائية') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    {{-- Target Mode Selection --}}
                    <div class="mb-3 p-3 rounded-3" style="background:#F8FAFC;border:1px solid var(--crm-border);">
                        <label class="form-label fw-bold small text-dark mb-2">{{ __('تحديد الشريحة المستهدفة للإرسال:') }}</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="targetMode" id="targetModeSelected" value="selected" checked>
                                <label class="form-check-label" for="targetModeSelected">
                                    <strong>{{ __('العملاء المحددين فقط في الصفحة') }}</strong>
                                    (<span id="modalSelectedCount">0</span> {{ __('عميل') }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="targetMode" id="targetModeFiltered" value="filtered">
                                <label class="form-check-label" for="targetModeFiltered">
                                    <strong>{{ __('كافة العملاء المطابقين للفلتر الحالي') }}</strong>
                                    ({{ $leads->total() }} {{ __('عميل عبر كافة الصفحات') }})
                                    @if(request('booking_status_group'))
                                        <span class="badge bg-success-subtle text-success ms-1">
                                            {{ match(request('booking_status_group')) {
                                                'active' => __('طلبات نشطة'),
                                                'received' => __('طلبات مستلمة'),
                                                'closed' => __('طلبات مغلقة'),
                                                'no_orders' => __('بدون طلبات'),
                                                default => ''
                                            } }}
                                        </span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">{{ __('نص الرسالة') }} <span class="text-danger">*</span></label>
                        <textarea id="campaignMessage" class="form-control bg-light border-0 shadow-none" rows="5" style="border-radius:10px;font-size:13.5px;line-height:1.6;"
                                  placeholder="{{ __('اكتب نص الرسالة هنا... مثال: مرحباً أستاذ {name}، يسعدنا في زاد كابيتال التواصل معك...') }}"></textarea>
                    </div>

                    <div class="p-3 rounded-3 mb-2" style="background:#FFFBEB;border:1px solid #FEF3C7;font-size:12px;color:#92400E;">
                        <div class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> {{ __('المتغيرات المتاحة للدمج التلقائي في الرسالة:') }}</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-white text-dark border pointer" onclick="insertPlaceholder('{name}')" style="cursor:pointer;"><code>{name}</code> ← {{ __('اسم العميل') }}</span>
                            <span class="badge bg-white text-dark border pointer" onclick="insertPlaceholder('{phone}')" style="cursor:pointer;"><code>{phone}</code> ← {{ __('رقم هاتف العميل') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3 flex-fill" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="button" id="btnSendCampaign" class="btn btn-success py-2 px-4 fw-bold rounded-3 flex-fill text-white">
                        <i class="bi bi-send-fill me-1"></i> {{ __('بدء إرسال الحملة') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function insertPlaceholder(tag) {
    const textarea = document.getElementById('campaignMessage');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + tag + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
}

document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllLeads');
    const checkboxes = document.querySelectorAll('.lead-checkbox');
    const btnCampaign = document.getElementById('btnWhatsappCampaign');
    const btnOpenFiltered = document.getElementById('btnOpenCampaignFiltered');
    const countEl = document.getElementById('selectedCount');
    const modalCountEl = document.getElementById('modalSelectedCount');
    const selectionStatusText = document.getElementById('selectionStatusText');
    const selectedCountText = document.getElementById('selectedCountText');
    const modalEl = document.getElementById('whatsappCampaignModal');
    const modal = new bootstrap.Modal(modalEl);
    const btnSend = document.getElementById('btnSendCampaign');
    const messageInput = document.getElementById('campaignMessage');
    const targetModeSelected = document.getElementById('targetModeSelected');
    const targetModeFiltered = document.getElementById('targetModeFiltered');

    function updateCount() {
        const checked = document.querySelectorAll('.lead-checkbox:checked').length;
        countEl.textContent = checked;
        modalCountEl.textContent = checked;
        if (selectedCountText) selectedCountText.textContent = checked;

        if (checked > 0) {
            btnCampaign.classList.remove('d-none');
            btnCampaign.classList.add('d-flex');
            if (selectionStatusText) selectionStatusText.classList.remove('d-none');
        } else {
            btnCampaign.classList.add('d-none');
            btnCampaign.classList.remove('d-flex');
            if (selectionStatusText) selectionStatusText.classList.add('d-none');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                if (!cb.disabled) {
                    cb.checked = selectAll.checked;
                }
            });
            updateCount();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    if (btnCampaign) {
        btnCampaign.addEventListener('click', function () {
            if (targetModeSelected) targetModeSelected.checked = true;
            modal.show();
        });
    }

    if (btnOpenFiltered) {
        btnOpenFiltered.addEventListener('click', function () {
            if (targetModeFiltered) targetModeFiltered.checked = true;
            modal.show();
        });
    }

    if (btnSend) {
        btnSend.addEventListener('click', function () {
            const message = messageInput.value.trim();
            if (!message) {
                messageInput.classList.add('is-invalid');
                return;
            }
            messageInput.classList.remove('is-invalid');

            const isTargetAllFiltered = targetModeFiltered && targetModeFiltered.checked;
            const leadIds = Array.from(document.querySelectorAll('.lead-checkbox:checked'))
                .map(cb => parseInt(cb.value));

            if (!isTargetAllFiltered && leadIds.length === 0) {
                alert('{{ __("يرجى تحديد عميل واحد على الأقل أو اختيار إرسال للفلتر الحالي.") }}');
                return;
            }

            btnSend.disabled = true;
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري جدولة الإرسال...") }}';

            const payload = {
                message: message,
                target_all_filtered: isTargetAllFiltered ? 1 : 0,
                lead_ids: leadIds,
                search: '{{ request("search") }}',
                status: '{{ request("status") }}',
                contact_source_id: '{{ request("contact_source_id") }}',
                employee_id: '{{ request("employee_id") }}',
                booking_status_group: '{{ request("booking_status_group") }}',
                booking_status: '{{ request("booking_status") }}'
            };

            fetch('{{ route("crm.leads.whatsapp-campaign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(res => res.json().then(data => ({ status: res.status, data })))
            .then(({ status, data }) => {
                modal.hide();
                messageInput.value = '';
                if (selectAll) selectAll.checked = false;
                checkboxes.forEach(cb => cb.checked = false);
                updateCount();

                if (status >= 200 && status < 300 && data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || '{{ __("حدث خطأ أثناء الإرسال") }}', 'danger');
                }
            })
            .catch(() => {
                showToast('{{ __("حدث خطأ في الاتصال بالخادم") }}', 'danger');
            })
            .finally(() => {
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bi bi-send-fill me-1"></i> {{ __("بدء إرسال الحملة") }}';
            });
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed shadow-lg rounded-3`;
        toast.style.cssText = 'top:80px;right:20px;z-index:9999;min-width:320px;font-weight:bold;';
        toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>${message}<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 6000);
    }
});
</script>
@endsection
