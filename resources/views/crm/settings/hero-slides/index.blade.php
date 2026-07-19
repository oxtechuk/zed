@extends('partials.Layouts.crm-master')
@section('title', __('شرائح الهيرو') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ __('شرائح الهيرو (Hero Slider)') }}</h4>
            <p class="text-muted mb-0 small">{{ __('إجمالي') }} {{ $slides->count() }} {{ __('شريحة') }}</p>
        </div>
        @can('manage-hero-slides')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addSlideModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة شريحة') }}
        </button>
        @endcan
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        @forelse($slides as $slide)
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="ratio ratio-16x9 bg-light">
                    <img src="{{ $slide->image_desktop }}" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-1 fw-bold text-dark">{{ $slide->getTranslation('title', 'ar', false) }}</h5>
                        @if(!$slide->is_active)<span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>@endif
                    </div>
                    <p class="text-muted small mb-0">{{ $slide->getTranslation('subtitle', 'ar', false) }}</p>
                </div>
                <div class="card-footer bg-light border-0 d-flex gap-2 p-3">
                    @can('manage-hero-slides')
                    <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#editSlideModal{{ $slide->id }}">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('تعديل') }}
                    </button>
                    <form action="{{ route('crm.settings.hero-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الشريحة؟') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">{{ __('تعديل الشريحة') }}</h5>
                        <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.settings.hero-slides.update', $slide) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('crm.settings.hero-slides._form', ['slide' => $slide])
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ التغييرات') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                <i class="bi bi-images fs-1 d-block mb-3"></i>
                <h6 class="fw-bold">{{ __('لا توجد شرائح بعد') }}</h6>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="addSlideModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة شريحة جديدة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('crm.settings.hero-slides._form', ['slide' => null])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('إضافة الشريحة') }}</button>
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
