@extends('partials.Layouts.crm-master')
@section('title', __('تفاصيل الطلب') . ' #' . $booking->id . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    @php
        $dotClass = match($booking->status) {
            'new','pending'  => 'planned',
            'contacted'      => 'waiting',
            'sold','done'    => 'done',
            'rejected'       => 'late',
            default          => 'confirmed',
        };
        $historyNotes = $booking->notes_list->where('type', 'status_change');
        $comments = $booking->notes_list->whereIn('type', ['note', 'call']);
        $taskDot = fn ($task) => $task->status === 'done' ? 'done' : ($task->is_late ? 'late' : 'waiting');
    @endphp

    {{-- Breadcrumb --}}
    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.bookings.index') }}">{{ __('الطلبات') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('تفاصيل الطلب') }} #{{ $booking->id }}</span>
    </nav>

    {{-- ===== Header Banner ===== --}}
    <div class="rounded-4 mb-3 p-4" style="background:#14234d;box-shadow:0 8px 20px rgba(249,115,22,0.25);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <h5 class="fw-bold mb-0 text-white">{{ __('عرض بيانات الطلب') }} <span style="opacity:0.85;">#{{ $booking->id }}</span></h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm rounded-3 fw-bold" style="background:rgba(255,255,255,0.18);color:#fff;padding:8px 14px;">
                    <i class="bi bi-arrow-right"></i>
                    <span class="d-none d-md-inline">{{ __('العودة للطلبات') }}</span>
                </a>
                <button onclick="window.print()" class="btn btn-sm rounded-3 fw-bold" style="background:#fff;color:var(--crm-orange-dark);padding:8px 14px;">
                    <i class="bi bi-printer"></i>
                    <span class="d-none d-md-inline">{{ __('طباعة تفاصيل الطلب') }}</span>
                </button>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-4 text-white" style="font-size:13px;opacity:0.95;">
            <span>
                <i class="bi bi-person-circle me-1"></i>{{ __('العميل') }}: <strong>{{ $booking->client_name }}</strong>
                @if($booking->source === 'calculator')
                    <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: bold;">
                        <i class="bi bi-calculator me-1"></i>{{ __('عميل حاسبة') }}
                    </span>
                @endif
            </span>
            <span dir="ltr"><i class="bi bi-telephone me-1"></i>{{ $booking->client_phone }}</span>
            <span><i class="bi bi-flag me-1"></i>{{ __('الحالة') }}: <strong>{{ $booking->status_label }}</strong></span>
            <span><i class="bi bi-calendar3 me-1"></i>{{ $booking->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    @if($booking->status === 'waiting_supervisor_approval' && $booking->proposed_status)
    <div class="card border-0 shadow-sm rounded-4 mb-3" style="border: 2px solid var(--crm-orange) !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle p-2 bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-shield-lock fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color: var(--crm-text);">
                        {{ __('بانتظار اعتماد إغلاق الطلب من المشرف') }}
                    </h6>
                    <p class="text-muted small mb-0">
                        {{ __('يقوم الموظف المسؤول بطلب إغلاق هذا الطلب إلى') }} 
                        <strong class="text-danger">"{{ \App\Models\Booking::STATUSES[$booking->proposed_status]['label'] ?? $booking->proposed_status }}"</strong>.
                    </p>
                </div>
            </div>

            @if(auth('employee')->user()->isAdmin())
            <div class="d-flex flex-wrap gap-2">
                {{-- Approve form --}}
                <form action="{{ route('crm.bookings.approve', $booking) }}" method="POST" class="m-0">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success py-2.5 px-4 fw-bold rounded-3 shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i> {{ __('اعتماد الإغلاق') }}
                    </button>
                </form>

                {{-- Reject button (opens modal) --}}
                <button type="button" class="btn btn-danger py-2.5 px-4 fw-bold rounded-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#rejectStatusModal">
                    <i class="bi bi-reply-all me-1"></i> {{ __('رفض وإعادة الطلب لمرحلة أخرى') }}
                </button>
            </div>
            @else
            <div class="alert alert-light border m-0 text-secondary" style="font-size: 13px; font-weight: 700;">
                <i class="bi bi-info-circle me-1"></i>
                {{ __('فقط المشرف يملك الصلاحية لاعتماد هذا الإغلاق أو إعادة توجيه الطلب.') }}
            </div>
            @endif
        </div>
    </div>

    @if(auth('employee')->user()->isAdmin())
    <!-- Reject / Return to stage Modal -->
    <div class="modal fade" id="rejectStatusModal" tabindex="-1" aria-labelledby="rejectStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="rejectStatusModalLabel">{{ __('رفض الإغلاق وإعادة الطلب') }}</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('crm.bookings.reject', $booking) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('المرحلة البديلة (إعادة إلى)') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select bg-light border-0 shadow-none" required style="border-radius:10px; font-size:14px; padding:10px 14px;">
                                <option value="">{{ __('اختر المرحلة النشطة...') }}</option>
                                @foreach(\App\Models\Booking::STATUSES as $key => $s)
                                    @if($s['group'] === 'active' && ($s['is_close'] ?? false) === false)
                                        <option value="{{ $key }}">{{ $s['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('سبب رفض الإغلاق / الملاحظة') }} <span class="text-danger">*</span></label>
                            <textarea name="note" class="form-control bg-light border-0 shadow-none" rows="4" required style="border-radius:10px; font-size:14px;" placeholder="{{ __('اكتب هنا سبب إرجاع الطلب أو التوجيهات...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-danger py-2 px-3 fw-bold rounded-3">{{ __('إعادة الطلب') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- ===== Tabs ===== --}}
    <ul class="nav nav-tabs mb-3" style="border-bottom:1px solid var(--crm-border);" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-data" type="button">
                <i class="bi bi-file-text me-1"></i>{{ __('بيانات الطلب') }}
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-offer" type="button">
                <i class="bi bi-cash-coin me-1"></i>{{ __('تفاصيل العرض') }}
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-history" type="button">
                <i class="bi bi-clock-history me-1"></i>{{ __('سجل تغيير الحالات') }}
                <span class="badge bg-light text-dark ms-1">{{ $historyNotes->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-comments" type="button">
                <i class="bi bi-chat-left-dots me-1"></i>{{ __('التعليقات') }}
                <span class="badge bg-light text-dark ms-1">{{ $comments->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button">
                <i class="bi bi-list-check me-1"></i>{{ __('المهام') }}
                <span class="badge bg-light text-dark ms-1">{{ $booking->tasks->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ===== Tab 1: بيانات الطلب ===== --}}
        <div class="tab-pane fade show active" id="tab-data">
            <div class="row g-3 mb-3">
                {{-- بيانات العميل والتواصل --}}
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                            <h6 class="fw-bold mb-0">{{ __('بيانات العميل والتواصل') }}</h6>
                        </div>
                        <div class="card-body px-4 py-3">
                            @php
                                $orderRows = [
                                    __('اسم العميل')      => $booking->client_name,
                                    __('جوال العميل')     => $booking->client_phone,
                                    __('البريد الإلكتروني') => $booking->client_email ?: '—',
                                    __('رقم الطلب')       => '#' . $booking->id,
                                    __('تاريخ إنشاء الطلب') => $booking->created_at->format('d/m/Y • H:i'),
                                    __('نوع الطلب')       => $booking->booking_type ? (\App\Models\Booking::BOOKING_TYPES_LABELS[$booking->booking_type] ?? '—') : '—',
                                    __('الموقع الجغرافي') => $booking->location ?: '—',
                                ];
                            @endphp
                            @foreach($orderRows as $label => $value)
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ $label }}</span>
                                <span style="font-size:13px;font-weight:700;color:var(--crm-text);" dir="{{ in_array($label, [__('جوال العميل')]) ? 'ltr' : 'inherit' }}">{{ $value }}</span>
                            </div>
                            @endforeach
                            <div class="d-flex justify-content-between py-2 align-items-center">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('حالة الطلب الحالية') }}</span>
                                <span class="status-dot {{ $dotClass }}">{{ $booking->status_label }}</span>
                            </div>
                            
                            @if($booking->status === 'pending')
                            <div class="p-3 mb-3 rounded-3" style="background:#FFF9E6; border:1px solid #FFE58F;">
                                <div class="fw-bold text-warning-dark mb-1" style="font-size:13px;">
                                    <i class="bi bi-clock-history me-1"></i> {{ __('تفاصيل قيد الانتظار') }}
                                </div>
                                <div class="mb-1" style="font-size:12px;color:#5c3e00;">
                                    <strong>{{ __('سبب الانتظار') }}:</strong> {{ $booking->pending_reason }}
                                </div>
                                <div style="font-size:12px;color:#5c3e00;">
                                    <strong>{{ __('موعد المتابعة') }}:</strong> {{ $booking->follow_up_at?->format('d/m/Y H:i') }} ({{ $booking->follow_up_at?->diffForHumans() }})
                                </div>
                            </div>
                            @endif

                            @if($booking->calculatorLead && !empty($booking->calculatorLead->details))
                                @php
                                    $leadDetails = $booking->calculatorLead->details;
                                @endphp
                                <div class="p-3 mb-3 rounded-4" style="background:#F0FDF4; border:1px solid #BBF7D0;">
                                    <div class="fw-bold text-success mb-2" style="font-size:13.5px;">
                                        <i class="bi bi-wallet2 me-1"></i> {{ __('تفاصيل الملاءة المالية والائتمانية (حاسبة التمويل)') }}
                                    </div>
                                    
                                    <div class="row g-2 text-start" style="font-family:'Cairo', sans-serif;">
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('الراتب الشهري') }}:</strong> {{ number_format($leadDetails['salary'] ?? 0) }} {{ __('ريال') }}
                                        </div>
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('الالتزامات الشهريّة') }}:</strong> {{ number_format($leadDetails['monthly_obligations'] ?? 0) }} {{ __('ريال') }}
                                        </div>
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('جهة العمل') }}:</strong> 
                                            @php
                                                $empTypes = [
                                                    'government' => 'حكومي',
                                                    'semi-government' => 'شبه حكومي',
                                                    'private' => 'قطاع خاص',
                                                    'military' => 'عسكري',
                                                    'retired' => 'متقاعد',
                                                    'freelance' => 'عمل حر',
                                                ];
                                                $empKey = $leadDetails['employer_type'] ?? '';
                                                $empVal = $empTypes[$empKey] ?? $empKey ?: '—';
                                            @endphp
                                            {{ $empVal }}
                                        </div>
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('مدة الخدمة') }}:</strong> {{ $leadDetails['years_of_service'] ?? '—' }} {{ __('سنة') }}
                                        </div>
                                        
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('تمويل شخصي') }}:</strong> {{ ($leadDetails['has_personal_loan'] ?? false) ? __('نعم') : __('لا') }}
                                        </div>
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('تمويل عقاري') }}:</strong> {{ ($leadDetails['has_mortgage_loan'] ?? false) ? __('نعم') : __('لا') }}
                                        </div>
                                        
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('تعثر في سمة') }}:</strong> 
                                            <span class="{{ ($leadDetails['has_simah_default'] ?? false) ? 'text-danger fw-bold' : '' }}">
                                                {{ ($leadDetails['has_simah_default'] ?? false) ? __('نعم') : __('لا') }}
                                            </span>
                                        </div>
                                        <div class="col-6" style="font-size:12px;color:#166534;">
                                            <strong>{{ __('مخالفات مرورية') }}:</strong> {{ ($leadDetails['has_traffic_violations'] ?? false) ? __('نعم') : __('لا') }}
                                        </div>

                                        @if(!empty($leadDetails['preferred_color']))
                                        <div class="col-12 text-success mt-2 pt-2 border-top" style="font-size:12px;border-top-style:dashed!important;">
                                            <strong>{{ __('اللون المفضل للعميل') }}:</strong> {{ $leadDetails['preferred_color'] }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between py-2 align-items-center">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('الموظف المسؤول') }}</span>
                                <span style="font-size:13px;font-weight:700;color:var(--crm-text);">{{ $booking->employee->name ?? __('غير معين') }}</span>
                            </div>

                            {{-- تعيين مسؤول المبيعات --}}
                            @can('manage-bookings')
                            <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                                <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;">{{ __('إسناد المسؤول') }}</label>
                                <form action="{{ route('crm.bookings.assign', $booking) }}" method="POST" class="d-flex align-items-center gap-2 w-100">
                                    @csrf @method('PATCH')
                                    <select name="employee_id" class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;">
                                        <option value="">{{ __('غير معين') }}</option>
                                        @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $booking->assigned_to == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-2 text-white flex-shrink-0" style="background:var(--crm-text);font-size:12px;white-space:nowrap;padding: 6px 12px;">
                                        {{ __('تحويل') }}
                                    </button>
                                </form>
                            </div>

                            {{-- تغيير الحالة --}}
                            <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                                <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;">{{ __('تغيير حالة الطلب') }}</label>
                                <form action="{{ route('crm.bookings.status', $booking) }}" method="POST" class="d-flex align-items-center gap-2 w-100" id="bookingStatusForm">
                                    @csrf @method('PATCH')
                                    <select name="status" id="bookingStatusSelect" class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;" {{ ($booking->status === 'waiting_supervisor_approval' && ! auth('employee')->user()->isAdmin()) ? 'disabled' : '' }}>
                                        <optgroup label="{{ __('الحالات الأساسية (Active)') }}">
                                            @foreach($statuses as $key => $s)
                                                @if($s['group'] === 'active')
                                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }} data-group="{{ $s['group'] }}" data-close="{{ ($s['is_close'] ?? false) ? '1' : '0' }}">{{ $s['label'] }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="{{ __('الحالات الخاسرة (Closed - Lost)') }}">
                                            @foreach($statuses as $key => $s)
                                                @if($s['group'] === 'lost')
                                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }} data-group="{{ $s['group'] }}" data-close="{{ ($s['is_close'] ?? false) ? '1' : '0' }}">{{ $s['label'] }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-2 text-white flex-shrink-0" style="background:var(--crm-orange);font-size:12px;white-space:nowrap;" {{ ($booking->status === 'waiting_supervisor_approval' && ! auth('employee')->user()->isAdmin()) ? 'disabled' : '' }}>
                                        {{ __('حفظ') }}
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- بيانات السيارة وجهة التمويل --}}
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                            <h6 class="fw-bold mb-0">{{ __('بيانات السيارة وجهة التمويل') }}</h6>
                        </div>
                        <div class="card-body px-4 py-3">
                            @if($booking->car)
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('كود السيارة') }}</span>
                                <span style="font-size:13px;font-weight:700;">#{{ $booking->car->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('نوع السيارة') }}</span>
                                <span style="font-size:13px;font-weight:700;">{{ $booking->car->brand->name ?? '' }} {{ $booking->car->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('اللون المطلوب') }}</span>
                                <span style="font-size:13px;font-weight:700;">{{ $booking->car->color ?? __('غير محدد') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('سعر السيارة') }}</span>
                                <span style="font-size:13px;font-weight:700;">{{ number_format($booking->car->cash_price) }} {!! __('ريال') !!}</span>
                            </div>
                            @else
                            <div class="text-center text-muted py-3" style="font-size:13px;">{{ __('لا توجد سيارة مرتبطة بالطلب') }}</div>
                            @endif
                            <div class="d-flex justify-content-between py-2 mt-2">
                                <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('جهة التمويل') }}</span>
                                <span style="font-size:13px;font-weight:700;">{{ $booking->financingBank->name ?? __('غير محددة') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- المستندات والتصاريح --}}
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('المستندات والتصاريح') }}</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('crm.bookings.documents.store', $booking) }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="fw-bold mb-1" style="font-size:12px;">{{ __('اسم المستند (اختياري)') }}</label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="{{ __('مثال: الهوية الوطنية، تصريح المرور...') }}" style="border-radius:8px;font-size:13px;padding:8px 12px;">
                            </div>
                            <div class="col-md-5">
                                <label class="fw-bold mb-1" style="font-size:12px;">{{ __('الملف') }}</label>
                                <input type="file" name="file" class="form-control form-control-sm" required style="border-radius:8px;font-size:13px;padding:8px 12px;">
                            </div>
                            <div class="col-md-2">
                                @can('manage-bookings')
                                <button type="submit" class="btn-crm-primary w-100" style="padding:8px 16px;">
                                    <i class="bi bi-upload"></i> {{ __('رفع') }}
                                </button>
                                @endcan
                            </div>
                        </div>
                    </form>

                    @if($booking->documents && $booking->documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="border:1px solid var(--crm-border);border-radius:8px;overflow:hidden;">
                            <thead style="background:#F8F9FC;">
                                <tr>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);">{{ __('المستند') }}</th>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);">{{ __('بواسطة') }}</th>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);">{{ __('التاريخ') }}</th>
                                    <th class="py-2 px-3 text-muted fw-bold text-end" style="font-size:12px;border-bottom:1px solid var(--crm-border);">{{ __('إجراء') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->documents as $doc)
                                <tr>
                                    <td class="px-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;background:#F1F5F9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#475467;">
                                                @if(in_array(strtolower($doc->file_type), ['png','jpg','jpeg','gif']))
                                                    <i class="bi bi-file-image fs-5"></i>
                                                @elseif(strtolower($doc->file_type) == 'pdf')
                                                    <i class="bi bi-file-pdf fs-5" style="color:var(--crm-red);"></i>
                                                @else
                                                    <i class="bi bi-file-earmark fs-5"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $doc->title }}</div>
                                                <div style="font-size:11px;color:var(--crm-text-muted);">.{{ strtoupper($doc->file_type) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:12px;color:var(--crm-text-muted);">{{ $doc->employee->name ?? __('النظام') }}</td>
                                    <td style="font-size:12px;color:var(--crm-text-muted);">{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-end px-3">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-light rounded-2 text-primary" title="{{ __('عرض') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @can('manage-bookings')
                                            <form action="{{ route('crm.bookings.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('{{ __('هل تريد حذف هذا المستند؟') }}')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);" title="{{ __('حذف') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 opacity-50">
                        <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                        <p class="mb-0 small">{{ __('لا توجد مستندات مرفوعة بعد') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== Tab 2: تفاصيل العرض ===== --}}
        <div class="tab-pane fade" id="tab-offer">
            <div class="card border-0 shadow-sm rounded-4 mb-3" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('تفاصيل العرض') }}</h6>
                </div>
                <div class="card-body px-4 py-3">
                    @php
                        $commission = $booking->monthly_installment * 0.035;
                        $delivery   = 125;
                        $total      = $booking->monthly_installment + $commission + $delivery;
                    @endphp
                    <div class="row g-0">
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('سعر السيارة') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ number_format($booking->car->cash_price ?? $booking->total_price) }} {!! __('ريال') !!}</div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('الدفعة الأولى') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ number_format($booking->down_payment) }} {!! __('ريال') !!}</div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('مدة التمويل') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ $booking->duration_years }} {{ __('سنوات') }}</div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('القسط الشهري') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ number_format($booking->monthly_installment) }} {!! __('ريال') !!}</div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('الدفعة الأخيرة') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ $booking->balloon_payment ? number_format($booking->balloon_payment) . ' ' . __('ريال') : '—' }}</div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('جهة التمويل') }}</div>
                            <div style="font-size:14px;font-weight:800;">{{ $booking->financingBank->name ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="row g-0 mt-2">
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                            <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('عمولة البنك') }} (3.5%)</span>
                            <span style="font-size:13px;font-weight:700;">{{ number_format($commission) }} {!! __('ريال') !!}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                            <span style="font-size:13px;color:var(--crm-text-muted);">{{ __('رسوم التوصيل') }}</span>
                            <span style="font-size:13px;font-weight:700;">{{ number_format($delivery) }} {!! __('ريال') !!}</span>
                        </div>
                        <div class="d-flex justify-content-between py-3">
                            <span style="font-size:14px;font-weight:800;color:var(--crm-text);">{{ __('الإجمالي') }}</span>
                            <span style="font-size:14px;font-weight:900;color:var(--crm-orange-dark);">{{ number_format($total) }} {!! __('ريال') !!}</span>
                        </div>
                    </div>

                    @if($booking->offer_notes)
                    <div class="mt-2 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;">{{ __('تفاصيل العرض المرسل للعميل') }}</div>
                        <div style="font-size:13px;font-weight:600;white-space:pre-line;">{{ $booking->offer_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- تعديل العرض --}}
            @can('manage-bookings')
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('تعديل تفاصيل العرض') }}</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <form action="{{ route('crm.bookings.offer', $booking) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="font-size:12px;">{{ __('جهة التمويل') }}</label>
                                <select name="calculator_bank_id" class="form-select form-select-sm" style="border-radius:8px;">
                                    <option value="">{{ __('— اختر —') }}</option>
                                    @foreach($calculatorBanks as $bank)
                                    <option value="{{ $bank->id }}" {{ $booking->calculator_bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="font-size:12px;">{{ __('الدفعة الأخيرة') }}</label>
                                <input type="number" name="balloon_payment" min="0" value="{{ $booking->balloon_payment }}" class="form-control form-control-sm" style="border-radius:8px;">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn-crm-primary w-100" style="padding:8px 16px;">{{ __('حفظ') }}</button>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold" style="font-size:12px;">{{ __('تفاصيل العرض المرسل للعميل') }}</label>
                                <textarea name="offer_notes" rows="3" class="form-control form-control-sm" style="border-radius:8px;">{{ $booking->offer_notes }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>

        {{-- ===== Tab 3: سجل تغيير الحالات ===== --}}
        <div class="tab-pane fade" id="tab-history">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('سجل تغيير الحالات') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div style="position:relative;padding-{{ app()->getLocale()=='ar'?'right':'left' }}:20px;border-{{ app()->getLocale()=='ar'?'right':'left' }}:2px solid var(--crm-border);">
                        @forelse($historyNotes as $note)
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <div class="position-absolute" style="{{ app()->getLocale()=='ar'?'right':'left' }}:-9px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid var(--crm-blue);"></div>
                            <div class="flex-grow-1">
                                <div class="p-3 rounded-3 border" style="background:#fff;border-color:var(--crm-border)!important;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="status-dot planned" style="font-size:11px;">{{ \App\Models\Booking::STATUSES[$note->old_status]['label'] ?? $note->old_status }}</span>
                                        <i class="bi bi-arrow-{{ app()->getLocale()=='ar'?'left':'right' }}"></i>
                                        <span class="status-dot done" style="font-size:11px;">{{ \App\Models\Booking::STATUSES[$note->new_status]['label'] ?? $note->new_status }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border" style="font-size:11px;font-weight:600;">{{ $note->employee->name ?? __('النظام') }}</span>
                                        <span style="font-size:11px;color:var(--crm-text-muted);"><i class="bi bi-clock me-1"></i>{{ $note->created_at->format('d/m/Y H:i') }} — {{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            <p class="mb-0 small">{{ __('لا توجد تغييرات في الحالة بعد') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Tab 4: التعليقات ===== --}}
        <div class="tab-pane fade" id="tab-comments">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('التعليقات') }}</h6>
                </div>
                <div class="card-body p-4">
                    @can('manage-bookings')
                    <form action="{{ route('crm.bookings.note', $booking) }}" method="POST" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        @csrf
                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <label class="fw-bold mb-1" style="font-size:12px;">{{ __('إضافة تعليق جديد') }}</label>
                                <textarea name="note" rows="2" required placeholder="{{ __('اكتب تعليقاً...') }}"
                                          style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:10px 14px;font-size:13px;font-family:'Cairo',sans-serif;outline:none;resize:none;"></textarea>
                            </div>
                            <div>
                                <select name="type" style="border:1px solid var(--crm-border);border-radius:8px;padding:9px 12px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;margin-bottom:4px;display:block;">
                                    <option value="note">📌 {{ __('ملاحظة') }}</option>
                                    <option value="call">📞 {{ __('مكالمة') }}</option>
                                </select>
                                <button type="submit" class="btn-crm-primary w-100" style="padding:9px 16px;">{{ __('إضافة') }}</button>
                            </div>
                        </div>
                    </form>
                    @endcan

                    <div style="position:relative;padding-{{ app()->getLocale()=='ar'?'right':'left' }}:20px;border-{{ app()->getLocale()=='ar'?'right':'left' }}:2px solid var(--crm-border);">
                        @forelse($comments as $note)
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <div class="position-absolute" style="{{ app()->getLocale()=='ar'?'right':'left' }}:-9px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid {{ $note->type === 'call' ? '#12B76A' : 'var(--crm-orange)' }};"></div>
                            <div class="flex-grow-1">
                                <div class="p-3 rounded-3 border" style="background:#fff;border-color:var(--crm-border)!important;">
                                    <p class="mb-2" style="font-size:13px;font-weight:600;color:var(--crm-text);">
                                        {{ $note->type === 'call' ? '📞 ' : '📌 ' }}{{ $note->note }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border" style="font-size:11px;font-weight:600;">{{ $note->employee->name ?? __('النظام') }}</span>
                                        <span style="font-size:11px;color:var(--crm-text-muted);"><i class="bi bi-clock me-1"></i>{{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-chat-left-dots fs-1 d-block mb-2"></i>
                            <p class="mb-0 small">{{ __('لا توجد تعليقات بعد') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Tab 5: المهام ===== --}}
        <div class="tab-pane fade" id="tab-tasks">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('المهام الخاصة بالطلب') }}</h6>
                    @can('manage-tasks')
                    <button class="btn-crm-primary" style="padding:7px 14px;" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="bi bi-plus-lg"></i> {{ __('إضافة مهمة جديدة') }}
                    </button>
                    @endcan
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @forelse($booking->tasks as $task)
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background:#F8F9FC;border:1px solid var(--crm-border);border-{{ app()->getLocale()=='ar'?'right':'left' }}:3px solid {{ $task->priority === 'high' ? '#DC2626' : ($task->priority === 'medium' ? 'var(--crm-orange)' : 'var(--crm-green)') }};">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <strong style="font-size:13px;{{ $task->status === 'done' ? 'text-decoration:line-through;opacity:0.6;' : '' }}">{{ $task->title }}</strong>
                                    <span class="status-dot {{ $taskDot($task) }}">{{ $task->display_status_label }}</span>
                                </div>
                                @if($task->description)
                                <p class="mb-2" style="font-size:12px;color:var(--crm-text-muted);">{{ Str::limit($task->description, 90) }}</p>
                                @endif
                                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:11px;color:var(--crm-text-muted);">
                                    @if($task->assignedTo)
                                    <span><i class="bi bi-person-circle me-1"></i>{{ $task->assignedTo->name }}</span>
                                    @endif
                                    @if($task->due_date)
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                                @can('manage-tasks')
                                <div class="d-flex gap-1 flex-wrap">
                                    @if($task->status !== 'done')
                                    <form action="{{ route('crm.tasks.complete', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm rounded-2 fw-bold text-white" style="font-size:11px;background:var(--crm-green);"><i class="bi bi-check-lg"></i> {{ __('إنهاء') }}</button>
                                    </form>
                                    <form action="{{ route('crm.tasks.postpone', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="days" value="1">
                                        <button class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;"><i class="bi bi-clock-history"></i> {{ __('تأجيل') }}</button>
                                    </form>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;"
                                            data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                            data-task-url="{{ route('crm.tasks.update', $task) }}"
                                            data-task-title="{{ $task->title }}"
                                            data-task-description="{{ $task->description }}"
                                            data-task-priority="{{ $task->priority }}"
                                            data-task-status="{{ $task->status }}"
                                            data-task-due="{{ $task->due_date?->format('Y-m-d') }}"
                                            data-task-assigned="{{ $task->assigned_to }}">
                                        <i class="bi bi-pencil"></i> {{ __('تعديل') }}
                                    </button>
                                    <form action="{{ route('crm.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('{{ __('حذف هذه المهمة؟') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light rounded-2" style="font-size:11px;color:var(--crm-red);"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                                @endcan
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5 opacity-50">
                            <i class="bi bi-list-check fs-1 d-block mb-2"></i>
                            <p class="mb-0 small">{{ __('لا توجد مهام مرتبطة بهذا الطلب') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== Add Task Modal ===== --}}
    @can('manage-tasks')
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('إضافة مهمة جديدة') }}</h5>
                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('crm.tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('عنوان المهمة') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3" required placeholder="{{ __('أدخل عنوان المهمة...') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('الوصف') }}</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="{{ __('تفاصيل المهمة...') }}"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('الأولوية') }}</label>
                                <select name="priority" class="form-select rounded-3">
                                    <option value="low">{{ __('منخفضة') }}</option>
                                    <option value="medium" selected>{{ __('متوسطة') }}</option>
                                    <option value="high">{{ __('عالية') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('الحالة') }}</label>
                                <select name="status" class="form-select rounded-3">
                                    <option value="new">{{ __('جديدة') }}</option>
                                    <option value="in_progress">{{ __('قيد التنفيذ') }}</option>
                                    <option value="done">{{ __('مكتملة') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('تاريخ الاستحقاق') }}</label>
                                <input type="date" name="due_date" class="form-control rounded-3">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('إسناد إلى') }}</label>
                                <select name="assigned_to" class="form-select rounded-3">
                                    <option value="">{{ __('— اختر موظفاً —') }}</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn-crm-light" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn-crm-primary">{{ __('إضافة المهمة') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Edit Task Modal ===== --}}
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('تعديل المهمة') }}</h5>
                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTaskForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('عنوان المهمة') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="editTaskTitle" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('الوصف') }}</label>
                            <textarea name="description" id="editTaskDescription" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('الأولوية') }}</label>
                                <select name="priority" id="editTaskPriority" class="form-select rounded-3">
                                    <option value="low">{{ __('منخفضة') }}</option>
                                    <option value="medium">{{ __('متوسطة') }}</option>
                                    <option value="high">{{ __('عالية') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('الحالة') }}</label>
                                <select name="status" id="editTaskStatus" class="form-select rounded-3">
                                    <option value="new">{{ __('جديدة') }}</option>
                                    <option value="in_progress">{{ __('قيد التنفيذ') }}</option>
                                    <option value="done">{{ __('مكتملة') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('تاريخ الاستحقاق') }}</label>
                                <input type="date" name="due_date" id="editTaskDue" class="form-control rounded-3">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">{{ __('إسناد إلى') }}</label>
                                <select name="assigned_to" id="editTaskAssigned" class="form-select rounded-3">
                                    <option value="">{{ __('— اختر موظفاً —') }}</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn-crm-light" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn-crm-primary">{{ __('حفظ التعديلات') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Pending Details Modal -->
    <div class="modal fade" id="pendingStatusModal" tabindex="-1" aria-labelledby="pendingStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="pendingStatusModalLabel">{{ __('تفاصيل قيد الانتظار') }}</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('crm.bookings.status', $booking) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="pending">
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('سبب الانتظار') }} <span class="text-danger">*</span></label>
                            <input type="text" name="pending_reason" class="form-control bg-light border-0 shadow-none" required style="border-radius:10px; font-size:14px; padding:10px 14px;" placeholder="{{ __('مثال: العميل خارج المملكة، انتظار الراتب...') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('موعد وتاريخ إعادة المتابعة') }} <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="follow_up_at" class="form-control bg-light border-0 shadow-none" required style="border-radius:10px; font-size:14px; padding:10px 14px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('ملاحظة تفصيلية') }} <span class="text-danger">*</span></label>
                            <textarea name="note" class="form-control bg-light border-0 shadow-none" rows="3" required style="border-radius:10px; font-size:14px;" placeholder="{{ __('اكتب هنا تفاصيل إضافية للمتابعة...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-primary py-2 px-3 fw-bold rounded-3">{{ __('حفظ وتعديل') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Close Approval Modal -->
    <div class="modal fade" id="requestCloseModal" tabindex="-1" aria-labelledby="requestCloseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="requestCloseModalLabel">{{ __('طلب إغلاق الطلب') }}</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('crm.bookings.status', $booking) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" id="requestCloseTargetStatus">
                    <div class="modal-body py-3">
                        <div class="alert alert-warning border-0 rounded-3 text-warning-dark" style="font-size: 13px;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            {{ __('سيتم إرسال هذا الطلب إلى المشرف للاعتماد. لا يمكنك إغلاق الطلب مباشرة.') }}
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">{{ __('المرحلة المطلوبة') }}</label>
                            <input type="text" id="requestCloseTargetLabel" class="form-control bg-light border-0 shadow-none fw-bold" readonly style="border-radius:10px; font-size:14px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('ملاحظة أو تبرير الإغلاق للمشرف') }} <span class="text-danger">*</span></label>
                            <textarea name="note" class="form-control bg-light border-0 shadow-none" rows="3" required style="border-radius:10px; font-size:14px;" placeholder="{{ __('اكتب هنا تبرير الإغلاق أو الملاحظات...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-primary py-2 px-3 fw-bold rounded-3">{{ __('إرسال الطلب') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection

@section('scripts')
<script>
window.onbeforeprint = () => document.title = 'طلب #{{ $booking->id }} — Zad Capital';

document.getElementById('editTaskModal')?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    const form = document.getElementById('editTaskForm');
    form.action = btn.getAttribute('data-task-url');
    document.getElementById('editTaskTitle').value = btn.getAttribute('data-task-title') || '';
    document.getElementById('editTaskDescription').value = btn.getAttribute('data-task-description') || '';
    document.getElementById('editTaskPriority').value = btn.getAttribute('data-task-priority') || 'medium';
    document.getElementById('editTaskStatus').value = btn.getAttribute('data-task-status') || 'new';
    document.getElementById('editTaskDue').value = btn.getAttribute('data-task-due') || '';
    document.getElementById('editTaskAssigned').value = btn.getAttribute('data-task-assigned') || '';
});

document.addEventListener('DOMContentLoaded', function() {
    const statusForm = document.getElementById('bookingStatusForm');
    const statusSelect = document.getElementById('bookingStatusSelect');
    if (statusForm && statusSelect) {
        statusForm.addEventListener('submit', function(e) {
            const selectedOpt = statusSelect.options[statusSelect.selectedIndex];
            const targetStatus = statusSelect.value;
            const isClose = selectedOpt.getAttribute('data-close') === '1';
            const isAdmin = {{ auth('employee')->user()->isAdmin() ? 'true' : 'false' }};

            if (targetStatus === 'pending') {
                e.preventDefault();
                const modal = new bootstrap.Modal(document.getElementById('pendingStatusModal'));
                modal.show();
            } else if (isClose && !isAdmin) {
                e.preventDefault();
                document.getElementById('requestCloseTargetStatus').value = targetStatus;
                document.getElementById('requestCloseTargetLabel').value = selectedOpt.text;
                const modal = new bootstrap.Modal(document.getElementById('requestCloseModal'));
                modal.show();
            }
        });
    }
});
</script>
@endsection
