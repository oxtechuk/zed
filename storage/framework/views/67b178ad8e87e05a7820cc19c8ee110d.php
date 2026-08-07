<?php $__env->startSection('title', __('المهام') . ' | Zad Capital CRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<div class="crm-page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="crm-page-title"><?php echo e(__('المهام')); ?></h1>
        <p class="crm-page-sub"><?php echo e(__('إدارة مهام الفريق ومتابعة التنفيذ')); ?></p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
    <button class="btn-crm-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="bi bi-plus-lg"></i> <?php echo e(__('مهمة جديدة')); ?>

    </button>
    <?php endif; ?>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="crm-stat-card">
            <span class="crm-stat-label"><?php echo e(__('إجمالي المهام')); ?></span>
            <span class="crm-stat-value"><?php echo e($counts['total']); ?></span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="crm-stat-card info">
            <span class="crm-stat-label"><?php echo e(__('جديدة')); ?></span>
            <span class="crm-stat-value"><?php echo e($counts['new']); ?></span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="crm-stat-card" style="--crm-text:#FF9800">
            <span class="crm-stat-label"><?php echo e(__('قيد التنفيذ')); ?></span>
            <span class="crm-stat-value" style="color:#FF9800"><?php echo e($counts['in_progress']); ?></span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="crm-stat-card" style="--crm-text:#4CAF50">
            <span class="crm-stat-label"><?php echo e(__('مكتملة')); ?></span>
            <span class="crm-stat-value" style="color:#4CAF50"><?php echo e($counts['done']); ?></span>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-4 mb-4" style="border:1px solid var(--crm-border)!important;">
    <div class="card-body p-3">
        <?php
            $qs = fn (array $override = []) => array_filter(array_merge(request()->except('page'), $override), fn ($v) => $v !== null && $v !== '');
        ?>
        
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            
            <div class="d-flex flex-wrap gap-3 align-items-center">
                
                <div class="btn-group rounded-3 overflow-hidden p-1 bg-light" style="border: 1px solid var(--crm-border);">
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['view' => 'today', 'from' => null, 'to' => null]))); ?>" 
                       class="btn-crm-<?php echo e($view === 'today' ? 'primary' : 'light'); ?> btn-sm fw-bold px-3 py-2 text-decoration-none"
                       style="border-radius: 6px;">
                        <i class="bi bi-calendar-event me-1"></i> <?php echo e(__('مهام اليوم')); ?>

                    </a>
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['view' => 'all']))); ?>" 
                       class="btn-crm-<?php echo e($view === 'all' ? 'primary' : 'light'); ?> btn-sm fw-bold px-3 py-2 text-decoration-none"
                       style="border-radius: 6px;">
                        <i class="bi bi-collection me-1"></i> <?php echo e(__('كل المهام')); ?>

                    </a>
                </div>

                <span class="mx-1 d-none d-md-inline" style="width:1px;height:24px;background:var(--crm-border);display:inline-block;"></span>

                
                <div class="d-flex gap-1 align-items-center">
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['status' => null]))); ?>" class="btn-crm-<?php echo e(!$status ? 'primary' : 'light'); ?> btn-sm px-3 py-2 fw-bold text-decoration-none" style="border-radius: 8px;"><?php echo e(__('الكل')); ?></a>
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['status' => 'new']))); ?>" class="btn-crm-<?php echo e($status == 'new' ? 'primary' : 'light'); ?> btn-sm px-3 py-2 fw-bold text-decoration-none" style="border-radius: 8px;"><?php echo e(__('جديدة')); ?></a>
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['status' => 'in_progress']))); ?>" class="btn-crm-<?php echo e($status == 'in_progress' ? 'primary' : 'light'); ?> btn-sm px-3 py-2 fw-bold text-decoration-none" style="border-radius: 8px;"><?php echo e(__('قيد التنفيذ')); ?></a>
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['status' => 'done']))); ?>" class="btn-crm-<?php echo e($status == 'done' ? 'primary' : 'light'); ?> btn-sm px-3 py-2 fw-bold text-decoration-none" style="border-radius: 8px;"><?php echo e(__('مكتملة')); ?></a>
                </div>
            </div>

            
            <form action="<?php echo e(route('crm.tasks.index')); ?>" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                <input type="hidden" name="view" value="all">
                <?php if($status): ?>
                    <input type="hidden" name="status" value="<?php echo e($status); ?>">
                <?php endif; ?>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" name="from" value="<?php echo e($from); ?>" class="form-control form-control-sm border shadow-xs bg-light" style="width:140px;border-radius:8px;" placeholder="<?php echo e(__('من تاريخ')); ?>" title="<?php echo e(__('من تاريخ')); ?>">
                    <span class="text-muted small fw-bold"><?php echo e(__('إلى')); ?></span>
                    <input type="date" name="to" value="<?php echo e($to); ?>" class="form-control form-control-sm border shadow-xs bg-light" style="width:140px;border-radius:8px;" placeholder="<?php echo e(__('إلى تاريخ')); ?>" title="<?php echo e(__('إلى تاريخ')); ?>">
                </div>
                <button type="submit" class="btn-crm-primary btn-sm px-3 py-2 fw-bold" style="border-radius:8px;">
                    <i class="bi bi-funnel"></i> <?php echo e(__('فلترة')); ?>

                </button>
                <?php if($from || $to): ?>
                    <a href="<?php echo e(route('crm.tasks.index', $qs(['from' => null, 'to' => null, 'view' => $view === 'all' ? 'all' : null]))); ?>" class="btn btn-sm btn-outline-secondary px-3 py-2 fw-bold text-decoration-none" style="border-radius:8px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>


<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="crm-card h-100" style="position:relative; border-right: 4px solid <?php echo e($task->priority == 'high' ? 'var(--crm-red)' : ($task->priority == 'medium' ? '#FF9800' : '#4CAF50')); ?>">
            
            <div class="d-flex align-items-start justify-content-between mb-12">
                <span class="badge-<?php echo e($task->priority == 'high' ? 'rejected' : ($task->priority == 'medium' ? 'pending' : 'active')); ?>">
                    <?php echo e($task->priority_label); ?>

                </span>
                <span class="badge-<?php echo e($task->status == 'done' ? 'done' : ($task->status == 'in_progress' ? 'pending' : 'new')); ?>">
                    <?php echo e($task->status_label); ?>

                </span>
            </div>

            <h6 style="font-weight:800;font-size:15px;color:#1a1a2e;margin-bottom:8px;<?php echo e($task->status == 'done' ? 'text-decoration:line-through;opacity:0.5;' : ''); ?>">
                <?php echo e($task->title); ?>

            </h6>

            <?php if($task->description): ?>
            <p style="font-size:13px;color:#888;margin-bottom:12px;line-height:1.6;"><?php echo e(Str::limit($task->description, 80)); ?></p>
            <?php endif; ?>

            <div class="d-flex align-items-center gap-2 mt-auto" style="font-size:12px;color:#aaa;border-top:1px solid #f5f5f5;padding-top:12px;margin-top:12px;">
                <?php if($task->assignedTo): ?>
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo e($task->assignedTo->name); ?></span>
                    <span style="margin:0 4px">·</span>
                <?php endif; ?>
                <?php if($task->due_date): ?>
                    <i class="bi bi-calendar3"></i>
                    <span style="<?php echo e($task->due_date->isPast() && $task->status != 'done' ? 'color:var(--crm-red);font-weight:700;' : ''); ?>">
                        <?php echo e($task->due_date->format('d/m/Y')); ?>

                    </span>
                <?php endif; ?>
                <div class="d-flex gap-1 me-auto">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                    <form action="<?php echo e(route('crm.tasks.toggle', $task)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="btn-crm-light" style="padding:5px 10px;font-size:11px;" title="<?php echo e(__('تغيير الحالة')); ?>">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                    <form action="<?php echo e(route('crm.tasks.destroy', $task)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('حذف هذه المهمة؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn-crm-light" style="padding:5px 10px;font-size:11px;color:var(--crm-red);" title="<?php echo e(__('حذف')); ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="crm-card text-center py-5">
            <i class="bi bi-check2-square" style="font-size:48px;color:#ddd;display:block;margin-bottom:16px;"></i>
            <p style="color:#aaa;font-weight:700;"><?php echo e(__('لا توجد مهام. ابدأ بإضافة أول مهمة!')); ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="mt-4"><?php echo e($tasks->links()); ?></div>


<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><?php echo e(__('إضافة مهمة جديدة')); ?></h5>
                <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('crm.tasks.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?php echo e(__('عنوان المهمة')); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-3" required placeholder="<?php echo e(__('أدخل عنوان المهمة...')); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?php echo e(__('الوصف')); ?></label>
                        <textarea name="description" class="form-control rounded-3" rows="3" placeholder="<?php echo e(__('تفاصيل المهمة...')); ?>"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('الأولوية')); ?></label>
                            <select name="priority" class="form-select rounded-3">
                                <option value="low"><?php echo e(__('منخفضة')); ?></option>
                                <option value="medium" selected><?php echo e(__('متوسطة')); ?></option>
                                <option value="high"><?php echo e(__('عالية')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('الحالة')); ?></label>
                            <select name="status" class="form-select rounded-3">
                                <option value="new"><?php echo e(__('جديدة')); ?></option>
                                <option value="in_progress"><?php echo e(__('قيد التنفيذ')); ?></option>
                                <option value="done"><?php echo e(__('مكتملة')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('تاريخ الاستحقاق')); ?></label>
                            <input type="date" name="due_date" class="form-control rounded-3">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold"><?php echo e(__('إسناد إلى')); ?></label>
                            <select name="assigned_to" class="form-select rounded-3">
                                <option value=""><?php echo e(__('— اختر موظفاً —')); ?></option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-crm-light" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                    <button type="submit" class="btn-crm-primary"><?php echo e(__('إضافة المهمة')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/tasks/index.blade.php ENDPATH**/ ?>