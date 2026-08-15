@extends('partials.Layouts.crm-master')
@section('title', __('تعديل سيارة') . ' | Zad Capital')

@section('content')
<div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <nav class="crm-breadcrumb">
        <a href="{{ route('crm.dashboard') }}">{{ __('الرئيسية') }}</a>
        <span class="sep">›</span>
        <a href="{{ route('crm.cars.index') }}">{{ __('الكتالوج') }}</a>
        <span class="sep">›</span>
        <span class="current">{{ __('تعديل') }}: {{ $car->name }}</span>
    </nav>

    <form action="{{ route('crm.cars.update', $car) }}" method="POST" enctype="multipart/form-data" id="car-edit-form">
        @csrf @method('PUT')

        {{-- Sticky Save Bar --}}
        <div class="car-save-bar">
            <span class="fw-bold" style="font-size:15px;">{{ $car->name }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('crm.cars.index') }}" class="btn-crm-light">{{ __('إلغاء') }}</a>
                <button type="submit" class="btn-crm-primary">
                    <i class="bi bi-check2-circle"></i> {{ __('حفظ التعديلات') }}
                </button>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('يوجد أخطاء في البيانات') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-lg-8">

                {{-- البيانات الأساسية --}}
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-info-circle"></i> {{ __('البيانات الأساسية') }}</div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('الماركة') }} <span class="text-danger">*</span></label>
                                    <button type="button" class="btn-quick-add-link" data-bs-toggle="modal" data-bs-target="#quickAddBrandModal" title="{{ __('إضافة ماركة جديدة') }}">
                                        <i class="bi bi-plus-circle-fill"></i> {{ __('إضافة سريعة') }}
                                    </button>
                                </div>
                                <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                    <option value="">{{ __('اختر الماركة') }}</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $car->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('التصنيف') }}</label>
                                <select name="category_id" class="form-select">
                                    <option value="">{{ __('بدون تصنيف') }}</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected($car->category_id == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('النوع') }} <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    @foreach($carTypes as $carType)
                                    <option value="{{ $carType->slug }}" {{ $car->type === $carType->slug ? 'selected' : '' }}>{{ $carType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">{{ __('الموديل') }} <span class="text-danger">*</span></label>
                                    <button type="button" class="btn-quick-add-link" data-bs-toggle="modal" data-bs-target="#quickAddModelModal" title="{{ __('إضافة موديل جديد') }}" id="btn-quick-add-model" {{ $car->brand_id ? '' : 'disabled' }}>
                                        <i class="bi bi-plus-circle-fill"></i> {{ __('إضافة سريعة') }}
                                    </button>
                                </div>
                                <select name="car_model_id" id="car_model_id" class="form-select @error('car_model_id') is-invalid @enderror" required>
                                    <option value="">{{ __('اختر الموديل') }}</option>
                                </select>
                                @error('car_model_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('سنة الصنع') }} <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control" value="{{ $car->year }}" min="2000" max="2030" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('حالة الإتاحة') }}</label>
                                <select name="availability_status" class="form-select">
                                    <option value="available" {{ $car->availability_status=='available'?'selected':'' }}>{{ __('متاحة للعرض') }}</option>
                                    <option value="order_now"  {{ $car->availability_status=='order_now'?'selected':'' }}>{{ __('اطلب الآن') }}</option>
                                    <option value="on_request" {{ $car->availability_status=='on_request'?'selected':'' }}>{{ __('عند الطلب') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الاسم والوصف --}}
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-translate"></i> {{ __('اسم السيارة والوصف') }}</div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('بالعربية') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror"
                                       value="{{ $car->getTranslation('name','ar') }}" required dir="rtl">
                                @error('name.ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">English <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                                       value="{{ $car->getTranslation('name','en') }}" required dir="ltr">
                                @error('name.en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('الوصف (عربي)') }}</label>
                                <textarea name="description[ar]" class="form-control" rows="3" dir="rtl">{{ $car->getTranslation('description','ar',false) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description (English)</label>
                                <textarea name="description[en]" class="form-control" rows="3" dir="ltr">{{ $car->getTranslation('description','en',false) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('المميزات العامة (نص عربي)') }}</label>
                                <textarea name="features[ar]" class="form-control" rows="3" dir="rtl">{{ $car->getTranslation('features','ar',false) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Features (English)</label>
                                <textarea name="features[en]" class="form-control" rows="3" dir="ltr">{{ $car->getTranslation('features','en',false) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- التسعير --}}
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-currency-dollar"></i> {{ __('التسعير والتقسيط') }}</div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('سعر الكاش') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="cash_price" class="form-control" value="{{ $car->cash_price }}" min="0" required>
                                    <span class="input-group-text">{!! __('ريال') !!}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('أقل مقدم') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="min_down_payment" class="form-control" value="{{ $car->min_down_payment }}" min="0" required>
                                    <span class="input-group-text">{!! __('ريال') !!}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('أقل قسط شهري') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="min_installment" class="form-control" value="{{ $car->min_installment }}" min="0" required>
                                    <span class="input-group-text">{!! __('ريال') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- المواصفات التقنية والمميزات وميزات السلامة --}}
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-speedometer2"></i> {{ __('المواصفات التقنية والمميزات') }}</div>
                    <div class="car-section-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">{{ __('قوة المحرك') }}</label>
                                <input type="text" name="specs[hp]" class="form-control" placeholder="300 HP" value="{{ $car->specs['hp'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('نوع الوقود') }}</label>
                                <input type="text" name="specs[fuel]" class="form-control" placeholder="بنزين" value="{{ $car->specs['fuel'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('ناقل الحركة') }}</label>
                                <input type="text" name="specs[gearbox]" class="form-control" placeholder="أوتوماتيك" value="{{ $car->specs['gearbox'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('عدد المقاعد') }}</label>
                                <input type="number" name="specs[seats]" class="form-control" placeholder="5" value="{{ $car->specs['seats'] ?? '' }}">
                            </div>
                        </div>

                        {{-- 1. المواصفات (Specifications) --}}
                        <div class="col-12 border-top pt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 fw-bold fs-6">{{ __('المواصفات') }}</label>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1" id="spec-count">{{ count($specifications) }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size:12px;" data-bs-toggle="modal" data-bs-target="#quickAddSpecModal">
                                        <i class="bi bi-plus-lg"></i> {{ __('إضافة مواصفة') }}
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="quick-search-box position-relative">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" class="form-control form-control-sm quick-search-input rounded-pill" placeholder="{{ __('بحث سريع في المواصفات...') }}" oninput="filterCheckboxGrid(this, '#specs-grid .spec-item')">
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('specifications[]', true)">{{ __('تحديد الكل') }}</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('specifications[]', false)">{{ __('إلغاء الكل') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="checkbox-grid-container">
                                <div class="row g-2" id="specs-grid">
                                    @foreach($specifications as $spec)
                                    <div class="col-md-4 col-lg-3 spec-item" data-name="{{ strtolower($spec->name) }}">
                                        <div class="checkbox-item-wrapper">
                                            <input type="checkbox" name="specifications[]" value="{{ $spec->id }}" id="spec_{{ $spec->id }}" class="btn-check" @checked($car->specifications->contains($spec->id))>
                                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="spec_{{ $spec->id }}">
                                                <i class="bi bi-check-circle-fill check-icon"></i>
                                                <span class="item-text">{{ $spec->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="no-results-msg text-center text-muted py-3 d-none">
                                    <i class="bi bi-search me-1"></i> {{ __('لا توجد مواصفات مطابقة لبحثك') }}
                                </div>
                            </div>
                        </div>

                        {{-- 2. المميزات (Features) --}}
                        <div class="col-12 border-top pt-4 mt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 fw-bold fs-6">{{ __('المميزات') }}</label>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1" id="feat-count">{{ count($features_list) }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size:12px;" data-bs-toggle="modal" data-bs-target="#quickAddFeatureModal">
                                        <i class="bi bi-plus-lg"></i> {{ __('إضافة ميزة') }}
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="quick-search-box position-relative">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" class="form-control form-control-sm quick-search-input rounded-pill" placeholder="{{ __('بحث سريع في المميزات...') }}" oninput="filterCheckboxGrid(this, '#features-grid .feat-item')">
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('features_list[]', true)">{{ __('تحديد الكل') }}</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('features_list[]', false)">{{ __('إلغاء الكل') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="checkbox-grid-container">
                                <div class="row g-2" id="features-grid">
                                    @foreach($features_list as $feat)
                                    <div class="col-md-4 col-lg-3 feat-item" data-name="{{ strtolower($feat->name) }}">
                                        <div class="checkbox-item-wrapper">
                                            <input type="checkbox" name="features_list[]" value="{{ $feat->id }}" id="feat_{{ $feat->id }}" class="btn-check" @checked($car->features_list->contains($feat->id))>
                                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="feat_{{ $feat->id }}">
                                                <i class="bi bi-check-circle-fill check-icon"></i>
                                                <span class="item-text">{{ $feat->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="no-results-msg text-center text-muted py-3 d-none">
                                    <i class="bi bi-search me-1"></i> {{ __('لا توجد مميزات مطابقة لبحثك') }}
                                </div>
                            </div>
                        </div>

                        {{-- 3. ميزات السلامة (Safety Features) --}}
                        <div class="col-12 border-top pt-4 mt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 fw-bold fs-6">{{ __('ميزات السلامة والأمان') }}</label>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1" id="safety-count">{{ count($safety_features) }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size:12px;" data-bs-toggle="modal" data-bs-target="#quickAddSafetyModal">
                                        <i class="bi bi-plus-lg"></i> {{ __('إضافة ميزة سلامة') }}
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="quick-search-box position-relative">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" class="form-control form-control-sm quick-search-input rounded-pill" placeholder="{{ __('بحث سريع في الأمان...') }}" oninput="filterCheckboxGrid(this, '#safety-grid .safety-item')">
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('safety_features[]', true)">{{ __('تحديد الكل') }}</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('safety_features[]', false)">{{ __('إلغاء الكل') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="checkbox-grid-container">
                                <div class="row g-2" id="safety-grid">
                                    @foreach($safety_features as $safetyFeat)
                                    <div class="col-md-4 col-lg-3 safety-item" data-name="{{ strtolower($safetyFeat->name) }}">
                                        <div class="checkbox-item-wrapper">
                                            <input type="checkbox" name="safety_features[]" value="{{ $safetyFeat->id }}" id="safety_feat_{{ $safetyFeat->id }}" class="btn-check" @checked($car->safety_features->contains($safetyFeat->id))>
                                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="safety_feat_{{ $safetyFeat->id }}">
                                                <i class="bi bi-check-circle-fill check-icon"></i>
                                                <span class="item-text">{{ $safetyFeat->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="no-results-msg text-center text-muted py-3 d-none">
                                    <i class="bi bi-search me-1"></i> {{ __('لا توجد ميزات سلامة مطابقة لبحثك') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- الألوان --}}
                <div class="car-section mb-4">
                    <div class="car-section-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-palette"></i> {{ __('الألوان المتاحة') }}</span>
                        <button type="button" class="btn-crm-primary" style="padding:6px 14px;font-size:12px;" onclick="addColorRow()">
                            <i class="bi bi-plus-lg"></i> {{ __('إضافة لون') }}
                        </button>
                    </div>
                    <div class="car-section-body">
                        <div id="colors-container"></div>
                        <p id="no-colors-msg" class="text-muted text-center py-3" style="font-size:13px;{{ $car->colors && count($car->colors) > 0 ? 'display:none;' : '' }}">
                            <i class="bi bi-palette2 d-block fs-2 mb-2 opacity-25"></i>
                            {{ __('لا توجد ألوان — اضغط "إضافة لون"') }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- الجانب الأيمن --}}
            <div class="col-12 col-lg-4">
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-gear"></i> {{ __('الإعدادات') }}</div>
                    <div class="car-section-body">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                            <div>
                                <div class="fw-bold" style="font-size:13px;">{{ __('عرض في الصفحة الرئيسية') }}</div>
                                <small class="text-muted">{{ __('تظهر في قسم المميزة') }}</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $car->is_featured ? 'checked' : '' }} style="width:40px;height:22px;">
                            </div>
                        </div>
                        <div class="d-flex flex-column p-3 rounded-3 mb-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                            <div class="mb-2">
                                <div class="fw-bold" style="font-size:13px;">{{ __('سيارة محددة / Highlight') }}</div>
                                <small class="text-muted">{{ __('تظهر في تبويبات الصفحة الرئيسية') }}</small>
                            </div>
                            <select name="is_highlighted" class="form-select form-select-sm">
                                <option value="none" {{ $car->is_highlighted == 'none' ? 'selected' : '' }}>{{ __('بدون تمييز') }}</option>
                                <option value="new_arrival" {{ $car->is_highlighted == 'new_arrival' ? 'selected' : '' }}>{{ __('أحدث السيارات') }}</option>
                                <option value="featured" {{ $car->is_highlighted == 'featured' ? 'selected' : '' }}>{{ __('سيارات مختارة') }}</option>
                                <option value="trending" {{ $car->is_highlighted == 'trending' ? 'selected' : '' }}>{{ __('الأكثر طلباً') }}</option>
                                <option value="exclusive" {{ $car->is_highlighted == 'exclusive' ? 'selected' : '' }}>{{ __('إصدار خاص') }}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                            <div>
                                <div class="fw-bold" style="font-size:13px;">{{ __('السيارة نشطة') }}</div>
                                <small class="text-muted">{{ __('ظاهرة في المعرض') }}</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $car->is_active ? 'checked' : '' }} style="width:40px;height:22px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="car-section mb-4">
                    <div class="car-section-header"><i class="bi bi-images"></i> {{ __('الصور') }}</div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ __('الصورة الرئيسية') }}</label>
                                @if($car->thumbnail)
                                <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                                    <img src="{{ $car->thumbnail }}" class="car-img-preview" id="thumbPreview">
                                    <div>
                                        <div class="fw-bold" style="font-size:12px;">{{ __('الصورة الحالية') }}</div>
                                        <small class="text-muted">{{ __('ارفع صورة جديدة للاستبدال') }}</small>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('صور خارجية جديدة') }}</label>
                                <input type="file" name="exterior_images[]" class="form-control" accept="image/*" multiple>
                                @if($car->images->where('type','exterior')->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($car->images->where('type','exterior') as $img)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/'.$img->image_path) }}" class="rounded" style="width:70px;height:52px;object-fit:cover;">
                                        <a href="{{ route('crm.cars.delete-image', $img) }}" class="position-absolute top-0 end-0" style="background:#16254F;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;text-decoration:none;" onclick="return confirm('{{ __('هل أنت متأكد من حذف الصورة؟') }}')">×</a>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('صور داخلية جديدة') }}</label>
                                <input type="file" name="interior_images[]" class="form-control" accept="image/*" multiple>
                                @if($car->images->where('type','interior')->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($car->images->where('type','interior') as $img)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/'.$img->image_path) }}" class="rounded" style="width:70px;height:52px;object-fit:cover;">
                                        <a href="{{ route('crm.cars.delete-image', $img) }}" class="position-absolute top-0 end-0" style="background:#16254F;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;text-decoration:none;" onclick="return confirm('{{ __('هل أنت متأكد من حذف الصورة؟') }}')">×</a>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أزرار الحفظ --}}
                <div class="car-section">
                    <div class="car-section-body">
                        <button type="submit" class="btn-crm-primary w-100" style="padding:14px;font-size:15px;justify-content:center;">
                            <i class="bi bi-check2-circle fs-5"></i> {{ __('حفظ التعديلات') }}
                        </button>
                        <a href="{{ route('crm.cars.index') }}" class="btn-crm-light w-100 mt-2" style="justify-content:center;">
                            {{ __('إلغاء') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ======================================================== --}}
{{-- ==================== MODALS الإضافة السريعة ============= --}}
{{-- ======================================================== --}}

{{-- 1. Modal إضافة ماركة سريعة --}}
<div class="modal fade" id="quickAddBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-tag-fill me-2 text-primary"></i>{{ __('إضافة ماركة جديدة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quick-add-brand-form" action="{{ route('crm.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-brand-ar" type="button" role="tab">{{ __('العربية') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-brand-en" type="button" role="tab">{{ __('الإنجليزية') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3">
                        <div class="tab-pane fade show active" id="quick-brand-ar" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الماركة (بالعربية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]" id="quick_brand_name_ar" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: تويوتا') }}" required dir="rtl">
                        </div>
                        <div class="tab-pane fade" id="quick-brand-en" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الماركة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]" id="quick_brand_name_en" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., Toyota') }}" required dir="ltr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btn-save-quick-brand">{{ __('حفظ الماركة') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. Modal إضافة موديل سريع --}}
<div class="modal fade" id="quickAddModelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-car-front-fill me-2 text-primary"></i>{{ __('إضافة موديل جديد') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quick-add-model-form" action="{{ route('crm.car-models.store') }}" method="POST">
                @csrf
                <input type="hidden" name="brand_id" id="quick_add_brand_id" value="{{ $car->brand_id }}">
                <div class="modal-body p-4 pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('الماركة المختارة') }}</label>
                        <input type="text" id="quick_add_brand_name" class="form-control bg-light border-0 shadow-none fw-bold text-primary" value="{{ $car->brand?->name }}" readonly>
                    </div>

                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-model-ar" type="button" role="tab">{{ __('العربية') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-model-en" type="button" role="tab">{{ __('الإنجليزية') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-4">
                        <div class="tab-pane fade show active" id="quick-model-ar" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الموديل (بالعربية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]" id="quick_add_name_ar" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: كامري') }}" required dir="rtl">
                        </div>
                        <div class="tab-pane fade" id="quick-model-en" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الموديل (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]" id="quick_add_name_en" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., Camry') }}" required dir="ltr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btn-save-quick-model">{{ __('حفظ الموديل') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. Modal إضافة مواصفة سريعة --}}
<div class="modal fade" id="quickAddSpecModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('إضافة مواصفة جديدة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quick-add-spec-form" action="{{ route('crm.specifications.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-spec-ar" type="button" role="tab">{{ __('العربية') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-spec-en" type="button" role="tab">{{ __('الإنجليزية') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3">
                        <div class="tab-pane fade show active" id="quick-spec-ar" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم المواصفة (بالعربية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]" id="quick_spec_name_ar" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: دفع رباعي') }}" required dir="rtl">
                        </div>
                        <div class="tab-pane fade" id="quick-spec-en" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم المواصفة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]" id="quick_spec_name_en" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., 4WD') }}" required dir="ltr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btn-save-quick-spec">{{ __('حفظ المواصفة') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. Modal إضافة ميزة سريعة --}}
<div class="modal fade" id="quickAddFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-star-fill me-2 text-primary"></i>{{ __('إضافة ميزة جديدة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quick-add-feature-form" action="{{ route('crm.features.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-feat-ar" type="button" role="tab">{{ __('العربية') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-feat-en" type="button" role="tab">{{ __('الإنجليزية') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3">
                        <div class="tab-pane fade show active" id="quick-feat-ar" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الميزة (بالعربية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]" id="quick_feat_name_ar" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: فتحة سقف بانوراما') }}" required dir="rtl">
                        </div>
                        <div class="tab-pane fade" id="quick-feat-en" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم الميزة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]" id="quick_feat_name_en" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., Panoramic Sunroof') }}" required dir="ltr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btn-save-quick-feat">{{ __('حفظ الميزة') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 5. Modal إضافة ميزة سلامة سريعة --}}
<div class="modal fade" id="quickAddSafetyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-start">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-check me-2 text-primary"></i>{{ __('إضافة ميزة سلامة جديدة') }}</h5>
                <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quick-add-safety-form" action="{{ route('crm.safety-features.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-safety-ar" type="button" role="tab">{{ __('العربية') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#quick-safety-en" type="button" role="tab">{{ __('الإنجليزية') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3">
                        <div class="tab-pane fade show active" id="quick-safety-ar" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم ميزة السلامة (بالعربية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[ar]" id="quick_safety_name_ar" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: نظام الكبح التلقائي') }}" required dir="rtl">
                        </div>
                        <div class="tab-pane fade" id="quick-safety-en" role="tabpanel">
                            <label class="form-label fw-bold small">{{ __('اسم ميزة السلامة (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name[en]" id="quick_safety_name_en" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., Automatic Emergency Braking') }}" required dir="ltr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btn-save-quick-safety">{{ __('حفظ ميزة السلامة') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.car-save-bar {
    position: sticky; top: 64px; z-index: 90;
    background: #fff; border-bottom: 1px solid var(--crm-border);
    padding: 12px 0 12px 0; margin-bottom: 24px;
    display: flex; justify-content: space-between; align-items: center;
}
.car-section { background: #fff; border: 1px solid var(--crm-border); border-radius: var(--crm-radius); overflow: hidden; }
.car-section-header { padding: 14px 20px; font-size: 14px; font-weight: 800; color: var(--crm-text); background: #FAFBFD; border-bottom: 1px solid var(--crm-border); display: flex; align-items: center; gap: 8px; }
.car-section-body { padding: 20px; }
.car-img-drop { border: 2px dashed var(--crm-border); border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: 0.2s; background: #FAFBFD; font-size: 13px; color: var(--crm-text-muted); }
.car-img-drop:hover { border-color: var(--crm-red); background: var(--crm-red-light); }
.car-img-preview { max-height: 160px; border-radius: 10px; border: 1px solid var(--crm-border); }

.btn-quick-add-link {
    background: none;
    border: none;
    padding: 0;
    font-size: 12px;
    font-weight: 700;
    color: var(--crm-red);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    transition: 0.2s;
}
.btn-quick-add-link:hover {
    color: #0B7A70;
    text-decoration: underline;
}
.btn-quick-add-link:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    text-decoration: none;
}

/* Quick Search Box */
.quick-search-box {
    width: 220px;
}
.quick-search-box .search-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #8E92A4;
    font-size: 13px;
    pointer-events: none;
}
[dir="rtl"] .quick-search-box .search-icon { right: 12px; }
[dir="ltr"] .quick-search-box .search-icon { left: 12px; }
[dir="rtl"] .quick-search-box .quick-search-input { padding-right: 32px; padding-left: 12px; }
[dir="ltr"] .quick-search-box .quick-search-input { padding-left: 32px; padding-right: 12px; }

/* Premium Checkbox Grid */
.checkbox-grid-container {
    max-height: 300px;
    overflow-y: auto;
    padding: 15px;
    background: #fcfcfd;
    border: 1px solid #edf0f5;
    border-radius: 12px;
}
.checkbox-grid-container::-webkit-scrollbar { width: 6px; }
.checkbox-grid-container::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }

.btn-outline-premium {
    border: 1px solid #edf0f5;
    background: #fff;
    color: #4a5568;
    padding: 10px 15px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-align: right !important;
}
.btn-outline-premium:hover {
    background: #f7fafc;
    border-color: var(--crm-red);
    color: var(--crm-red);
}
.btn-check:checked + .btn-outline-premium {
    background: var(--crm-red-light);
    border-color: var(--crm-red);
    color: var(--crm-red);
    box-shadow: 0 4px 12px rgba(20, 35, 77, 0.1);
}
.check-icon {
    font-size: 16px;
    opacity: 0;
    transition: all 0.2s ease;
}
.btn-check:checked + .btn-outline-premium .check-icon {
    opacity: 1;
}

/* Color Row */
.color-row { display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--crm-border); border-radius: 10px; margin-bottom: 8px; background: #FAFBFD; }
.color-row .color-swatch { width: 36px; height: 36px; border-radius: 8px; border: 2px solid var(--crm-border); flex-shrink: 0; cursor: pointer; }
.color-row input[type="color"] { opacity: 0; position: absolute; width: 36px; height: 36px; cursor: pointer; }
.color-row .color-img-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: var(--crm-text-muted); padding: 6px 12px; border: 1px solid var(--crm-border); border-radius: 8px; cursor: pointer; background: #fff; white-space: nowrap; }
.color-row .color-img-label:hover { border-color: var(--crm-red); color: var(--crm-red); }
.color-row .color-img-preview { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; border: 1px solid var(--crm-border); }
.color-row .color-remove { background: none; border: none; color: var(--crm-red); cursor: pointer; font-size: 18px; flex-shrink: 0; padding: 0; }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Configure global jQuery AJAX headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json'
    }
});

// Toast notification helper
function showToast(message, isSuccess = true) {
    const existingToast = document.getElementById('quick-action-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.id = 'quick-action-toast';
    toast.className = `crm-toast show ${isSuccess ? 'success' : 'error'}`;
    toast.innerHTML = `
        <div class="crm-toast-icon">
            <i class="bi ${isSuccess ? 'bi-check-lg' : 'bi-exclamation-triangle-fill'}"></i>
        </div>
        <div class="crm-toast-content">${message}</div>
        <button class="crm-toast-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x"></i>
        </button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// Highlight element flash effect
function flashElement(el) {
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    el.style.transition = 'all 0.3s ease';
    el.style.transform = 'scale(1.04)';
    setTimeout(() => { el.style.transform = 'scale(1)'; }, 400);
}

// Real-time live search filter for checkbox grids
function filterCheckboxGrid(input, itemSelector) {
    const q = (input.value || '').trim().toLowerCase();
    const container = input.closest('.car-section-body') || input.closest('.col-12');
    const items = container.querySelectorAll(itemSelector);
    let visibleCount = 0;

    items.forEach(el => {
        const text = (el.getAttribute('data-name') || el.innerText || '').toLowerCase();
        if (!q || text.includes(q)) {
            el.classList.remove('d-none');
            visibleCount++;
        } else {
            el.classList.add('d-none');
        }
    });

    const noResults = container.querySelector('.no-results-msg');
    if (noResults) {
        if (visibleCount === 0 && q.length > 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
}

// Toggle checkboxes helper
function toggleCheckboxes(name, state) {
    const checkboxes = document.querySelectorAll(`input[name="${name}"]`);
    checkboxes.forEach(cb => cb.checked = state);
}

// Thumbnail preview
document.getElementById('thumbnailInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        let prev = document.getElementById('thumbPreview');
        if (!prev) {
            prev = document.createElement('img');
            prev.id = 'thumbPreview';
            prev.className = 'car-img-preview mt-2';
            this.parentNode.appendChild(prev);
        }
        prev.src = e.target.result;
        prev.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

// ===== Color Rows =====
let colorCount = 0;

function addColorRow(name = '', hex = '#16254F', existingImg = null, existingImgPath = '') {
    const idx = colorCount++;
    const noMsg = document.getElementById('no-colors-msg');
    if (noMsg) noMsg.style.display = 'none';

    const container = document.getElementById('colors-container');
    const div = document.createElement('div');
    div.className = 'color-row';
    div.id = 'color-row-' + idx;

    const hasExisting = existingImg && existingImg !== '';
    div.innerHTML = `
        <div style="position:relative;flex-shrink:0;">
            <div class="color-swatch" id="swatch-${idx}" style="background:${hex};" onclick="document.getElementById('hex-${idx}').click()"></div>
            <input type="color" id="hex-${idx}" name="color_hexes[]" value="${hex}"
                   style="position:absolute;top:0;left:0;opacity:0;width:36px;height:36px;cursor:pointer;"
                   oninput="document.getElementById('swatch-${idx}').style.background=this.value">
        </div>
        <input type="text" name="color_names[]" class="form-control" placeholder="{{ __('اسم اللون') }}"
               value="${name}" style="flex:1;font-size:13px;">
        ${existingImgPath ? `<input type="hidden" name="color_keep_images[${idx}]" value="${existingImgPath}">` : ''}
        <div style="position:relative;flex-shrink:0;">
            <label class="color-img-label" for="cimg-${idx}">
                <i class="bi bi-image"></i>
                <span id="cimg-lbl-${idx}">${hasExisting ? '{{ __("تغيير الصورة") }}' : '{{ __("صورة اللون") }}'}</span>
            </label>
            <input type="file" id="cimg-${idx}" name="color_images[${idx}]" accept="image/*" class="d-none"
                   onchange="previewColorImg(this, ${idx})">
        </div>
        <img id="cprev-${idx}" src="${hasExisting ? existingImg : ''}" class="color-img-preview${hasExisting ? '' : ' d-none'}">
        <button type="button" class="color-remove" onclick="removeColorRow(${idx})">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeColorRow(idx) {
    document.getElementById('color-row-' + idx)?.remove();
    if (document.querySelectorAll('.color-row').length === 0) {
        const noMsg = document.getElementById('no-colors-msg');
        if (noMsg) noMsg.style.display = '';
    }
}

function previewColorImg(input, idx) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('cprev-' + idx);
        prev.src = e.target.result;
        prev.classList.remove('d-none');
        document.getElementById('cimg-lbl-' + idx).textContent = '{{ __("تم الرفع") }} ✓';
    };
    reader.readAsDataURL(input.files[0]);
}

// Load existing colors on page load
const existingColors = @json($car->colors ?? []);
existingColors.forEach(c => {
    const isNew = typeof c === 'string';
    const name = isNew ? '' : (c.name || '');
    const hex  = isNew ? c  : (c.hex  || '#16254F');
    const img  = isNew ? null : (c.image ? '{{ asset("storage/") }}/' + c.image : null);
    const path = isNew ? '' : (c.image || '');
    addColorRow(name, hex, img, path);
});

// ===== AJAX Model Loader =====
const modelsUrlTemplate = "{{ route('crm.brands.models', ':brandId') }}";

function fetchModelsForBrand(brandId, targetSelectedId = null) {
    const modelSelect = $('#car_model_id');
    const quickAddBtn = $('#btn-quick-add-model');

    if (!brandId) {
        modelSelect.html('<option value="">{{ __("اختر الماركة أولاً") }}</option>');
        quickAddBtn.prop('disabled', true);
        $('#quick_add_brand_id').val('');
        $('#quick_add_brand_name').val('');
        return;
    }

    const brandName = $('#brand_id option:selected').text();
    quickAddBtn.prop('disabled', false);
    $('#quick_add_brand_id').val(brandId);
    $('#quick_add_brand_name').val(brandName);

    modelSelect.html('<option value="">{{ __("جاري تحميل الموديلات...") }}</option>');

    const fetchUrl = modelsUrlTemplate.replace(':brandId', brandId);

    $.ajax({
        url: fetchUrl,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            modelSelect.html('<option value="">{{ __("اختر الموديل") }}</option>');
            if (Array.isArray(data) && data.length > 0) {
                $.each(data, function(index, model) {
                    const currentModelId = targetSelectedId || "{{ old('car_model_id', $car->car_model_id) }}";
                    const isSelected = model.id == currentModelId;
                    modelSelect.append(`<option value="${model.id}" ${isSelected ? 'selected' : ''}>${model.name}</option>`);
                });
            } else {
                modelSelect.html('<option value="">{{ __("لا توجد موديلات لهذه الماركة — اضغط إضافة سريعة (+)") }}</option>');
            }
        },
        error: function(xhr) {
            console.error('Error loading models:', xhr);
            modelSelect.html('<option value="">{{ __("خطأ في تحميل الموديلات — اضغط لإعادة المحاولة") }}</option>');
        }
    });
}

$('#brand_id').on('change', function() {
    fetchModelsForBrand($(this).val());
});

// Trigger change on load if brand is already selected
if ($('#brand_id').val()) {
    fetchModelsForBrand($('#brand_id').val(), "{{ old('car_model_id', $car->car_model_id) }}");
}

// ===== Quick Add 1: Brand =====
$('#quick-add-brand-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btn-save-quick-brand');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري الحفظ...") }}');

    const formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success && response.brand) {
                const brand = response.brand;
                const newOption = new Option(brand.name, brand.id, true, true);
                $('#brand_id').append(newOption).val(brand.id).trigger('change');

                $('#quick_brand_name_ar').val('');
                $('#quick_brand_name_en').val('');
                bootstrap.Modal.getInstance(document.getElementById('quickAddBrandModal')).hide();
                showToast(response.message || '{{ __("تمت إضافة الماركة بنجاح") }}', true);
            } else {
                showToast(response.message || '{{ __("حدث خطأ ما") }}', false);
            }
        },
        error: function(xhr) {
            let msg = '{{ __("حدث خطأ أثناء حفظ الماركة") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showToast(msg, false);
        },
        complete: function() {
            btn.prop('disabled', false).text('{{ __("حفظ الماركة") }}');
        }
    });
});

// ===== Quick Add 2: Model =====
$('#quick-add-model-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btn-save-quick-model');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري الحفظ...") }}');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success && response.model) {
                const model = response.model;
                const newOption = new Option(model.name, model.id, true, true);
                $('#car_model_id').append(newOption).val(model.id).trigger('change');

                $('#quick_add_name_ar').val('');
                $('#quick_add_name_en').val('');
                bootstrap.Modal.getInstance(document.getElementById('quickAddModelModal')).hide();
                showToast(response.message || '{{ __("تمت إضافة الموديل بنجاح") }}', true);
            } else {
                showToast(response.message || '{{ __("حدث خطأ ما") }}', false);
            }
        },
        error: function(xhr) {
            let msg = '{{ __("حدث خطأ أثناء حفظ الموديل") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showToast(msg, false);
        },
        complete: function() {
            btn.prop('disabled', false).text('{{ __("حفظ الموديل") }}');
        }
    });
});

// ===== Quick Add 3: Specification =====
$('#quick-add-spec-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btn-save-quick-spec');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري الحفظ...") }}');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success && response.specification) {
                const spec = response.specification;
                const specHtml = `
                    <div class="col-md-4 col-lg-3 spec-item" data-name="${(spec.name || '').toLowerCase()}" id="spec-wrapper-${spec.id}">
                        <div class="checkbox-item-wrapper">
                            <input type="checkbox" name="specifications[]" value="${spec.id}" id="spec_${spec.id}" class="btn-check" checked>
                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="spec_${spec.id}">
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <span class="item-text">${spec.name}</span>
                            </label>
                        </div>
                    </div>
                `;
                $('#specs-grid').prepend(specHtml);
                $('#spec-count').text($('#specs-grid .spec-item').length);
                flashElement(document.getElementById(`spec-wrapper-${spec.id}`));

                $('#quick_spec_name_ar').val('');
                $('#quick_spec_name_en').val('');
                bootstrap.Modal.getInstance(document.getElementById('quickAddSpecModal')).hide();
                showToast(response.message || '{{ __("تمت إضافة المواصفة وتحديدها بنجاح") }}', true);
            } else {
                showToast(response.message || '{{ __("حدث خطأ ما") }}', false);
            }
        },
        error: function(xhr) {
            let msg = '{{ __("حدث خطأ أثناء حفظ المواصفة") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showToast(msg, false);
        },
        complete: function() {
            btn.prop('disabled', false).text('{{ __("حفظ المواصفة") }}');
        }
    });
});

// ===== Quick Add 4: Feature =====
$('#quick-add-feature-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btn-save-quick-feat');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري الحفظ...") }}');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success && response.feature) {
                const feat = response.feature;
                const featHtml = `
                    <div class="col-md-4 col-lg-3 feat-item" data-name="${(feat.name || '').toLowerCase()}" id="feat-wrapper-${feat.id}">
                        <div class="checkbox-item-wrapper">
                            <input type="checkbox" name="features_list[]" value="${feat.id}" id="feat_${feat.id}" class="btn-check" checked>
                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="feat_${feat.id}">
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <span class="item-text">${feat.name}</span>
                            </label>
                        </div>
                    </div>
                `;
                $('#features-grid').prepend(featHtml);
                $('#feat-count').text($('#features-grid .feat-item').length);
                flashElement(document.getElementById(`feat-wrapper-${feat.id}`));

                $('#quick_feat_name_ar').val('');
                $('#quick_feat_name_en').val('');
                bootstrap.Modal.getInstance(document.getElementById('quickAddFeatureModal')).hide();
                showToast(response.message || '{{ __("تمت إضافة الميزة وتحديدها بنجاح") }}', true);
            } else {
                showToast(response.message || '{{ __("حدث خطأ ما") }}', false);
            }
        },
        error: function(xhr) {
            let msg = '{{ __("حدث خطأ أثناء حفظ الميزة") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showToast(msg, false);
        },
        complete: function() {
            btn.prop('disabled', false).text('{{ __("حفظ الميزة") }}');
        }
    });
});

// ===== Quick Add 5: Safety Feature =====
$('#quick-add-safety-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btn-save-quick-safety');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> {{ __("جاري الحفظ...") }}');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success && response.safety_feature) {
                const safety = response.safety_feature;
                const safetyHtml = `
                    <div class="col-md-4 col-lg-3 safety-item" data-name="${(safety.name || '').toLowerCase()}" id="safety-wrapper-${safety.id}">
                        <div class="checkbox-item-wrapper">
                            <input type="checkbox" name="safety_features[]" value="${safety.id}" id="safety_feat_${safety.id}" class="btn-check" checked>
                            <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="safety_feat_${safety.id}">
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <span class="item-text">${safety.name}</span>
                            </label>
                        </div>
                    </div>
                `;
                $('#safety-grid').prepend(safetyHtml);
                $('#safety-count').text($('#safety-grid .safety-item').length);
                flashElement(document.getElementById(`safety-wrapper-${safety.id}`));

                $('#quick_safety_name_ar').val('');
                $('#quick_safety_name_en').val('');
                bootstrap.Modal.getInstance(document.getElementById('quickAddSafetyModal')).hide();
                showToast(response.message || '{{ __("تمت إضافة ميزة السلامة وتحديدها بنجاح") }}', true);
            } else {
                showToast(response.message || '{{ __("حدث خطأ ما") }}', false);
            }
        },
        error: function(xhr) {
            let msg = '{{ __("حدث خطأ أثناء حفظ ميزة السلامة") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showToast(msg, false);
        },
        complete: function() {
            btn.prop('disabled', false).text('{{ __("حفظ ميزة السلامة") }}');
        }
    });
});
</script>
@endsection
