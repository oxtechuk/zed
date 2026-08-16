@extends('partials.Layouts.crm-master')
@section('title', __('الطلبات النشطة') . ' | Zad Capital')

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
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <div class="stat-icon red"><i class="bi bi-clock"></i></div>
                <div class="stat-lbl">{{ __('بانتظار التواصل والمراجعة') }}</div>
                <div class="stat-val">{{ number_format($stats['pending_review']) }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <div class="stat-icon blue"><i class="bi bi-bank"></i></div>
                <div class="stat-lbl">{{ __('تحت الدراسة والتعميد') }}</div>
                <div class="stat-val">{{ number_format($stats['under_bank']) }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <div class="stat-icon purple"><i class="bi bi-person-lines-fill"></i></div>
                <div class="stat-lbl">{{ __('إجمالي الطلبات النشطة') }}</div>
                <div class="stat-val">{{ number_format($stats['total']) }}</div>
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

                    {{-- مصدر الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value="">{{ __('المصدر — الكل') }}</option>
                        <option value="booking" {{ request('source')==='booking'?'selected':'' }}>{{ __('طلبات عادية') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>{{ __('عملاء حاسبة فقط') }}</option>
                    </select>

                    {{-- الحالة النشطة --}}
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="">{{ __('الحالة — جميع الحالات النشطة') }}</option>
                        @foreach($statuses as $key => $s)
                        <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $s['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
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
                            <div class="fw-bold" style="font-size:13px;">{{ $pa->client_name }}</div>
                            <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $pa->client_phone }}</div>
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
                            <div class="d-flex gap-2 flex-wrap">
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
                                {{-- حذف --}}
                                <form action="{{ route('crm.bookings.destroy', $pa) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:5px 10px;">
                                        <i class="bi bi-trash" style="font-size:13px;"></i>
                                    </button>
                                </form>
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
    </div>
    @endif

    {{-- Active Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill me-1 text-primary"></i> {{ __('قائمة الطلبات النشطة') }}</h6>
            <span style="font-size:12px;color:var(--crm-text-muted);">{{ __('إجمالي الطلبات') }}: <strong>{{ $bookings->total() }}</strong></span>
        </div>

        {{-- Desktop Table --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('البيانات / التحديث') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('السيارة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('حالة الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold px-4" style="font-size:12px;">{{ __('تحكم') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($bookings as $index => $b)
                    <tr>
                        <td class="px-4 text-muted small" style="font-size:13px;">
                            {{ $bookings->firstItem() + $index }}
                        </td>
                        <td class="fw-bold" style="font-size:13px;">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#{{ $b->id }}</a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><span class="text-muted">{{ __('إنشاء :') }}</span> <strong class="text-dark">{{ $b->created_at->diffForHumans() }}</strong></div>
                                <div><span class="text-muted">{{ __('الموظف :') }}</span> <strong class="text-dark">{{ $b->employee?->name ?: __('غير معين') }}</strong></div>
                                <div class="mt-1">
                                    <span class="text-muted">{{ __('المصدر :') }}</span>
                                    @if($b->source === 'calculator')
                                        <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                            <i class="bi bi-calculator me-1"></i>{{ __('عملاء حاسبة') }}
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #EBF5FF; color: #1E40AF; border: 1px solid #BFDBFE; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                            <i class="bi bi-file-earmark-text me-1"></i>{{ __('طلب عادي') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;">{{ $b->client_name }}</div>
                            <a href="tel:{{ $b->client_phone }}" class="text-decoration-none small text-muted" dir="ltr">{{ $b->client_phone }}</a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><span class="text-muted">{{ __('الماركة :') }}</span> <strong class="text-dark">{{ $b->car?->brand?->name ?? '—' }}</strong></div>
                                <div><span class="text-muted">{{ __('الموديل :') }}</span> <strong class="text-dark">{{ $b->car?->name ?? '—' }}</strong></div>
                                @if($b->car?->year)
                                <div><span class="text-muted">{{ __('سنة الصنع :') }}</span> <strong class="text-dark">{{ $b->car->year }}</strong></div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('crm.bookings.status', $b) }}" method="POST" class="m-0 d-inline-block">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm border shadow-none fw-bold"
                                        style="font-size:12px;border-radius:8px;background:#F8FAFC;min-width:160px;cursor:pointer;"
                                        data-current-status="{{ $b->status }}"
                                        onchange="handleBookingStatusSelectChange(this, {{ $b->id }}, '{{ route('crm.bookings.status', $b) }}', {{ $isAdmin ? 'true' : 'false' }})">
                                    <optgroup label="{{ __('المراحل النشطة (Active)') }}">
                                        @foreach($statuses as $key => $s)
                                            <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>⚡ {{ $s['label'] }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{ __('حالات خاصة / معلقة') }}">
                                        <option value="pending">⏳ {{ __('قيد الانتظار (مع موعد متابعة)') }}</option>
                                    </optgroup>
                                    <optgroup label="{{ __('تسليم الطلب (ناجح)') }}">
                                        <option value="received" data-close="1">✅ {{ __('تم التسليم (المستلمة)') }}</option>
                                    </optgroup>
                                    <optgroup label="{{ __('إغلاق الحجز (خاسر)') }}">
                                        @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                            @if(($s['group'] ?? '') === 'lost')
                                                <option value="{{ $key }}" data-close="1">❌ {{ $s['label'] }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-4">
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض وتعديل') }}">
                                    <i class="bi bi-pencil" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض التفاصيل') }}">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="{{ __('واتساب') }}" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                @can('manage-bookings')
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-2 border" style="color:var(--crm-red);" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold">{{ __('لا توجد طلبات نشطة حالياً') }}</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none p-3">
            @forelse($bookings as $b)
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#{{ $b->id }}</a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">
                            {{ $b->client_name }}
                            @if($b->source === 'calculator')
                                <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-calculator me-1"></i>{{ __('حاسبة') }}
                                </span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                    </div>
                </div>

                {{-- Car & Employee --}}
                <div class="d-flex justify-content-between align-items-center small text-muted mb-2 pt-2 border-top">
                    <div><i class="bi bi-car-front me-1"></i> {{ $b->car?->brand?->name }} {{ $b->car?->name ?? '—' }}</div>
                    <div><i class="bi bi-person me-1"></i> {{ $b->employee?->name ?? __('غير معين') }}</div>
                </div>

                {{-- Status Select --}}
                <div class="mt-2">
                    <label class="form-label small fw-bold text-muted mb-1">{{ __('الحالة:') }}</label>
                    <form action="{{ route('crm.bookings.status', $b) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select form-select-sm"
                                style="font-size:12px;border-radius:8px;"
                                data-current-status="{{ $b->status }}"
                                onchange="handleBookingStatusSelectChange(this, {{ $b->id }}, '{{ route('crm.bookings.status', $b) }}', {{ $isAdmin ? 'true' : 'false' }})">
                            <optgroup label="{{ __('المراحل النشطة (Active)') }}">
                                @foreach($statuses as $key => $s)
                                    <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>⚡ {{ $s['label'] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('حالات خاصة / معلقة') }}">
                                <option value="pending">⏳ {{ __('قيد الانتظار') }}</option>
                            </optgroup>
                            <optgroup label="{{ __('تسليم الطلب (ناجح)') }}">
                                <option value="received" data-close="1">✅ {{ __('تم التسليم') }}</option>
                            </optgroup>
                            <optgroup label="{{ __('إغلاق الحجز (خاسر)') }}">
                                @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                    @if(($s['group'] ?? '') === 'lost')
                                        <option value="{{ $key }}" data-close="1">❌ {{ $s['label'] }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </form>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> {{ __('عرض') }}
                    </a>
                    <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;color:#25D366;">
                        <i class="bi bi-whatsapp"></i> {{ __('واتساب') }}
                    </a>
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
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            {{ $bookings->links() }}
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
