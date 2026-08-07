@extends('partials.Layouts.crm-master')
@section('title', __('شاشة الموظف') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    @php
        $rangeLinks = [
            'today' => __('اليوم'),
            'week'  => __('هذا الأسبوع'),
            'month' => __('هذا الشهر'),
            'year'  => __('هذا العام'),
            'ytd'   => __('بداية السنة حتى اليوم'),
        ];
        $qs = fn (array $override = []) => array_filter(array_merge(request()->except('page'), $override), fn ($v) => $v !== null && $v !== '');
        $dotClassFor = fn ($status) => match($status) {
            'new','pending'  => 'planned',
            'in_progress','contacted' => 'waiting',
            'sold','done'    => 'done',
            'rejected'       => 'late',
            default          => 'confirmed',
        };
        $priorityColor = fn ($p) => match($p) {
            'high' => '#DC2626',
            'medium' => 'var(--crm-orange)',
            default => 'var(--crm-green)',
        };
    @endphp

    {{-- ===== Employee Header Banner ===== --}}
    <div class="rounded-4 mb-4 p-4 p-md-4" style="background:linear-gradient(135deg,#14234d 0%,#091842 100%);box-shadow:0 8px 20px rgba(249,115,22,0.25);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-1 fw-bold text-white">👋 {{ __('أهلاً بك') }}, {{ auth()->user()?->name }}</h4>
                <div class="text-white" style="font-size:13px;opacity:0.9;">{{ now()->translatedFormat('l، d F Y') }}</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @can('manage-bookings')
                <a href="{{ route('crm.bookings.index') }}" class="btn btn-sm rounded-3 fw-bold" style="background:rgba(255,255,255,0.18);color:#fff;padding:9px 16px;">
                    <i class="bi bi-plus-lg"></i> {{ __('إضافة حجز جديد') }}
                </a>
                @endcan
                @can('manage-leads')
                <a href="{{ route('crm.leads.index') }}" class="btn btn-sm rounded-3 fw-bold" style="background:#fff;color:var(--crm-orange-dark);padding:9px 16px;">
                    <i class="bi bi-person-plus"></i> {{ __('إضافة عميل') }}
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ===== شريط الإحصائيات العلوي ===== --}}
    <div class="d-flex gap-3 mb-4 pb-1" style="overflow-x:auto;">
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon blue"><i class="bi bi-bag"></i></div>
            <div class="stat-lbl">{{ __('إجمالي الطلبات') }}</div>
            <div class="stat-val">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-lbl">{{ __('الطلبات المفتوحة') }}</div>
            <div class="stat-val">{{ number_format($stats['open']) }}</div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
            <div class="stat-lbl">{{ __('الطلبات المغلقة') }}</div>
            <div class="stat-val">{{ number_format($stats['closed']) }}</div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div class="stat-lbl">{{ __('الطلبات المكتملة (تم الاستلام)') }}</div>
            <div class="stat-val">{{ number_format($stats['completed']) }}</div>
        </div>
    </div>

    {{-- ===== البحث والفلترة ===== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-body p-4">
            <form action="{{ route('crm.dashboard') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                {{-- فترات سريعة --}}
                @foreach($rangeLinks as $key => $label)
                    <a href="{{ route('crm.dashboard', $qs(['range' => $key, 'from' => null, 'to' => null])) }}"
                       class="crm-filter-tab {{ $range === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('crm.dashboard', $qs(['range' => null, 'from' => null, 'to' => null])) }}"
                   class="crm-filter-tab {{ $range === 'all' ? 'active' : '' }}">{{ __('الكل') }}</a>

                <span class="mx-1" style="width:1px;height:24px;background:var(--crm-border);display:inline-block;"></span>

                {{-- فترة مخصصة --}}
                <input type="hidden" name="range" value="custom">
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" style="width:150px;border-radius:8px;">
                <span class="text-muted small">{{ __('إلى') }}</span>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm" style="width:150px;border-radius:8px;">
                @foreach(request()->except(['from','to','range','page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <button type="submit" class="btn-crm-primary" style="padding:7px 16px;">{{ __('تطبيق') }}</button>
            </form>

            <hr style="border-color:var(--crm-border);">

            {{-- بحث --}}
            <form action="{{ route('crm.dashboard') }}" method="GET" class="d-flex gap-2">
                @foreach(request()->except(['search','page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="position-relative flex-grow-1" style="max-width:480px;">
                    <i class="bi bi-search position-absolute" style="{{ app()->getLocale()=='ar'?'right':'left' }}:14px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('ابحث برقم الطلب، اسم العميل أو رقم الجوال...') }}"
                           class="form-control" style="padding-{{ app()->getLocale()=='ar'?'right':'left' }}:38px;border-radius:10px;background:#F8F9FC;border:1px solid var(--crm-border);">
                </div>
                <button type="submit" class="btn-crm-light">{{ __('بحث') }}</button>
                @if($search)
                <a href="{{ route('crm.dashboard', $qs(['search' => null])) }}" class="btn-crm-light">{{ __('مسح') }}</a>
                @endif
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- ===== المهام اليومية ===== --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('المهام اليومية') }}</h6>
                    <a href="{{ route('crm.tasks.index') }}" class="fw-bold text-decoration-none" style="font-size:12px;color:var(--crm-text-muted);">{{ __('عرض كل المهام') }}</a>
                </div>
                <div class="card-body p-3">
                    <ul class="nav nav-pills gap-2 mb-3 px-1" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-today" type="button">
                                {{ __('المستحقة اليوم') }} <span class="badge bg-light text-dark ms-1">{{ $tasksToday->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-overdue" type="button">
                                {{ __('المتأخرة') }} <span class="badge bg-light text-dark ms-1">{{ $tasksOverdue->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-upcoming" type="button">
                                {{ __('القادمة') }} <span class="badge bg-light text-dark ms-1">{{ $tasksUpcoming->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" style="max-height:360px;overflow-y:auto;">
                        @foreach(['tasks-today' => $tasksToday, 'tasks-overdue' => $tasksOverdue, 'tasks-upcoming' => $tasksUpcoming] as $paneId => $taskList)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $paneId }}">
                            @forelse($taskList as $task)
                            <div class="p-3 rounded-3 mb-2" style="background:#F8F9FC;border:1px solid var(--crm-border);border-{{ app()->getLocale()=='ar'?'right':'left' }}:3px solid {{ $priorityColor($task->priority) }};">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <strong style="font-size:13px;">{{ $task->title }}</strong>
                                    @if($task->due_date)
                                    <span class="text-nowrap" style="font-size:11px;color:var(--crm-text-muted);">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex gap-1 flex-wrap">
                                    @can('manage-tasks')
                                    <form action="{{ route('crm.tasks.start', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;background:var(--crm-red-light);color:var(--crm-red);" title="{{ __('تنفيذ') }}">
                                            <i class="bi bi-play-fill"></i> {{ __('تنفيذ') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('crm.tasks.postpone', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="days" value="1">
                                        <button class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;" title="{{ __('تأجيل يوم') }}">
                                            <i class="bi bi-clock-history"></i> {{ __('تأجيل') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('crm.tasks.complete', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm rounded-2 fw-bold text-white" style="font-size:11px;background:var(--crm-green);" title="{{ __('إنهاء') }}">
                                            <i class="bi bi-check-lg"></i> {{ __('إنهاء') }}
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4" style="font-size:13px;">{{ __('لا توجد مهام') }}</div>
                            @endforelse
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== الطلبات ===== --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0">{{ __('الطلبات') }}</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('crm.dashboard', $qs(['sort' => 'priority'])) }}" class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;{{ $sort==='priority' ? 'background:var(--crm-red);color:#fff;' : 'background:#F8F9FC;color:var(--crm-text-muted);' }}">{{ __('الأولوية') }}</a>
                        <a href="{{ route('crm.dashboard', $qs(['sort' => 'recent'])) }}" class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;{{ $sort==='recent' ? 'background:var(--crm-red);color:#fff;' : 'background:#F8F9FC;color:var(--crm-text-muted);' }}">{{ __('آخر تحديث') }}</a>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height:440px;overflow-y:auto;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#F8F9FC;">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">{{ __('رقم الطلب') }}</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('العميل') }}</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('السيارة') }}</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;">{{ __('الحالة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $booking)
                                <tr>
                                    <td class="px-4 fw-bold" style="font-size:13px;">
                                        <a href="{{ route('crm.bookings.show', $booking) }}" class="text-decoration-none" style="color:var(--crm-text);">#{{ $booking->id }}</a>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="font-size:13px;color:var(--crm-text);">{{ $booking->client_name }}</div>
                                        <small class="text-muted">{{ $booking->client_phone }}</small>
                                    </td>
                                    <td style="font-size:12px;color:var(--crm-text);">{{ $booking->car->name ?? '—' }}</td>
                                    <td><span class="status-dot {{ $dotClassFor($booking->status) }}">{{ $booking->status_label }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-5">{{ __('لا توجد طلبات') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-4 py-3" style="border-top:1px solid var(--crm-border);">
                    <a href="{{ route('crm.bookings.index') }}" class="text-decoration-none fw-bold" style="font-size:13px;color:var(--crm-red);">{{ __('عرض كل الطلبات') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== التنبيهات ===== --}}
    <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0">{{ __('التنبيهات') }}</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                {{-- مهام مستحقة --}}
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#FFF8EC;border:1px solid #FDEBC8;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-clock-history" style="color:var(--crm-orange);"></i>
                            <strong style="font-size:13px;">{{ __('مهام مستحقة') }}</strong>
                        </div>
                        @forelse($alerts['tasks'] as $task)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #FDEBC8;">
                            <span style="font-size:12px;">{{ Str::limit($task->title, 28) }}</span>
                            <a href="{{ route('crm.tasks.index') }}" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-orange-dark);">{{ __('فتح') }}</a>
                        </div>
                        @empty
                        <div class="text-muted text-center py-2" style="font-size:12px;">{{ __('لا توجد تنبيهات') }}</div>
                        @endforelse
                    </div>
                </div>

                {{-- طلبات بانتظار المتابعة --}}
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#EFF8FF;border:1px solid #D6E9FF;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-telephone-outbound" style="color:var(--crm-blue);"></i>
                            <strong style="font-size:13px;">{{ __('طلبات بانتظار المتابعة') }}</strong>
                        </div>
                        @forelse($alerts['follow_ups'] as $booking)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #D6E9FF;">
                            <span style="font-size:12px;">{{ $booking->client_name }}</span>
                            <a href="{{ route('crm.bookings.show', $booking) }}" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-blue);">{{ __('عرض') }}</a>
                        </div>
                        @empty
                        <div class="text-muted text-center py-2" style="font-size:12px;">{{ __('لا توجد تنبيهات') }}</div>
                        @endforelse
                    </div>
                </div>

                {{-- طلبات بانتظار اعتماد المشرف --}}
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#FFF0F0;border:1px solid #FFCDD2;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-patch-check" style="color:var(--crm-red);"></i>
                            <strong style="font-size:13px;">{{ __('طلبات بانتظار اعتماد المشرف') }}</strong>
                        </div>
                        @forelse($alerts['approvals'] as $booking)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #FFCDD2;">
                            <span style="font-size:12px;">#{{ $booking->id }} — {{ $booking->client_name }}</span>
                            <a href="{{ route('crm.bookings.show', $booking) }}" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-red);">{{ __('عرض') }}</a>
                        </div>
                        @empty
                        <div class="text-muted text-center py-2" style="font-size:12px;">{{ __('لا توجد تنبيهات') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
