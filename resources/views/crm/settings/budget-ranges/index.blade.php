@extends('partials.Layouts.crm-master')
@section('title', __('نطاقات الميزانية') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ __('نطاقات الميزانية') }}</h4>
            <p class="text-muted mb-0 small">{{ __('التبويبات التي تظهر في قسم "سيارات حسب ميزانيتك"') }}</p>
        </div>
        @can('manage-budget-ranges')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addRangeModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة نطاق') }}
        </button>
        @endcan
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold">{{ __('التسمية') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحد الأدنى') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحد الأعلى') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحالة') }}</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranges as $range)
                    <tr>
                        <td class="px-4 fw-bold text-dark">{{ $range->getTranslation('label', 'ar', false) }}</td>
                        <td class="text-muted">{{ number_format($range->min) }}</td>
                        <td class="text-muted">{{ $range->max ? number_format($range->max) : __('بدون حد') }}</td>
                        <td>@if($range->is_active)<span class="badge bg-success-subtle text-success small">{{ __('مفعّل') }}</span>@else<span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>@endif</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end px-3">
                                @can('manage-budget-ranges')
                                <button class="btn btn-sm btn-white border shadow-xs rounded-2" data-bs-toggle="modal" data-bs-target="#editRangeModal{{ $range->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('crm.settings.budget-ranges.destroy', $range) }}" method="POST" onsubmit="return confirm('{{ __('حذف هذا النطاق؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger-subtle text-danger rounded-2"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">{{ __('لا توجد نطاقات بعد') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($ranges as $range)
<div class="modal fade" id="editRangeModal{{ $range->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('تعديل النطاق') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.budget-ranges.update', $range) }}" method="POST">
                @csrf @method('PUT')
                @include('crm.settings.budget-ranges._form', ['range' => $range])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="addRangeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة نطاق') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.budget-ranges.store') }}" method="POST">
                @csrf
                @include('crm.settings.budget-ranges._form', ['range' => null])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('إضافة') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .btn-danger-subtle { background: #ffebee; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>
@endsection
