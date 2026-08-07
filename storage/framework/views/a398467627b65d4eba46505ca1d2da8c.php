<?php $__env->startSection('title', __('شرائح الهيرو') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold"><?php echo e(__('شرائح الهيرو (Hero Slider)')); ?></h4>
            <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($slides->count()); ?> <?php echo e(__('شريحة')); ?></p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-hero-slides')): ?>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addSlideModal">
            <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة شريحة')); ?>

        </button>
        <?php endif; ?>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="ratio ratio-16x9 bg-light">
                    <img src="<?php echo e($slide->image_desktop); ?>" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-1 fw-bold text-dark"><?php echo e($slide->getTranslation('title', 'ar', false)); ?></h5>
                        <?php if(!$slide->is_active): ?><span class="badge bg-secondary-subtle text-secondary small"><?php echo e(__('معطل')); ?></span><?php endif; ?>
                    </div>
                    <p class="text-muted small mb-0"><?php echo e($slide->getTranslation('subtitle', 'ar', false)); ?></p>
                </div>
                <div class="card-footer bg-light border-0 d-flex gap-2 p-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-hero-slides')): ?>
                    <button class="btn btn-sm btn-white border shadow-xs flex-grow-1 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#editSlideModal<?php echo e($slide->id); ?>">
                        <i class="bi bi-pencil-square me-1"></i> <?php echo e(__('تعديل')); ?>

                    </button>
                    <form action="<?php echo e(route('crm.settings.hero-slides.destroy', $slide)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذه الشريحة؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger-subtle text-danger rounded-3 px-3 shadow-xs"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSlideModal<?php echo e($slide->id); ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold"><?php echo e(__('تعديل الشريحة')); ?></h5>
                        <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('crm.settings.hero-slides.update', $slide)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <?php echo $__env->make('crm.settings.hero-slides._form', ['slide' => $slide], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold"><?php echo e(__('حفظ التغييرات')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                <i class="bi bi-images fs-1 d-block mb-3"></i>
                <h6 class="fw-bold"><?php echo e(__('لا توجد شرائح بعد')); ?></h6>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addSlideModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold"><?php echo e(__('إضافة شريحة جديدة')); ?></h5>
                <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('crm.settings.hero-slides.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('crm.settings.hero-slides._form', ['slide' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold"><?php echo e(__('إضافة الشريحة')); ?></button>
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

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/hero-slides/index.blade.php ENDPATH**/ ?>