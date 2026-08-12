<?php $__env->startSection('title', __('الطلبات') . ' | Zad Capital'); ?>

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
                <div class="stat-icon red"><i class="bi bi-clock"></i></div>
                <div class="stat-lbl"><?php echo e(__('بانتظار المراجعة')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['pending'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <div class="stat-icon blue"><i class="bi bi-people"></i></div>
                <div class="stat-lbl"><?php echo e(__('طلبات اليوم')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['today'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="crm-stat-new">
                <div class="stat-icon purple"><i class="bi bi-person-lines-fill"></i></div>
                <div class="stat-lbl"><?php echo e(__('إجمالي الطلبات')); ?></div>
                <div class="stat-val"><?php echo e(number_format($stats['total'])); ?></div>
            </div>
        </div>
    </div>

    
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                    <div style="position:relative;">
                        <input type="date" name="date" value="<?php echo e(request('date', now()->format('Y-m-d'))); ?>" lang="en"
                               style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif; width: 170px; min-width: 170px;">
                        <i class="bi bi-calendar3" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:10px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);pointer-events:none;"></i>
                    </div>
                    
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value=""><?php echo e(__('المصدر — الكل')); ?></option>
                        <option value="booking" <?php echo e(request('source')==='booking'?'selected':''); ?>><?php echo e(__('طلبات فقط')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>><?php echo e(__('عملاء حاسبة فقط')); ?></option>
                    </select>
                    
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="newest" <?php echo e(request('sort','newest')==='newest'?'selected':''); ?>><?php echo e(__('الأحدث أولاً')); ?></option>
                        <option value="oldest" <?php echo e(request('sort','newest')==='oldest'?'selected':''); ?>><?php echo e(__('الأقدم أولاً')); ?></option>
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
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($pa->car?->brand?->name); ?> <?php echo e($pa->car?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($pa->employee?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3">
                            <?php if($pa->proposed_status && isset($statuses[$pa->proposed_status])): ?>
                                <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:700;border:1px solid #FCA5A5;">
                                    <?php echo e($statuses[$pa->proposed_status]['label']); ?>

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
                                
                                <form action="<?php echo e(route('crm.bookings.destroy', $pa)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2" style="font-size:12px;color:var(--crm-red);padding:5px 10px;">
                                        <i class="bi bi-trash" style="font-size:13px;"></i>
                                    </button>
                                </form>
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
                                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($s['group'] === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval'): ?>
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
            <div class="mb-3 p-3 rounded-3" style="border:1px solid #FCA5A5;background:#FFF5F5;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $pa)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($pa->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:13px;"><?php echo e($pa->client_name); ?></div>
                        <div style="font-size:11px;color:var(--crm-text-muted);"><?php echo e($pa->employee?->name); ?> &bull; <?php echo e($pa->car?->brand?->name); ?> <?php echo e($pa->car?->name); ?></div>
                    </div>
                    <?php if($pa->proposed_status && isset($statuses[$pa->proposed_status])): ?>
                        <span class="badge" style="background:#FEE2E2;color:#DC2626;font-size:10px;border:1px solid #FCA5A5;">
                            <?php echo e($statuses[$pa->proposed_status]['label']); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <form action="<?php echo e(route('crm.bookings.approve', $pa)); ?>" method="POST" class="m-0 flex-fill">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="btn btn-sm btn-success w-100 fw-bold" style="font-size:11px;"><?php echo e(__('موافقة')); ?></button>
                    </form>
                    <button class="btn btn-sm fw-bold flex-fill" style="font-size:11px;background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;"
                            data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($pa->id); ?>"><?php echo e(__('إعادة')); ?></button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0"><?php echo e(__('سجل الطلبات')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي الطلبات')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
        </div>

        
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8F9FC;">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-bold" style="font-size:12px;">#</th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('رقم الطلب')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تاريخ التحديث')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('العميل')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('السيارة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('الحالة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تحكم')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 text-muted small" style="font-size:13px;">
                            <?php echo e($bookings->firstItem() + $index); ?>

                        </td>
                        <td class="fw-bold" style="font-size:13px;">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="text-decoration-none fw-bold" style="color:var(--crm-red);"><?php echo e($b->id); ?></a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><span class="text-muted"><?php echo e(__('انشاء :')); ?></span> <strong class="text-dark"><?php echo e($b->created_at->diffForHumans()); ?></strong></div>
                                <div><span class="text-muted"><?php echo e(__('التعديل :')); ?></span> <strong class="text-dark"><?php echo e($b->updated_at->diffForHumans()); ?></strong></div>
                                <div><span class="text-muted"><?php echo e(__('الموظف :')); ?></span> <strong class="text-dark"><?php echo e($b->employee?->name ?: __('غير معين')); ?></strong></div>
                                <div class="mt-1">
                                    <span class="text-muted"><?php echo e(__('المصدر :')); ?></span>
                                    <?php if($b->source === 'calculator'): ?>
                                        <span class="badge" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                            <i class="bi bi-calculator me-1"></i><?php echo e(__('عملاء حاسبة')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: #EBF5FF; color: #1E40AF; border: 1px solid #BFDBFE; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                            <i class="bi bi-file-earmark-text me-1"></i><?php echo e(__('طلب عادي')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:13px;"><?php echo e($b->client_name); ?></div>
                            <a href="tel:<?php echo e($b->client_phone); ?>" class="text-decoration-none small text-muted" dir="ltr"><?php echo e($b->client_phone); ?></a>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><span class="text-muted"><?php echo e(__('الماركة :')); ?></span> <strong class="text-dark"><?php echo e($b->car?->brand?->name ?? '—'); ?></strong></div>
                                <div><span class="text-muted"><?php echo e(__('موديل :')); ?></span> <strong class="text-dark"><?php echo e($b->car?->model ?? '—'); ?></strong></div>
                                <div><span class="text-muted"><?php echo e(__('الفئة :')); ?></span> <strong class="text-dark"><?php echo e($b->car?->category?->name ?? '—'); ?></strong></div>
                                <div><span class="text-muted"><?php echo e(__('سنة الصنع :')); ?></span> <strong class="text-dark"><?php echo e($b->car?->year ?? '—'); ?></strong></div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="text-muted me-1" style="white-space: nowrap;"><?php echo e(__('حالة الطلب :')); ?></span>
                                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0 d-inline-block">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <?php
                                            $dotClass = match(true) {
                                                $b->status === 'new' => 'planned',
                                                in_array($b->status, ['pending', 'waiting_supervisor_approval']) => 'waiting',
                                                $b->status === 'received' => 'done',
                                                str_starts_with($b->status, 'lost_') => 'late',
                                                default => 'confirmed',
                                            };
                                        ?>
                                        <select name="status" class="form-select form-select-sm border-0 shadow-none status-dot <?php echo e($dotClass); ?>" style="font-size:12px;font-weight:700;padding-top:2px;padding-bottom:2px;padding-right:24px;width:auto;display:inline-block;cursor:pointer;" onchange="this.form.submit()" <?php echo e(($b->status === 'waiting_supervisor_approval' && ! auth('employee')->user()->isAdmin()) ? 'disabled' : ''); ?>>
                                            <optgroup label="<?php echo e(__('الحالات الأساسية (Active)')); ?>">
                                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($s['group'] === 'active'): ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                            <optgroup label="<?php echo e(__('الحالات الخاسرة (Closed - Lost)')); ?>">
                                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($s['group'] === 'lost'): ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        </select>
                                    </form>
                                </div>
                                <div>
                                    <span class="text-muted"><?php echo e(__('الحالة الرئيسية :')); ?></span>
                                    <?php
                                        $currentStatus = $statuses[$b->status] ?? [];
                                        $isClosed = (($currentStatus['group'] ?? '') === 'lost') || ($currentStatus['is_close'] ?? false);
                                    ?>
                                    <strong class="<?php echo e($isClosed ? 'text-danger' : 'text-success'); ?>">
                                        <?php echo e($isClosed ? __('مغلق') : __('مفتوح')); ?>

                                    </strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض وتعديل')); ?>">
                                    <i class="bi bi-pencil" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض التفاصيل')); ?>">
                                    <i class="bi bi-eye" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
                                <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('واتساب')); ?>" style="color:#25D366;">
                                    <i class="bi bi-whatsapp" style="font-size:14px;"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب؟')); ?>')">
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
                        <td colspan="7" class="text-center text-muted py-5">
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
                $dotClassM = match(true) {
                    $b->status === 'new' => 'planned',
                    in_array($b->status, ['pending', 'waiting_supervisor_approval']) => 'waiting',
                    $b->status === 'received' => 'done',
                    str_starts_with($b->status, 'lost_') => 'late',
                    default => 'confirmed',
                };
            ?>
            <div class="mb-3 p-3 rounded-3" style="border:1px solid var(--crm-border);background:#fff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">
                            <?php echo e($b->client_name); ?>

                            <?php if($b->source === 'calculator'): ?>
                                <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 3px 6px; border-radius: 6px; font-weight: bold;">
                                    <i class="bi bi-calculator me-1"></i><?php echo e(__('عميل حاسبة')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-select form-select-sm border-0 shadow-none status-dot <?php echo e($dotClassM); ?>" style="font-size:11px;font-weight:700;padding:3px 8px;width:auto;" onchange="this.form.submit()" <?php echo e(($b->status === 'waiting_supervisor_approval' && ! auth('employee')->user()->isAdmin()) ? 'disabled' : ''); ?>>
                            <optgroup label="<?php echo e(__('الحالات الأساسية (Active)')); ?>">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($s['group'] === 'active'): ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="<?php echo e(__('الحالات الخاسرة (Closed - Lost)')); ?>">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($s['group'] === 'lost'): ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
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

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/index.blade.php ENDPATH**/ ?>