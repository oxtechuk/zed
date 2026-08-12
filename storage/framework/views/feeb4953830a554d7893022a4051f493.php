<?php $__env->startSection('title', __('مستخدمي الحاسبة') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold"><?php echo e(__('سجلات استخدام الحاسبة')); ?></h4>
                <p class="text-muted mb-0"><?php echo e(__('إجمالي')); ?> <?php echo e($leads->total()); ?> <?php echo e(__('سجل استخدام')); ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><?php echo e(__('بحث بالاسم أو الجوال')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0 shadow-none" value="<?php echo e(request('search')); ?>"
                                placeholder="<?php echo e(__('الاسم أو الجوال...')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 w-100 fw-bold"><?php echo e(__('تصفية')); ?></button>
                        <a href="<?php echo e(route('crm.calculator-leads.index')); ?>" class="btn btn-light px-3 rounded-3"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-bold">#</th>
                            <th class="py-3 text-muted fw-bold"><?php echo e(__('الاسم')); ?></th>
                            <th class="py-3 text-muted fw-bold"><?php echo e(__('الجوال')); ?></th>
                            <th class="py-3 text-muted fw-bold"><?php echo e(__('السيارة المهتم بها')); ?></th>
                            <th class="py-3 text-muted fw-bold"><?php echo e(__('تاريخ الاستخدام')); ?></th>
                            <th class="py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 text-muted small"><?php echo e($lead->id); ?></td>
                                <td class="fw-bold text-dark"><?php echo e($lead->name); ?></td>
                                <td class="text-muted"><i class="bi bi-telephone me-1 small"></i> <?php echo e($lead->phone); ?></td>
                                <td>
                                    <?php if($lead->car): ?>
                                        <div class="text-primary fw-bold small"><?php echo e($lead->car->name); ?></div>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($lead->created_at->format('Y-m-d H:i')); ?></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-end px-3">
                                        <?php if($lead->booking): ?>
                                        <a href="<?php echo e(route('crm.bookings.show', $lead->booking)); ?>" class="btn btn-sm btn-primary-subtle rounded-2 fw-bold" title="<?php echo e(__('متابعة الطلب الإداري')); ?>">
                                            <i class="bi bi-eye me-1"></i> <?php echo e(__('متابعة الطلب')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <a href="https://wa.me/<?php echo e($lead->phone); ?>" target="_blank" class="btn btn-sm btn-success-subtle rounded-2" title="<?php echo e(__('واتساب')); ?>">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-calculator-leads')): ?>
                                        <form action="<?php echo e(route('crm.calculator-leads.destroy', $lead)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا السجل؟')); ?>')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger-subtle text-danger rounded-2" title="<?php echo e(__('حذف')); ?>"><i class="bi bi-trash"></i></button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="bi bi-calculator fs-1 d-block mb-3 opacity-25"></i>
                                        <h6 class="fw-bold"><?php echo e(__('لا يوجد سجلات حالياً')); ?></h6>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($leads->hasPages()): ?>
                <div class="card-footer bg-white py-3 border-0">
                    <?php echo e($leads->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .btn-danger-subtle { background: #ffebee; }
        .btn-primary-subtle { background: #E0F2FE; color: #0369A1; }
        .btn-primary-subtle:hover { background: #BAE6FD; color: #0369A1; }
        .btn-success-subtle { background: #E8F5E9; color: #2E7D32; }
        .btn-success-subtle:hover { background: #C8E6C9; color: #2E7D32; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/calculator-leads/index.blade.php ENDPATH**/ ?>