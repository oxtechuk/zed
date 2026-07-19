@extends('partials.Layouts.crm-master')
@section('title', __('البطاقات الترويجية') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ __('البطاقات الترويجية (Bento)') }}</h4>
            <p class="text-muted mb-0 small">{{ __('بطاقات CMS ترويجية، ليست سيارات — تظهر أسفل الهيرو مباشرة') }}</p>
        </div>
        @can('manage-promo-cards')
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addCardModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة بطاقة') }}
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
        @forelse($cards as $card)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="ratio ratio-4x3 bg-light">
                    <img src="{{ $card->image }}" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-light text-primary border px-2 py-1 small text-uppercase">{{ $card->type }}</span>
                        @if(!$card->is_active)<span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>@endif
                    </div>
                    <h6 class="mb-1 fw-bold text-dark">{{ $card->getTranslation('title', 'ar', false) }}</h6>
                    <p class="text-muted small mb-0">{{ $card->getTranslation('subtitle', 'ar', false) }}</p>
                </div>
                <div class="card-footer bg-light border-0 d-flex gap-2 p-3">
                    @can('manage-promo-cards')
                    <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#editCardModal{{ $card->id }}">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('تعديل') }}
                    </button>
                    <form action="{{ route('crm.settings.promo-cards.destroy', $card) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه البطاقة؟') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCardModal{{ $card->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">{{ __('تعديل البطاقة') }}</h5>
                        <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('crm.settings.promo-cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('crm.settings.promo-cards._form', ['card' => $card])
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
                <i class="bi bi-grid-3x3-gap fs-1 d-block mb-3"></i>
                <h6 class="fw-bold">{{ __('لا توجد بطاقات ترويجية بعد') }}</h6>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">{{ __('إضافة بطاقة ترويجية') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.settings.promo-cards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('crm.settings.promo-cards._form', ['card' => null])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('إضافة البطاقة') }}</button>
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
