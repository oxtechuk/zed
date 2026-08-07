
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-5 text-center">
        <i class="bi <?php echo e($icon); ?> fs-1 text-primary opacity-50 d-block mb-3"></i>
        <h6 class="fw-bold mb-2"><?php echo e($title); ?></h6>
        <p class="text-muted small mb-4 mx-auto" style="max-width:520px;"><?php echo e($description); ?></p>
        <span class="badge bg-light text-muted rounded-pill px-3 py-2 mb-4 d-inline-block">
            <?php echo e($count); ?> <?php echo e($countLabel); ?>

        </span>
        <div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($permission)): ?>
            <a href="<?php echo e(route($route)); ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-box-arrow-up-right me-1"></i> <?php echo e(__('الذهاب إلى شاشة الإدارة')); ?>

            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\Projects\XO\Zad\resources\views/partials/settings-manage-link.blade.php ENDPATH**/ ?>