@extends('partials.Layouts.crm-master')
@section('title', __('نصوص أقسام الرئيسية') . ' | AutoCRM')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="mb-4">
        <h4 class="mb-1 fw-bold">{{ __('أقسام الصفحة الرئيسية') }}</h4>
        <p class="text-muted mb-0 small">{{ __('العنوان والوصف والزر لكل قسم من أقسام الصفحة الرئيسية. عناوين السيارات والماركات وغيرها تُدار من صفحاتها الخاصة.') }}</p>
    </div>

    @php
        $sectionMeta = [
            'hero' => ['icon' => 'bi-images', 'label' => __('الهيرو الرئيسي (تفاصيله في شرائح الهيرو)')],
            'promo' => ['icon' => 'bi-grid-3x3-gap', 'label' => __('البطاقات الترويجية (تفاصيلها في البطاقات الترويجية)')],
            'search' => ['icon' => 'bi-search', 'label' => __('قسم البحث والتصفية')],
            'brands' => ['icon' => 'bi-award', 'label' => __('الماركات')],
            'featured_cars' => ['icon' => 'bi-star', 'label' => __('السيارات المميزة')],
            'featured_banner' => ['icon' => 'bi-megaphone', 'label' => __('البانر الترويجي الكبير')],
            'latest_cars' => ['icon' => 'bi-car-front', 'label' => __('أحدث السيارات')],
            'budget' => ['icon' => 'bi-wallet2', 'label' => __('السيارات حسب الميزانية')],
            'finance' => ['icon' => 'bi-currency-dollar', 'label' => __('كيف يعمل التمويل (خطواته في خطوات التمويل)')],
            'footer' => ['icon' => 'bi-layout-text-window-reverse', 'label' => __('الفوتر')],
        ];
    @endphp

    <div class="d-flex flex-column gap-3">
        @foreach($sections as $section)
        @php $meta = $sectionMeta[$section->key] ?? ['icon' => 'bi-layout-text-window', 'label' => $section->key]; @endphp
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <button type="button" class="btn text-start p-4 d-flex align-items-center gap-3 border-0 bg-white rounded-4" onclick="toggleSection('sec-{{ $section->key }}', this)">
                <i class="bi {{ $meta['icon'] }} text-danger"></i>
                <span class="fw-semibold">{{ $meta['label'] }}</span>
                @if(!$section->is_active)
                    <span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>
                @endif
                <i class="bi bi-chevron-down ms-auto text-muted small toggle-chevron"></i>
            </button>
            <div class="section-body d-none border-top" id="sec-{{ $section->key }}">
                <form action="{{ route('crm.settings.home-sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('العنوان — عربي') }}</label>
                                <input type="text" name="title[ar]" class="form-control bg-light border-0" value="{{ $section->getTranslation('title', 'ar', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('العنوان — إنجليزي') }}</label>
                                <input type="text" name="title[en]" class="form-control bg-light border-0" value="{{ $section->getTranslation('title', 'en', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الوصف الفرعي — عربي') }}</label>
                                <input type="text" name="subtitle[ar]" class="form-control bg-light border-0" value="{{ $section->getTranslation('subtitle', 'ar', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الوصف الفرعي — إنجليزي') }}</label>
                                <input type="text" name="subtitle[en]" class="form-control bg-light border-0" value="{{ $section->getTranslation('subtitle', 'en', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الوصف — عربي') }}</label>
                                <textarea name="description[ar]" rows="2" class="form-control bg-light border-0">{{ $section->getTranslation('description', 'ar', false) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الوصف — إنجليزي') }}</label>
                                <textarea name="description[en]" rows="2" class="form-control bg-light border-0">{{ $section->getTranslation('description', 'en', false) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الشارة (Badge) — عربي') }}</label>
                                <input type="text" name="badge[ar]" class="form-control bg-light border-0" value="{{ $section->getTranslation('badge', 'ar', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">{{ __('الشارة (Badge) — إنجليزي') }}</label>
                                <input type="text" name="badge[en]" class="form-control bg-light border-0" value="{{ $section->getTranslation('badge', 'en', false) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">{{ __('نص الزر — عربي') }}</label>
                                <input type="text" name="button_text[ar]" class="form-control bg-light border-0" value="{{ $section->getTranslation('button_text', 'ar', false) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">{{ __('نص الزر — إنجليزي') }}</label>
                                <input type="text" name="button_text[en]" class="form-control bg-light border-0" value="{{ $section->getTranslation('button_text', 'en', false) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">{{ __('رابط الزر') }}</label>
                                <input type="text" name="button_url" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $section->button_url }}" placeholder="/cars">
                            </div>
                            @if(in_array($section->key, ['featured_banner']))
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('الصورة') }}</label>
                                @if($section->image)
                                    <div class="mb-2 rounded-3 overflow-hidden bg-light" style="max-height:100px;"><img src="{{ $section->image }}" class="img-fluid w-100 object-fit-cover" style="max-height:100px;"></div>
                                @endif
                                <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted d-block mb-2">{{ __('صورة الخلفية') }}</label>
                                @if($section->background_image)
                                    <div class="mb-2 rounded-3 overflow-hidden bg-light" style="max-height:100px;"><img src="{{ $section->background_image }}" class="img-fluid w-100 object-fit-cover" style="max-height:100px;"></div>
                                @endif
                                <input type="file" name="background_image" class="form-control bg-light border-0" accept="image/*">
                            </div>
                            @endif
                            <div class="col-12">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                    <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1" id="active{{ $section->id }}" {{ $section->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold ms-2" for="active{{ $section->id }}">{{ __('إظهار هذا القسم في الصفحة الرئيسية') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 pt-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ') }}</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
function toggleSection(id, btn) {
    const body = document.getElementById(id);
    body.classList.toggle('d-none');
    const chevron = btn.querySelector('.toggle-chevron');
    if (chevron) chevron.classList.toggle('bi-chevron-up');
    if (chevron) chevron.classList.toggle('bi-chevron-down');
}
</script>
@endsection
