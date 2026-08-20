@extends('partials.Layouts.crm-master')
@section('title', __('الطلبات المغلقة') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.bookings.index') }}">{{ __('الطلبات') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('الطلبات المغلقة') }}</span>
    </nav>

    {{-- Title Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#FEE2E2;color:#DC2626;">
                    <i class="bi bi-folder-x"></i>
                </span>
                {{ __('طلبات الإغلاق (الحالات المغلقة والخاسرة)') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('استعراض وتحليل أسباب الإغلاق للطلبات ومتابعة ما تم إغلاقه خلال الشهر') }}</p>
        </div>
        <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 fw-bold px-3">
            <i class="bi bi-arrow-right me-1"></i> {{ __('العودة للطلبات النشطة') }}
        </a>
    </div>

    {{-- Stats Cards (Dashboard Style) --}}
    <div class="row g-3 mb-4">
        <!-- Main total closed card -->
        <div class="col-12 col-md-4 col-xl-3">
            <div class="crm-stat-new py-3 shadow-sm h-100 d-flex flex-column justify-content-between" style="border: 1px solid var(--crm-border) !important; background: linear-gradient(135deg, #FFF, #FEF2F2); border-radius: 16px;">
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-icon red" style="background: #FEE2E2; color: #EF4444;"><i class="bi bi-folder-x"></i></div>
                        <span class="badge bg-danger text-white">{{ __('خاسرة / مغلقة') }}</span>
                    </div>
                    <div class="stat-lbl fw-bold text-muted mt-2" style="font-size: 13px;">{{ __('إجمالي الطلبات المغلقة') }}</div>
                    <div class="stat-val text-danger fw-black fs-2 mt-1">{{ number_format($totalClosed) }}</div>
                </div>
                <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center" style="font-size:12px;">
                    <span class="text-muted">{{ __('إغلاقات هذا الشهر:') }}</span>
                    <strong class="text-danger fs-6">{{ number_format($closedThisMonth) }}</strong>
                </div>
            </div>
        </div>

        <!-- Closed Statuses Breakdowns -->
        <div class="col-12 col-md-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important; border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart me-1"></i> {{ __('نسب وتوزيع أسباب الإغلاق') }}</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <div class="row g-2">
                        @foreach($statsByStatus as $statusKey => $statusData)
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="p-2 rounded-3 text-start border" style="background: #F8FAFC; border-color: #E2E8F0 !important;">
                                    <div class="text-muted small text-truncate fw-semibold" title="{{ $statusData['label'] }}">
                                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--bs-{{ $statusData['color'] ?? 'secondary' }});"></span>
                                        {{ $statusData['label'] }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span class="fw-bold text-dark fs-6">{{ number_format($statusData['count']) }}</span>
                                        <span class="badge bg-light text-dark border small">{{ $statusData['percentage'] }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                               onchange="this.form.submit()" title="{{ __('تصفية بشهر الإغلاق') }}">
                    </div>

                    {{-- مصدر ونوع الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value="">{{ __('المصدر والنوع — الكل') }}</option>
                        <option value="cars" {{ request('source')==='cars'?'selected':'' }}>🚗 {{ __('طلبات السيارات (حجز وشراء)') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>🧮 {{ __('عملاء حاسبة التمويل') }}</option>
                    </select>

                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="newest" {{ request('sort','newest')==='newest'?'selected':'' }}>{{ __('الأحدث إغلاقاً') }}</option>
                        <option value="oldest" {{ request('sort','newest')==='oldest'?'selected':'' }}>{{ __('الأقدم إغلاقاً') }}</option>
                    </select>

                    {{-- الحالة المغلقة --}}
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;">
                        <option value="">{{ __('سبب الإغلاق — الكل') }}</option>
                        @foreach($statuses as $key => $s)
                        <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $s['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- Search --}}
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('بحث بالاسم أو الهاتف...') }}"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;">{{ __('تصفية') }}</button>
                    <a href="{{ route('crm.bookings.closed') }}" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Closed Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;">#</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('العميل') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('السيارة') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('المندوب') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('سبب الإغلاق') }}</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('تاريخ الإغلاق') }}</th>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('الإجراء') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);">#{{ $b->id }}</a>
                        </td>
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-bold" style="font-size:13px;">
                                        {{ $b->client_name }}
                                        @if($b->source === 'calculator')
                                            <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 5px; border-radius: 4px; font-weight: bold;">
                                                <i class="bi bi-calculator me-1"></i>{{ __('حاسبة') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            {{ $b->car?->brand?->name }} {{ $b->car?->name ?? '—' }}
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            {{ $b->employee?->name ?? '—' }}
                        </td>
                        <td class="px-3 py-3">
                            <form action="{{ route('crm.bookings.status', $b) }}" method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm border shadow-none bg-danger bg-opacity-10 text-danger fw-bold p-1"
                                        style="font-size:12px; width:auto; border-radius: 6px; cursor:pointer;"
                                        data-current-status="{{ $b->status }}"
                                        onchange="handleBookingStatusSelectChange(this, {{ $b->id }}, '{{ route('crm.bookings.status', $b) }}', {{ $isAdmin ? 'true' : 'false' }})">
                                    <optgroup label="{{ __('إرجاع إلى مسار المبيعات النشط') }}">
                                        @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                            @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'pending' && $key !== 'waiting_supervisor_approval')
                                                <option value="{{ $key }}">⚡ {{ $s['label'] }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{ __('حالات أخرى') }}">
                                        <option value="pending">⏳ {{ __('قيد الانتظار') }}</option>
                                        <option value="received" data-close="1">✅ {{ __('تم التسليم') }}</option>
                                    </optgroup>
                                    <optgroup label="{{ __('تعديل سبب الإغلاق') }}">
                                        @foreach($statuses as $key => $s)
                                            <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>❌ {{ $s['label'] }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-3 py-3 text-muted small" style="font-size:12px;">
                            <div><i class="bi bi-calendar3 me-1"></i> {{ $b->updated_at->format('Y-m-d H:i') }}</div>
                            <div style="font-size:11px;">{{ $b->updated_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2" style="font-size:12px; font-weight:600; padding:6px 12px;">
                                    <i class="bi bi-eye me-1"></i>{{ __('عرض') }}
                                </a>
                                <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 text-success" style="font-size:12px; font-weight:600; padding:6px 12px;">
                                    <i class="bi bi-whatsapp me-1"></i>{{ __('واتساب') }}
                                </a>
                                @if($isAdmin)
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2 text-danger" style="font-size:12px; padding:6px 10px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-25"></i>
                            {{ __('لا توجد طلبات مغلقة مطابقة للبحث') }}
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
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">{{ $b->client_name }}</div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:11px;">
                        {{ $statuses[$b->status]['label'] ?? $b->status }}
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center small text-muted mb-2 pt-2 border-top">
                    <div><i class="bi bi-car-front me-1"></i> {{ $b->car?->brand?->name }} {{ $b->car?->name }}</div>
                    <div><i class="bi bi-person me-1"></i> {{ $b->employee?->name ?? '—' }}</div>
                </div>

                <div class="d-flex gap-2 mt-2">
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
                <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-25"></i>
                <div>{{ __('لا توجد طلبات مغلقة حالياً') }}</div>
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
