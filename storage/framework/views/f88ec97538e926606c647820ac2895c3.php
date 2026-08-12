<?php $__env->startSection('title', __('إدارة الموديلات') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"> <?php echo e(__('إدارة موديلات السيارات')); ?></h4>
                <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($carModels->total()); ?> <?php echo e(__('موديل مسجل في النظام')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-brands')): ?>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addModelModal">
                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة موديل جديد')); ?>

            </button>
            <?php endif; ?>
        </div>

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="<?php echo e(route('crm.car-models.index')); ?>" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted"><?php echo e(__('تصفية حسب الماركة')); ?></label>
                        <select name="brand_id" class="form-select bg-light border-0 shadow-none">
                            <option value=""><?php echo e(__('كل الماركات')); ?></option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>" <?php echo e(request('brand_id') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3"><i class="bi bi-filter me-1"></i><?php echo e(__('تصفية')); ?></button>
                    </div>
                    <?php if(request()->filled('brand_id')): ?>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('crm.car-models.index')); ?>" class="btn btn-outline-secondary w-100 rounded-3"><?php echo e(__('إعادة تعيين')); ?></a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo e($message); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الموديل')); ?></th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الماركة')); ?></th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الحالة')); ?></th>
                            <th class="border-0 px-4 py-3 text-end"><?php echo e(__('الإجراءات')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $carModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    <?php echo e($model->name); ?> 
                                    <span class="text-muted d-block small font-monospace"><?php echo e($model->getTranslation('name', 'en', false)); ?> / <?php echo e($model->getTranslation('name', 'ar', false)); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($model->brand): ?>
                                        <div class="d-flex align-items-center">
                                            <?php if($model->brand->logo): ?>
                                                <img src="<?php echo e(asset('storage/' . $model->brand->logo)); ?>" alt="<?php echo e($model->brand->name); ?>" width="30" height="30" class="object-fit-contain me-2">
                                            <?php endif; ?>
                                            <span class="fw-semibold"><?php echo e($model->brand->name); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if(!$model->is_active): ?>
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('متوقف')); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('نشط')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-brands')): ?>
                                    <button class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#editModelModal<?php echo e($model->id); ?>" title="<?php echo e(__('تعديل')); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="<?php echo e(route('crm.car-models.destroy', $model)); ?>" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('<?php echo e(__("هل أنت متأكد من حذف هذا الموديل بشكل نهائي؟")); ?>')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger rounded-3" title="<?php echo e(__('حذف')); ?>"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editModelModal<?php echo e($model->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header border-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold"><?php echo e(__('تعديل موديل')); ?>: <?php echo e($model->name); ?></h5>
                                            <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo e(route('crm.car-models.update', $model)); ?>" method="POST">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <div class="modal-body p-4 pt-2 text-start">
                                                
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold small"><?php echo e(__('الماركة')); ?> <span class="text-danger">*</span></label>
                                                    <select name="brand_id" class="form-select bg-light border-0 shadow-none" required>
                                                        <option value=""><?php echo e(__('اختر الماركة')); ?></option>
                                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($brand->id); ?>" <?php echo e($model->brand_id == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                
                                                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-ar-<?php echo e($model->id); ?>" type="button"><?php echo e(__('العربية')); ?></button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-en-<?php echo e($model->id); ?>" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content mb-4">
                                                    <div class="tab-pane fade show active" id="edit-ar-<?php echo e($model->id); ?>">
                                                        <label class="form-label fw-bold small"><?php echo e(__('اسم الموديل (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($model->getTranslation('name', 'ar', false) ?? ''); ?>" required>
                                                    </div>
                                                    <div class="tab-pane fade" id="edit-en-<?php echo e($model->id); ?>">
                                                        <label class="form-label fw-bold small"><?php echo e(__('اسم الموديل (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($model->getTranslation('name', 'en', false) ?? ''); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                                    <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1"
                                                        id="active<?php echo e($model->id); ?>" <?php echo e($model->is_active ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-bold ms-2" for="active<?php echo e($model->id); ?>"><?php echo e(__('تفعيل الموديل في الموقع')); ?></label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><?php echo e(__('حفظ التغييرات')); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-list-nested fs-1 d-block mb-3 opacity-25"></i>
                                        <h6 class="fw-bold"><?php echo e(__('لا توجد موديلات مسجلة حالياً')); ?></h6>
                                        <p class="small mb-0"><?php echo e(__('ابدأ بإضافة الموديلات وربطها بالماركات')); ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center"><?php echo e($carModels->appends(request()->input())->links()); ?></div>

    </div>

    
    <div class="modal fade" id="addModelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold"><?php echo e(__('إضافة موديل جديد')); ?></h5>
                    <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('crm.car-models.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4 pt-2">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small"><?php echo e(__('الماركة')); ?> <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select bg-light border-0 shadow-none" required>
                                <option value=""><?php echo e(__('اختر الماركة')); ?></option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(request('brand_id') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-ar" type="button"><?php echo e(__('العربية')); ?></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-en" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                            </li>
                        </ul>

                        <div class="tab-content mb-4">
                            <div class="tab-pane fade show active" id="add-ar">
                                <label class="form-label fw-bold small"><?php echo e(__('اسم الموديل (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('مثال: كامري')); ?>" required>
                            </div>
                            <div class="tab-pane fade" id="add-en">
                                <label class="form-label fw-bold small"><?php echo e(__('اسم الموديل (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('e.g., Camry')); ?>" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><?php echo e(__('حفظ الموديل')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/car-models/index.blade.php ENDPATH**/ ?>