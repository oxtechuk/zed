<div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom flex-wrap" style="border-color:var(--crm-border)!important;">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings')): ?>
    <a href="<?php echo e(route('crm.settings.general')); ?>"
       class="btn btn-sm rounded-3 fw-bold px-3 py-2 <?php echo e(request()->routeIs('crm.settings.general') ? 'text-white' : 'text-muted'); ?>"
       style="<?php echo e(request()->routeIs('crm.settings.general') ? 'background:var(--crm-red);' : 'background:var(--crm-bg);'); ?>">
        <i class="bi bi-gear me-1"></i> <?php echo e(__('العامة')); ?>

    </a>
    <a href="<?php echo e(route('crm.settings.seo')); ?>"
       class="btn btn-sm rounded-3 fw-bold px-3 py-2 <?php echo e(request()->routeIs('crm.settings.seo') ? 'text-white' : 'text-muted'); ?>"
       style="<?php echo e(request()->routeIs('crm.settings.seo') ? 'background:var(--crm-red);' : 'background:var(--crm-bg);'); ?>">
        <i class="bi bi-search-heart me-1"></i> <?php echo e(__('SEO والتحليلات')); ?>

    </a>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings-integrations')): ?>
    <a href="<?php echo e(route('crm.settings.integrations')); ?>"
       class="btn btn-sm rounded-3 fw-bold px-3 py-2 <?php echo e(request()->routeIs('crm.settings.integrations') ? 'text-white' : 'text-muted'); ?>"
       style="<?php echo e(request()->routeIs('crm.settings.integrations') ? 'background:var(--crm-red);' : 'background:var(--crm-bg);'); ?>">
        <i class="bi bi-plugin me-1"></i> <?php echo e(__('الربط والإشعارات')); ?>

    </a>
    <?php endif; ?>
</div>
<?php /**PATH C:\wamp64\www\zed\resources\views/partials/settings-subnav.blade.php ENDPATH**/ ?>