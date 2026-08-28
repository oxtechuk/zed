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
            'featured_banner' => ['icon' => 'bi-alarm-fill', 'label' => __('بانر العروض الترويجي مع العداد التنازلي (رمضان / العروض الخاصة)')],
            'search' => ['icon' => 'bi-search', 'label' => __('قسم البحث والتصفية')],
            'featured_cars' => ['icon' => 'bi-star', 'label' => __('قسم السيارات المميزة')],
            'offers' => ['icon' => 'bi-percent', 'label' => __('قسم العروض')],
            'budget' => ['icon' => 'bi-wallet2', 'label' => __('قسم السيارات حسب الميزانية')],
            'finance' => ['icon' => 'bi-currency-dollar', 'label' => __('قسم خطوات التمويل')],
        ];
    @endphp

    <div class="d-flex flex-column gap-3">
        @foreach($sections as $section)
        @php $meta = $sectionMeta[$section->key] ?? ['icon' => 'bi-layout-text-window', 'label' => $section->key]; @endphp
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <button type="button" class="btn text-start p-4 d-flex align-items-center gap-3 border-0 bg-white rounded-4" onclick="toggleSection('sec-{{ $section->key }}', this)">
                <i class="bi {{ $meta['icon'] }} text-danger fs-5"></i>
                <div>
                    <span class="fw-bold fs-6 d-block">{{ $meta['label'] }}</span>
                    @if($section->key === 'featured_banner')
                    <span class="text-muted small">{{ __('يظهر أعلى قسم أحدث السيارات في الصفحة الرئيسية مع عداد تنازلي وشارة وزر العروض') }}</span>
                    @endif
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    @if(!$section->is_active)
                        <span class="badge bg-secondary-subtle text-secondary small">{{ __('معطل') }}</span>
                    @else
                        <span class="badge bg-success-subtle text-success small">{{ __('مفعل نشط') }}</span>
                    @endif
                    <i class="bi bi-chevron-down text-muted small toggle-chevron"></i>
                </div>
            </button>
            <div class="section-body d-none border-top" id="sec-{{ $section->key }}">
                <form action="{{ route('crm.settings.home-sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-4">

                        @if($section->key === 'featured_banner')
                        {{-- ===== تصميم مخصص لبانر العروض والعداد التنازلي ===== --}}
                        <div class="alert alert-primary border-0 rounded-3 mb-4 p-3 d-flex align-items-center gap-2" style="font-size:13px;">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span>{{ __('يمكنك من هنا تخصيص نصوص العرض والعداد التنازلي وتاريخ الانتهاء والأزرار لبانر الصفحة الرئيسية.') }}</span>
                        </div>

                        <div class="row g-3">
                            {{-- تاريخ ووقت انتهاء العرض للعداد --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-danger"><i class="bi bi-clock-history me-1"></i> {{ __('تاريخ ووقت انتهاء العرض (العداد التنازلي)') }}</label>
                                <input type="datetime-local" name="countdown_end" class="form-control bg-light border-0 shadow-none fw-bold" value="{{ $section->countdown_end ? $section->countdown_end->format('Y-m-d\TH:i') : '' }}">
                                <small class="text-muted" style="font-size:11px;">{{ __('العداد في الصفحة الرئيسية سيحسب الأيام والساعات والدقائق والثواني تلقائياً بناءً على هذا التاريخ') }}</small>
                            </div>

                            {{-- شارة العرض --}}
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الشارة العلوية (Badge) — عربي') }}</label>
                                <input type="text" name="badge[ar]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: عرض محدود') }}" value="{{ $section->getTranslation('badge', 'ar', false) }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الشارة العلوية — إنجليزي') }}</label>
                                <input type="text" name="badge[en]" class="form-control bg-light border-0 shadow-none" placeholder="Limited Offer" value="{{ $section->getTranslation('badge', 'en', false) }}">
                            </div>

                            {{-- العنوان الرئيسي للعرض --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('العنوان الترويجي الرئيسي — عربي') }}</label>
                                <input type="text" name="title[ar]" class="form-control bg-light border-0 shadow-none fw-bold" placeholder="{{ __('مثال: تمويل بدون أرباح لأول 6 أشهر*') }}" value="{{ $section->getTranslation('title', 'ar', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('العنوان الترويجي الرئيسي — إنجليزي') }}</label>
                                <input type="text" name="title[en]" class="form-control bg-light border-0 shadow-none fw-bold" placeholder="Zero-profit financing for the first 6 months*" value="{{ $section->getTranslation('title', 'en', false) }}">
                            </div>

                            {{-- الوسم الجانبي وعنوان المناسبة --}}
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الوسم الصغير (Tag) — عربي') }}</label>
                                <input type="text" name="extra_tag[ar]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: عروض') }}" value="{{ $section->getTranslation('extra_tag', 'ar', false) }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الوسم الصغير — إنجليزي') }}</label>
                                <input type="text" name="extra_tag[en]" class="form-control bg-light border-0 shadow-none" placeholder="Offers" value="{{ $section->getTranslation('extra_tag', 'en', false) }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('عنوان المناسبة (Side Title) — عربي') }}</label>
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label fw-bold small text-muted">{{ __('عنوان المناسبة — إنجليزي') }}</label>
                                <input type="text" name="subtitle[en]" class="form-control bg-light border-0 shadow-none" placeholder="Ramadan Special Offers" value="{{ $section->getTranslation('subtitle', 'en', false) }}">
                            </div>

                            {{-- نص وزر العرض والرابط --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('نص زر العرض — عربي') }}</label>
                                <input type="text" name="button_text[ar]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: اطلع على العروض') }}" value="{{ $section->getTranslation('button_text', 'ar', false) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('نص زر العرض — إنجليزي') }}</label>
                                <input type="text" name="button_text[en]" class="form-control bg-light border-0 shadow-none" placeholder="View Offers" value="{{ $section->getTranslation('button_text', 'en', false) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">{{ __('رابط الزر') }}</label>
                                <input type="text" name="button_url" class="form-control bg-light border-0 shadow-none text-start" dir="ltr" value="{{ $section->button_url ?? '/offers' }}" placeholder="/offers">
                            </div>

                            {{-- الملاحظة السفلية / الشروط --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الملاحظة السفلية / الشروط — عربي') }}</label>
                                <input type="text" name="disclaimer[ar]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: *تطبق الشروط والأحكام') }}" value="{{ $section->getTranslation('disclaimer', 'ar', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">{{ __('الملاحظة السفلية / الشروط — إنجليزي') }}</label>
                                <input type="text" name="disclaimer[en]" class="form-control bg-light border-0 shadow-none" placeholder="*Terms & Conditions Apply" value="{{ $section->getTranslation('disclaimer', 'en', false) }}">
                            </div>

                            {{-- صورة أو أيقونة العرض الجانبية --}}
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold small text-muted mb-0">{{ __('صورة / أيقونة العرض الجانبية (اختياري)') }}</label>
                                    <span class="badge bg-light text-muted border">400 × 400 px</span>
                                </div>
                                <p class="text-muted small mb-2">{{ __('المقاس الموصى به: 400×400 بكسل بخلفية شفافة PNG.') }}</p>
                                @if($section->image)
                                    <div class="mb-2 rounded-3 overflow-hidden bg-dark p-2 d-inline-block" style="max-height:80px;"><img src="{{ $section->image }}" class="img-fluid" style="max-height:60px;"></div>
                                @endif
                                <input type="file" name="image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                            </div>

                            {{-- صورة الخلفية المخصصة --}}
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold small text-muted mb-0">{{ __('صورة خلفية مخصصة للبانر (اختياري)') }}</label>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">1920 × 450 px</span>
                                </div>
                                <p class="text-muted small mb-2">{{ __('المقاس الموصى به: 1920×450 بكسل (أبعاد عريضة تغطي خلفية البانر).') }}</p>
                                @if($section->background_image)
                                    <div class="mb-2 rounded-3 overflow-hidden bg-light border" style="max-height:80px;"><img src="{{ $section->background_image }}" class="img-fluid w-100 object-fit-cover" style="max-height:80px;"></div>
                                @endif
                                <input type="file" name="background_image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                            </div>

                            {{-- تفعيل القسم --}}
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                    <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1" id="active{{ $section->id }}" {{ $section->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold ms-2" for="active{{ $section->id }}">{{ __('إظهار بانر العروض والعداد التنازلي في الصفحة الرئيسية') }}</label>
                                </div>
                            </div>
                        </div>

                        @else
                        {{-- ===== الأقسام القياسية الأخرى ===== --}}
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
                            <div class="col-12">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                    <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1" id="active{{ $section->id }}" {{ $section->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold ms-2" for="active{{ $section->id }}">{{ __('إظهار هذا القسم في الصفحة الرئيسية') }}</label>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="p-4 pt-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">{{ __('حفظ التغييرات') }}</button>
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
