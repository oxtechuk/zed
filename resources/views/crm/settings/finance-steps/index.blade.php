@extends('partials.Layouts.crm-master')
@section('title', __('خطوات التمويل') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ __('خطوات التمويل (كيف يعمل)') }}</h4>
            <p class="text-muted mb-0 small">{{ __('الخطوات التي تظهر في قسم "كيف يعمل التمويل" بالصفحة الرئيسية') }}</p>
        </div>
        @can('manage-finance-steps')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addStepModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة خطوة') }}
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
                        <th class="px-4 py-3 text-muted fw-bold">#</th>
                        <th class="py-3 text-muted fw-bold">{{ __('العنوان') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الأيقونة') }}</th>
                        <th class="py-3 text-muted fw-bold">{{ __('الحالة') }}</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($steps as $step)
                    <tr>
                        <td class="px-4 fw-bold text-primary">{{ $step->number }}</td>
                        <td class="fw-bold text-dark">{{ $step->getTranslation('title', 'ar', false) }}</td>
                        <td class="text-muted small"><code>{{ $step->icon }}</code></td>
                        <td>@if($step->is_active)<span class="badge bg-success-subtle text-success small">{{ __('مفعّلة') }}</span>@else<span class="badge bg-secondary-subtle text-secondary small">{{ __('معطلة') }}</span>@endif</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end px-3">
                                @can('manage-finance-steps')
                                <button class="btn btn-sm btn-white border shadow-xs rounded-2" data-bs-toggle="modal" data-bs-target="#editStepModal{{ $step->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('crm.settings.finance-steps.destroy', $step) }}" method="POST" onsubmit="return confirm('{{ __('حذف هذه الخطوة؟') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger-subtle text-danger rounded-2"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">{{ __('لا توجد خطوات بعد') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($steps as $step)
<div class="modal fade" id="editStepModal{{ $step->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('تعديل الخطوة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.finance-steps.update', $step) }}" method="POST">
                @csrf @method('PUT')
                @include('crm.settings.finance-steps._form', ['step' => $step])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="addStepModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة خطوة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.finance-steps.store') }}" method="POST">
                @csrf
                @include('crm.settings.finance-steps._form', ['step' => null])
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
