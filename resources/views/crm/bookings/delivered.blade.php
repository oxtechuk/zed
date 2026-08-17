@extends('partials.Layouts.crm-master')
@section('title', __('طلبات تم التسليم') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.bookings.index') }}">{{ __('الطلبات') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('طلبات تم التسليم') }}</span>
    </nav>

    {{-- Title Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#DCFCE7;color:#16A34A;">
                    <i class="bi bi-check2-circle"></i>
                </span>
                {{ __('طلبات تم التسليم (المستلمة)') }}
            </h4>
            <p class="text-muted small mb-0">{{ __('سجل الصفقات الناجحة والطلبات المسلّمة للعملاء ومتابعة الإيرادات والعمولات') }}</p>
        </div>
        <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 fw-bold px-3">
            <i class="bi bi-arrow-right me-1"></i> {{ __('العودة للطلبات النشطة') }}
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #F0FDF4);">
                <div class="stat-icon green"><i class="bi bi-trophy"></i></div>
                <div class="stat-lbl">{{ __('إجمالي المسلّم') }}</div>
                <div class="stat-val text-success">{{ number_format($stats['total_delivered']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #EFF6FF);">
                <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-lbl">{{ __('تسليمات هذا الشهر') }}</div>
                <div class="stat-val text-primary">{{ number_format($stats['month_delivered']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #FDF4FF);">
                <div class="stat-icon purple"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-lbl">{{ __('عمولات هذا الشهر') }}</div>
                <div class="stat-val" style="color:#9333EA;font-size:1.4rem;">{{ number_format($stats['month_commission'], 2) }} <small style="font-size:11px;">ر.س</small></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #FEFCE8);">
                <div class="stat-icon orange"><i class="bi bi-wallet2"></i></div>
                <div class="stat-lbl">{{ __('إجمالي صافي العمولات') }}</div>
                <div class="stat-val text-warning-dark" style="font-size:1.4rem;">{{ number_format($stats['total_commission'], 2) }} <small style="font-size:11px;">ر.س</small></div>
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

                    {{-- فلتر الشهر (يتيح اختيار هذا الشهر أو أي شهر محدد) --}}
                    <div style="position:relative;">
                        <input type="month" name="month" value="{{ request('month') }}"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; min-width: 150px;"
                               onchange="this.form.submit()" title="{{ __('تصفية بشهر التسليم') }}">
                    </div>

                    {{-- زر سريع للشهر الحالي --}}
                    @if(!request('month') || request('month') !== now()->format('Y-m'))
                    <a href="{{ request()->fullUrlWithQuery(['month' => now()->format('Y-m')]) }}" class="btn btn-sm btn-light border fw-bold" style="font-size:12px;padding:8px 12px;">
                        <i class="bi bi-calendar-event me-1"></i> {{ __('تسليمات هذا الشهر') }}
                    </a>
                    @endif

                    {{-- مصدر الطلب --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value="">{{ __('المصدر — الكل') }}</option>
                        <option value="booking" {{ request('source')==='booking'?'selected':'' }}>{{ __('طلبات عادية') }}</option>
                        <option value="calculator" {{ request('source')==='calculator'?'selected':'' }}>{{ __('عملاء حاسبة فقط') }}</option>
                    </select>

                    {{-- الترتيب --}}
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:170px;">
                        <option value="newest" {{ request('sort','newest')==='newest'?'selected':'' }}>{{ __('الأحدث تسليماً') }}</option>
                        <option value="highest_commission" {{ request('sort')==='highest_commission'?'selected':'' }}>{{ __('الأعلى عمولة') }}</option>
                        <option value="highest_price" {{ request('sort')==='highest_price'?'selected':'' }}>{{ __('الأعلى سعراً') }}</option>
                        <option value="oldest" {{ request('sort')==='oldest'?'selected':'' }}>{{ __('الأقدم تسليماً') }}</option>
                    </select>

                    {{-- Search --}}
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('بحث بالاسم أو الهاتف...') }}"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;">{{ __('تصفية') }}</button>
                    <a href="{{ route('crm.bookings.delivered') }}" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Delivered Bookings Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-check2-circle me-1 text-success"></i> {{ __('قائمة الطلبات المسلّمة') }}</h6>
            <span style="font-size:12px;color:var(--crm-text-muted);">{{ __('إجمالي المسلّم') }}: <strong>{{ $bookings->total() }}</strong></span>
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
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('سعر الشراء') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('سعر التعميد') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('المصروفات') }}</th>
                        <th class="py-3 text-muted fw-bold text-success" style="font-size:12px;">{{ __('صافي العمولة') }}</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('تاريخ التسليم') }}</th>
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
                        <td style="font-size:12px;">
                            @if($b->purchase_price)
                                <span class="fw-bold">{{ number_format($b->purchase_price, 2) }}</span> <small class="text-muted">ر.س</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;">
                            @if($b->authorization_price)
                                <span class="fw-bold">{{ number_format($b->authorization_price, 2) }}</span> <small class="text-muted">ر.س</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;">
                            @if($b->expenses)
                                <span class="fw-bold text-danger">{{ number_format($b->expenses, 2) }}</span> <small class="text-muted">ر.س</small>
                            @else
                                <span class="text-muted">0.00</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">
                            @if($b->net_commission !== null)
                                <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:12px;font-weight:800;border:1px solid #BBF7D0;padding:5px 10px;">
                                    {{ number_format($b->net_commission, 2) }} ر.س
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;">
                            @php
                                $deliveryDate = $b->delivered_at ?? $b->updated_at;
                            @endphp
                            <div><i class="bi bi-calendar3 me-1 text-muted"></i> {{ $deliveryDate->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $deliveryDate->diffForHumans() }}</div>
                            @if($b->delivery_note_text)
                                <div class="text-muted mt-1" style="font-size:11px;" title="{{ $b->delivery_note_text }}">
                                    <i class="bi bi-chat-square-text me-1 text-success"></i>{{ Str::limit($b->delivery_note_text, 25) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4">
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض وتعديل التفاصيل') }}">
                                    <i class="bi bi-pencil-square" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="{{ route('crm.bookings.show', $b) }}" class="btn btn-sm btn-light rounded-2 border" title="{{ __('عرض التفاصيل') }}">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/{{ $b->client_phone }}" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="{{ __('واتساب') }}" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                @can('manage-bookings')
                                <form action="{{ route('crm.bookings.destroy', $b) }}" method="POST" class="m-0"
                                      onsubmit="return confirm('{{ __('هل تريد حذف هذا الطلب نهائياً؟') }}')">
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
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="bi bi-check2-all fs-1 d-block mb-2 opacity-25 text-success"></i>
                            <div class="fw-bold">{{ __('لا توجد طلبات مسلّمة حالياً') }}</div>
                            <small class="text-muted">{{ __('عند تحويل أي طلب إلى "تم التسليم" وإدخال بياناته المالية، سيظهر في هذه القائمة تلقائياً') }}</small>
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
                    <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:11px;">
                        <i class="bi bi-check2-circle me-1"></i> {{ __('تم التسليم') }}
                    </span>
                </div>

                {{-- Financial Details Grid --}}
                <div class="p-2 rounded-3 mb-2" style="background:#F8FAFC;border:1px solid var(--crm-border);font-size:12px;">
                    <div class="row g-2">
                        <div class="col-6">
                            <span class="text-muted small">{{ __('سعر الشراء:') }}</span>
                            <div class="fw-bold">{{ $b->purchase_price ? number_format($b->purchase_price, 2).' ر.س' : '—' }}</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small">{{ __('سعر التعميد:') }}</span>
                            <div class="fw-bold">{{ $b->authorization_price ? number_format($b->authorization_price, 2).' ر.س' : '—' }}</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small">{{ __('المصروفات:') }}</span>
                            <div class="fw-bold text-danger">{{ $b->expenses ? number_format($b->expenses, 2).' ر.س' : '0.00 ر.س' }}</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small">{{ __('صافي العمولة:') }}</span>
                            <div class="fw-bold text-success">{{ $b->net_commission !== null ? number_format($b->net_commission, 2).' ر.س' : '—' }}</div>
                        </div>
                    </div>
                </div>

                @if($b->delivery_note_text)
                <div class="p-2 rounded-3 mb-2" style="background:#F0FDF4;border:1px solid #BBF7D0;font-size:11.5px;color:#166534;">
                    <div class="fw-bold mb-1"><i class="bi bi-chat-square-text me-1"></i>{{ __('ملاحظة التسليم') }}:</div>
                    <div class="text-dark">{{ $b->delivery_note_text }}</div>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                    <div><i class="bi bi-person me-1"></i> {{ $b->employee?->name ?? __('غير معين') }}</div>
                    <div><i class="bi bi-calendar3 me-1"></i> {{ ($b->delivered_at ?? $b->updated_at)->format('d/m/Y') }}</div>
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
                <i class="bi bi-check2-all fs-1 d-block mb-2 opacity-25 text-success"></i>
                <div>{{ __('لا توجد طلبات مسلّمة حالياً') }}</div>
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
