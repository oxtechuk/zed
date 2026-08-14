<?php $__env->startSection('title', __('إدارة الصلاحيات والأدوار') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">🛡️ <?php echo e(__('إدارة الصلاحيات والأدوار')); ?></h4>
            <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($roles->total()); ?> <?php echo e(__('دور مسجل في النظام')); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('crm.employees.index')); ?>" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-arrow-right me-1"></i> <?php echo e(__('العودة للموظفين')); ?>

            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-roles')): ?>
            <a href="<?php echo e(route('crm.roles.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-shield-plus me-1"></i> <?php echo e(__('إضافة دور جديد')); ?>

            </a>
            <?php endif; ?>
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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold small text-uppercase"><?php echo e(__('اسم الدور')); ?></th>
                        <th class="py-3 text-muted fw-bold small text-uppercase text-center"><?php echo e(__('عدد الموظفين')); ?></th>
                        <th class="py-3 text-muted fw-bold small text-uppercase text-center"><?php echo e(__('عدد الصلاحيات')); ?></th>
                        <th class="py-3 text-end px-4"></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4">
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e($role->name); ?></h6>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small fw-bold">
                                <?php echo e($role->users_count); ?> <?php echo e(__('موظف')); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 small fw-bold">
                                <?php echo e($role->permissions->count()); ?> <?php echo e(__('صلاحية')); ?>

                            </span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-roles')): ?>
                                <a href="<?php echo e(route('crm.roles.edit', $role)); ?>" class="btn btn-sm btn-white border shadow-xs rounded-2">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-roles')): ?>
                                <?php if($role->name !== 'admin'): ?>
                                <form action="<?php echo e(route('crm.roles.destroy', $role)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__("هل أنت متأكد من حذف هذا الدور؟")); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger-subtle text-danger rounded-2 shadow-xs"><i class="bi bi-trash"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="opacity-25 mb-3">
                                <i class="bi bi-shield-lock" style="font-size: 4rem;"></i>
                            </div>
                            <h6 class="fw-bold"><?php echo e(__('لا يوجد أدوار مسجلة حالياً')); ?></h6>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($roles->hasPages()): ?>
        <div class="card-footer bg-white border-top-0 p-4">
            <?php echo e($roles->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .btn-danger-subtle { background: #ffebee; border: none; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .bg-primary-subtle { background: #e7f1ff; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/roles/index.blade.php ENDPATH**/ ?>