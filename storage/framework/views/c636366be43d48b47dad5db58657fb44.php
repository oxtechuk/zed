<?php $__env->startSection('title', __('شاشة الموظف') . ' | GR Motors'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <?php
        $rangeLinks = [
            'today' => __('اليوم'),
            'week'  => __('هذا الأسبوع'),
            'month' => __('هذا الشهر'),
            'year'  => __('هذا العام'),
            'ytd'   => __('بداية السنة حتى اليوم'),
        ];
        $qs = fn (array $override = []) => array_filter(array_merge(request()->except('page'), $override), fn ($v) => $v !== null && $v !== '');
        $dotClassFor = fn ($status) => match($status) {
            'new','pending'  => 'planned',
            'in_progress','contacted' => 'waiting',
            'sold','done'    => 'done',
            'rejected'       => 'late',
            default          => 'confirmed',
        };
        $priorityColor = fn ($p) => match($p) {
            'high' => '#DC2626',
            'medium' => 'var(--crm-orange)',
            default => 'var(--crm-green)',
        };
    ?>

    
    <div class="rounded-4 mb-4 p-4 p-md-4" style="background:linear-gradient(135deg,var(--crm-orange) 0%,var(--crm-orange-dark) 100%);box-shadow:0 8px 20px rgba(249,115,22,0.25);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-1 fw-bold text-white">👋 <?php echo e(__('أهلاً بك')); ?>, <?php echo e(auth()->user()?->name); ?></h4>
                <div class="text-white" style="font-size:13px;opacity:0.9;"><?php echo e(now()->translatedFormat('l، d F Y')); ?></div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                <a href="<?php echo e(route('crm.bookings.index')); ?>" class="btn btn-sm rounded-3 fw-bold" style="background:rgba(255,255,255,0.18);color:#fff;padding:9px 16px;">
                    <i class="bi bi-plus-lg"></i> <?php echo e(__('إضافة حجز جديد')); ?>

                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-leads')): ?>
                <a href="<?php echo e(route('crm.leads.index')); ?>" class="btn btn-sm rounded-3 fw-bold" style="background:#fff;color:var(--crm-orange-dark);padding:9px 16px;">
                    <i class="bi bi-person-plus"></i> <?php echo e(__('إضافة عميل')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="d-flex gap-3 mb-4 pb-1" style="overflow-x:auto;">
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon blue"><i class="bi bi-bag"></i></div>
            <div class="stat-lbl"><?php echo e(__('إجمالي الطلبات')); ?></div>
            <div class="stat-val"><?php echo e(number_format($stats['total'])); ?></div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-lbl"><?php echo e(__('الطلبات المفتوحة')); ?></div>
            <div class="stat-val"><?php echo e(number_format($stats['open'])); ?></div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
            <div class="stat-lbl"><?php echo e(__('الطلبات المغلقة')); ?></div>
            <div class="stat-val"><?php echo e(number_format($stats['closed'])); ?></div>
        </div>
        <div class="crm-stat-new flex-shrink-0" style="min-width:220px;">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div class="stat-lbl"><?php echo e(__('الطلبات المكتملة (تم الاستلام)')); ?></div>
            <div class="stat-val"><?php echo e(number_format($stats['completed'])); ?></div>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-body p-4">
            <form action="<?php echo e(route('crm.dashboard')); ?>" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                
                <?php $__currentLoopData = $rangeLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('crm.dashboard', $qs(['range' => $key, 'from' => null, 'to' => null]))); ?>"
                       class="crm-filter-tab <?php echo e($range === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('crm.dashboard', $qs(['range' => null, 'from' => null, 'to' => null]))); ?>"
                   class="crm-filter-tab <?php echo e($range === 'all' ? 'active' : ''); ?>"><?php echo e(__('الكل')); ?></a>

                <span class="mx-1" style="width:1px;height:24px;background:var(--crm-border);display:inline-block;"></span>

                
                <input type="hidden" name="range" value="custom">
                <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control form-control-sm" style="width:150px;border-radius:8px;">
                <span class="text-muted small"><?php echo e(__('إلى')); ?></span>
                <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control form-control-sm" style="width:150px;border-radius:8px;">
                <?php $__currentLoopData = request()->except(['from','to','range','page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="submit" class="btn-crm-primary" style="padding:7px 16px;"><?php echo e(__('تطبيق')); ?></button>
            </form>

            <hr style="border-color:var(--crm-border);">

            
            <form action="<?php echo e(route('crm.dashboard')); ?>" method="GET" class="d-flex gap-2">
                <?php $__currentLoopData = request()->except(['search','page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="position-relative flex-grow-1" style="max-width:480px;">
                    <i class="bi bi-search position-absolute" style="<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:14px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('ابحث برقم الطلب، اسم العميل أو رقم الجوال...')); ?>"
                           class="form-control" style="padding-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:38px;border-radius:10px;background:#F8F9FC;border:1px solid var(--crm-border);">
                </div>
                <button type="submit" class="btn-crm-light"><?php echo e(__('بحث')); ?></button>
                <?php if($search): ?>
                <a href="<?php echo e(route('crm.dashboard', $qs(['search' => null]))); ?>" class="btn-crm-light"><?php echo e(__('مسح')); ?></a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('المهام اليومية')); ?></h6>
                    <a href="<?php echo e(route('crm.tasks.index')); ?>" class="fw-bold text-decoration-none" style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('عرض كل المهام')); ?></a>
                </div>
                <div class="card-body p-3">
                    <ul class="nav nav-pills gap-2 mb-3 px-1" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-today" type="button">
                                <?php echo e(__('المستحقة اليوم')); ?> <span class="badge bg-light text-dark ms-1"><?php echo e($tasksToday->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-overdue" type="button">
                                <?php echo e(__('المتأخرة')); ?> <span class="badge bg-light text-dark ms-1"><?php echo e($tasksOverdue->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" style="font-size:12px;" data-bs-toggle="pill" data-bs-target="#tasks-upcoming" type="button">
                                <?php echo e(__('القادمة')); ?> <span class="badge bg-light text-dark ms-1"><?php echo e($tasksUpcoming->count()); ?></span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" style="max-height:360px;overflow-y:auto;">
                        <?php $__currentLoopData = ['tasks-today' => $tasksToday, 'tasks-overdue' => $tasksOverdue, 'tasks-upcoming' => $tasksUpcoming]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paneId => $taskList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" id="<?php echo e($paneId); ?>">
                            <?php $__empty_1 = true; $__currentLoopData = $taskList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-3 rounded-3 mb-2" style="background:#F8F9FC;border:1px solid var(--crm-border);border-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:3px solid <?php echo e($priorityColor($task->priority)); ?>;">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <strong style="font-size:13px;"><?php echo e($task->title); ?></strong>
                                    <?php if($task->due_date): ?>
                                    <span class="text-nowrap" style="font-size:11px;color:var(--crm-text-muted);">
                                        <i class="bi bi-calendar3 me-1"></i><?php echo e($task->due_date->format('d/m/Y')); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-1 flex-wrap">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                                    <form action="<?php echo e(route('crm.tasks.start', $task)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;background:var(--crm-red-light);color:var(--crm-red);" title="<?php echo e(__('تنفيذ')); ?>">
                                            <i class="bi bi-play-fill"></i> <?php echo e(__('تنفيذ')); ?>

                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('crm.tasks.postpone', $task)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="days" value="1">
                                        <button class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;" title="<?php echo e(__('تأجيل يوم')); ?>">
                                            <i class="bi bi-clock-history"></i> <?php echo e(__('تأجيل')); ?>

                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('crm.tasks.complete', $task)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm rounded-2 fw-bold text-white" style="font-size:11px;background:var(--crm-green);" title="<?php echo e(__('إنهاء')); ?>">
                                            <i class="bi bi-check-lg"></i> <?php echo e(__('إنهاء')); ?>

                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted py-4" style="font-size:13px;"><?php echo e(__('لا توجد مهام')); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('الطلبات')); ?></h6>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('crm.dashboard', $qs(['sort' => 'priority']))); ?>" class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;<?php echo e($sort==='priority' ? 'background:var(--crm-red);color:#fff;' : 'background:#F8F9FC;color:var(--crm-text-muted);'); ?>"><?php echo e(__('الأولوية')); ?></a>
                        <a href="<?php echo e(route('crm.dashboard', $qs(['sort' => 'recent']))); ?>" class="btn btn-sm rounded-2 fw-bold" style="font-size:11px;<?php echo e($sort==='recent' ? 'background:var(--crm-red);color:#fff;' : 'background:#F8F9FC;color:var(--crm-text-muted);'); ?>"><?php echo e(__('آخر تحديث')); ?></a>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height:440px;overflow-y:auto;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#F8F9FC;">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم الطلب')); ?></th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('العميل')); ?></th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('السيارة')); ?></th>
                                    <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الحالة')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-4 fw-bold" style="font-size:13px;">
                                        <a href="<?php echo e(route('crm.bookings.show', $booking)); ?>" class="text-decoration-none" style="color:var(--crm-text);">#<?php echo e($booking->id); ?></a>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="font-size:13px;color:var(--crm-text);"><?php echo e($booking->client_name); ?></div>
                                        <small class="text-muted"><?php echo e($booking->client_phone); ?></small>
                                    </td>
                                    <td style="font-size:12px;color:var(--crm-text);"><?php echo e($booking->car->name ?? '—'); ?></td>
                                    <td><span class="status-dot <?php echo e($dotClassFor($booking->status)); ?>"><?php echo e($booking->status_label); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="text-center text-muted py-5"><?php echo e(__('لا توجد طلبات')); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-4 py-3" style="border-top:1px solid var(--crm-border);">
                    <a href="<?php echo e(route('crm.bookings.index')); ?>" class="text-decoration-none fw-bold" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('عرض كل الطلبات')); ?></a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0"><?php echo e(__('التنبيهات')); ?></h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#FFF8EC;border:1px solid #FDEBC8;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-clock-history" style="color:var(--crm-orange);"></i>
                            <strong style="font-size:13px;"><?php echo e(__('مهام مستحقة')); ?></strong>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $alerts['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #FDEBC8;">
                            <span style="font-size:12px;"><?php echo e(Str::limit($task->title, 28)); ?></span>
                            <a href="<?php echo e(route('crm.tasks.index')); ?>" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-orange-dark);"><?php echo e(__('فتح')); ?></a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted text-center py-2" style="font-size:12px;"><?php echo e(__('لا توجد تنبيهات')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#EFF8FF;border:1px solid #D6E9FF;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-telephone-outbound" style="color:var(--crm-blue);"></i>
                            <strong style="font-size:13px;"><?php echo e(__('طلبات بانتظار المتابعة')); ?></strong>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $alerts['follow_ups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #D6E9FF;">
                            <span style="font-size:12px;"><?php echo e($booking->client_name); ?></span>
                            <a href="<?php echo e(route('crm.bookings.show', $booking)); ?>" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-blue);"><?php echo e(__('عرض')); ?></a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted text-center py-2" style="font-size:12px;"><?php echo e(__('لا توجد تنبيهات')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="col-12 col-lg-4">
                    <div class="p-3 rounded-3 h-100" style="background:#FFF0F0;border:1px solid #FFCDD2;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-patch-check" style="color:var(--crm-red);"></i>
                            <strong style="font-size:13px;"><?php echo e(__('طلبات بانتظار اعتماد المشرف')); ?></strong>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $alerts['approvals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px dashed #FFCDD2;">
                            <span style="font-size:12px;">#<?php echo e($booking->id); ?> — <?php echo e($booking->client_name); ?></span>
                            <a href="<?php echo e(route('crm.bookings.show', $booking)); ?>" class="fw-bold text-decoration-none" style="font-size:11px;color:var(--crm-red);"><?php echo e(__('عرض')); ?></a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted text-center py-2" style="font-size:12px;"><?php echo e(__('لا توجد تنبيهات')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/dashboard.blade.php ENDPATH**/ ?>