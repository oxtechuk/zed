@extends('partials.Layouts.crm-master')
@section('title', __('البانرات الترويجية') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ __('البانرات الترويجية') }}</h4>
            <p class="text-muted mb-0 small">{{ __('إجمالي') }} {{ $banners->count() }} {{ __('بانر') }}</p>
        </div>
        @can('manage-promo-banners')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold"
                data-bs-toggle="modal" data-bs-target="#addBannerModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة بانر') }}
        </button>
        @endcan
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        @forelse($banners as $banner)
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                {{-- صورة البانر --}}
                <div class="ratio ratio-21x9 bg-light">
                    <img src="{{ $banner->image }}" class="w-100 h-100 object-fit-cover"
                         alt="{{ $banner->getTranslation('title', 'ar', false) }}">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0 fw-bold text-dark">
                            {{ $banner->getTranslation('title', 'ar', false) }}
                        </h5>
                        @if(!$banner->is_active)
                            <span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>
                        @else
                            <span class="badge bg-success-subtle text-success small">{{ __('نشط') }}</span>
                        @endif
                    </div>
                    @if($banner->getTranslation('subtitle', 'ar', false))
                    <p class="text-muted small mb-2">{{ $banner->getTranslation('subtitle', 'ar', false) }}</p>
                    @endif
                    @if($banner->button_url)
                    <a href="{{ $banner->button_url }}" target="_blank"
                       class="badge bg-light text-primary border small text-decoration-none">
                        <i class="bi bi-link-45deg me-1"></i>{{ $banner->button_url }}
                    </a>
                    @endif
                </div>
                <div class="card-footer bg-light border-0 d-flex gap-2 p-3">
                    @can('manage-promo-banners')
                    <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 fw-bold rounded-3"
                            data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $banner->id }}">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('تعديل') }}
                    </button>
                    <form action="{{ route('crm.settings.promo-banners.destroy', $banner) }}"
                          method="POST"
                          onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا البانر؟') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        {{-- مودال التعديل --}}
        <div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">{{ __('تعديل البانر') }}</h5>
                        <button type="button"
                                class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}"
                                data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.settings.promo-banners.update', $banner) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('crm.settings.promo-banners._form', ['banner' => $banner])
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-3"
                                    data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                            <button type="submit"
                                    class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ التغييرات') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                <i class="bi bi-image-fill fs-1 d-block mb-3 text-muted"></i>
                <h6 class="fw-bold">{{ __('لا توجد بانرات ترويجية بعد') }}</h6>
                <p class="text-muted small">{{ __('أضف أول بانر بالضغط على زر "إضافة بانر"') }}</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- مودال الإضافة --}}
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة بانر ترويجي') }}</h5>
                <button type="button"
                        class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}"
                        data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.promo-banners.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @include('crm.settings.promo-banners._form', ['banner' => null])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3"
                            data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit"
                            class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('إضافة البانر') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .btn-danger-subtle { background: #ffebee; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,.05); }
</style>
@endsection
