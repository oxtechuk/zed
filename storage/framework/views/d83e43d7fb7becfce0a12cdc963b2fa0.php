<?php $__env->startSection('title', __('إضافة سيارة جديدة') . ' | GR Motors'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <a href="<?php echo e(route('crm.cars.index')); ?>"><?php echo e(__('الكتالوج')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('إضافة سيارة جديدة')); ?></span>
    </nav>

    <form action="<?php echo e(route('crm.cars.store')); ?>" method="POST" enctype="multipart/form-data" id="car-form">
        <?php echo csrf_field(); ?>

        
        <div class="car-save-bar">
            <span class="fw-bold" style="font-size:15px;"><?php echo e(__('إضافة سيارة جديدة')); ?></span>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('crm.cars.index')); ?>" class="btn-crm-light"><?php echo e(__('إلغاء')); ?></a>
                <button type="submit" class="btn-crm-primary">
                    <i class="bi bi-check2-circle"></i> <?php echo e(__('حفظ السيارة')); ?>

                </button>
            </div>
        </div>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo e(__('يوجد أخطاء في البيانات المدخلة')); ?>

        </div>
        <?php endif; ?>

        <div class="row g-4">

            
            <div class="col-12 col-lg-8">

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-info-circle"></i> <?php echo e(__('البيانات الأساسية')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('الماركة')); ?> <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value=""><?php echo e(__('اختر الماركة')); ?></option>
                                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('التصنيف')); ?></label>
                                <select name="category_id" class="form-select">
                                    <option value=""><?php echo e(__('بدون تصنيف')); ?></option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('النوع')); ?> <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <?php $__currentLoopData = $carTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($carType->slug); ?>" <?php echo e(old('type') === $carType->slug ? 'selected' : ''); ?>><?php echo e($carType->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('الموديل')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control" value="<?php echo e(old('model')); ?>" placeholder="<?php echo e(__('مثال: 2.5L V6')); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('سنة الصنع')); ?> <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control" value="<?php echo e(old('year', date('Y'))); ?>" min="2000" max="2030" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('حالة الإتاحة')); ?></label>
                                <select name="availability_status" class="form-select">
                                    <option value="available"><?php echo e(__('متاحة للعرض')); ?></option>
                                    <option value="order_now"><?php echo e(__('اطلب الآن')); ?></option>
                                    <option value="on_request"><?php echo e(__('عند الطلب')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-translate"></i> <?php echo e(__('اسم السيارة')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('بالعربية')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control <?php $__errorArgs = ['name.ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('name.ar')); ?>" placeholder="<?php echo e(__('مثال: تويوتا كامري')); ?>" required dir="rtl">
                                <?php $__errorArgs = ['name.ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('بالإنجليزية')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control <?php $__errorArgs = ['name.en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('name.en')); ?>" placeholder="<?php echo e(__('e.g. Toyota Camry')); ?>" required dir="ltr">
                                <?php $__errorArgs = ['name.en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('الوصف (عربي)')); ?></label>
                                <textarea name="description[ar]" class="form-control" rows="3" dir="rtl" placeholder="<?php echo e(__('وصف السيارة...')); ?>"><?php echo e(old('description.ar')); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('الوصف (إنجليزي)')); ?></label>
                                <textarea name="description[en]" class="form-control" rows="3" dir="ltr" placeholder="<?php echo e(__('Car description...')); ?>"><?php echo e(old('description.en')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-currency-dollar"></i> <?php echo e(__('التسعير والتقسيط')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('سعر الكاش')); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="cash_price" class="form-control" value="<?php echo e(old('cash_price')); ?>" min="0" required>
                                    <span class="input-group-text"><?php echo __('ريال'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('أقل مقدم')); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="min_down_payment" class="form-control" value="<?php echo e(old('min_down_payment')); ?>" min="0" required>
                                    <span class="input-group-text"><?php echo __('ريال'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?php echo e(__('أقل قسط شهري')); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="min_installment" class="form-control" value="<?php echo e(old('min_installment')); ?>" min="0" required>
                                    <span class="input-group-text"><?php echo __('ريال'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-speedometer2"></i> <?php echo e(__('المواصفات التقنية')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('قوة المحرك')); ?></label>
                                <input type="text" name="specs[hp]" class="form-control" placeholder="<?php echo e(__('300 HP')); ?>" value="<?php echo e(old('specs.hp')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('نوع الوقود')); ?></label>
                                <input type="text" name="specs[fuel]" class="form-control" placeholder="<?php echo e(__('بنزين / كهربائي')); ?>" value="<?php echo e(old('specs.fuel')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('ناقل الحركة')); ?></label>
                                <input type="text" name="specs[gearbox]" class="form-control" placeholder="<?php echo e(__('أوتوماتيك')); ?>" value="<?php echo e(old('specs.gearbox')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?php echo e(__('عدد المقاعد')); ?></label>
                                <input type="number" name="specs[seats]" class="form-control" placeholder="<?php echo e(__('5')); ?>" value="<?php echo e(old('specs.seats')); ?>">
                            </div>
                        </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0 fw-bold"><?php echo e(__('المواصفات')); ?></label>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('specifications[]', true)"><?php echo e(__('تحديد الكل')); ?></button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('specifications[]', false)"><?php echo e(__('إلغاء الكل')); ?></button>
                                    </div>
                                </div>
                                <div class="checkbox-grid-container">
                                    <div class="row g-2">
                                        <?php $__currentLoopData = $specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-lg-3">
                                            <div class="checkbox-item-wrapper">
                                                <input type="checkbox" name="specifications[]" value="<?php echo e($spec->id); ?>" id="spec_<?php echo e($spec->id); ?>" class="btn-check" <?php echo e(is_array(old('specifications')) && in_array($spec->id, old('specifications')) ? 'checked' : ''); ?>>
                                                <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="spec_<?php echo e($spec->id); ?>">
                                                    <i class="bi bi-check-circle-fill check-icon"></i>
                                                    <span><?php echo e($spec->name); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0 fw-bold"><?php echo e(__('المميزات')); ?></label>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('features_list[]', true)"><?php echo e(__('تحديد الكل')); ?></button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('features_list[]', false)"><?php echo e(__('إلغاء الكل')); ?></button>
                                    </div>
                                </div>
                                <div class="checkbox-grid-container">
                                    <div class="row g-2">
                                        <?php $__currentLoopData = $features_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-lg-3">
                                            <div class="checkbox-item-wrapper">
                                                <input type="checkbox" name="features_list[]" value="<?php echo e($feat->id); ?>" id="feat_<?php echo e($feat->id); ?>" class="btn-check" <?php echo e(is_array(old('features_list')) && in_array($feat->id, old('features_list')) ? 'checked' : ''); ?>>
                                                <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="feat_<?php echo e($feat->id); ?>">
                                                    <i class="bi bi-check-circle-fill check-icon"></i>
                                                    <span><?php echo e($feat->name); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0 fw-bold"><?php echo e(__('ميزات السلامة')); ?></label>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('safety_features[]', true)"><?php echo e(__('تحديد الكل')); ?></button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleCheckboxes('safety_features[]', false)"><?php echo e(__('إلغاء الكل')); ?></button>
                                    </div>
                                </div>
                                <div class="checkbox-grid-container">
                                    <div class="row g-2">
                                        <?php $__currentLoopData = $safety_features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $safetyFeat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-lg-3">
                                            <div class="checkbox-item-wrapper">
                                                <input type="checkbox" name="safety_features[]" value="<?php echo e($safetyFeat->id); ?>" id="safety_feat_<?php echo e($safetyFeat->id); ?>" class="btn-check" <?php echo e(is_array(old('safety_features')) && in_array($safetyFeat->id, old('safety_features')) ? 'checked' : ''); ?>>
                                                <label class="btn btn-outline-premium w-100 text-start d-flex align-items-center gap-2" for="safety_feat_<?php echo e($safetyFeat->id); ?>">
                                                    <i class="bi bi-check-circle-fill check-icon"></i>
                                                    <span><?php echo e($safetyFeat->name); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                
                <div class="car-section mb-4">
                    <div class="car-section-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-palette"></i> <?php echo e(__('الألوان المتاحة')); ?></span>
                        <button type="button" class="btn-crm-primary" style="padding:6px 14px;font-size:12px;" onclick="addColorRow()">
                            <i class="bi bi-plus-lg"></i> <?php echo e(__('إضافة لون')); ?>

                        </button>
                    </div>
                    <div class="car-section-body">
                        <div id="colors-container">
                            
                        </div>
                        <p id="no-colors-msg" class="text-muted text-center py-3" style="font-size:13px;">
                            <i class="bi bi-palette2 d-block fs-2 mb-2 opacity-25"></i>
                            <?php echo e(__('لم تضف ألواناً بعد — اضغط "إضافة لون"')); ?>

                        </p>
                    </div>
                </div>



            </div>

            
            <div class="col-12 col-lg-4">

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-gear"></i> <?php echo e(__('الإعدادات')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                            <div>
                                <div class="fw-bold" style="font-size:13px;"><?php echo e(__('عرض في الصفحة الرئيسية')); ?></div>
                                <small class="text-muted"><?php echo e(__('تظهر في قسم المميزة')); ?></small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?> style="width:40px;height:22px;">
                            </div>
                        </div>
                        <div class="d-flex flex-column p-3 rounded-3 mb-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                            <div class="mb-2">
                                <div class="fw-bold" style="font-size:13px;"><?php echo e(__('سيارة محددة / Highlight')); ?></div>
                                <small class="text-muted"><?php echo e(__('تظهر في تبويبات الصفحة الرئيسية')); ?></small>
                            </div>
                            <select name="is_highlighted" class="form-select form-select-sm">
                                <option value="none" <?php echo e(old('is_highlighted') == 'none' ? 'selected' : ''); ?>><?php echo e(__('بدون تمييز')); ?></option>
                                <option value="new_arrival" <?php echo e(old('is_highlighted') == 'new_arrival' ? 'selected' : ''); ?>><?php echo e(__('أحدث السيارات')); ?></option>
                                <option value="featured" <?php echo e(old('is_highlighted') == 'featured' ? 'selected' : ''); ?>><?php echo e(__('سيارات مختارة')); ?></option>
                                <option value="trending" <?php echo e(old('is_highlighted') == 'trending' ? 'selected' : ''); ?>><?php echo e(__('الأكثر طلباً')); ?></option>
                                <option value="exclusive" <?php echo e(old('is_highlighted') == 'exclusive' ? 'selected' : ''); ?>><?php echo e(__('إصدار خاص')); ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="car-section mb-4">
                    <div class="car-section-header">
                        <i class="bi bi-images"></i> <?php echo e(__('الصور')); ?>

                    </div>
                    <div class="car-section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label"><?php echo e(__('الصورة الرئيسية')); ?> <span class="text-danger">*</span></label>
                                <div class="car-img-drop" id="thumb-drop" onclick="document.getElementById('thumbnailInput').click()">
                                    <i class="bi bi-cloud-upload fs-2 d-block mb-2 opacity-40"></i>
                                    <span><?php echo e(__('اضغط لرفع صورة رئيسية')); ?></span>
                                    <small class="d-block text-muted"><?php echo e(__('JPG, PNG, WebP — حد أقصى 5MB')); ?></small>
                                </div>
                                <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" class="d-none">
                                <img id="thumbPreview" class="car-img-preview d-none mt-2">
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?php echo e(__('صور خارجية')); ?></label>
                                <input type="file" name="exterior_images[]" class="form-control" accept="image/*" multiple>
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?php echo e(__('صور داخلية')); ?></label>
                                <input type="file" name="interior_images[]" class="form-control" accept="image/*" multiple>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="car-section">
                    <div class="car-section-body">
                        <button type="submit" class="btn-crm-primary w-100" style="padding:14px;font-size:15px;justify-content:center;">
                            <i class="bi bi-check2-circle fs-5"></i> <?php echo e(__('حفظ السيارة')); ?>

                        </button>
                        <a href="<?php echo e(route('crm.cars.index')); ?>" class="btn-crm-light w-100 mt-2" style="justify-content:center;">
                            <?php echo e(__('إلغاء')); ?>

                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
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
    box-shadow: 0 4px 12px #EDC98E1A
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%', dir: '<?php echo e(app()->getLocale() == "ar" ? "rtl" : "ltr"); ?>' });
});

// Thumbnail preview
document.getElementById('thumbnailInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('thumbPreview');
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        document.getElementById('thumb-drop').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// Drag & Drop
const drop = document.getElementById('thumb-drop');
drop.addEventListener('dragover', e => { e.preventDefault(); drop.style.borderColor = 'var(--crm-red)'; });
drop.addEventListener('dragleave', () => { drop.style.borderColor = ''; });
drop.addEventListener('drop', e => {
    e.preventDefault();
    document.getElementById('thumbnailInput').files = e.dataTransfer.files;
    document.getElementById('thumbnailInput').dispatchEvent(new Event('change'));
});

// ===== Color Rows =====
let colorCount = 0;

function addColorRow(name = '', hex = '#16254F', imgSrc = '') {
    const idx = colorCount++;
    const noMsg = document.getElementById('no-colors-msg');
    if (noMsg) noMsg.style.display = 'none';

    const container = document.getElementById('colors-container');
    const div = document.createElement('div');
    div.className = 'color-row';
    div.id = 'color-row-' + idx;
    div.innerHTML = `
        <div style="position:relative;flex-shrink:0;">
            <div class="color-swatch" id="swatch-${idx}" style="background:${hex};" onclick="document.getElementById('hex-${idx}').click()"></div>
            <input type="color" id="hex-${idx}" name="color_hexes[]" value="${hex}"
                   style="position:absolute;top:0;left:0;opacity:0;width:36px;height:36px;cursor:pointer;"
                   oninput="document.getElementById('swatch-${idx}').style.background=this.value">
        </div>
        <input type="text" name="color_names[]" class="form-control" placeholder="<?php echo e(__('اسم اللون (أحمر، أبيض...)')); ?>"
               value="${name}" style="flex:1;font-size:13px;">
        <div style="position:relative;flex-shrink:0;">
            <label class="color-img-label" for="cimg-${idx}">
                <i class="bi bi-image"></i>
                <span id="cimg-lbl-${idx}"><?php echo e(__('صورة اللون')); ?></span>
            </label>
            <input type="file" id="cimg-${idx}" name="color_images[${idx}]" accept="image/*" class="d-none"
                   onchange="previewColorImg(this, ${idx})">
        </div>
        ${imgSrc ? `<img id="cprev-${idx}" src="${imgSrc}" class="color-img-preview">` : `<img id="cprev-${idx}" class="color-img-preview d-none">`}
        <button type="button" class="color-remove" onclick="removeColorRow(${idx})" title="<?php echo e(__('حذف')); ?>">
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
        document.getElementById('cimg-lbl-' + idx).textContent = '<?php echo e(__("تم الرفع")); ?> ✓';
    };
    reader.readAsDataURL(input.files[0]);
}

// Toggle checkboxes helper
function toggleCheckboxes(name, state) {
    const checkboxes = document.querySelectorAll(`input[name="${name}"]`);
    checkboxes.forEach(cb => cb.checked = state);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/cars/create.blade.php ENDPATH**/ ?>