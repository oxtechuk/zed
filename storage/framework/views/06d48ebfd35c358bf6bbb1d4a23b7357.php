<?php $__env->startSection('title', __('إعدادات SEO') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
        <div class="mb-2">
            <h4 class="mb-1 fw-bold"><?php echo e(__('إعدادات تحسين محركات البحث (SEO)')); ?></h4>
            <p class="text-muted mb-0 small"><?php echo e(__('إدارة الكلمات المفتاحية والأوصاف لمحركات البحث وأدوات التتبع')); ?></p>
        </div>

        <?php echo $__env->make('partials.settings-subnav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form action="<?php echo e(route('crm.settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title mb-0 fw-bold"><?php echo e(__('بيانات الميتا (Meta Tags)')); ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('العنوان الافتراضي للموقع (Meta Title)')); ?></label>
                                <input type="text" name="meta_title" class="form-control bg-light border-0 shadow-none py-2" value="<?php echo e($settings['meta_title'] ?? ''); ?>">
                                <small class="text-muted"><?php echo e(__('يظهر في عناوين صفحات المتصفح ومحركات البحث')); ?></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('الوصف الافتراضي (Meta Description)')); ?></label>
                                <textarea name="meta_description" class="form-control bg-light border-0 shadow-none" rows="4"><?php echo e($settings['meta_description'] ?? ''); ?></textarea>
                                <small class="text-muted"><?php echo e(__('وصف مختصر يظهر في نتائج البحث (يفضل ألا يتجاوز 160 حرفاً)')); ?></small>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('الكلمات المفتاحية (Keywords)')); ?></label>
                                <textarea name="meta_keywords" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="<?php echo e(__('سيارات، بيع سيارات، تقسيط...')); ?>"><?php echo e($settings['meta_keywords'] ?? ''); ?></textarea>
                                <small class="text-muted"><?php echo e(__('افصل بين الكلمات بفاصلة (,) ')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title mb-0 fw-bold"><?php echo e(__('تحليلات وأدوات التتبع')); ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <?php
                                $gaId = $settings['google_analytics_id'] ?? '';
                                $pixelId = $settings['meta_pixel_id'] ?? '';
                            ?>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-google text-primary"></i>
                                    <?php echo e(__('معرف Google Analytics (GA4)')); ?>

                                    <?php if($gaId): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i><?php echo e(__('مفعل')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i><?php echo e(__('غير مفعل')); ?>

                                        </span>
                                    <?php endif; ?>
                                </label>
                                <input type="text" name="google_analytics_id"
                                    class="form-control bg-light border-0 shadow-none py-2"
                                    placeholder="G-XXXXXXXXXX"
                                    value="<?php echo e($gaId); ?>" dir="ltr">
                                <small class="text-muted"><?php echo e(__('مثال: G-1234567890')); ?></small>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-facebook text-primary" style="color:#1877F2 !important;"></i>
                                    <?php echo e(__('معرف Meta Pixel (Facebook)')); ?>

                                    <?php if($pixelId): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-check-circle-fill me-1"></i><?php echo e(__('مفعل')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 ms-auto" style="font-size:10px;">
                                            <i class="bi bi-dash-circle me-1"></i><?php echo e(__('غير مفعل')); ?>

                                        </span>
                                    <?php endif; ?>
                                </label>
                                <input type="text" name="meta_pixel_id"
                                    class="form-control bg-light border-0 shadow-none py-2"
                                    placeholder="1234567890"
                                    value="<?php echo e($pixelId); ?>" dir="ltr">
                                <small class="text-muted"><?php echo e(__('مثال: 1234567890')); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-save me-1"></i> <?php echo e(__('حفظ الإعدادات')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/seo.blade.php ENDPATH**/ ?>