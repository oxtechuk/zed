<?php $__env->startSection('title', __('إدارة تصنيفات المقالات') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"><?php echo e(__('إدارة تصنيفات المقالات')); ?></h4>
                <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($categories->total()); ?> <?php echo e(__('تصنيف مسجل في النظام')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-blog')): ?>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة تصنيف جديد')); ?>

            </button>
            <?php endif; ?>
        </div>

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
                            <th class="border-0 px-4 py-3" style="width: 60px;">#</th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الاسم')); ?></th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('المقالات')); ?></th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الترتيب')); ?></th>
                            <th class="border-0 px-4 py-3"><?php echo e(__('الحالة')); ?></th>
                            <th class="border-0 px-4 py-3 text-end"><?php echo e(__('الإجراءات')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3 text-muted small"><?php echo e($category->id); ?></td>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    <?php if($category->icon): ?>
                                        <i class="bi bi-<?php echo e($category->icon); ?> me-2 text-muted"></i>
                                    <?php endif; ?>
                                    <?php echo e($category->name); ?>

                                </td>
                                <td class="px-4 py-3 text-muted"><?php echo e($category->posts_count ?? 0); ?> <?php echo e(__('مقال')); ?></td>
                                <td class="px-4 py-3 text-muted"><?php echo e($category->sort_order); ?></td>
                                <td class="px-4 py-3">
                                    <?php if(!$category->is_active): ?>
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('غير نشط')); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-bold"><?php echo e(__('نشط')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-blog')): ?>
                                    <button class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo e($category->id); ?>" title="<?php echo e(__('تعديل')); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="<?php echo e(route('crm.blog-categories.destroy', $category)); ?>" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('<?php echo e(__("هل أنت متأكد من حذف هذا التصنيف بشكل نهائي؟")); ?>')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger rounded-3" title="<?php echo e(__('حذف')); ?>"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editCategoryModal<?php echo e($category->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header border-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold"><?php echo e(__('تعديل تصنيف')); ?>: <?php echo e($category->name); ?></h5>
                                            <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo e(route('crm.blog-categories.update', $category)); ?>" method="POST">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <div class="modal-body p-4 pt-2 text-start">
                                                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-ar-<?php echo e($category->id); ?>" type="button"><?php echo e(__('العربية')); ?></button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#edit-en-<?php echo e($category->id); ?>" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content mb-3">
                                                    <div class="tab-pane fade show active" id="edit-ar-<?php echo e($category->id); ?>">
                                                        <label class="form-label fw-bold small"><?php echo e(__('اسم التصنيف (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($category->getTranslation('name', 'ar', false) ?? ''); ?>" required>
                                                    </div>
                                                    <div class="tab-pane fade" id="edit-en-<?php echo e($category->id); ?>">
                                                        <label class="form-label fw-bold small"><?php echo e(__('اسم التصنيف (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                                        <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($category->getTranslation('name', 'en', false) ?? ''); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small"><?php echo e(__('أيقونة Bootstrap')); ?></label>
                                                        <input type="text" name="icon" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($category->icon); ?>" placeholder="car-front">
                                                        <small class="text-muted mt-1 d-block"><?php echo e(__('اسم أيقونة Bootstrap Icons')); ?></small>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small"><?php echo e(__('ترتيب العرض')); ?></label>
                                                        <input type="number" name="sort_order" class="form-control bg-light border-0 shadow-none"
                                                            value="<?php echo e($category->sort_order); ?>" min="0">
                                                    </div>
                                                </div>

                                                <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                                    <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1"
                                                        id="active<?php echo e($category->id); ?>" <?php echo e($category->is_active ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-bold ms-2" for="active<?php echo e($category->id); ?>"><?php echo e(__('تفعيل التصنيف في الموقع')); ?></label>
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
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4 opacity-50">
                                        <i class="bi bi-tags fs-1 d-block mb-3 opacity-25"></i>
                                        <h6 class="fw-bold"><?php echo e(__('لا توجد تصنيفات مسجلة حالياً')); ?></h6>
                                        <p class="small mb-0"><?php echo e(__('أضف تصنيفات للمقالات مثل نصائح التمويل، أخبار السيارات، إلخ')); ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center"><?php echo e($categories->links()); ?></div>

    </div>

    
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold"><?php echo e(__('إضافة تصنيف جديد')); ?></h5>
                    <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('crm.blog-categories.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4 pt-2">
                        <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-ar" type="button"><?php echo e(__('العربية')); ?></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill py-1 fw-bold" data-bs-toggle="tab" data-bs-target="#add-en" type="button"><?php echo e(__('الإنجليزية')); ?></button>
                            </li>
                        </ul>

                        <div class="tab-content mb-3">
                            <div class="tab-pane fade show active" id="add-ar">
                                <label class="form-label fw-bold small"><?php echo e(__('اسم التصنيف (بالعربية)')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('مثال: نصائح التمويل')); ?>" required>
                            </div>
                            <div class="tab-pane fade" id="add-en">
                                <label class="form-label fw-bold small"><?php echo e(__('اسم التصنيف (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('e.g., Financing Tips')); ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small"><?php echo e(__('أيقونة Bootstrap')); ?></label>
                                <input type="text" name="icon" class="form-control bg-light border-0 shadow-none" placeholder="car-front">
                                <small class="text-muted mt-1 d-block"><?php echo e(__('اسم أيقونة Bootstrap Icons')); ?></small>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small"><?php echo e(__('ترتيب العرض')); ?></label>
                                <input type="number" name="sort_order" class="form-control bg-light border-0 shadow-none" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><?php echo e(__('إضافة التصنيف')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Projects\XO\Zad\resources\views/crm/blog-categories/index.blade.php ENDPATH**/ ?>