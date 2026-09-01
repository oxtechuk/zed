<?php $__env->startSection('title', __('طلبات قيد الانتظار') . ' | Zad Capital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <a href="<?php echo e(route('crm.bookings.index')); ?>"><?php echo e(__('الطلبات')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('طلبات قيد الانتظار')); ?></span>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#FEF3C7;color:#D97706;">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                <?php echo e(__('طلبات قيد الانتظار')); ?>

            </h4>
            <p class="text-muted small mb-0"><?php echo e(__('متابعة الطلبات المعلقة مع أسباب الانتظار ومواعيد إعادة التواصل والتنفيذ')); ?></p>
        </div>
        <a href="<?php echo e(route('crm.bookings.index')); ?>" class="btn btn-sm btn-outline-secondary rounded-3 fw-bold px-3">
            <i class="bi bi-arrow-right me-1"></i> <?php echo e(__('العودة لمسار المبيعات النشط')); ?>

        </a>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-lbl"><?php echo e(__('إجمالي قيد الانتظار')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['total'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
                <div class="stat-lbl"><?php echo e(__('مواعيد متابعة اليوم')); ?></div>
                <div class="stat-val text-primary"><?php echo e(number_format($stats['today'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-lbl"><?php echo e(__('متابعات متأخرة')); ?></div>
                <div class="stat-val text-danger"><?php echo e(number_format($stats['overdue'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new">
                <div class="stat-icon purple"><i class="bi bi-clock-history"></i></div>
                <div class="stat-lbl"><?php echo e(__('متابعات قادمة')); ?></div>
                <div class="stat-val" style="color:#7C3AED;"><?php echo e(number_format($stats['upcoming'])); ?></div>
            </div>
        </div>
    </div>

    
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                    
                    <?php if($isAdmin): ?>
                    <div style="min-width:180px;">
                        <select name="employee_id" class="form-select form-select-sm" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;" onchange="this.form.submit()">
                            <option value=""><?php echo e(__('الموظف — جميع الموظفين')); ?></option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>" <?php echo e(request('employee_id') == $emp->id ? 'selected' : ''); ?>>
                                    <?php echo e($emp->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    
                    <div style="position:relative;">
                        <input type="month" name="month" value="<?php echo e(request('month')); ?>"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; min-width: 150px;"
                               onchange="this.form.submit()" title="<?php echo e(__('تصفية بالشهر')); ?>">
                    </div>

                    
                    <select name="timing" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;" onchange="this.form.submit()">
                        <option value=""><?php echo e(__('موعد المتابعة — الكل')); ?></option>
                        <option value="today" <?php echo e(request('timing')==='today'?'selected':''); ?>><?php echo e(__('متابعات اليوم')); ?></option>
                        <option value="overdue" <?php echo e(request('timing')==='overdue'?'selected':''); ?>><?php echo e(__('متأخرة عن الموعد')); ?></option>
                        <option value="upcoming" <?php echo e(request('timing')==='upcoming'?'selected':''); ?>><?php echo e(__('مواعيد قادمة')); ?></option>
                    </select>

                    
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value=""><?php echo e(__('المصدر والنوع — الكل')); ?></option>
                        <option value="cars" <?php echo e(request('source')==='cars'?'selected':''); ?>><?php echo e(__('طلبات السيارات (حجز وشراء)')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>><?php echo e(__('عملاء حاسبة التمويل')); ?></option>
                        <option value="test_drive" <?php echo e(request('source')==='test_drive'?'selected':''); ?>><?php echo e(__('طلبات تجربة القيادة')); ?></option>
                    </select>

                    
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:170px;">
                        <option value="nearest_follow_up" <?php echo e(request('sort','nearest_follow_up')==='nearest_follow_up'?'selected':''); ?>><?php echo e(__('الأقرب في موعد المتابعة')); ?></option>
                        <option value="furthest_follow_up" <?php echo e(request('sort')==='furthest_follow_up'?'selected':''); ?>><?php echo e(__('الأبعد في موعد المتابعة')); ?></option>
                        <option value="newest" <?php echo e(request('sort')==='newest'?'selected':''); ?>><?php echo e(__('الأحدث إنشاءً')); ?></option>
                        <option value="oldest" <?php echo e(request('sort')==='oldest'?'selected':''); ?>><?php echo e(__('الأقدم إنشاءً')); ?></option>
                    </select>

                    
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('بحث بالاسم أو الهاتف...')); ?>"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;"><?php echo e(__('تصفية')); ?></button>
                    <a href="<?php echo e(route('crm.bookings.pending')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-1 text-warning"></i> <?php echo e(__('قائمة الطلبات المعلقة (قيد الانتظار)')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي النتائج')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
        </div>

        
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('السيارة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الموظف')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;min-width:200px;"><?php echo e(__('سبب الانتظار')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('موعد المتابعة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تغيير الحالة / Action')); ?></th>
                        <th class="py-3 text-muted fw-bold px-4" style="font-size:12px;"><?php echo e(__('تحكم')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isOverdue = $b->follow_up_at && $b->follow_up_at->isPast() && !$b->follow_up_at->isToday();
                        $isToday = $b->follow_up_at && $b->follow_up_at->isToday();
                    ?>
                    <tr style="<?php echo e($isOverdue ? 'background-color:#FFF5F5;' : ($isToday ? 'background-color:#FFFBEB;' : '')); ?>">
                        <td class="px-4 text-muted small" style="font-size:13px;">
                            <?php echo e($bookings->firstItem() + $index); ?>

                        </td>
                        <td class="fw-bold" style="font-size:13px;">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#<?php echo e($b->id); ?></a>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;"><?php echo e($b->client_name); ?></div>
                            <a href="tel:<?php echo e($b->client_phone); ?>" class="text-decoration-none small text-muted" dir="ltr"><?php echo e($b->client_phone); ?></a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <strong class="text-dark"><?php echo e($b->car?->brand?->name ?? ''); ?> <?php echo e($b->car?->name ?? '—'); ?></strong>
                                <?php if($b->car?->year): ?>
                                    <span class="text-muted small">(<?php echo e($b->car->year); ?>)</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size:12px;">
                                <i class="bi bi-person me-1"></i> <?php echo e($b->employee?->name ?? __('غير معين')); ?>

                            </span>
                        </td>
                        <td>
                            <div class="p-2 rounded-3" style="background:#FFF9EB;border:1px dashed #FDE68A;font-size:12px;color:#92400E;line-height:1.6;">
                                <i class="bi bi-chat-left-quote me-1 text-warning"></i>
                                <strong><?php echo e($b->pending_reason ?: __('لا يوجد سبب محدد')); ?></strong>
                            </div>
                        </td>
                        <td>
                            <?php if($b->follow_up_at): ?>
                                <div class="d-flex flex-column" style="font-size:12px;">
                                    <span class="fw-bold <?php echo e($isOverdue ? 'text-danger' : ($isToday ? 'text-warning-dark fw-black' : 'text-dark')); ?>">
                                        <i class="bi bi-clock me-1"></i> <?php echo e($b->follow_up_at->format('d/m/Y - h:i A')); ?>

                                    </span>
                                    <?php if($isOverdue): ?>
                                        <span class="badge bg-danger text-white mt-1" style="font-size:10px;width:fit-content;"><?php echo e(__('متأخرة')); ?> (<?php echo e($b->follow_up_at->diffForHumans()); ?>)</span>
                                    <?php elseif($isToday): ?>
                                        <span class="badge bg-warning text-dark mt-1" style="font-size:10px;width:fit-content;"><?php echo e(__('اليوم')); ?> (<?php echo e($b->follow_up_at->diffForHumans()); ?>)</span>
                                    <?php else: ?>
                                        <span class="text-muted mt-1" style="font-size:11px;"><?php echo e($b->follow_up_at->diffForHumans()); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            
                            <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <select name="status" class="form-select form-select-sm border shadow-none fw-bold"
                                        style="font-size:12px;border-radius:8px;background:#F8FAFC;min-width:150px;cursor:pointer;"
                                        data-current-status="pending"
                                        onchange="handleBookingStatusSelectChange(this, <?php echo e($b->id); ?>, '<?php echo e(route('crm.bookings.status', $b)); ?>', <?php echo e($isAdmin ? 'true' : 'false'); ?>)">
                                    <option value="pending" selected><?php echo e(__('قيد الانتظار')); ?></option>
                                    <optgroup label="<?php echo e(__('إرجاع إلى مسار المبيعات النشط')); ?>">
                                         <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <?php if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'pending' && $key !== 'waiting_supervisor_approval'): ?>
                                                 <option value="<?php echo e($key); ?>"><?php echo e($s['label']); ?></option>
                                             <?php endif; ?>
                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                     </optgroup>
                                     <optgroup label="<?php echo e(__('تسليم الطلب (ناجح)')); ?>">
                                         <option value="received" data-close="1"><?php echo e(__('تم التسليم (تم الاستلام)')); ?></option>
                                     </optgroup>
                                     <optgroup label="<?php echo e(__('إغلاق الحجز (خاسر)')); ?>">
                                         <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <?php if(($s['group'] ?? '') === 'lost'): ?>
                                                 <option value="<?php echo e($key); ?>" data-close="1"><?php echo e($s['label']); ?></option>
                                             <?php endif; ?>
                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                     </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-4">
                            <div class="d-flex gap-1 align-items-center">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض التفاصيل')); ?>">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('واتساب')); ?>" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                <?php if($isAdmin): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-light rounded-2 border" style="color:var(--crm-red);" title="<?php echo e(__('حذف')); ?>">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-hourglass-bottom fs-1 d-block mb-2 opacity-25 text-warning"></i>
                            <div class="fw-bold"><?php echo e(__('لا توجد طلبات قيد الانتظار حالياً')); ?></div>
                            <small class="text-muted"><?php echo e(__('أي طلب يتم نقله إلى حالة "قيد الانتظار" سيظهر في هذه القائمة تلقائياً')); ?></small>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-md-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isOverdueM = $b->follow_up_at && $b->follow_up_at->isPast() && !$b->follow_up_at->isToday();
                $isTodayM = $b->follow_up_at && $b->follow_up_at->isToday();
            ?>
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:<?php echo e($isOverdueM ? '#FFF5F5' : ($isTodayM ? '#FFFBEB' : '#fff')); ?>;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);"><?php echo e($b->client_name); ?></div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                    <span class="badge" style="background:#FEF3C7;color:#D97706;font-size:11px;">
                        <i class="bi bi-hourglass-split me-1"></i> <?php echo e(__('قيد الانتظار')); ?>

                    </span>
                </div>

                
                <div class="p-2 rounded-3 mb-2" style="background:#FFF9EB;border:1px dashed #FDE68A;font-size:12px;color:#92400E;">
                    <i class="bi bi-chat-left-quote me-1"></i> <strong><?php echo e($b->pending_reason ?: __('لا يوجد سبب محدد')); ?></strong>
                </div>

                
                <?php if($b->follow_up_at): ?>
                <div class="mb-2 d-flex justify-content-between align-items-center" style="font-size:12px;">
                    <span class="text-muted"><?php echo e(__('موعد المتابعة:')); ?></span>
                    <span class="fw-bold <?php echo e($isOverdueM ? 'text-danger' : ($isTodayM ? 'text-warning-dark' : 'text-dark')); ?>">
                        <i class="bi bi-clock me-1"></i> <?php echo e($b->follow_up_at->format('d/m/Y - h:i A')); ?>

                    </span>
                </div>
                <?php endif; ?>

                
                <div class="mt-3 pt-2 border-top">
                    <label class="form-label small fw-bold text-muted mb-1"><?php echo e(__('تغيير الحالة / إرجاع للمسار:')); ?></label>
                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-select form-select-sm"
                                style="font-size:12px;border-radius:8px;"
                                data-current-status="pending"
                                onchange="handleBookingStatusSelectChange(this, <?php echo e($b->id); ?>, '<?php echo e(route('crm.bookings.status', $b)); ?>', <?php echo e($isAdmin ? 'true' : 'false'); ?>)">
                            <option value="pending" selected><?php echo e(__('قيد الانتظار')); ?></option>
                            <optgroup label="<?php echo e(__('إرجاع إلى مسار المبيعات النشط')); ?>">
                                <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'pending' && $key !== 'waiting_supervisor_approval'): ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($s['label']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="<?php echo e(__('تسليم الطلب (ناجح)')); ?>">
                                <option value="received" data-close="1"><?php echo e(__('تم التسليم')); ?></option>
                            </optgroup>
                            <optgroup label="<?php echo e(__('إغلاق الحجز (خاسر)')); ?>">
                                <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($s['group'] ?? '') === 'lost'): ?>
                                        <option value="<?php echo e($key); ?>" data-close="1"><?php echo e($s['label']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        </select>
                    </form>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> <?php echo e(__('عرض')); ?>

                    </a>
                    <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;color:#25D366;">
                        <i class="bi bi-whatsapp"></i> <?php echo e(__('واتساب')); ?>

                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-hourglass-bottom fs-1 d-block mb-2 opacity-25 text-warning"></i>
                <div><?php echo e(__('لا توجد طلبات قيد الانتظار حالياً')); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if($bookings->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            <?php echo e($bookings->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php echo $__env->make('crm.bookings.partials.status-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/pending.blade.php ENDPATH**/ ?>