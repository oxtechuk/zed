@extends('partials.Layouts.crm-master')
@section('title', __('تتبع الحالات') . ' | AutoCRM')

@php
    // Map of statuses to sub-groups for cleaner Kanban dashboard filtering
    $subgroups = [
        'new' => 'contact',
        'contacted_no_answer' => 'contact',
        'recontact_client' => 'contact',
        'pending' => 'contact',

        'waiting_documents' => 'processing',
        'bank_review' => 'processing',

        'approved' => 'approval',
        'authorized' => 'approval',
        'received' => 'approval',
        'waiting_supervisor_approval' => 'approval',

        'lost_no_answer' => 'no_response',
        'lost_no_response' => 'no_response',
        'lost_wrong_info' => 'no_response',

        'lost_offer_not_suitable' => 'client_request',
        'lost_client_cancelled' => 'client_request',
        'lost_cancelled_after_approval' => 'client_request',

        'lost_rejected_high_liabilities' => 'bank_rejection',
        'lost_rejected_simah' => 'bank_rejection',
        'lost_rejected_finance_terms' => 'bank_rejection',
    ];
@endphp

@section('css')
<style>
    .kanban-container {
        overflow-x: auto;
        padding-bottom: 20px;
        margin-right: -15px;
        margin-left: -15px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .kanban-board { 
        display: flex; 
        gap: 20px; 
        align-items: start; 
        min-width: max-content;
    }
    .kanban-col { 
        background: #f8f9fa; 
        border-radius: 20px; 
        width: 310px;
        flex-shrink: 0;
        min-height: 75vh; 
        display: flex; 
        flex-direction: column;
        border: 1px solid #edf2f7;
        transition: all 0.2s ease-in-out;
    }
    .kanban-col-header { 
        padding: 20px; 
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        border-bottom: 1px solid #edf2f7;
    }
    .kanban-col-title { 
        font-weight: 800; 
        font-size: 14px; 
        color: #2d3748; 
    }
    .kanban-col-count { 
        background: #fff; 
        border-radius: 10px; 
        font-size: 12px; 
        font-weight: 800; 
        color: #4a5568; 
        padding: 4px 12px; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .kanban-col-body { 
        padding: 15px; 
        display: flex; 
        flex-direction: column; 
        gap: 12px; 
        flex-grow: 1;
        max-height: 70vh;
        overflow-y: auto;
    }
    /* Style scrollbar for column body */
    .kanban-col-body::-webkit-scrollbar { width: 4px; }
    .kanban-col-body::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }

    .kanban-card { 
        background: #fff; 
        border-radius: 15px; 
        padding: 18px; 
        border: 1px solid #edf2f7; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        cursor: pointer; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .kanban-card:hover { 
        box-shadow: 0 10px 20px rgba(0,0,0,0.06); 
        transform: translateY(-5px); 
        border-color: var(--crm-red);
    }
    .kanban-card-id { 
        font-size: 11px; 
        font-weight: 800; 
        color: #a0aec0; 
        margin-bottom: 8px; 
        display: block;
    }
    .kanban-card-name { 
        font-size: 15px; 
        font-weight: 800; 
        color: #1a202c; 
        margin-bottom: 6px; 
    }
    .kanban-card-car {
        font-size: 13px;
        color: #718096;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .kanban-card-footer { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px dashed #edf2f7;
    }
    .kanban-card-time { 
        font-size: 11px; 
        color: #a0aec0;
        font-weight: 600;
    }
    .kanban-card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .kanban-note-badge {
        font-size: 10px;
        font-weight: 800;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 6px;
    }
    .kanban-avatar { 
        width: 28px; 
        height: 28px; 
        border-radius: 10px; 
        background: var(--crm-red); 
        color: #fff; 
        font-size: 11px; 
        font-weight: 800; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border: 2px solid #fff; 
    }
    
    .nav-pills .nav-link {
        font-size: 14px;
        color: #64748b;
        background-color: #f1f5f9;
        transition: all 0.3s;
    }
    .nav-pills .nav-link.active {
        background-color: var(--crm-red) !important;
        color: #fff !important;
    }
    .nav-pills .nav-link:not(.active):hover {
        background-color: #cbd5e1 !important;
        color: #1e293b !important;
    }

    /* Sub-pills styles */
    .sub-pill-btn {
        transition: all 0.2s ease-in-out;
        font-size: 13px !important;
        padding: 6px 16px !important;
    }
    .active-sub-pill {
        background-color: var(--crm-text) !important;
        color: #fff !important;
        border: 1px solid var(--crm-text) !important;
    }
    .inactive-sub-pill {
        background-color: #f8f9fa !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
    }
    .inactive-sub-pill:hover {
        background-color: #e2e8f0 !important;
        color: #1e293b !important;
    }

    /* Style container scrollbar */
    .kanban-container::-webkit-scrollbar { height: 8px; }
    .kanban-container::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
</style>
@endsection

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="mb-4">
        <h4 class="mb-1 fw-bold">{{ __('تتبع مسار العمل') }}</h4>
        <p class="text-muted mb-0 small">{{ __('متابعة حالة الطلبات من الاستلام حتى التنفيذ النهائي') }}</p>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('crm.tracking.index') }}" class="mb-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    {{-- Assigned employee --}}
                    <select name="assigned_to" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:165px;">
                        <option value="">{{ __('الموظف المسؤول — الكل') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('assigned_to') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>

                    {{-- Car --}}
                    <select name="car_id" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value="">{{ __('السيارة — الكل') }}</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ request('car_id') == $car->id ? 'selected' : '' }}>{{ $car->brand?->name }} {{ $car->name }}</option>
                        @endforeach
                    </select>

                    {{-- Booking Type --}}
                    <select name="booking_type" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value="">{{ __('نوع الطلب — الكل') }}</option>
                        @foreach(\App\Models\Booking::BOOKING_TYPES_LABELS as $key => $label)
                            <option value="{{ $key }}" {{ request('booking_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    {{-- Source --}}
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value="">{{ __('المصدر — الكل') }}</option>
                        @foreach($sources as $src)
                            <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endforeach
                    </select>

                    {{-- Date From --}}
                    <div style="position:relative;">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" lang="en"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; width: 170px; min-width: 170px;" placeholder="{{ __('من تاريخ') }}">
                        <i class="bi bi-calendar3" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:10px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);pointer-events:none;"></i>
                    </div>

                    {{-- Date To --}}
                    <div style="position:relative;">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" lang="en"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; width: 170px; min-width: 170px;" placeholder="{{ __('إلى تاريخ') }}">
                        <i class="bi bi-calendar3" style="position:absolute;{{ app()->getLocale()=='ar'?'left':'right' }}:10px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);pointer-events:none;"></i>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm py-2 px-3 fw-bold rounded-2">{{ __('تصفية') }}</button>
                    <a href="{{ route('crm.tracking.index') }}" class="fw-bold text-decoration-none small ms-2" style="color:var(--crm-red);">{{ __('حذف الفلاتر') }}</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Tabs to switch groups --}}
    <ul class="nav nav-pills mb-4 gap-2" id="kanbanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-2.5 fw-bold rounded-3 shadow-xs border-0" id="active-tab" data-bs-toggle="pill" data-bs-target="#active-columns" type="button" role="tab" aria-selected="true">
                <i class="bi bi-play-circle me-1"></i>
                {{ __('الحالات الأساسية (Active)') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2.5 fw-bold rounded-3 shadow-xs border-0" id="lost-tab" data-bs-toggle="pill" data-bs-target="#lost-columns" type="button" role="tab" aria-selected="false">
                <i class="bi bi-x-circle me-1"></i>
                {{ __('الحالات الخاسرة (Closed - Lost)') }}
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="kanbanTabContent">
        {{-- Active Group Tab Pane --}}
        <div class="tab-pane fade show active" id="active-columns" role="tabpanel" aria-labelledby="active-tab">
            
            {{-- Active Sub-groups Navigation Pills --}}
            <div class="sub-pills-container mb-3 d-flex gap-2 flex-wrap" id="activeSubPills">
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn active-sub-pill" data-subgroup="all">
                    {{ __('الكل') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="contact">
                    <i class="bi bi-chat-text me-1"></i>{{ __('التواصل والتنسيق') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="processing">
                    <i class="bi bi-file-earmark-check me-1"></i>{{ __('الدراسة والمعاملة') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="approval">
                    <i class="bi bi-check-all me-1"></i>{{ __('الموافقة والتنفيذ') }}
                </button>
            </div>

            <div class="kanban-container">
                <div class="kanban-board">
                    @foreach($columns as $key => $col)
                        @if($col['group'] === 'active')
                        <div class="kanban-col" data-subgroup="{{ $subgroups[$key] ?? '' }}">
                            <div class="kanban-col-header" style="border-top: 4px solid {{ $col['color'] }}">
                                <span class="kanban-col-title">{{ __($col['label']) }}</span>
                                <span class="kanban-col-count">{{ $col['count'] }}</span>
                            </div>
                            <div class="kanban-col-body">
                                @forelse($col['items'] as $booking)
                                    @include('crm.tracking.partials.card', ['booking' => $booking])
                                @empty
                                    <div class="text-center py-5 opacity-25">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        <span class="x-small fw-bold">{{ __('فارغ') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Lost Group Tab Pane --}}
        <div class="tab-pane fade" id="lost-columns" role="tabpanel" aria-labelledby="lost-tab">
            
            {{-- Lost Sub-groups Navigation Pills --}}
            <div class="sub-pills-container mb-3 d-flex gap-2 flex-wrap" id="lostSubPills">
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn active-sub-pill" data-subgroup="all">
                    {{ __('الكل') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="no_response">
                    <i class="bi bi-telephone-x me-1"></i>{{ __('عدم الرد/التواصل') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="client_request">
                    <i class="bi bi-person-x me-1"></i>{{ __('رغبة العميل') }}
                </button>
                <button class="btn btn-sm rounded-pill fw-bold sub-pill-btn inactive-sub-pill" data-subgroup="bank_rejection">
                    <i class="bi bi-bank me-1"></i>{{ __('الرفض البنكي') }}
                </button>
            </div>

            <div class="kanban-container">
                <div class="kanban-board">
                    @foreach($columns as $key => $col)
                        @if($col['group'] === 'lost')
                        <div class="kanban-col" data-subgroup="{{ $subgroups[$key] ?? '' }}">
                            <div class="kanban-col-header" style="border-top: 4px solid {{ $col['color'] }}">
                                <span class="kanban-col-title">{{ __($col['label']) }}</span>
                                <span class="kanban-col-count">{{ $col['count'] }}</span>
                            </div>
                            <div class="kanban-col-body">
                                @forelse($col['items'] as $booking)
                                    @include('crm.tracking.partials.card', ['booking' => $booking])
                                @empty
                                    <div class="text-center py-5 opacity-25">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        <span class="x-small fw-bold">{{ __('فارغ') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept sub-pill filtering
    document.querySelectorAll('.sub-pill-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.tab-pane');
            
            // Toggle active styling
            container.querySelectorAll('.sub-pill-btn').forEach(b => {
                b.classList.remove('active-sub-pill');
                b.classList.add('inactive-sub-pill');
            });
            this.classList.add('active-sub-pill');
            this.classList.remove('inactive-sub-pill');

            // Hide/Show columns
            const targetSubgroup = this.getAttribute('data-subgroup');
            container.querySelectorAll('.kanban-col').forEach(col => {
                if (targetSubgroup === 'all' || col.getAttribute('data-subgroup') === targetSubgroup) {
                    col.style.display = 'flex';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });

    // Reset filters on tab switch so it starts at "All"
    document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tabBtn => {
        tabBtn.addEventListener('shown.bs.tab', function(e) {
            const targetPaneId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetPaneId);
            if (targetPane) {
                const allBtn = targetPane.querySelector('.sub-pill-btn[data-subgroup="all"]');
                if (allBtn) {
                    allBtn.click();
                }
            }
        });
    });
});
</script>
@endsection
