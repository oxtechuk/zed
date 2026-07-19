@php $s = $slide; @endphp
<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — عربي') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'ar', false) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — EN') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'en', false) }}" required>
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
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('صورة الديسكتوب') }} {{ $s ? '' : '*' }}</label>
            @if($s?->image_desktop)
                <div class="rounded-3 overflow-hidden mb-2" style="height:80px;"><img src="{{ $s->image_desktop }}" class="w-100 h-100 object-fit-cover"></div>
            @endif
            <input type="file" name="image_desktop" class="form-control bg-light border-0" accept="image/*" {{ $s ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('صورة الموبايل') }}</label>
            @if($s?->image_mobile)
                <div class="rounded-3 overflow-hidden mb-2" style="height:80px;"><img src="{{ $s->image_mobile }}" class="w-100 h-100 object-fit-cover"></div>
            @endif
            <input type="file" name="image_mobile" class="form-control bg-light border-0" accept="image/*">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — عربي') }}</label>
            <input type="text" name="button_text[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('button_text', 'ar', false) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — EN') }}</label>
            <input type="text" name="button_text[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('button_text', 'en', false) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('رابط الزر') }}</label>
            <input type="text" name="button_url" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $s?->button_url }}" placeholder="/cars">
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
