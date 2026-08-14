@extends('partials.Layouts.crm-master')
@section('title', __('الطلبات المغلقة') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('الطلبات المغلقة') }}</span>
    </nav>

    {{-- Title Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('الطلبات المغلقة') }}</h4>
            <p class="text-muted small mb-0">{{ __('استعراض وتحليل الطلبات المنتهية والخاسرة حسب الحالات المختلفة') }}</p>
        </div>
    </div>

    {{-- Stats Cards (Dashboard Style) --}}
    <div class="row g-3 mb-4">
        <!-- Main total closed card -->
        <div class="col-12 col-md-4 col-xl-3">
            <div class="crm-stat-new py-4 shadow-sm" style="border: 1px solid var(--crm-border) !important; background: linear-gradient(135deg, #FFF, #FEF2F2); border-radius: 16px;">
                <div class="stat-icon red" style="background: #FEE2E2; color: #EF4444;"><i class="bi bi-folder-x"></i></div>
                <div class="stat-lbl fw-bold text-muted mt-2" style="font-size: 13px;">{{ __('إجمالي الطلبات المغلقة') }}</div>
                <div class="stat-val text-danger fw-black fs-2 mt-1">{{ number_format($totalClosed) }}</div>
            </div>
        </div>

        <!-- Closed Statuses Breakdowns -->
        <div class="col-12 col-md-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important; border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart me-1"></i> {{ __('نسب الحالات المغلقة') }}</h6>
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
                    {{-- مصدر الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="">{{ __('المصدر — الكل') }}</option>
                        <option value="booking" {{ request('source')==='booking'?'selected':'' }}>{{ __('طلبات فقط') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>{{ __('عملاء حاسبة فقط') }}</option>
                    </select>
                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="newest" {{ request('sort','newest')==='newest'?'selected':'' }}>{{ __('الأحدث أولاً') }}</option>
                        <option value="oldest" {{ request('sort','newest')==='oldest'?'selected':'' }}>{{ __('الأقدم أولاً') }}</option>
                    </select>
                    {{-- الحالة --}}
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;">
                        <option value="">{{ __('الحالة المغلقة — الكل') }}</option>
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
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;">{{ __('الحالة') }}</th>
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
                                @php
                                    $badgeColor = $b->status === 'received' ? 'success' : 'danger';
                                @endphp
                                <select name="status" class="form-select form-select-sm border shadow-none bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} fw-bold p-1" style="font-size:12px; width:auto; border-radius: 6px; border-color: rgba(var(--bs-{{ $badgeColor }}-rgb), 0.25) !important;" onchange="this.form.submit()">
                                    <optgroup label="{{ __('إرجاع إلى المندوب (Active)') }}">
                                        @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                            @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval')
                                                <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{ __('الحالات المغلقة (Closed)') }}">
                                        @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                            @if(($s['is_close'] ?? false) === true)
                                                <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-3 py-3 text-muted small" style="font-size:12px;">
                            {{ $b->updated_at->format('Y-m-d H:i') }}
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
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            {{ __('لا توجد طلبات مغلقة حالياً مطابقة لخيارات التصفية') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-block d-md-none">
            @forelse($bookings as $b)
            <div class="mb-3 p-3 rounded-3 bg-white border-bottom" style="border:1px solid var(--crm-border);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="{{ route('crm.bookings.show', $b) }}" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#{{ $b->id }}</a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">
                            {{ $b->client_name }}
                            @if($b->source === 'calculator')
                                <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 5px; border-radius: 4px;">
                                    {{ __('حاسبة') }}
                                </span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr">{{ $b->client_phone }}</div>
                    </div>
                    <form action="{{ route('crm.bookings.status', $b) }}" method="POST" class="m-0">
                        @csrf @method('PATCH')
                        @php
                            $badgeColor = $b->status === 'received' ? 'success' : 'danger';
                        @endphp
                        <select name="status" class="form-select form-select-sm border shadow-none bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} fw-bold p-1" style="font-size:11px; width:auto; border-radius: 6px;" onchange="this.form.submit()">
                            <optgroup label="{{ __('إرجاع إلى المندوب (Active)') }}">
                                @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                    @if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval')
                                        <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('الحالات المغلقة (Closed)') }}">
                                @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                    @if(($s['is_close'] ?? false) === true)
                                        <option value="{{ $key }}" {{ $b->status === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </form>
                </div>
                <div class="d-flex align-items-center justify-content-between" style="font-size:12px;color:var(--crm-text-muted);border-top:1px solid var(--crm-border);padding-top:10px;margin-top:8px;">
                    <div>
                        <i class="bi bi-car-front me-1"></i>
                        {{ $b->car?->brand?->name }} {{ $b->car?->name ?? '—' }}
                    </div>
                    <div class="small">
                        {{ $b->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> {{ __('عرض') }}
                    </a>
                    <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center text-success" style="font-size:12px;">
                        <i class="bi bi-whatsapp"></i> {{ __('واتساب') }}
                    </a>
                    @if($isAdmin)
                    <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                          onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light border text-danger" style="font-size:12px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5 bg-white">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                {{ __('لا توجد طلبات مغلقة حالياً') }}
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
@endsection
