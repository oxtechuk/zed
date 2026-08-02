<?php $__env->startSection('title', __('الطلبات') . ' | GR Motors'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('الطلبات')); ?></span>
    </nav>


    
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge orange">65%</span>
                <div class="stat-icon red"><i class="bi bi-clock"></i></div>
                <div class="stat-lbl"><?php echo e(__('بانتظار المراجعة')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['new'] ?? 0)); ?></div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge green">+3%</span>
                <div class="stat-icon blue"><i class="bi bi-people"></i></div>
                <div class="stat-lbl"><?php echo e(__('عدد طلبات اليوم')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['in_progress'] ?? 0)); ?></div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <span class="stat-badge green">+12%</span>
                <div class="stat-icon purple"><i class="bi bi-person-lines-fill"></i></div>
                <div class="stat-lbl"><?php echo e(__('إجمالي عدد الطلبات')); ?></div>
                <div class="stat-val"><?php echo e(number_format($bookings->total())); ?></div>
            </div>
        </div>
    </div>

    
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                    <div style="position:relative;">
                        <input type="date" name="date" value="<?php echo e(request('date', now()->format('Y-m-d'))); ?>"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-calendar3" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:10px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);pointer-events:none;"></i>
                    </div>
                    
                    <select name="type" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;">
                        <option value=""><?php echo e(__('مصرف الخدمة — الكل')); ?></option>
                        <option value="loan" <?php echo e(request('type')=='loan'?'selected':''); ?>><?php echo e(__('تمويل')); ?></option>
                        <option value="test" <?php echo e(request('type')=='test'?'selected':''); ?>><?php echo e(__('تجربة قيادة')); ?></option>
                        <option value="booking" <?php echo e(request('type')=='booking'?'selected':''); ?>><?php echo e(__('حجز سيارة')); ?></option>
                    </select>
                    
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:140px;">
                        <option value=""><?php echo e(__('الحالة — الكل')); ?></option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status')===$key?'selected':''); ?>><?php echo e($s['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('بحث...')); ?>"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>
                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;"><?php echo e(__('تصفية')); ?></button>
                    <a href="<?php echo e(route('crm.bookings.index')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>



    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0"><?php echo e(__('سجل الطلبات')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي الطلبات')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
        </div>

        
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الراتب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('نوع الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('نوع السيارات')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('سعر السيارة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('المسؤول')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تاريخ الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الحالة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('إجراءات')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 fw-bold" style="font-size:13px;">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="text-decoration-none" style="color:var(--crm-text);">#<?php echo e($b->id); ?></a>
                        </td>
                        <td>
                            <div class="fw-bold" style="font-size:13px;color:var(--crm-text);"><?php echo e($b->client_name); ?></div>
                            <small class="text-muted" dir="ltr"><?php echo e($b->client_phone); ?></small>
                        </td>
                        <td style="font-size:13px;">
                            <?php echo e(number_format($b->monthly_installment)); ?>

                            <small class="text-muted"><?php echo __('ريال'); ?></small>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('طلب تجربة قيادة')); ?></td>
                        <td>
                            <div style="font-size:12px;color:var(--crm-text);"><?php echo e($b->car?->name ?? '—'); ?></div>
                            <small class="text-muted"><?php echo e($b->car?->brand?->name); ?></small>
                        </td>
                        <td style="font-size:13px;font-weight:700;">
                            <?php echo e(number_format($b->car?->cash_price ?? 0)); ?>

                            <small class="text-muted fw-normal"><?php echo __('ريال'); ?></small>
                        </td>
                        <td style="font-size:12px;">
                            <form action="<?php echo e(route('crm.bookings.assign', $b)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="d-flex align-items-center gap-1 bg-light rounded-pill p-1 pe-2 border" style="width: fit-content; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--crm-red)'" onmouseout="this.style.borderColor='var(--crm-border)'">
                                    <?php if($b->employee): ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:24px;height:24px;font-size:10px;font-weight:bold;background:#16254F;">
                                            <?php echo e(strtoupper(substr($b->employee->name, 0, 1))); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-muted flex-shrink-0 border shadow-sm" style="width:24px;height:24px;font-size:12px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    <?php endif; ?>
                                    <select <?php if(!auth()->user()->hasRole('admin')): echo 'disabled'; endif; ?> name="employee_id" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-bold p-0 ps-1" style="font-size:12px;color:var(--crm-text);width:auto;cursor:pointer;background-image:none;outline:none;" onchange="this.form.submit()">
                                        <option value=""><?php echo e(__('غير معين')); ?></option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($emp->id); ?>" <?php echo e($b->assigned_to == $emp->id ? 'selected' : ''); ?>><?php echo e($emp->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="bi bi-chevron-down text-muted" style="font-size:10px; pointer-events: none;"></i>
                                </div>
                            </form>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);"><?php echo e($b->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <?php
                                    $dotClass = match($b->status) {
                                        'new','pending'  => 'planned',
                                        'in_progress'    => 'waiting',
                                        'sold','done'    => 'done',
                                        'rejected'       => 'late',
                                        default          => 'confirmed',
                                    };
                                ?>
                                <select name="status" class="form-select form-select-sm border-0 shadow-none status-dot <?php echo e($dotClass); ?>" style="font-size:12px;font-weight:700;padding-top:4px;padding-bottom:4px;width:auto;display:inline-block;" onchange="this.form.submit()">
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2" title="<?php echo e(__('عرض')); ?>">
                                    <i class="bi bi-eye" style="font-size:14px;"></i>
                                </a>
                                <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2" title="<?php echo e(__('واتساب')); ?>" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);" title="<?php echo e(__('حذف')); ?>">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <?php echo e(__('لا توجد طلبات حالياً')); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-md-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $dotClassM = match($b->status) {
                    'new','pending'  => 'planned',
                    'in_progress'    => 'waiting',
                    'sold','done'    => 'done',
                    'rejected'       => 'late',
                    default          => 'confirmed',
                };
            ?>
            <div class="mb-3 p-3 rounded-3" style="border:1px solid var(--crm-border);background:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);"><?php echo e($b->client_name); ?></div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-select form-select-sm border-0 shadow-none status-dot <?php echo e($dotClassM); ?>" style="font-size:11px;font-weight:700;padding:3px 8px;width:auto;" onchange="this.form.submit()">
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>
                </div>
                <div class="d-flex align-items-center justify-content-between" style="font-size:12px;color:var(--crm-text-muted);border-top:1px solid var(--crm-border);padding-top:10px;margin-top:8px;">
                    <div>
                        <i class="bi bi-car-front me-1"></i>
                        <?php echo e($b->car?->brand?->name); ?> <?php echo e($b->car?->name ?? '—'); ?>

                    </div>
                    <div>
                        <form action="<?php echo e(route('crm.bookings.assign', $b)); ?>" method="POST" class="m-0 d-inline-block">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <div class="d-flex align-items-center gap-1 bg-light rounded-pill px-2 py-1 border" style="width: fit-content;">
                                <?php if($b->employee): ?>
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:20px;height:20px;font-size:9px;font-weight:bold;background:#16254F;"><?php echo e(strtoupper(substr($b->employee->name,0,1))); ?></span>
                                <?php else: ?>
                                    <i class="bi bi-person-circle text-muted"></i>
                                <?php endif; ?>
                                <select name="employee_id" class="form-select form-select-sm border-0 shadow-none bg-transparent p-0 fw-bold" style="font-size:11px;color:var(--crm-text);width:auto;display:inline-block;background-image:none;" onchange="this.form.submit()">
                                    <option value=""><?php echo e(__('غير معين')); ?></option>
                                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($emp->id); ?>" <?php echo e($b->assigned_to == $emp->id ? 'selected' : ''); ?>><?php echo e($emp->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> <?php echo e(__('عرض')); ?>

                    </a>
                    <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;color:#25D366;">
                        <i class="bi bi-whatsapp"></i> <?php echo e(__('واتساب')); ?>

                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                    <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);font-size:12px;"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                <?php echo e(__('لا توجد طلبات حالياً')); ?>

            </div>
            <?php endif; ?>
        </div>

        <?php if($bookings->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            <?php echo e($bookings->links()); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
    <button class="btn btn-crm-primary position-fixed shadow-lg d-flex align-items-center justify-content-center hover-lift"
            style="bottom: 30px; left: 30px; width: 60px; height: 60px; border-radius: 50%; z-index: 1050; border: none; background: #16254F;"
            data-bs-toggle="modal" data-bs-target="#createBookingModal" title="<?php echo e(__('إضافة طلب جديد')); ?>">
        <i class="bi bi-plus" style="font-size: 2rem; color: #fff;"></i>
    </button>
    <?php endif; ?>

    
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FAF9F6;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" style="color: var(--crm-text);"><?php echo e(__('إضافة عميل / طلب')); ?></h5>
                    <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('crm.bookings.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body px-4 py-4">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small"><?php echo e(__('اسم العميل')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="client_name" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" required placeholder="<?php echo e(__('اسم العميل')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small"><?php echo e(__('رقم الهاتف')); ?> <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-0 text-muted" dir="ltr" style="font-size: 14px;">+966 🇸🇦</span>
                                    <input type="text" name="client_phone" class="form-control border-0" style="font-size: 14px;" required placeholder="5X XXX XXXX" dir="ltr">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small"><?php echo e(__('البريد الإلكتروني')); ?></label>
                                <input type="email" name="client_email" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" placeholder="<?php echo e(__('البريد الإلكتروني (اختياري)')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small"><?php echo e(__('نوع الطلب')); ?></label>
                                <select name="type" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;">
                                    <option value="booking"><?php echo e(__('حجز سيارة')); ?></option>
                                    <option value="loan"><?php echo e(__('تمويل')); ?></option>
                                    <option value="test"><?php echo e(__('تجربة قيادة')); ?></option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="p-4 mb-4" style="background: #F4EFF0; border-radius: 16px;">
                            <h6 class="fw-bold mb-3" style="color: var(--crm-text);"><?php echo e(__('تفاصيل السيارة')); ?></h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small"><?php echo e(__('السيارة المطلوبة')); ?></label>
                                    <select name="car_id" class="form-select form-select-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" id="bookingCarSelect">
                                        <option value=""><?php echo e(__('اختر سيارة (أو اتركها فارغة)')); ?></option>
                                        <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($car->id); ?>" data-price="<?php echo e($car->cash_price); ?>" data-installment="<?php echo e($car->min_installment); ?>"><?php echo e($car->brand->name ?? ''); ?> <?php echo e($car->name); ?> (<?php echo e($car->year); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small"><?php echo e(__('سعر السيارة الإجمالي')); ?></label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="total_price" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small"><?php echo e(__('الدفعة الأولى (إن وجدت)')); ?></label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="down_payment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small"><?php echo e(__('القسط الشهري المتوقع')); ?></label>
                                    <div class="input-group input-group-lg bg-white border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <input type="number" name="monthly_installment" class="form-control border-0" style="font-size: 14px;">
                                        <span class="input-group-text bg-white border-0 text-muted" style="font-size: 14px;">ر.س</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small"><?php echo e(__('مدة التمويل (سنوات)')); ?></label>
                                    <input type="number" name="duration_years" class="form-control form-control-lg bg-white border-0 shadow-sm" style="border-radius: 12px; font-size: 14px;" value="5">
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted small"><?php echo e(__('ملاحظات إضافية')); ?></label>
                            <textarea name="notes" class="form-control bg-white border-0 shadow-sm" rows="2" style="border-radius: 12px; font-size: 14px;" placeholder="<?php echo e(__('إضافة ملاحظة...')); ?>"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 flex-nowrap">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                        <button type="submit" class="btn flex-fill fw-bold py-3 text-white" style="background: #16254F; border-radius: 12px;"><?php echo e(__('حفظ بيانات العميل')); ?></button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary flex-fill fw-bold py-3" data-bs-dismiss="modal" style="border-radius: 12px; background: white;"><?php echo e(__('إلغاء')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-4px) scale(1.05); box-shadow: 0 1rem 3rem rgba(227, 6, 19, 0.4) !important; }
    .modal-backdrop.show { opacity: 0.6; backdrop-filter: blur(4px); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carSelect = document.getElementById('bookingCarSelect');
        const priceInput = document.querySelector('input[name="total_price"]');
        const installmentInput = document.querySelector('input[name="monthly_installment"]');

        if(carSelect) {
            carSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if(selectedOption.value) {
                    const price = selectedOption.getAttribute('data-price');
                    const installment = selectedOption.getAttribute('data-installment');

                    if(priceInput) priceInput.value = price || '';
                    if(installmentInput) installmentInput.value = installment || '';
                } else {
                    if(priceInput) priceInput.value = '';
                    if(installmentInput) installmentInput.value = '';
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Projects\XO\Zad\resources\views/crm/bookings/index.blade.php ENDPATH**/ ?>