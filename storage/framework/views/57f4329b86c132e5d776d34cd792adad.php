<?php $__env->startSection('title', __('أنواع السيارات') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"> <?php echo e(__('أنواع السيارات')); ?></h4>
                <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($types->total()); ?> <?php echo e(__('نوع متاح في النظام')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-car-types')): ?>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة نوع جديد')); ?>

            </button>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card border-0 shadow-sm text-center h-100 rounded-4 overflow-hidden transition-hover">
                        <div class="card-body p-4">
                            <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 border border-white shadow-xs"
                                style="width:65px;height:65px;">
                                <i class="bi bi-car-front fs-2 text-primary"></i>
                            </div>
                            <h5 class="mb-1 fw-bold text-dark"><?php echo e($type->name); ?></h5>
                            <p class="text-muted small mb-3 fw-medium"><?php echo e($type->cars_count ?? 0); ?> <?php echo e(__('سيارة')); ?></p>
                            <?php if(!$type->is_active): ?>
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('متوقف')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('نشط')); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-light border-0 d-flex gap-2 p-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-car-types')): ?>
                            <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 rounded-3 fw-bold" data-bs-toggle="modal"
                                data-bs-target="#editType<?php echo e($type->id); ?>"><i class="bi bi-pencil-square me-1"></i> <?php echo e(__('تعديل')); ?></button>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-car-types')): ?>
                            <form action="<?php echo e(route('crm.car-types.destroy', $type)); ?>" method="POST"
                                onsubmit="return confirm('<?php echo e(__("هل أنت متأكد من حذف هذا النوع؟")); ?>')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editType<?php echo e($type->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-header border-0 pt-4 px-4">
                                <h5 class="modal-title fw-bold"><?php echo e(__('تعديل النوع')); ?></h5>
                                <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="<?php echo e(route('crm.car-types.update', $type)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-body p-4 pt-2">
                                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-ar-type-<?php echo e($type->id); ?>" type="button"><?php echo e(__('العربية')); ?></button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-en-type-<?php echo e($type->id); ?>" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                                        </li>
                                    </ul>

                                    <div class="tab-content mb-4">
                                        <div class="tab-pane fade show active" id="edit-ar-type-<?php echo e($type->id); ?>">
                                            <label class="form-label fw-bold small"><?php echo e(__('اسم النوع (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none"
                                                value="<?php echo e($type->getTranslation('name', 'ar', false) ?? ''); ?>" required>
                                        </div>
                                        <div class="tab-pane fade" id="edit-en-type-<?php echo e($type->id); ?>">
                                            <label class="form-label fw-bold small"><?php echo e(__('اسم النوع (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none"
                                                value="<?php echo e($type->getTranslation('name', 'en', false) ?? ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small"><?php echo e(__('ترتيب العرض')); ?></label>
                                        <input type="number" name="sort_order" class="form-control bg-light border-0 shadow-none"
                                            value="<?php echo e($type->sort_order); ?>">
                                    </div>
                                    <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                        <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1"
                                            id="tp<?php echo e($type->id); ?>" <?php echo e($type->is_active ? 'checked' : ''); ?>>
                                        <label class="form-check-label fw-bold ms-2" for="tp<?php echo e($type->id); ?>"><?php echo e(__('تفعيل النوع في الموقع')); ?></label>
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
                <div class="col-12 text-center py-5">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                        <i class="bi bi-car-front fs-1 d-block mb-3 opacity-25"></i>
                        <h6 class="fw-bold"><?php echo e(__('لا توجد أنواع مسجلة حالياً')); ?></h6>
                        <p class="small"><?php echo e(__('قم بإضافة أنواع مثل (سيدان، SUV، هاتشباك) لتصنيف سياراتك')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-5 d-flex justify-content-center"><?php echo e($types->links()); ?></div>

        <div class="modal fade" id="addTypeModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold"><?php echo e(__('إضافة نوع جديد')); ?></h5>
                        <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('crm.car-types.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body p-4 pt-2">
                            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-ar-type" type="button"><?php echo e(__('العربية')); ?></button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-en-type" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                                </li>
                            </ul>

                            <div class="tab-content mb-4">
                                <div class="tab-pane fade show active" id="add-ar-type">
                                    <label class="form-label fw-bold small"><?php echo e(__('اسم النوع (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('مثال: سيدان')); ?>" required>
                                </div>
                                <div class="tab-pane fade" id="add-en-type">
                                    <label class="form-label fw-bold small"><?php echo e(__('اسم النوع (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('e.g., Sedan')); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small"><?php echo e(__('ترتيب العرض')); ?></label>
                                <input type="number" name="sort_order" class="form-control bg-light border-0 shadow-none" value="0">
                            </div>
                            <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1" id="nctp" checked>
                                <label class="form-check-label fw-bold ms-2" for="nctp"><?php echo e(__('تفعيل النوع فوراً')); ?></label>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><?php echo e(__('إضافة النوع الآن')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-hover { transition: transform 0.3s ease-in-out; }
        .transition-hover:hover { transform: translateY(-8px); }
        .bg-primary-subtle { background: #e7f1ff; }
        .btn-white { background: #fff; }
        .btn-danger-subtle { background: #ffebee; border: none; }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/car-types/index.blade.php ENDPATH**/ ?>