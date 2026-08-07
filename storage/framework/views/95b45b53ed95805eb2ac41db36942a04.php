<?php $__env->startSection('title', __('توصيات العملاء') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"><?php echo e(__('توصيات العملاء (Testimonials)')); ?></h4>
                <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($testimonials->count()); ?> <?php echo e(__('توصية مسجلة')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-testimonials')): ?>
            <a href="<?php echo e(route('crm.settings.testimonials.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-circle me-1"></i> <?php echo e(__('إضافة توصية')); ?>

            </a>
            <?php endif; ?>
        </div>

     

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="<?php echo e(app()->getLocale() == 'ar' ? 'ms-3' : 'me-3'); ?>">
                                    <?php if($testimonial->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $testimonial->image)); ?>" alt="User" class="rounded-circle object-fit-cover" width="50" height="50">
                                    <?php else: ?>
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                            <i class="bi bi-person fs-4 text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark"><?php echo e($testimonial->getTranslation('name', app()->getLocale())); ?></h6>
                                    <small class="text-muted"><?php echo e($testimonial->getTranslation('title', app()->getLocale())); ?></small>
                                </div>
                                <div class="<?php echo e(app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto'); ?> text-warning">
                                    <?php for($i=0; $i<$testimonial->rating; $i++): ?> <i class="bi bi-star-fill small"></i> <?php endfor; ?>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-0 fst-italic">
                                "<?php echo e(mb_strimwidth($testimonial->getTranslation('content', app()->getLocale()), 0, 150, '...')); ?>"
                            </p>
                        </div>
                        <div class="card-footer bg-light border-0 d-flex gap-2 p-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-testimonials')): ?>
                            <a href="<?php echo e(route('crm.settings.testimonials.edit', $testimonial)); ?>" class="btn btn-sm btn-white border shadow-xs rounded-2 flex-grow-1">
                                <i class="bi bi-pencil"></i> <?php echo e(__('تعديل')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-testimonials')): ?>
                            <form action="<?php echo e(route('crm.settings.testimonials.destroy', $testimonial)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذه التوصية؟')); ?>')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger border shadow-xs rounded-2 px-3"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <div class="card border-0 shadow-sm rounded-4 p-5">
                        <i class="bi bi-chat-quote fs-1 d-block mb-3 opacity-25"></i>
                        <h6 class="fw-bold"><?php echo e(__('لا يوجد توصيات مسجلة حالياً')); ?></h6>
                        <div class="mt-3">
                            <a href="<?php echo e(route('crm.settings.testimonials.create')); ?>" class="btn btn-primary btn-sm rounded-pill px-4"><?php echo e(__('إضافة أول توصية')); ?></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<style>
    .btn-white { background: #fff; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .object-fit-cover { object-fit: cover; }
</style>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/settings/testimonials/index.blade.php ENDPATH**/ ?>