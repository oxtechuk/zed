<?php $__env->startSection('title', __('الطلبات النشطة') . ' | Zad Capital'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .crm-custom-booking-table {
        border-collapse: collapse;
        width: 100%;
    }
    .crm-custom-booking-table tr.crm-booking-row {
        border-bottom: 1px solid #ECEEF2;
        transition: background-color 0.15s ease;
    }
    .crm-custom-booking-table tr.crm-booking-row:hover {
        background-color: #FAFAFC;
    }
    .booking-id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #E6F4EA;
        color: #137333;
        font-weight: 700;
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    .booking-id-badge:hover {
        background-color: #CEEAD6;
        color: #0D652D;
    }
    .booking-meta-box {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 12.5px;
        line-height: 1.45;
    }
    .booking-meta-item {
        display: flex;
        align-items: baseline;
        gap: 6px;
        white-space: nowrap;
    }
    .booking-meta-key {
        color: #1E293B;
        font-weight: 700;
        font-size: 12.5px;
    }
    .booking-meta-val {
        color: #64748B;
        font-size: 12.5px;
    }
    .badge-payment {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-payment.badge-unpaid {
        background-color: #FEE2E2;
        color: #DC2626;
    }
    .badge-payment.badge-paid {
        background-color: #DCFCE7;
        color: #16A34A;
    }
    .badge-payment .dot {
        font-size: 8px;
    }
    .btn-action-square {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease;
        padding: 0;
    }
    .btn-action-square:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
    }
    /* Pagination styling matching the orange circular style in screenshot */
    .crm-pagination-wrapper .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .crm-pagination-wrapper .page-item .page-link {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
        border: 1px solid #E2E8F0;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        background: #FFFFFF;
        text-decoration: none;
        transition: all 0.2s;
        margin: 0;
        padding: 0;
    }
    .crm-pagination-wrapper .page-item.active .page-link {
        background-color: #EA580C !important;
        border-color: #EA580C !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.35);
    }
    .crm-pagination-wrapper .page-item .page-link:hover:not(.active) {
        background-color: #F8FAFC;
        border-color: #CBD5E1;
        color: #0F172A;
    }
    .crm-pagination-wrapper .page-item.disabled .page-link {
        background-color: #F8FAFC;
        border-color: #ECEEF2;
        color: #CBD5E1;
    }
    /* Sleek Professional Stat Cards */
    .stat-card-clean {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 16px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        text-decoration: none;
    }
    .stat-card-clean:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }
    .stat-card-clean.active {
        border-color: #2563EB;
        background: #F8FAFC;
        box-shadow: 0 0 0 1px #2563EB;
    }
    .stat-card-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-card-icon-box.blue {
        background: #EFF6FF;
        color: #2563EB;
    }
    .stat-card-icon-box.sky {
        background: #F0F9FF;
        color: #0284C7;
    }
    .stat-card-icon-box.purple {
        background: #FAF5FF;
        color: #9333EA;
    }
    .stat-card-icon-box.amber {
        background: #FFFBEB;
        color: #D97706;
    }
    .stat-card-icon-box.emerald {
        background: #F0FDF4;
        color: #16A34A;
    }
    .stat-card-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    .stat-card-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748B;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stat-card-value {
        font-size: 22px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
    }
</style>
<?php $__env->stopSection(); ?>

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
                <div class="stat-card-clean <?php echo e(!request('source') ? 'active' : ''); ?>">
                    <div class="stat-card-info">
                        <span class="stat-card-label"><?php echo e(__('إجمالي الطلبات النشطة')); ?></span>
                        <span class="stat-card-value"><?php echo e(number_format($stats['total'])); ?></span>
                    </div>
                    <div class="stat-card-icon-box blue">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-xl-2 col-md-4">
            <a href="<?php echo e(route('crm.bookings.index', array_merge(request()->query(), ['source' => 'cars']))); ?>" class="text-decoration-none d-block h-100">
                <div class="stat-card-clean <?php echo e(request('source') === 'cars' ? 'active' : ''); ?>">
                    <div class="stat-card-info">
                        <span class="stat-card-label"><?php echo e(__('طلبات السيارات')); ?></span>
                        <span class="stat-card-value" style="color: #0284C7;"><?php echo e(number_format($stats['car_requests'])); ?></span>
                    </div>
                    <div class="stat-card-icon-box sky">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-xl-3 col-md-4">
            <a href="<?php echo e(route('crm.bookings.index', array_merge(request()->query(), ['source' => 'calculator']))); ?>" class="text-decoration-none d-block h-100">
                <div class="stat-card-clean <?php echo e(request('source') === 'calculator' ? 'active' : ''); ?>">
                    <div class="stat-card-info">
                        <span class="stat-card-label"><?php echo e(__('عملاء حاسبة التمويل')); ?></span>
                        <span class="stat-card-value" style="color: #7C3AED;"><?php echo e(number_format($stats['calculator_leads'])); ?></span>
                    </div>
                    <div class="stat-card-icon-box purple">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-xl-2 col-md-6">
            <div class="stat-card-clean">
                <div class="stat-card-info">
                    <span class="stat-card-label"><?php echo e(__('بانتظار التواصل والمراجعة')); ?></span>
                    <span class="stat-card-value text-warning"><?php echo e(number_format($stats['pending_review'])); ?></span>
                </div>
                <div class="stat-card-icon-box amber">
                    <i class="bi bi-telephone-inbound-fill"></i>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-xl-3 col-md-6">
            <div class="stat-card-clean">
                <div class="stat-card-info">
                    <span class="stat-card-label"><?php echo e(__('تحت الدراسة والتعميد')); ?></span>
                    <span class="stat-card-value text-success"><?php echo e(number_format($stats['under_bank'])); ?></span>
                </div>
                <div class="stat-card-icon-box emerald">
                    <i class="bi bi-bank2"></i>
                </div>
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
                        <option value="cars" <?php echo e(request('source')==='cars'?'selected':''); ?>><?php echo e(__('طلبات السيارات (حجز وشراء)')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>><?php echo e(__('عملاء حاسبة التمويل')); ?></option>
                        <option value="crm_manual" <?php echo e(request('source')==='crm_manual'?'selected':''); ?>><?php echo e(__('طلبات داخلية (CRM)')); ?></option>
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
                            <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="fw-bold text-decoration-none text-dark d-block hover-primary" style="font-size:13px;"><?php echo e($pa->client_name); ?></a>
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
                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                
                                <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;padding:5px 12px;color:var(--crm-text);" title="<?php echo e(__('عرض وتعديل تفاصيل الطلب')); ?>">
                                    <i class="bi bi-eye me-1 text-primary"></i><?php echo e(__('عرض الطلب')); ?>

                                </a>
                                
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

        
        <div class="d-md-none p-3">
            <?php $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-3 p-3 rounded-3 shadow-sm border" style="background:#fff; border-color: #FCA5A5 !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($pa->id); ?></a>
                            <?php if($pa->source === 'calculator' || $pa->calculator_bank_id): ?>
                                <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-calculator me-1"></i><?php echo e(__('حاسبة تمويل')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-car-front me-1"></i><?php echo e(__('طلب سيارة')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="fw-bold text-dark text-decoration-none d-block mt-1" style="font-size:14px;"><?php echo e($pa->client_name); ?></a>
                        <div class="small text-muted" dir="ltr"><?php echo e($pa->client_phone); ?></div>
                    </div>
                </div>

                <div class="p-2.5 rounded-2 mb-2.5" style="background:#FEF2F2; font-size:12px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted"><?php echo e(__('السيارة')); ?>:</span>
                        <span class="fw-semibold"><?php echo e($pa->car?->brand?->name); ?> <?php echo e($pa->car?->name ?? '—'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted"><?php echo e(__('المندوب')); ?>:</span>
                        <span class="fw-semibold"><?php echo e($pa->employee?->name ?? '—'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><?php echo e(__('السبب المقترح')); ?>:</span>
                        <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:700;border:1px solid #FCA5A5;">
                            <?php echo e(\App\Models\Booking::STATUSES[$pa->proposed_status]['label'] ?? '—'); ?>

                        </span>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap align-items-center pt-2 border-top">
                    <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="btn btn-sm btn-light border fw-bold rounded-2 flex-grow-1" style="font-size:12px;padding:6px 12px;">
                        <i class="bi bi-eye me-1 text-primary"></i><?php echo e(__('عرض الطلب')); ?>

                    </a>
                    <form action="<?php echo e(route('crm.bookings.approve', $pa)); ?>" method="POST" class="m-0"
                          onsubmit="return confirm('<?php echo e(__('هل تريد الموافقة على إغلاق هذا الطلب؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-sm btn-success fw-bold rounded-2" style="font-size:12px;padding:6px 12px;">
                            <i class="bi bi-check-lg me-1"></i><?php echo e(__('موافقة')); ?>

                        </button>
                    </form>
                    <button type="button" class="btn btn-sm fw-bold rounded-2"
                            style="font-size:12px;padding:6px 12px;background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;"
                            data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($pa->id); ?>">
                        <i class="bi bi-arrow-counterclockwise me-1"></i><?php echo e(__('إعادة')); ?>

                    </button>
                    <?php if($isAdmin): ?>
                    <form action="<?php echo e(route('crm.bookings.destroy', $pa)); ?>" method="POST" class="m-0"
                          onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:6px 10px;" title="<?php echo e(__('حذف')); ?>">
                            <i class="bi bi-trash" style="font-size:13px;"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill me-1 text-primary"></i> <?php echo e(__('قائمة كافة الطلبات النشطة')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي الطلبات')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
        </div>

        
        <div class="table-responsive d-none d-lg-block">
            <table class="table align-middle mb-0 crm-custom-booking-table">
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Calculate relative update time
                        $updatedDiff = '—';
                        if ($b->updated_at) {
                            if ($b->updated_at->diffInHours(now()) < 24) {
                                $updatedDiff = __('اقل من 24 ساعة');
                            } else {
                                $updatedDiff = $b->updated_at->diffForHumans();
                            }
                        }

                        $createdDiff = $b->created_at ? $b->created_at->diffForHumans() : '—';
                        $employeeName = $b->employee?->name ?? __('لايوجد');
                        $sourceName = $b->source ?: ($b->calculator_bank_id ? __('حاسبة تمويل') : __('لايوجد'));
                        $brandName = $b->car?->brand?->name ?? __('لايوجد');
                        $modelName = $b->car?->carModel?->name ?? ($b->car?->model ?? ($b->car?->name ?? __('لايوجد')));
                        $categoryName = $b->car?->category?->name ?? ($b->car?->type ?? __('لايوجد'));
                        $yearVal = $b->car?->year ?? __('لايوجد');
                        $statusLabel = $statuses[$b->status]['label'] ?? ($b->status_label ?? '—');
                        $mainStatusGroup = match(\App\Models\Booking::STATUSES[$b->status]['group'] ?? 'active') {
                            'active' => __('مفتوح'),
                            'lost' => __('مغلق'),
                            'received' => __('مكتمل'),
                            default => __('مفتوح')
                        };
                        $isPaid = $b->down_payment > 0 || in_array($b->status, ['authorized', 'received']);
                    ?>
                    <tr class="crm-booking-row">
                        
                        <td class="px-4 py-3" style="width: 140px;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted fw-bold" style="font-size:14px; min-width: 16px;">
                                    <?php echo e($bookings->firstItem() + $index); ?>

                                </span>
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="booking-id-badge" title="<?php echo e(__('عرض تفاصيل الطلب')); ?>">
                                    <?php echo e($b->id); ?>

                                </a>
                            </div>
                        </td>

                        
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                
                                <div class="text-start" style="min-width: 130px;">
                                    <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none text-dark d-block hover-primary" style="font-size: 14px;">
                                        <?php echo e($b->client_name); ?>

                                    </a>
                                    <div class="d-flex align-items-center gap-1.5 mt-1">
                                        <a href="tel:<?php echo e($b->client_phone); ?>" class="fw-bold text-decoration-none" style="color: #2563EB; font-size: 13px;" dir="ltr">
                                            <?php echo e($b->client_phone); ?>

                                        </a>
                                        <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $b->client_phone)); ?>" target="_blank" class="text-success small" title="<?php echo e(__('واتساب')); ?>">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>

                                
                                <div class="booking-meta-box">
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key"><?php echo e(__('انشاء :')); ?></span>
                                        <span class="booking-meta-val" title="<?php echo e($b->created_at?->format('Y-m-d H:i')); ?>"><?php echo e($createdDiff); ?></span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key"><?php echo e(__('التعديل :')); ?></span>
                                        <span class="booking-meta-val fw-bold text-dark" title="<?php echo e($b->updated_at?->format('Y-m-d H:i')); ?>"><?php echo e($updatedDiff); ?></span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key"><?php echo e(__('الموظف :')); ?></span>
                                        <span class="booking-meta-val"><?php echo e($employeeName); ?></span>
                                    </div>
                                    <div class="booking-meta-item">
                                        <span class="booking-meta-key"><?php echo e(__('المصدر :')); ?></span>
                                        <span class="booking-meta-val"><?php echo e($sourceName); ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-3 py-3" style="min-width: 200px;">
                            <div class="booking-meta-box">
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key"><?php echo e(__('الماركة :')); ?></span>
                                    <span class="booking-meta-val"><?php echo e($brandName); ?></span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key"><?php echo e(__('موديل :')); ?></span>
                                    <span class="booking-meta-val"><?php echo e($modelName); ?></span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key"><?php echo e(__('الفئة :')); ?></span>
                                    <span class="booking-meta-val"><?php echo e($categoryName); ?></span>
                                </div>
                                <div class="booking-meta-item">
                                    <span class="booking-meta-key"><?php echo e(__('سنة الصنع :')); ?></span>
                                    <span class="booking-meta-val"><?php echo e($yearVal); ?></span>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-3 py-3" style="min-width: 250px;">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                
                                <div>
                                    <?php if($isPaid): ?>
                                        <span class="badge-payment badge-paid">
                                            <span class="dot">●</span> <?php echo e(__('مدفوع')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge-payment badge-unpaid">
                                            <span class="dot">●</span> <?php echo e(__('غير مدفوع')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="booking-meta-box text-end">
                                    <div class="booking-meta-item justify-content-end">
                                        <span class="booking-meta-key"><?php echo e(__('حالة الطلب :')); ?></span>
                                        <span class="booking-meta-val fw-semibold text-dark"><?php echo e($statusLabel); ?></span>
                                    </div>
                                    <div class="booking-meta-item justify-content-end">
                                        <span class="booking-meta-key"><?php echo e(__('الحالة الرئيسية :')); ?></span>
                                        <span class="booking-meta-val text-muted"><?php echo e($mainStatusGroup); ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3 text-start" style="width: 120px;">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn-action-square" title="<?php echo e(__('عرض تفاصيل الطلب')); ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn-action-square" title="<?php echo e(__('تعديل الطلب')); ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <?php if($isAdmin): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0 d-inline"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-action-square text-danger" title="<?php echo e(__('حذف الطلب')); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold"><?php echo e(__('لا توجد طلبات نشطة حالياً مطابقة للشروط')); ?></div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-lg-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $updatedDiff = '—';
                if ($b->updated_at) {
                    if ($b->updated_at->diffInHours(now()) < 24) {
                        $updatedDiff = __('اقل من 24 ساعة');
                    } else {
                        $updatedDiff = $b->updated_at->diffForHumans();
                    }
                }
                $createdDiff = $b->created_at ? $b->created_at->diffForHumans() : '—';
                $employeeName = $b->employee?->name ?? __('لايوجد');
                $sourceName = $b->source ?: ($b->calculator_bank_id ? __('حاسبة تمويل') : __('لايوجد'));
                $brandName = $b->car?->brand?->name ?? __('لايوجد');
                $modelName = $b->car?->carModel?->name ?? ($b->car?->model ?? ($b->car?->name ?? __('لايوجد')));
                $categoryName = $b->car?->category?->name ?? ($b->car?->type ?? __('لايوجد'));
                $yearVal = $b->car?->year ?? __('لايوجد');
                $statusLabel = $statuses[$b->status]['label'] ?? ($b->status_label ?? '—');
                $mainStatusGroup = match(\App\Models\Booking::STATUSES[$b->status]['group'] ?? 'active') {
                    'active' => __('مفتوح'),
                    'lost' => __('مغلق'),
                    'received' => __('مكتمل'),
                    default => __('مفتوح')
                };
                $isPaid = $b->down_payment > 0 || in_array($b->status, ['authorized', 'received']);
            ?>
            <div class="mb-3 p-3 rounded-4 shadow-sm border bg-white" style="border: 1px solid #ECEEF2 !important;">
                
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-bold">#<?php echo e($bookings->firstItem() + $index); ?></span>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="booking-id-badge">
                            <?php echo e($b->id); ?>

                        </a>
                    </div>
                    <div>
                        <?php if($isPaid): ?>
                            <span class="badge-payment badge-paid">
                                <span class="dot">●</span> <?php echo e(__('مدفوع')); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge-payment badge-unpaid">
                                <span class="dot">●</span> <?php echo e(__('غير مدفوع')); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-dark text-decoration-none" style="font-size: 14px;">
                            <?php echo e($b->client_name); ?>

                        </a>
                        <div class="d-flex align-items-center gap-1.5 mt-0.5">
                            <a href="tel:<?php echo e($b->client_phone); ?>" class="fw-bold text-decoration-none" style="color: #2563EB; font-size: 13px;" dir="ltr">
                                <?php echo e($b->client_phone); ?>

                            </a>
                            <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $b->client_phone)); ?>" target="_blank" class="text-success small" title="<?php echo e(__('واتساب')); ?>">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="p-2.5 rounded-3 mb-2" style="background:#F8FAFC; font-size:12px;">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('انشاء:')); ?></span>
                            <span class="fw-semibold"><?php echo e($createdDiff); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('التعديل:')); ?></span>
                            <span class="fw-bold text-dark"><?php echo e($updatedDiff); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('الموظف:')); ?></span>
                            <span class="fw-semibold"><?php echo e($employeeName); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('المصدر:')); ?></span>
                            <span class="fw-semibold"><?php echo e($sourceName); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="p-2.5 rounded-3 mb-2" style="background:#FFF9F5; font-size:12px; border:1px solid #FFEDD5;">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('الماركة:')); ?></span>
                            <span class="fw-bold text-dark"><?php echo e($brandName); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('موديل:')); ?></span>
                            <span class="fw-bold text-dark"><?php echo e($modelName); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('الفئة:')); ?></span>
                            <span class="fw-semibold"><?php echo e($categoryName); ?></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted"><?php echo e(__('سنة الصنع:')); ?></span>
                            <span class="fw-semibold"><?php echo e($yearVal); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div style="font-size:12px;">
                        <span class="text-muted"><?php echo e(__('حالة الطلب:')); ?></span>
                        <span class="fw-bold text-dark"><?php echo e($statusLabel); ?></span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn-action-square" title="<?php echo e(__('عرض')); ?>">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn-action-square" title="<?php echo e(__('تعديل')); ?>">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <?php if($isAdmin): ?>
                        <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                              onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب؟')); ?>')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-action-square text-danger" title="<?php echo e(__('حذف')); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
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
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center" style="border-top:1px solid var(--crm-border)!important;">
            <div class="crm-pagination-wrapper">
                <?php echo e($bookings->links()); ?>

            </div>
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