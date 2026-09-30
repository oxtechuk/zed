@php $s = $slide; @endphp
<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — عربي') }}</label>
            <input type="text" name="title[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'ar', false) }}" placeholder="{{ __('اختياري (اتركه فارغاً إذا كان البانر مصمماً بالكامل)') }}">
            <div class="form-text small text-muted">{{ __('اتركه فارغاً إذا كانت النصوص مدمجة مسبقاً داخل صورة البانر لمنع تكرارها فوق الصورة.') }}</div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — EN') }}</label>
            <input type="text" name="title[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'en', false) }}" placeholder="{{ __('Optional (leave empty if banner is pre-designed)') }}">
            <div class="form-text small text-muted">{{ __('Leave empty if texts are pre-designed inside the banner image.') }}</div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف الفرعي — عربي') }}</label>
            <input type="text" name="subtitle[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('subtitle', 'ar', false) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف الفرعي — EN') }}</label>
            <input type="text" name="subtitle[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('subtitle', 'en', false) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف — عربي') }}</label>
            <textarea name="description[ar]" rows="2" class="form-control bg-light border-0">{{ $s?->getTranslation('description', 'ar', false) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف — EN') }}</label>
            <textarea name="description[en]" rows="2" class="form-control bg-light border-0">{{ $s?->getTranslation('description', 'en', false) }}</textarea>
        </div>
        <!-- بانر الديسكتوب والموبايل مع توضيح المقاسات -->
        <div class="col-12">
            <div class="alert alert-primary bg-primary-subtle border-0 rounded-3 p-3 mb-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                    <strong class="text-primary">{{ __('إرشادات مقاسات البانر') }}</strong>
                </div>
                <div class="row g-2 small text-secondary mt-1">
                    <div class="col-md-6">
                        <span class="badge bg-primary text-white me-1">{{ __('ديسكتوب') }}</span>
                        <strong>1920 × 540 px</strong> <span class="opacity-75">({{ __('نسبة العرض للارتفاع 32:9 العريضة القياسية للهيرو لمنع قص الأطراف') }})</span>
                    </div>
                    <div class="col-md-6">
                        <span class="badge bg-dark text-white me-1">{{ __('موبايل') }}</span>
                        <strong>768 × 420 px</strong> <span class="opacity-75">({{ __('أو 1080 × 590 px - نسبة 64:35 المخصصة لشاشات الجوال') }})</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold mb-0">
                        <i class="bi bi-laptop me-1 text-primary"></i> {{ __('صورة الديسكتوب (Desktop Banner)') }} {{ $s ? '' : '*' }}
                    </label>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">1920 × 540 px</span>
                </div>
                <p class="text-muted small mb-2">{{ __('المقاس الموصى به: 1920×540 بكسل (أبعاد تناسب شاشات الديسكتوب العريضة بالكامل دون قص أطراف السيارات أو النصوص).') }}</p>
                @if($s?->image_desktop)
                    <div class="rounded-3 overflow-hidden mb-2 border position-relative" style="height:100px;">
                        <img src="{{ $s->image_desktop }}" class="w-100 h-100 object-fit-cover">
                        <span class="position-absolute bottom-0 end-0 bg-dark text-white px-2 py-0.5 rounded-top-start small opacity-75">{{ __('الحالي') }}</span>
                    </div>
                @endif
                <input type="file" name="image_desktop" class="form-control bg-light border-0" accept="image/*" {{ $s ? '' : 'required' }}>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold mb-0">
                        <i class="bi bi-phone me-1 text-dark"></i> {{ __('صورة الموبايل (Mobile Banner)') }}
                    </label>
                    <span class="badge bg-dark-subtle text-dark border border-dark-subtle">768 × 420 px</span>
                </div>
                <p class="text-muted small mb-2">{{ __('المقاس الموصى به: 768×420 بكسل (أو 1080×590 بكسل، متناسق تماماً مع شاشات الجوال دون تشويه).') }}</p>
                @if($s?->image_mobile)
                    <div class="rounded-3 overflow-hidden mb-2 border position-relative" style="height:100px;">
                        <img src="{{ $s->image_mobile }}" class="w-100 h-100 object-fit-cover">
                        <span class="position-absolute bottom-0 end-0 bg-dark text-white px-2 py-0.5 rounded-top-start small opacity-75">{{ __('الحالي') }}</span>
                    </div>
                @endif
                <input type="file" name="image_mobile" class="form-control bg-light border-0" accept="image/*">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — عربي') }}</label>
            <input type="text" name="button_text[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('button_text', 'ar', false) }}" placeholder="{{ __('اختياري (اتركه فارغاً لإخفاء الزر)') }}">
            <div class="form-text small text-muted">{{ __('اتركه فارغاً إذا كنت لا تريد إظهار أي أزرار فوق البانر.') }}</div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — EN') }}</label>
            <input type="text" name="button_text[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('button_text', 'en', false) }}" placeholder="{{ __('Optional (leave empty to hide)') }}">
            <div class="form-text small text-muted">{{ __('Leave empty to hide button.') }}</div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('رابط البانر / الزر') }}</label>
            <input type="text" name="button_url" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $s?->button_url }}" placeholder="/cars">
            <div class="form-text small text-muted">{{ __('إذا تركت نص الزر فارغاً، سيظل البانر كاملاً قابلاً للنقر إلى هذا الرابط.') }}</div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الشارة (Badge)') }} — {{ __('عربي') }}</label>
            <input type="text" name="badge[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('badge', 'ar', false) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الشارة (Badge)') }} — EN</label>
            <input type="text" name="badge[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('badge', 'en', false) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الترتيب') }}</label>
            <input type="number" name="sort_order" class="form-control bg-light border-0" value="{{ $s->sort_order ?? 0 }}">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 w-100">
                <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1" {{ ($s->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2">{{ __('مفعّلة') }}</label>
            </div>
        </div>
    </div>
</div>
