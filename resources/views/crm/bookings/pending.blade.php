@extends('partials.Layouts.crm-master')
@section('title', __('طلبات قيد الانتظار') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.bookings.index') }}">{{ __('الطلبات') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('طلبات قيد الانتظار') }}</span>
    </nav>

    {{-- Title Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#FEF3C7;color:#D97706;">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                {{ __('طلبات قيد الانتظار') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('متابعة الطلبات المعلقة مع أسباب الانتظار ومواعيد إعادة التواصل والتنفيذ') }}</p>
        </div>
        <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 fw-bold px-3">
            <i class="bi bi-arrow-right me-1"></i> {{ __('العودة لمسار المبيعات النشط') }}
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-lbl">{{ __('إجمالي قيد الانتظار') }}</div>
                <div class="stat-val">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
                <div class="stat-lbl">{{ __('مواعيد متابعة اليوم') }}</div>
                <div class="stat-val text-primary">{{ number_format($stats['today']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-lbl">{{ __('متابعات متأخرة') }}</div>
                <div class="stat-val text-danger">{{ number_format($stats['overdue']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon purple"><i class="bi bi-clock-history"></i></div>
                <div class="stat-lbl">{{ __('متابعات قادمة') }}</div>
                <div class="stat-val" style="color:#7C3AED;">{{ number_format($stats['upcoming']) }}</div>
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

                    {{-- توقيت المتابعة --}}
                    <select name="timing" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;" onchange="this.form.submit()">
                        <option value="">{{ __('موعد المتابعة — الكل') }}</option>
                        <option value="today" {{ request('timing')==='today'?'selected':'' }}>{{ __('متابعات اليوم') }}</option>
                        <option value="overdue" {{ request('timing')==='overdue'?'selected':'' }}>{{ __('متأخرة عن الموعد') }}</option>
                        <option value="upcoming" {{ request('timing')==='upcoming'?'selected':'' }}>{{ __('مواعيد قادمة') }}</option>
                    </select>

                    {{-- مصدر ونوع الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value="">{{ __('المصدر والنوع — الكل') }}</option>
                        <option value="cars" {{ request('source')==='cars'?'selected':'' }}>🚗 {{ __('طلبات السيارات (حجز وشراء)') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>🧮 {{ __('عملاء حاسبة التمويل') }}</option>
                        <option value="test_drive" {{ request('source')==='test_drive'?'selected':'' }}>⏱️ {{ __('طلبات تجربة القيادة') }}</option>
                    </select>

                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:170px;">
                        <option value="nearest_follow_up" {{ request('sort','nearest_follow_up')==='nearest_follow_up'?'selected':'' }}>{{ __('الأقرب في موعد المتابعة') }}</option>
                        <option value="furthest_follow_up" {{ request('sort')==='furthest_follow_up'?'selected':'' }}>{{ __('الأبعد في موعد المتابعة') }}</option>
                        <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>{{ __('الأحدث إنشاءً') }}</option>
                        <option value="oldest" {{ request('sort')==='oldest'?'selected':'' }}>{{ __('الأقدم إنشاءً') }}</option>
                    </select>

                    {{-- Search --}}
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('بحث بالاسم أو الهاتف...') }}"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;">{{ __('تصفية') }}</button>
                    <a href="{{ route('crm.bookings.pending') }}" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Pending Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-1 text-warning"></i> {{ __('قائمة الطلبات المعلقة (قيد الانتظار)') }}</h6>
            <span style="font-size:12px;color:var(--crm-text-muted);">{{ __('إجمالي النتائج') }}: <strong>{{ $bookings->total() }}</strong></span>
        </div>

        {{-- Desktop Table --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم الطلب') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('العميل') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('السيارة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الموظف') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;min-width:200px;">{{ __('سبب الانتظار') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('موعد المتابعة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('تغيير الحالة / Action') }}</th>
                        <th class="py-3 text-muted fw-bold px-4" style="font-size:12px;">{{ __('تحكم') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($bookings as $index => $b)
                    @php
                        $isOverdue = $b->follow_up_at && $b->follow_up_at->isPast() && !$b->follow_up_at->isToday();
                        $isToday = $b->follow_up_at && $b->follow_up_at->isToday();
                    @endphp
                    <tr style="{{ $isOverdue ? 'background-color:#FFF5F5;' : ($isToday ? 'background-color:#FFFBEB;' : '') }}">
                        <td class="px-4 text-muted small" style="font-size:13px;">
                            {{ $bookings->firstItem() + $index }}
                        </td>
                        <td class="fw-bold" style="font-size:13px;">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#{{ $b->id }}</a>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;">{{ $b->client_name }}</div>
                            <a href="tel:{{ $b->client_phone }}" class="text-decoration-none small text-muted" dir="ltr">{{ $b->client_phone }}</a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <strong class="text-dark">{{ $b->car?->brand?->name ?? '' }} {{ $b->car?->name ?? '—' }}</strong>
                                @if($b->car?->year)
                                    <span class="text-muted small">({{ $b->car->year }})</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size:12px;">
                                <i class="bi bi-person me-1"></i> {{ $b->employee?->name ?? __('غير معين') }}
                            </span>
                        </td>
                        <td>
                            <div class="p-2 rounded-3" style="background:#FFF9EB;border:1px dashed #FDE68A;font-size:12px;color:#92400E;line-height:1.6;">
                                <i class="bi bi-chat-left-quote me-1 text-warning"></i>
                                <strong>{{ $b->pending_reason ?: __('لا يوجد سبب محدد') }}</strong>
                            </div>
                        </td>
                        <td>
                            @if($b->follow_up_at)
                                <div class="d-flex flex-column" style="font-size:12px;">
                                    <span class="fw-bold {{ $isOverdue ? 'text-danger' : ($isToday ? 'text-warning-dark fw-black' : 'text-dark') }}">
                                        <i class="bi bi-clock me-1"></i> {{ $b->follow_up_at->format('d/m/Y - h:i A') }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="badge bg-danger text-white mt-1" style="font-size:10px;width:fit-content;">{{ __('متأخرة') }} ({{ $b->follow_up_at->diffForHumans() }})</span>
                                    @elseif($isToday)
                                        <span class="badge bg-warning text-dark mt-1" style="font-size:10px;width:fit-content;">{{ __('اليوم') }} ({{ $b->follow_up_at->diffForHumans() }})</span>
                                    @else
                                        <span class="text-muted mt-1" style="font-size:11px;">{{ $b->follow_up_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted" style="font-size:12px;">—</span>
                            @endif
                        </td>
                        <td>
                            {{-- تغيير الحالة لإرجاع الطلب للمسار الطبيعي --}}
                            <form action="{{ route('crm.bookings.status', $b) }}" method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm border shadow-none fw-bold"
                                        style="font-size:12px;border-radius:8px;background:#F8FAFC;min-width:150px;cursor:pointer;"
                                        data-current-status="pending"
                                        onchange="handleBookingStatusSelectChange(this, {{ $b->id }}, '{{ route('crm.bookings.status', $b) }}', {{ $isAdmin ? 'true' : 'false' }})">
                                    <option value="pending" selected>⏳ {{ __('قيد الانتظار') }}</option>
                                    <optgroup label="{{ __('إرجاع إلى مسار المبيعات النشط') }}">
                                        @foreach($allStatuses as $key => $s)
                                            @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'pending' && $key !== 'waiting_supervisor_approval')
                                                <option value="{{ $key }}">⚡ {{ $s['label'] }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{ __('تسليم الطلب (ناجح)') }}">
                                        <option value="received" data-close="1">✅ {{ __('تم التسليم (تم الاستلام)') }}</option>
                                    </optgroup>
                                    <optgroup label="{{ __('إغلاق الحجز (خاسر)') }}">
                                        @foreach($allStatuses as $key => $s)
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
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض التفاصيل') }}">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="{{ __('واتساب') }}" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                @if($isAdmin)
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-2 border" style="color:var(--crm-red);" title="{{ __('حذف') }}">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-hourglass-bottom fs-1 d-block mb-2 opacity-25 text-warning"></i>
                            <div class="fw-bold">{{ __('لا توجد طلبات قيد الانتظار حالياً') }}</div>
                            <small class="text-muted">{{ __('أي طلب يتم نقله إلى حالة "قيد الانتظار" سيظهر في هذه القائمة تلقائياً') }}</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none p-3">
            @forelse($bookings as $b)
            @php
                $isOverdueM = $b->follow_up_at && $b->follow_up_at->isPast() && !$b->follow_up_at->isToday();
                $isTodayM = $b->follow_up_at && $b->follow_up_at->isToday();
            @endphp
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:{{ $isOverdueM ? '#FFF5F5' : ($isTodayM ? '#FFFBEB' : '#fff') }};">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#{{ $b->id }}</a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">{{ $b->client_name }}</div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                    </div>
                    <span class="badge" style="background:#FEF3C7;color:#D97706;font-size:11px;">
                        ⏳ {{ __('قيد الانتظار') }}
                    </span>
                </div>

                {{-- Reason Box --}}
                <div class="p-2 rounded-3 mb-2" style="background:#FFF9EB;border:1px dashed #FDE68A;font-size:12px;color:#92400E;">
                    <i class="bi bi-chat-left-quote me-1"></i> <strong>{{ $b->pending_reason ?: __('لا يوجد سبب محدد') }}</strong>
                </div>

                {{-- Follow Up Time --}}
                @if($b->follow_up_at)
                <div class="mb-2 d-flex justify-content-between align-items-center" style="font-size:12px;">
                    <span class="text-muted">{{ __('موعد المتابعة:') }}</span>
                    <span class="fw-bold {{ $isOverdueM ? 'text-danger' : ($isTodayM ? 'text-warning-dark' : 'text-dark') }}">
                        <i class="bi bi-clock me-1"></i> {{ $b->follow_up_at->format('d/m/Y - h:i A') }}
                    </span>
                </div>
                @endif

                {{-- Action / Status Switcher --}}
                <div class="mt-3 pt-2 border-top">
                    <label class="form-label small fw-bold text-muted mb-1">{{ __('تغيير الحالة / إرجاع للمسار:') }}</label>
                    <form action="{{ route('crm.bookings.status', $b) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select form-select-sm"
                                style="font-size:12px;border-radius:8px;"
                                data-current-status="pending"
                                onchange="handleBookingStatusSelectChange(this, {{ $b->id }}, '{{ route('crm.bookings.status', $b) }}', {{ $isAdmin ? 'true' : 'false' }})">
                            <option value="pending" selected>⏳ {{ __('قيد الانتظار') }}</option>
                            <optgroup label="{{ __('إرجاع إلى مسار المبيعات النشط') }}">
                                @foreach($allStatuses as $key => $s)
                                    @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'pending' && $key !== 'waiting_supervisor_approval')
                                        <option value="{{ $key }}">⚡ {{ $s['label'] }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('تسليم الطلب (ناجح)') }}">
                                <option value="received" data-close="1">✅ {{ __('تم التسليم') }}</option>
                            </optgroup>
                            <optgroup label="{{ __('إغلاق الحجز (خاسر)') }}">
                                @foreach($allStatuses as $key => $s)
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
                <i class="bi bi-hourglass-bottom fs-1 d-block mb-2 opacity-25 text-warning"></i>
                <div>{{ __('لا توجد طلبات قيد الانتظار حالياً') }}</div>
            </div>
            @endforelse
        </div>

        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

</div>

@include('crm.bookings.partials.status-modals')

@endsection
