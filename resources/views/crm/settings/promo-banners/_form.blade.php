@php $b = $banner; @endphp
<div class="modal-body p-4">
    <div class="row g-3">

        {{-- الصورة --}}
        <div class="col-12">
            <label class="form-label fw-bold">{{ __('صورة البانر') }} {{ $b ? '' : '*' }}</label>
            @if($b?->image)
                <div class="rounded-3 overflow-hidden mb-2 position-relative" style="max-height:160px;">
                    <img src="{{ $b->image }}" class="w-100 h-100 object-fit-cover rounded-3">
                </div>
            @endif
            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*" {{ $b ? '' : 'required' }}>
            <small class="text-muted">{{ __('يُفضَّل عرض 1600px أو أعلى') }}</small>
        </div>

        {{-- العنوان --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — عربي') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[ar]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('title', 'ar', false) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — EN') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[en]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('title', 'en', false) }}" required>
        </div>

        {{-- الوصف الفرعي --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف الفرعي — عربي') }}</label>
            <input type="text" name="subtitle[ar]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('subtitle', 'ar', false) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف الفرعي — EN') }}</label>
            <input type="text" name="subtitle[en]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('subtitle', 'en', false) }}">
        </div>

        {{-- الوصف التفصيلي --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف — عربي') }}</label>
            <textarea name="description[ar]" rows="3" class="form-control bg-light border-0">{{ $b?->getTranslation('description', 'ar', false) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الوصف — EN') }}</label>
            <textarea name="description[en]" rows="3" class="form-control bg-light border-0">{{ $b?->getTranslation('description', 'en', false) }}</textarea>
        </div>

        {{-- الزر --}}
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — عربي') }}</label>
            <input type="text" name="button_text[ar]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('button_text', 'ar', false) }}"
                   placeholder="{{ __('مثال: اطلع على العروض') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('نص الزر — EN') }}</label>
            <input type="text" name="button_text[en]" class="form-control bg-light border-0"
                   value="{{ $b?->getTranslation('button_text', 'en', false) }}"
                   placeholder="View Offers">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">{{ __('رابط الزر') }}</label>
            <input type="text" name="button_url" class="form-control bg-light border-0 text-start" dir="ltr"
                   value="{{ $b?->button_url }}" placeholder="/offers">
        </div>

        {{-- الترتيب والتفعيل --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الترتيب') }}</label>
            <input type="number" name="sort_order" class="form-control bg-light border-0"
                   value="{{ $b->sort_order ?? 0 }}" min="0">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 w-100">
                <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}"
                       type="checkbox" name="is_active" value="1"
                       {{ ($b->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2">{{ __('مفعّل') }}</label>
            </div>
        </div>

    </div>
</div>
