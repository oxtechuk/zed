<?php $s = $slide; ?>
<div class="modal-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('العنوان — عربي')); ?> <span class="text-danger">*</span></label>
            <input type="text" name="title[ar]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('title', 'ar', false)); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('العنوان — EN')); ?> <span class="text-danger">*</span></label>
            <input type="text" name="title[en]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('title', 'en', false)); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الوصف الفرعي — عربي')); ?></label>
            <input type="text" name="subtitle[ar]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('subtitle', 'ar', false)); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الوصف الفرعي — EN')); ?></label>
            <input type="text" name="subtitle[en]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('subtitle', 'en', false)); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الوصف — عربي')); ?></label>
            <textarea name="description[ar]" rows="2" class="form-control bg-light border-0"><?php echo e($s?->getTranslation('description', 'ar', false)); ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الوصف — EN')); ?></label>
            <textarea name="description[en]" rows="2" class="form-control bg-light border-0"><?php echo e($s?->getTranslation('description', 'en', false)); ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('صورة الديسكتوب')); ?> <?php echo e($s ? '' : '*'); ?></label>
            <?php if($s?->image_desktop): ?>
                <div class="rounded-3 overflow-hidden mb-2" style="height:80px;"><img src="<?php echo e($s->image_desktop); ?>" class="w-100 h-100 object-fit-cover"></div>
            <?php endif; ?>
            <input type="file" name="image_desktop" class="form-control bg-light border-0" accept="image/*" <?php echo e($s ? '' : 'required'); ?>>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('صورة الموبايل')); ?></label>
            <?php if($s?->image_mobile): ?>
                <div class="rounded-3 overflow-hidden mb-2" style="height:80px;"><img src="<?php echo e($s->image_mobile); ?>" class="w-100 h-100 object-fit-cover"></div>
            <?php endif; ?>
            <input type="file" name="image_mobile" class="form-control bg-light border-0" accept="image/*">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold"><?php echo e(__('نص الزر — عربي')); ?></label>
            <input type="text" name="button_text[ar]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('button_text', 'ar', false)); ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold"><?php echo e(__('نص الزر — EN')); ?></label>
            <input type="text" name="button_text[en]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('button_text', 'en', false)); ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold"><?php echo e(__('رابط الزر')); ?></label>
            <input type="text" name="button_url" class="form-control bg-light border-0 text-start" dir="ltr" value="<?php echo e($s?->button_url); ?>" placeholder="/cars">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الشارة (Badge)')); ?> — <?php echo e(__('عربي')); ?></label>
            <input type="text" name="badge[ar]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('badge', 'ar', false)); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الشارة (Badge)')); ?> — EN</label>
            <input type="text" name="badge[en]" class="form-control bg-light border-0" value="<?php echo e($s?->getTranslation('badge', 'en', false)); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold"><?php echo e(__('الترتيب')); ?></label>
            <input type="number" name="sort_order" class="form-control bg-light border-0" value="<?php echo e($s->sort_order ?? 0); ?>">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 w-100">
                <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_active" value="1" <?php echo e(($s->is_active ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label fw-bold ms-2"><?php echo e(__('مفعّلة')); ?></label>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/hero-slides/_form.blade.php ENDPATH**/ ?>