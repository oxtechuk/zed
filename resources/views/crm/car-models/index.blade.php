@extends('partials.Layouts.crm-master')
@section('title', __('إدارة الموديلات') . ' | AutoCRM')

@section('content')
    <div class="container-fluid" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"> {{ __('إدارة موديلات السيارات') }}</h4>
                <p class="text-muted mb-0 small">{{ __('إجمالي') }} {{ $carModels->total() }} {{ __('موديل مسجل في النظام') }}</p>
            </div>
            @can('manage-brands')
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addModelModal">
                <i class="bi bi-plus-lg me-1"></i> {{ __('إضافة موديل جديد') }}
            </button>
            @endcan
        </div>

        {{-- فلترة الموديلات حسب الماركة --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('crm.car-models.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">{{ __('تصفية حسب الماركة') }}</label>
                        <select name="brand_id" class="form-select bg-light border-0 shadow-none">
                            <option value="">{{ __('كل الماركات') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3"><i class="bi bi-filter me-1"></i>{{ __('تصفية') }}</button>
                    </div>
                    @if(request()->filled('brand_id'))
                    <div class="col-md-2">
                        <a href="{{ route('crm.car-models.index') }}" class="btn btn-outline-secondary w-100 rounded-3">{{ __('إعادة تعيين') }}</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @error('name')
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @enderror

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">{{ __('الموديل') }}</th>
                            <th class="border-0 px-4 py-3">{{ __('الماركة') }}</th>
                            <th class="border-0 px-4 py-3">{{ __('الحالة') }}</th>
                            <th class="border-0 px-4 py-3 text-end">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carModels as $model)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    {{ $model->name }} 
                                    <span class="text-muted d-block small font-monospace">{{ $model->getTranslation('name', 'en', false) }} / {{ $model->getTranslation('name', 'ar', false) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($model->brand)
                                        <div class="d-flex align-items-center">
                                            @if($model->brand->logo)
                                                <img src="{{ asset('storage/' . $model->brand->logo) }}" alt="{{ $model->brand->name }}" width="30" height="30" class="object-fit-contain me-2">
                                            @endif
                                            <span class="fw-semibold">{{ $model->brand->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if(!$model->is_active)
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-bold">{{ __('متوقف') }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-bold">{{ __('نشط') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    @can('manage-brands')
                                    <button class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#editModelModal{{ $model->id }}" title="{{ __('تعديل') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('crm.car-models.destroy', $model) }}" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('{{ __("هل أنت متأكد من حذف هذا الموديل بشكل نهائي؟") }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3" title="{{ __('حذف') }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            {{-- Modal التعديل --}}
                            <div class="modal fade" id="editModelModal{{ $model->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header border-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold">{{ __('تعديل موديل') }}: {{ $model->name }}</h5>
                                            <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('crm.car-models.update', $model) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 pt-2 text-start">
                                                
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small">{{ __('الماركة') }} <span class="text-danger">*</span></label>
                                                    <select name="brand_id" class="form-select bg-light border-0 shadow-none" required>
                                                        <option value="">{{ __('اختر الماركة') }}</option>
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}" {{ $model->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Tabs for Translation --}}
                                                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-ar-{{ $model->id }}" type="button">{{ __('العربية') }}</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-en-{{ $model->id }}" type="button">{{ __('الإنجليزية') }}</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content mb-4">
                                                    <div class="tab-pane fade show active" id="edit-ar-{{ $model->id }}">
                                                        <label class="form-label fw-bold small">{{ __('اسم الموديل (بالعربية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none"
                                                            value="{{ $model->getTranslation('name', 'ar', false) ?? '' }}" required>
                                                    </div>
                                                    <div class="tab-pane fade" id="edit-en-{{ $model->id }}">
                                                        <label class="form-label fw-bold small">{{ __('اسم الموديل (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none"
                                                            value="{{ $model->getTranslation('name', 'en', false) ?? '' }}" required>
                                                    </div>
                                                </div>

                                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                                    <input class="form-check-input {{ app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : '' }}" type="checkbox" name="is_active" value="1"
                                                        id="active{{ $model->id }}" {{ $model->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold ms-2" for="active{{ $model->id }}">{{ __('تفعيل الموديل في الموقع') }}</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">{{ __('حفظ التغييرات') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-list-nested fs-1 d-block mb-3 opacity-25"></i>
                                        <h6 class="fw-bold">{{ __('لا توجد موديلات مسجلة حالياً') }}</h6>
                                        <p class="small mb-0">{{ __('ابدأ بإضافة الموديلات وربطها بالماركات') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center">{{ $carModels->appends(request()->input())->links() }}</div>

    </div>

    {{-- Modal الإضافة --}}
    <div class="modal fade" id="addModelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">{{ __('إضافة موديل جديد') }}</h5>
                    <button type="button" class="btn-close {{ app()->getLocale() == 'ar' ? 'ms-0 me-auto' : '' }}" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('crm.car-models.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 pt-2">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">{{ __('الماركة') }} <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select bg-light border-0 shadow-none" required>
                                <option value="">{{ __('اختر الماركة') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tabs for Translation --}}
                        <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-ar" type="button">{{ __('العربية') }}</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-en" type="button">{{ __('الإنجليزية') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content mb-4">
                            <div class="tab-pane fade show active" id="add-ar">
                                <label class="form-label fw-bold small">{{ __('اسم الموديل (بالعربية)') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('مثال: كامري') }}" required>
                            </div>
                            <div class="tab-pane fade" id="add-en">
                                <label class="form-label fw-bold small">{{ __('اسم الموديل (بالإنجليزية)') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none" placeholder="{{ __('e.g., Camry') }}" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">{{ __('حفظ الموديل') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
