@php $s = $step; @endphp
<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الرقم') }}</label>
            <input type="number" name="number" class="form-control bg-light border-0" value="{{ $s->number ?? '' }}" min="1" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('الأيقونة') }}</label>
            <input type="text" name="icon" class="form-control bg-light border-0 text-start" dir="ltr" value="{{ $s->icon ?? '' }}" placeholder="car / calculator / file-text / key">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — عربي') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[ar]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'ar', false) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">{{ __('العنوان — EN') }} <span class="text-danger">*</span></label>
            <input type="text" name="title[en]" class="form-control bg-light border-0" value="{{ $s?->getTranslation('title', 'en', false) }}" required>
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
