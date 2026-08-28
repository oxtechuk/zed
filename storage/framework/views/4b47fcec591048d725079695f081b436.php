<?php $__env->startSection('title', __('العملاء') . ' | ' . (\App\Models\Setting::where('key', 'site_name')->first()?->value['ar'] ?? 'زد كابيتال')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <span><?php echo e(__('إدارة العملاء')); ?></span>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('العملاء')); ?></span>
    </nav>

    
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><?php echo e(__('قائمة العملاء')); ?></h5>
            <p class="text-muted small mb-0"><?php echo e(__('استهداف ومتابعة العملاء وإدارة حملات رسائل الواتساب بناءً على مسار الطلبات')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1.5 fw-bold rounded-3 shadow-xs" id="btnOpenCampaignFiltered" style="font-size:13px;padding:8px 16px;">
                <i class="bi bi-whatsapp"></i>
                <span><?php echo e(__('إرسال حملة واتساب للفلتر')); ?> (<?php echo e($leads->total()); ?>)</span>
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-leads')): ?>
            <a href="<?php echo e(route('crm.leads.create')); ?>" class="btn-crm-primary">
                <i class="bi bi-person-plus"></i> <?php echo e(__('إضافة عميل')); ?>

            </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('crm.leads.index')); ?>" class="text-decoration-none">
                <div class="crm-stat-new <?php echo e(!request('booking_status_group') ? 'border-primary' : ''); ?>">
                    <span class="stat-badge purple">100%</span>
                    <div class="stat-icon purple"><i class="bi bi-people"></i></div>
                    <div class="stat-lbl"><?php echo e(__('إجمالي العملاء')); ?></div>
                    <div class="stat-val"><?php echo e(number_format($totalLeadsAllCount)); ?></div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('crm.leads.index', ['booking_status_group' => 'active'])); ?>" class="text-decoration-none">
                <div class="crm-stat-new <?php echo e(request('booking_status_group') === 'active' ? 'border-primary' : ''); ?>">
                    <span class="stat-badge blue"><?php echo e($totalLeadsAllCount > 0 ? round(($activeOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0); ?>%</span>
                    <div class="stat-icon blue"><i class="bi bi-lightning-charge"></i></div>
                    <div class="stat-lbl"><?php echo e(__('عملاء بطلبات نشطة')); ?></div>
                    <div class="stat-val text-primary"><?php echo e(number_format($activeOrdersLeadsCount)); ?></div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('crm.leads.index', ['booking_status_group' => 'received'])); ?>" class="text-decoration-none">
                <div class="crm-stat-new <?php echo e(request('booking_status_group') === 'received' ? 'border-success' : ''); ?>">
                    <span class="stat-badge green"><?php echo e($totalLeadsAllCount > 0 ? round(($receivedOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0); ?>%</span>
                    <div class="stat-icon green"><i class="bi bi-patch-check"></i></div>
                    <div class="stat-lbl"><?php echo e(__('عملاء بطلبات مستلمة')); ?></div>
                    <div class="stat-val text-success"><?php echo e(number_format($receivedOrdersLeadsCount)); ?></div>
                </div>
            </a>
        </div>

        
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('crm.leads.index', ['booking_status_group' => 'closed'])); ?>" class="text-decoration-none">
                <div class="crm-stat-new <?php echo e(request('booking_status_group') === 'closed' ? 'border-danger' : ''); ?>">
                    <span class="stat-badge red"><?php echo e($totalLeadsAllCount > 0 ? round(($closedOrdersLeadsCount / $totalLeadsAllCount) * 100) : 0); ?>%</span>
                    <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                    <div class="stat-lbl"><?php echo e(__('عملاء بطلبات مغلقة')); ?></div>
                    <div class="stat-val text-danger"><?php echo e(number_format($closedOrdersLeadsCount)); ?></div>
                </div>
            </a>
        </div>
    </div>

    
    <?php
        $currentGroup = request('booking_status_group');
    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="crm-filter-tabs mb-0">
            <a href="<?php echo e(route('crm.leads.index', request()->except('booking_status_group', 'page'))); ?>"
               class="crm-filter-tab <?php echo e(empty($currentGroup) ? 'active' : ''); ?>">
                <i class="bi bi-grid me-1"></i> <?php echo e(__('جميع العملاء')); ?>

                <span class="badge rounded-pill bg-light text-dark ms-1"><?php echo e(number_format($totalLeadsAllCount)); ?></span>
            </a>
            <a href="<?php echo e(route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'active']))); ?>"
               class="crm-filter-tab <?php echo e($currentGroup === 'active' ? 'active' : ''); ?>">
                <i class="bi bi-lightning-charge me-1 text-primary"></i> <?php echo e(__('طلبات نشطة')); ?>

                <span class="badge rounded-pill <?php echo e($currentGroup === 'active' ? 'bg-white text-primary' : 'bg-primary-subtle text-primary'); ?> ms-1"><?php echo e(number_format($activeOrdersLeadsCount)); ?></span>
            </a>
            <a href="<?php echo e(route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'received']))); ?>"
               class="crm-filter-tab <?php echo e($currentGroup === 'received' ? 'active' : ''); ?>">
                <i class="bi bi-patch-check me-1 text-success"></i> <?php echo e(__('طلبات مستلمة')); ?>

                <span class="badge rounded-pill <?php echo e($currentGroup === 'received' ? 'bg-white text-success' : 'bg-success-subtle text-success'); ?> ms-1"><?php echo e(number_format($receivedOrdersLeadsCount)); ?></span>
            </a>
            <a href="<?php echo e(route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'closed']))); ?>"
               class="crm-filter-tab <?php echo e($currentGroup === 'closed' ? 'active' : ''); ?>">
                <i class="bi bi-x-circle me-1 text-danger"></i> <?php echo e(__('طلبات مغلقة')); ?>

                <span class="badge rounded-pill <?php echo e($currentGroup === 'closed' ? 'bg-white text-danger' : 'bg-danger-subtle text-danger'); ?> ms-1"><?php echo e(number_format($closedOrdersLeadsCount)); ?></span>
            </a>
            <a href="<?php echo e(route('crm.leads.index', array_merge(request()->except('page'), ['booking_status_group' => 'no_orders']))); ?>"
               class="crm-filter-tab <?php echo e($currentGroup === 'no_orders' ? 'active' : ''); ?>">
                <i class="bi bi-dash-circle me-1 text-muted"></i> <?php echo e(__('بدون طلبات')); ?>

                <span class="badge rounded-pill bg-light text-muted ms-1"><?php echo e(number_format($noOrdersLeadsCount)); ?></span>
            </a>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('crm.leads.index')); ?>" id="leadFilterForm">
        <?php if(request('booking_status_group')): ?>
            <input type="hidden" name="booking_status_group" value="<?php echo e(request('booking_status_group')); ?>">
        <?php endif; ?>
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
                    <a href="<?php echo e(route('crm.leads.index')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>

    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 px-1">
        <div style="font-size:12.5px;color:var(--crm-text-muted);">
            <?php echo e(__('نتائج البحث:')); ?> <strong><?php echo e(number_format($leads->total())); ?></strong> <?php echo e(__('عميل')); ?>

            <?php if(request('booking_status_group')): ?>
                <span class="badge bg-light text-dark border ms-1">
                    <?php echo e(match(request('booking_status_group')) {
                        'active' => __('فلتر: طلبات نشطة'),
                        'received' => __('فلتر: طلبات مستلمة'),
                        'closed' => __('فلتر: طلبات مغلقة'),
                        'no_orders' => __('فلتر: بدون طلبات'),
                        default => ''
                    }); ?>

                </span>
            <?php endif; ?>
        </div>
        <div id="selectionStatusText" class="text-success fw-bold d-none" style="font-size:12.5px;">
            <i class="bi bi-check-all me-1"></i> <span id="selectedCountText">0</span> <?php echo e(__('عميل محدد')); ?>

        </div>
    </div>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><?php echo e(__('جدول العملاء')); ?></h6>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border rounded-2 d-none" id="btnSelectAllOnPage">
                    <?php echo e(__('تحديد كل الصفحة')); ?>

                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;width:40px;">
                            <input type="checkbox" id="selectAllLeads" class="form-check-input" title="<?php echo e(__('تحديد الكل')); ?>">
                        </th>
                        <th class="px-3 py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('اسم العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الهاتف')); ?></th>
                        <th class="py-3 text-muted fw-bold text-center" style="font-size:12px;"><?php echo e(__('الطلبات ومسارها')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('السيارة المطلوبة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('مصدر العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تاريخ الإضافة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('حالة العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold text-end px-4" style="font-size:12px;"><?php echo e(__('الإجراءات')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4">
                            <input type="checkbox" name="lead_ids[]" value="<?php echo e($lead->id); ?>"
                                   class="form-check-input lead-checkbox"
                                   data-name="<?php echo e($lead->client_name); ?>"
                                   data-phone="<?php echo e($lead->client_phone); ?>"
                                   <?php echo e($lead->client_phone ? '' : 'disabled'); ?>>
                        </td>
                        <td class="px-3 fw-bold" style="font-size:13px;">
                            <a href="<?php echo e(route('crm.leads.show', $lead)); ?>" class="text-decoration-none fw-bold" style="color:var(--crm-red);">#<?php echo e($lead->id); ?></a>
                        </td>
                        <td>
                            <div class="fw-bold" style="font-size:13px;color:var(--crm-text);"><?php echo e($lead->client_name); ?></div>
                            <?php if($lead->client_email): ?>
                                <div class="text-muted small" style="font-size:11px;"><?php echo e($lead->client_email); ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:13px;">
                            <?php if($lead->client_phone): ?>
                                <div class="d-flex align-items-center gap-1">
                                    <span dir="ltr" class="fw-bold"><?php echo e($lead->client_phone); ?></span>
                                    <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $lead->client_phone)); ?>" target="_blank" class="badge text-white text-decoration-none" style="background:#25D366;font-size:10px;padding:3px 6px;" title="<?php echo e(__('مراسلة واتساب')); ?>">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php
                                $ordersCount = $lead->orders_count ?? $lead->orders->count();
                                $hasActiveOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::ACTIVE_BOOKING_STATUSES)->isNotEmpty();
                                $hasReceivedOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::RECEIVED_BOOKING_STATUSES)->isNotEmpty();
                                $hasClosedOrder = $lead->orders->whereIn('status', \App\Http\Controllers\CRM\LeadController::CLOSED_BOOKING_STATUSES)->isNotEmpty();
                            ?>
                            <?php if($ordersCount > 0): ?>
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-bold" style="font-size:12px;">
                                        <?php echo e($ordersCount); ?> <?php echo e(__('طلب')); ?>

                                    </span>
                                    <div class="d-flex gap-1">
                                        <?php if($hasReceivedOrder): ?>
                                            <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:10px;" title="<?php echo e(__('يوجد طلب مستلم')); ?>">
                                                <i class="bi bi-patch-check-fill"></i> <?php echo e(__('مستلم')); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($hasActiveOrder): ?>
                                            <span class="badge" style="background:#EFF6FF;color:#2563EB;font-size:10px;" title="<?php echo e(__('يوجد طلب نشط')); ?>">
                                                <i class="bi bi-lightning-charge-fill"></i> <?php echo e(__('نشط')); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($hasClosedOrder && !$hasReceivedOrder && !$hasActiveOrder): ?>
                                            <span class="badge" style="background:#FEF2F2;color:#DC2626;font-size:10px;" title="<?php echo e(__('طلب مغلق')); ?>">
                                                <i class="bi bi-x-circle-fill"></i> <?php echo e(__('مغلق')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border" style="font-size:11px;"><?php echo e(__('بدون طلبات')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">
                            <?php if($lead->car): ?>
                                <div class="fw-bold text-dark"><?php echo e($lead->car->brand?->name); ?> <?php echo e($lead->car->name); ?></div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <?php echo e($lead->contactSource?->name ?? __('مباشر')); ?>

                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--crm-text-muted);">
                            <div><?php echo e($lead->created_at?->format('d/m/Y') ?? ($lead->started_at?->format('d/m/Y') ?? '—')); ?></div>
                            <div style="font-size:11px;"><?php echo e($lead->created_at?->diffForHumans()); ?></div>
                        </td>
                        <td>
                            <?php
                                $dotClass = match($lead->status) {
                                    'new'         => 'confirmed',
                                    'contacted'   => 'waiting',
                                    'interested'  => 'planned',
                                    'negotiation' => 'waiting',
                                    'converted'   => 'done',
                                    'lost'        => 'late',
                                    default       => 'cancelled',
                                };
                            ?>
                            <span class="status-dot <?php echo e($dotClass); ?>"><?php echo e($lead->status_label); ?></span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-1 justify-content-end align-items-center">
                                <a href="<?php echo e(route('crm.leads.show', $lead)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض التفاصيل')); ?>">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-leads')): ?>
                                <a href="<?php echo e(route('crm.leads.edit', $lead)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('تعديل')); ?>">
                                    <i class="bi bi-pencil" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <form action="<?php echo e(route('crm.leads.destroy', $lead)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العميل؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('حذف')); ?>"
                                            style="color:var(--crm-red);">
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
                            <i class="bi bi-person-x fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-bold"><?php echo e(__('لا يوجد عملاء يطابقون خيارات الفلتر الحالية')); ?></div>
                            <small class="text-muted"><?php echo e(__('جرب تغيير خيارات البحث أو الفلاتر بالأعلى')); ?></small>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($leads->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--crm-border)!important;">
            <div class="small text-muted">
                <?php echo e(__('عرض')); ?> <strong><?php echo e($leads->firstItem() ?? 0); ?></strong> - <strong><?php echo e($leads->lastItem() ?? 0); ?></strong> <?php echo e(__('من أصل')); ?> <strong><?php echo e($leads->total()); ?></strong>
            </div>
            <div>
                <?php echo e($leads->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <button type="button" id="btnWhatsappCampaign"
            class="btn btn-success rounded-pill shadow-lg d-none align-items-center gap-2"
            style="position:fixed;bottom:30px;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:30px;z-index:1050;padding:12px 24px;font-size:14px;font-weight:700;box-shadow:0 8px 24px rgba(22,163,74,0.35)!important;">
        <i class="bi bi-whatsapp" style="font-size:20px;"></i>
        <span><?php echo e(__('إرسال واتساب للمحددين')); ?> (<span id="selectedCount">0</span>)</span>
    </button>

    
    <div class="modal fade" id="whatsappCampaignModal" tabindex="-1" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#F0FDF4;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#DCFCE7;color:#16A34A;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold mb-0 text-success"><?php echo e(__('حملة رسائل واتساب الجماعية')); ?></h6>
                            <span class="text-muted" style="font-size:12px;"><?php echo e(__('استهداف العملاء برسائل مخصصة وتلقائية')); ?></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    
                    <div class="mb-3 p-3 rounded-3" style="background:#F8FAFC;border:1px solid var(--crm-border);">
                        <label class="form-label fw-bold small text-dark mb-2"><?php echo e(__('تحديد الشريحة المستهدفة للإرسال:')); ?></label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="targetMode" id="targetModeSelected" value="selected" checked>
                                <label class="form-check-label" for="targetModeSelected">
                                    <strong><?php echo e(__('العملاء المحددين فقط في الصفحة')); ?></strong>
                                    (<span id="modalSelectedCount">0</span> <?php echo e(__('عميل')); ?>)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="targetMode" id="targetModeFiltered" value="filtered">
                                <label class="form-check-label" for="targetModeFiltered">
                                    <strong><?php echo e(__('كافة العملاء المطابقين للفلتر الحالي')); ?></strong>
                                    (<?php echo e($leads->total()); ?> <?php echo e(__('عميل عبر كافة الصفحات')); ?>)
                                    <?php if(request('booking_status_group')): ?>
                                        <span class="badge bg-success-subtle text-success ms-1">
                                            <?php echo e(match(request('booking_status_group')) {
                                                'active' => __('طلبات نشطة'),
                                                'received' => __('طلبات مستلمة'),
                                                'closed' => __('طلبات مغلقة'),
                                                'no_orders' => __('بدون طلبات'),
                                                default => ''
                                            }); ?>

                                        </span>
                                    <?php endif; ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('نص الرسالة')); ?> <span class="text-danger">*</span></label>
                        <textarea id="campaignMessage" class="form-control bg-light border-0 shadow-none" rows="5" style="border-radius:10px;font-size:13.5px;line-height:1.6;"
                                  placeholder="<?php echo e(__('اكتب نص الرسالة هنا... مثال: مرحباً أستاذ {name}، يسعدنا في زاد كابيتال التواصل معك...')); ?>"></textarea>
                    </div>

                    <div class="p-3 rounded-3 mb-2" style="background:#FFFBEB;border:1px solid #FEF3C7;font-size:12px;color:#92400E;">
                        <div class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> <?php echo e(__('المتغيرات المتاحة للدمج التلقائي في الرسالة:')); ?></div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-white text-dark border pointer" onclick="insertPlaceholder('{name}')" style="cursor:pointer;"><code>{name}</code> ← <?php echo e(__('اسم العميل')); ?></span>
                            <span class="badge bg-white text-dark border pointer" onclick="insertPlaceholder('{phone}')" style="cursor:pointer;"><code>{phone}</code> ← <?php echo e(__('رقم هاتف العميل')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light py-2 px-3 fw-bold rounded-3 flex-fill" data-bs-dismiss="modal"><?php echo e(__('إلغاء')); ?></button>
                    <button type="button" id="btnSendCampaign" class="btn btn-success py-2 px-4 fw-bold rounded-3 flex-fill text-white">
                        <i class="bi bi-send-fill me-1"></i> <?php echo e(__('بدء إرسال الحملة')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function insertPlaceholder(tag) {
    const textarea = document.getElementById('campaignMessage');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + tag + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
}

document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllLeads');
    const checkboxes = document.querySelectorAll('.lead-checkbox');
    const btnCampaign = document.getElementById('btnWhatsappCampaign');
    const btnOpenFiltered = document.getElementById('btnOpenCampaignFiltered');
    const countEl = document.getElementById('selectedCount');
    const modalCountEl = document.getElementById('modalSelectedCount');
    const selectionStatusText = document.getElementById('selectionStatusText');
    const selectedCountText = document.getElementById('selectedCountText');
    const modalEl = document.getElementById('whatsappCampaignModal');
    const modal = new bootstrap.Modal(modalEl);
    const btnSend = document.getElementById('btnSendCampaign');
    const messageInput = document.getElementById('campaignMessage');
    const targetModeSelected = document.getElementById('targetModeSelected');
    const targetModeFiltered = document.getElementById('targetModeFiltered');

    function updateCount() {
        const checked = document.querySelectorAll('.lead-checkbox:checked').length;
        countEl.textContent = checked;
        modalCountEl.textContent = checked;
        if (selectedCountText) selectedCountText.textContent = checked;

        if (checked > 0) {
            btnCampaign.classList.remove('d-none');
            btnCampaign.classList.add('d-flex');
            if (selectionStatusText) selectionStatusText.classList.remove('d-none');
        } else {
            btnCampaign.classList.add('d-none');
            btnCampaign.classList.remove('d-flex');
            if (selectionStatusText) selectionStatusText.classList.add('d-none');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                if (!cb.disabled) {
                    cb.checked = selectAll.checked;
                }
            });
            updateCount();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    if (btnCampaign) {
        btnCampaign.addEventListener('click', function () {
            if (targetModeSelected) targetModeSelected.checked = true;
            modal.show();
        });
    }

    if (btnOpenFiltered) {
        btnOpenFiltered.addEventListener('click', function () {
            if (targetModeFiltered) targetModeFiltered.checked = true;
            modal.show();
        });
    }

    if (btnSend) {
        btnSend.addEventListener('click', function () {
            const message = messageInput.value.trim();
            if (!message) {
                messageInput.classList.add('is-invalid');
                return;
            }
            messageInput.classList.remove('is-invalid');

            const isTargetAllFiltered = targetModeFiltered && targetModeFiltered.checked;
            const leadIds = Array.from(document.querySelectorAll('.lead-checkbox:checked'))
                .map(cb => parseInt(cb.value));

            if (!isTargetAllFiltered && leadIds.length === 0) {
                alert('<?php echo e(__("يرجى تحديد عميل واحد على الأقل أو اختيار إرسال للفلتر الحالي.")); ?>');
                return;
            }

            btnSend.disabled = true;
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> <?php echo e(__("جاري جدولة الإرسال...")); ?>';

            const payload = {
                message: message,
                target_all_filtered: isTargetAllFiltered ? 1 : 0,
                lead_ids: leadIds,
                search: '<?php echo e(request("search")); ?>',
                status: '<?php echo e(request("status")); ?>',
                contact_source_id: '<?php echo e(request("contact_source_id")); ?>',
                employee_id: '<?php echo e(request("employee_id")); ?>',
                booking_status_group: '<?php echo e(request("booking_status_group")); ?>',
                booking_status: '<?php echo e(request("booking_status")); ?>'
            };

            fetch('<?php echo e(route("crm.leads.whatsapp-campaign")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(res => res.json().then(data => ({ status: res.status, data })))
            .then(({ status, data }) => {
                modal.hide();
                messageInput.value = '';
                if (selectAll) selectAll.checked = false;
                checkboxes.forEach(cb => cb.checked = false);
                updateCount();

                if (status >= 200 && status < 300 && data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || '<?php echo e(__("حدث خطأ أثناء الإرسال")); ?>', 'danger');
                }
            })
            .catch(() => {
                showToast('<?php echo e(__("حدث خطأ في الاتصال بالخادم")); ?>', 'danger');
            })
            .finally(() => {
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bi bi-send-fill me-1"></i> <?php echo e(__("بدء إرسال الحملة")); ?>';
            });
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed shadow-lg rounded-3`;
        toast.style.cssText = 'top:80px;right:20px;z-index:9999;min-width:320px;font-weight:bold;';
        toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>${message}<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 6000);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/leads/index.blade.php ENDPATH**/ ?>