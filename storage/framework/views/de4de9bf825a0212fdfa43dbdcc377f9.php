<?php $__env->startSection('title', __('تعديل دور') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold"> <?php echo e(__('تعديل دور')); ?>: <?php echo e($role->name); ?></h4>
            <p class="text-muted mb-0 small"><?php echo e(__('قم بتعديل بيانات الدور والصلاحيات المرتبطة به')); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('crm.roles.index')); ?>" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-arrow-right me-1"></i> <?php echo e(__('العودة')); ?>

            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <form action="<?php echo e(route('crm.roles.update', $role)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-4">
                    <label class="form-label fw-bold"><?php echo e(__('اسم الدور')); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="<?php echo e(old('name', $role->name)); ?>" required placeholder="<?php echo e(__('أدخل اسم الدور')); ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold mb-3 d-block"><?php echo e(__('الصلاحيات المتاحة')); ?></label>
                    
                    <div class="row g-3">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-check form-switch p-3 border rounded-3 bg-light">
                                <input class="form-check-input float-end ms-0 me-2" type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>" id="perm_<?php echo e($permission->id); ?>" <?php echo e(in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : ''); ?>>
                                <label class="form-check-label fw-bold small ms-5" for="perm_<?php echo e($permission->id); ?>">
                                    <?php echo e(__('permissions.' . $permission->name) !== 'permissions.' . $permission->name ? __('permissions.' . $permission->name) : $permission->name); ?>

                                </label>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-roles')): ?>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> <?php echo e(__('تحديث الدور')); ?>

                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-check-input:checked {
        background-color: #14234d;
        border-color: #14234d;
    }
    .form-check-label {
        padding-right: 2.5rem; /* For RTL */
    }
    html[dir="ltr"] .form-check-label {
        padding-right: 0;
        padding-left: 2.5rem;
    }
    html[dir="rtl"] .form-check-input.float-end {
        float: left !important;
        margin-left: 0 !important;
        margin-right: .5rem !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/roles/edit.blade.php ENDPATH**/ ?>