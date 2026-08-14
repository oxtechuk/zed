<?php $r = $range; ?>
<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('التسمية — عربي')); ?> <span class="text-danger">*</span></label>
            <input type="text" name="label[ar]" class="form-control bg-light border-0" value="<?php echo e($r?->getTranslation('label', 'ar', false)); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('التسمية — EN')); ?> <span class="text-danger">*</span></label>
            <input type="text" name="label[en]" class="form-control bg-light border-0" value="<?php echo e($r?->getTranslation('label', 'en', false)); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الحد الأدنى')); ?></label>
            <input type="number" name="min" class="form-control bg-light border-0" value="<?php echo e($r->min ?? 0); ?>" min="0" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الحد الأعلى (اتركه فارغاً لعدم وجود حد)')); ?></label>
            <input type="number" name="max" class="form-control bg-light border-0" value="<?php echo e($r->max ?? ''); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الترتيب')); ?></label>
            <input type="number" name="sort_order" class="form-control bg-light border-0" value="<?php echo e($r->sort_order ?? 0); ?>">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 w-100">
                <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1" <?php echo e(($r->is_active ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label fw-bold ms-2"><?php echo e(__('مفعّل')); ?></label>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/budget-ranges/_form.blade.php ENDPATH**/ ?>