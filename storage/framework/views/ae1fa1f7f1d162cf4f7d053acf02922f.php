<?php $__env->startSection('title', __('الطلبات النشطة') . ' | Zad Capital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('الطلبات النشطة')); ?></span>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#EBF5FF;color:#2563EB;">
                    <i class="bi bi-lightning-charge"></i>
                </span>
                <?php echo e(__('الطلبات النشطة (Active)')); ?>

            </h4>
            <p class="text-muted small mb-0"><?php echo e(__('مسار المبيعات الفعلي ومتابعة الطلبات الجارية مع العملاء والبنوك')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('crm.bookings.pending')); ?>" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-3 px-3">
                <i class="bi bi-hourglass-split me-1 text-warning"></i> <?php echo e(__('قيد الانتظار')); ?>

            </a>
            <a href="<?php echo e(route('crm.bookings.delivered')); ?>" class="btn btn-sm btn-outline-success fw-bold rounded-3 px-3">
                <i class="bi bi-check2-circle me-1"></i> <?php echo e(__('تم التسليم')); ?>

            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
            <button class="btn btn-crm-primary btn-sm rounded-3 fw-bold px-3" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة طلب جديد')); ?>

            </button>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2 col-md-4">
            <a href="<?php echo e(route('crm.bookings.index')); ?>" class="text-decoration-none d-block h-100">
                <div class="crm-stat-new h-100 <?php echo e(!request('source') ? 'border-primary' : ''); ?>" style="background:#fff;">
                    <span class="stat-badge blue"><?php echo e(__('الكل')); ?></span>
                    <div class="stat-icon blue"><i class="bi bi-layers-fill"></i></div>
                    <div class="stat-lbl"><?php echo e(__('إجمالي الطلبات النشطة')); ?></div>
                    <div class="stat-val"><?php echo e(number_format($stats['total'])); ?></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-2 col-md-4">
            <a href="<?php echo e(route('crm.bookings.index', array_merge(request()->query(), ['source' => 'cars']))); ?>" class="text-decoration-none d-block h-100">
                <div class="crm-stat-new h-100 <?php echo e(request('source') === 'cars' ? 'border-primary' : ''); ?>" style="background: linear-gradient(135deg, #FFF, #EFF6FF);">
                    <span class="stat-badge blue"><?php echo e(__('سيارات')); ?></span>
                    <div class="stat-icon blue" style="background:#DBEAFE;color:#1D4ED8;"><i class="bi bi-car-front-fill"></i></div>
                    <div class="stat-lbl"><?php echo e(__('طلبات السيارات')); ?></div>
                    <div class="stat-val" style="color:#1D4ED8;"><?php echo e(number_format($stats['car_requests'])); ?></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3 col-md-4">
            <a href="<?php echo e(route('crm.bookings.index', array_merge(request()->query(), ['source' => 'calculator']))); ?>" class="text-decoration-none d-block h-100">
                <div class="crm-stat-new h-100 <?php echo e(request('source') === 'calculator' ? 'border-primary' : ''); ?>" style="background: linear-gradient(135deg, #FFF, #FAF5FF);">
                    <span class="stat-badge orange" style="background:#F3E8FF;color:#7C3AED;"><?php echo e(__('حاسبة')); ?></span>
                    <div class="stat-icon purple" style="background:#EDE9FE;color:#7C3AED;"><i class="bi bi-calculator-fill"></i></div>
                    <div class="stat-lbl"><?php echo e(__('عملاء حاسبة التمويل')); ?></div>
                    <div class="stat-val" style="color:#7C3AED;"><?php echo e(number_format($stats['calculator_leads'])); ?></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-2 col-md-6">
            <div class="crm-stat-new h-100">
                <span class="stat-badge orange"><?php echo e(__('متابعة')); ?></span>
                <div class="stat-icon orange"><i class="bi bi-telephone-inbound-fill"></i></div>
                <div class="stat-lbl"><?php echo e(__('بانتظار التواصل والمراجعة')); ?></div>
                <div class="stat-val text-warning"><?php echo e(number_format($stats['pending_review'])); ?></div>
            </div>
        </div>
        <div class="col-12 col-xl-3 col-md-6">
            <div class="crm-stat-new h-100" style="background: linear-gradient(135deg, #FFF, #F0FDF4);">
                <span class="stat-badge green"><?php echo e(__('بنوك')); ?></span>
                <div class="stat-icon green"><i class="bi bi-bank2"></i></div>
                <div class="stat-lbl"><?php echo e(__('تحت الدراسة والتعميد')); ?></div>
                <div class="stat-val text-success"><?php echo e(number_format($stats['under_bank'])); ?></div>
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

                    
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value=""><?php echo e(__('المصدر والنوع — الكل')); ?></option>
                        <option value="cars" <?php echo e(request('source')==='cars'?'selected':''); ?>>🚗 <?php echo e(__('طلبات السيارات (حجز وشراء)')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>>🧮 <?php echo e(__('عملاء حاسبة التمويل')); ?></option>
                        <option value="test_drive" <?php echo e(request('source')==='test_drive'?'selected':''); ?>>⏱️ <?php echo e(__('طلبات تجربة القيادة')); ?></option>
                        <option value="purchase" <?php echo e(request('source')==='purchase'?'selected':''); ?>>💳 <?php echo e(__('طلبات الشراء النقدي')); ?></option>
                        <option value="crm_manual" <?php echo e(request('source')==='crm_manual'?'selected':''); ?>>📋 <?php echo e(__('طلبات داخلية (CRM)')); ?></option>
                    </select>

                    
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;" onchange="this.form.submit()">
                        <option value=""><?php echo e(__('الحالة — جميع الحالات النشطة')); ?></option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status')===$key?'selected':''); ?>><?php echo e($s['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:150px;" onchange="this.form.submit()">
                        <option value="newest" <?php echo e(request('sort','newest')==='newest'?'selected':''); ?>><?php echo e(__('الأحدث أولاً')); ?></option>
                        <option value="oldest" <?php echo e(request('sort','newest')==='oldest'?'selected':''); ?>><?php echo e(__('الأقدم أولاً')); ?></option>
                    </select>

                    
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('بحث بالاسم أو الهاتف...')); ?>"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;"><?php echo e(__('تصفية')); ?></button>
                    <a href="<?php echo e(route('crm.bookings.index')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>

    
    <?php if($isAdmin && $pendingApprovals->isNotEmpty()): ?>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border: 2px solid #FCA5A5 !important; background: #FFF5F5;">
        <div class="card-header border-0 px-4 py-3 d-flex justify-content-between align-items-center"
             style="background: linear-gradient(135deg, #FEE2E2, #FFF5F5); border-bottom: 1px solid #FCA5A5 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-hourglass-split" style="color: #DC2626; font-size: 18px;"></i>
                <h6 class="fw-bold mb-0" style="color: #DC2626;"><?php echo e(__('بانتظار اعتماد المشرف')); ?></h6>
                <span class="badge rounded-pill" style="background:#DC2626; color:#fff; font-size:12px;"><?php echo e($pendingApprovals->count()); ?></span>
            </div>
            <span style="font-size:12px;color:#7f1d1d;"><?php echo e(__('هذه الطلبات تحتاج موافقتك على الإغلاق أو إعادتها للمندوب')); ?></span>
        </div>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#FEF2F2;">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;">#</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('العميل')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('النوع / المصدر')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('السيارة')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('المندوب')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('سبب الإغلاق المقترح')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('الإجراء')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);">#<?php echo e($pa->id); ?></a>
                        </td>
                        <td class="px-3 py-3">
                            <div class="fw-bold" style="font-size:13px;"><?php echo e($pa->client_name); ?></div>
                            <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($pa->client_phone); ?></div>
                        </td>
                        <td class="px-3 py-3">
                            <?php if($pa->source === 'calculator' || $pa->calculator_bank_id): ?>
                                <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 11px; padding: 3px 7px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-calculator me-1"></i><?php echo e(__('حاسبة تمويل')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11px; padding: 3px 7px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-car-front me-1"></i><?php echo e(__('طلب سيارة')); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($pa->car?->brand?->name); ?> <?php echo e($pa->car?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($pa->employee?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3">
                            <?php if($pa->proposed_status && isset(\App\Models\Booking::STATUSES[$pa->proposed_status])): ?>
                                <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:700;border:1px solid #FCA5A5;">
                                    <?php echo e(\App\Models\Booking::STATUSES[$pa->proposed_status]['label']); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3">
                            <div class="d-flex gap-2 flex-wrap">
                                
                                <form action="<?php echo e(route('crm.bookings.approve', $pa)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد الموافقة على إغلاق هذا الطلب؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-success fw-bold rounded-2" style="font-size:12px;padding:5px 12px;">
                                        <i class="bi bi-check-lg me-1"></i><?php echo e(__('موافقة')); ?>

                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-sm fw-bold rounded-2"
                                        style="font-size:12px;padding:5px 12px;background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($pa->id); ?>">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i><?php echo e(__('إعادة للمندوب')); ?>

                                </button>
                                
                                <?php if($isAdmin): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $pa)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:5px 10px;" title="<?php echo e(__('حذف')); ?>">
                                        <i class="bi bi-trash" style="font-size:13px;"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <div class="modal fade" id="rejectModal<?php echo e($pa->id); ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="modal-title fw-bold"><?php echo e(__('إعادة الطلب #')); ?><?php echo e($pa->id); ?> <?php echo e(__('للمندوب')); ?></h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?php echo e(route('crm.bookings.reject', $pa)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold" style="font-size:13px;"><?php echo e(__('إعادة إلى مرحلة')); ?></label>
                                            <select name="status" class="form-select form-select-sm" required>
                                                <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval'): ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($s['label']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold" style="font-size:13px;"><?php echo e(__('سبب الإعادة')); ?> <span class="text-danger">*</span></label>
                                            <textarea name="note" class="form-control form-control-sm" rows="3"
                                                      placeholder="<?php echo e(__('اكتب سبب إعادة الطلب للمندوب...')); ?>" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                                        <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('إعادة للمندوب')); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill me-1 text-primary"></i> <?php echo e(__('قائمة كافة الطلبات النشطة')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي الطلبات')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
        </div>

        
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('النوع والمصدر')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('السيارة / التمويل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الموظف المسند')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('حالة الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold px-4" style="font-size:12px;"><?php echo e(__('تحكم')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 text-muted small" style="font-size:13px;">
                            <?php echo e($bookings->firstItem() + $index); ?>

                        </td>
                        <td class="fw-bold" style="font-size:13px;">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#<?php echo e($b->id); ?></a>
                            <div class="text-muted small" style="font-size:11px;font-weight:normal;"><?php echo e($b->created_at->diffForHumans()); ?></div>
                        </td>
                        <td>
                            <?php if($b->source === 'calculator' || $b->calculator_bank_id): ?>
                                <span class="badge d-inline-flex align-items-center gap-1 mb-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-calculator"></i> <?php echo e(__('عميل حاسبة تمويل')); ?>

                                </span>
                                <?php if($b->financingBank): ?>
                                    <div class="small text-muted" style="font-size:11px;"><i class="bi bi-bank me-1"></i><?php echo e($b->financingBank->name); ?></div>
                                <?php endif; ?>
                            <?php elseif($b->booking_type === 'test_drive'): ?>
                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-speedometer2"></i> <?php echo e(__('تجربة قيادة')); ?>

                                </span>
                            <?php elseif($b->booking_type === 'purchase'): ?>
                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-cash-stack"></i> <?php echo e(__('شراء سيارة')); ?>

                                </span>
                            <?php elseif($b->source === 'CRM (يدوي)'): ?>
                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-pencil-square"></i> <?php echo e(__('طلب يدوي')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                    <i class="bi bi-car-front"></i> <?php echo e(__('طلب سيارة')); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;"><?php echo e($b->client_name); ?></div>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <a href="tel:<?php echo e($b->client_phone); ?>" class="text-decoration-none small text-muted" dir="ltr"><?php echo e($b->client_phone); ?></a>
                                <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $b->client_phone)); ?>" target="_blank" class="text-success small ms-1" title="<?php echo e(__('واتساب')); ?>">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </td>
                        <td>
                            <?php if($b->car): ?>
                            <div style="font-size: 12px; line-height: 1.6;">
                                <div class="fw-bold text-dark"><?php echo e($b->car->brand?->name); ?> <?php echo e($b->car->name); ?></div>
                                <?php if($b->car->year): ?>
                                <div class="text-muted"><?php echo e(__('سنة:')); ?> <?php echo e($b->car->year); ?></div>
                                <?php endif; ?>
                                <?php if($b->total_price > 0): ?>
                                <div class="text-muted small"><?php echo e(number_format($b->total_price)); ?> <?php echo e(__('ر.س')); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div class="text-muted small">—</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-secondary" style="width:26px;height:26px;font-size:11px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="fw-bold text-dark small" style="font-size:12px;"><?php echo e($b->employee?->name ?: __('غير معين')); ?></span>
                            </div>
                        </td>
                        <td>
                            <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0 d-inline-block">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <select name="status" class="form-select form-select-sm border shadow-none fw-bold"
                                        style="font-size:12px;border-radius:8px;background:#F8FAFC;min-width:160px;cursor:pointer;"
                                        data-current-status="<?php echo e($b->status); ?>"
                                        onchange="handleBookingStatusSelectChange(this, <?php echo e($b->id); ?>, '<?php echo e(route('crm.bookings.status', $b)); ?>', <?php echo e($isAdmin ? 'true' : 'false'); ?>)">
                                    <optgroup label="<?php echo e(__('المراحل النشطة (Active)')); ?>">
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>>⚡ <?php echo e($s['label']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                    <optgroup label="<?php echo e(__('حالات خاصة / معلقة')); ?>">
                                        <option value="pending">⏳ <?php echo e(__('قيد الانتظار (مع موعد متابعة)')); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php echo e(__('تسليم الطلب (ناجح)')); ?>">
                                        <option value="received" data-close="1">✅ <?php echo e(__('تم التسليم (المستلمة)')); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php echo e(__('إغلاق الحجز (خاسر)')); ?>">
                                        <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(($s['group'] ?? '') === 'lost'): ?>
                                                <option value="<?php echo e($key); ?>" data-close="1">❌ <?php echo e($s['label']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-4">
                            <div class="d-flex gap-1 align-items-center">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض وتعديل التفاصيل')); ?>">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $b->client_phone)); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('مراسلة واتساب')); ?>" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                
                                <?php if($isAdmin): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light rounded-2 border" style="color:var(--crm-red);" title="<?php echo e(__('حذف الطلب')); ?>">
                                        <i class="bi bi-trash" style="font-size:14px;"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold"><?php echo e(__('لا توجد طلبات نشطة حالياً مطابقة للشروط')); ?></div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-md-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                            <?php if($b->source === 'calculator' || $b->calculator_bank_id): ?>
                                <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-calculator me-1"></i><?php echo e(__('حاسبة تمويل')); ?>

                                </span>
                            <?php elseif($b->booking_type === 'test_drive'): ?>
                                <span class="badge" style="background-color: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-speedometer2 me-1"></i><?php echo e(__('تجربة قيادة')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-car-front me-1"></i><?php echo e(__('طلب سيارة')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">
                            <?php echo e($b->client_name); ?>

                        </div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                </div>

                
                <div class="d-flex justify-content-between align-items-center small text-muted mb-2 pt-2 border-top">
                    <div><i class="bi bi-car-front me-1"></i> <?php echo e($b->car?->brand?->name); ?> <?php echo e($b->car?->name ?? '—'); ?></div>
                    <div><i class="bi bi-person me-1"></i> <?php echo e($b->employee?->name ?? __('غير معين')); ?></div>
                </div>

                
                <div class="mt-2">
                    <label class="form-label small fw-bold text-muted mb-1"><?php echo e(__('الحالة:')); ?></label>
                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-select form-select-sm"
                                style="font-size:12px;border-radius:8px;"
                                data-current-status="<?php echo e($b->status); ?>"
                                onchange="handleBookingStatusSelectChange(this, <?php echo e($b->id); ?>, '<?php echo e(route('crm.bookings.status', $b)); ?>', <?php echo e($isAdmin ? 'true' : 'false'); ?>)">
                            <optgroup label="<?php echo e(__('المراحل النشطة (Active)')); ?>">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>>⚡ <?php echo e($s['label']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="<?php echo e(__('حالات خاصة / معلقة')); ?>">
                                <option value="pending">⏳ <?php echo e(__('قيد الانتظار')); ?></option>
                            </optgroup>
                            <optgroup label="<?php echo e(__('تسليم الطلب (ناجح)')); ?>">
                                <option value="received" data-close="1">✅ <?php echo e(__('تم التسليم')); ?></option>
                            </optgroup>
                            <optgroup label="<?php echo e(__('إغلاق الحجز (خاسر)')); ?>">
                                <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($s['group'] ?? '') === 'lost'): ?>
                                        <option value="<?php echo e($key); ?>" data-close="1">❌ <?php echo e($s['label']); ?></option>
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
                    <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $b->client_phone)); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;color:#25D366;">
                        <i class="bi bi-whatsapp"></i> <?php echo e(__('واتساب')); ?>

                    </a>
                    <?php if($isAdmin): ?>
                    <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                          onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-light rounded-2 border text-danger" title="<?php echo e(__('حذف')); ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                <div><?php echo e(__('لا توجد طلبات نشطة حالياً')); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if($bookings->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3" style="border-top:1px solid var(--crm-border)!important;">
            <?php echo e($bookings->links()); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FAF9F6;">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" style="color: var(--crm-text);"><?php echo e(__('إضافة عميل / طلب جديد')); ?></h5>
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

<?php echo $__env->make('crm.bookings.partials.status-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/index.blade.php ENDPATH**/ ?>