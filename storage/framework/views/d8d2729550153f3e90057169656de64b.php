<?php $__env->startSection('title', __('نطاقات الميزانية') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold"><?php echo e(__('نطاقات الميزانية')); ?></h4>
            <p class="text-muted mb-0 small"><?php echo e(__('التبويبات التي تظهر في قسم "سيارات حسب ميزانيتك"')); ?></p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-budget-ranges')): ?>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addRangeModal">
            <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة نطاق')); ?>

        </button>
        <?php endif; ?>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold"><?php echo e(__('التسمية')); ?></th>
                        <th class="py-3 text-muted fw-bold"><?php echo e(__('الحد الأدنى')); ?></th>
                        <th class="py-3 text-muted fw-bold"><?php echo e(__('الحد الأعلى')); ?></th>
                        <th class="py-3 text-muted fw-bold"><?php echo e(__('الحالة')); ?></th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 fw-bold text-dark"><?php echo e($range->getTranslation('label', 'ar', false)); ?></td>
                        <td class="text-muted"><?php echo e(number_format($range->min)); ?></td>
                        <td class="text-muted"><?php echo e($range->max ? number_format($range->max) : __('بدون حد')); ?></td>
                        <td><?php if($range->is_active): ?><span class="badge bg-success-subtle text-success small"><?php echo e(__('مفعّل')); ?></span><?php else: ?><span class="badge bg-secondary-subtle text-secondary small"><?php echo e(__('معطل')); ?></span><?php endif; ?></td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end px-3">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-budget-ranges')): ?>
                                <button class="btn btn-sm btn-white border shadow-xs rounded-2" data-bs-toggle="modal" data-bs-target="#editRangeModal<?php echo e($range->id); ?>"><i class="bi bi-pencil"></i></button>
                                <form action="<?php echo e(route('crm.settings.budget-ranges.destroy', $range)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('حذف هذا النطاق؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger-subtle text-danger rounded-2"><i class="bi bi-trash"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-5"><?php echo e(__('لا توجد نطاقات بعد')); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__currentLoopData = $ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editRangeModal<?php echo e($range->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold"><?php echo e(__('تعديل النطاق')); ?></h5>
                <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('crm.settings.budget-ranges.update', $range)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php echo $__env->make('crm.settings.budget-ranges._form', ['range' => $range], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold"><?php echo e(__('حفظ')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="modal fade" id="addRangeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold"><?php echo e(__('إضافة نطاق')); ?></h5>
                <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('crm.settings.budget-ranges.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('crm.settings.budget-ranges._form', ['range' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold"><?php echo e(__('إضافة')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .btn-danger-subtle { background: #ffebee; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/budget-ranges/index.blade.php ENDPATH**/ ?>