<?php $__env->startSection('title', __('الطلبات المغلقة') . ' | Zad Capital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('الطلبات المغلقة')); ?></span>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('الطلبات المغلقة')); ?></h4>
            <p class="text-muted small mb-0"><?php echo e(__('استعراض وتحليل الطلبات المنتهية والخاسرة حسب الحالات المختلفة')); ?></p>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <!-- Main total closed card -->
        <div class="col-12 col-md-4 col-xl-3">
            <div class="crm-stat-new py-4 shadow-sm" style="border: 1px solid var(--crm-border) !important; background: linear-gradient(135deg, #FFF, #FEF2F2); border-radius: 16px;">
                <div class="stat-icon red" style="background: #FEE2E2; color: #EF4444;"><i class="bi bi-folder-x"></i></div>
                <div class="stat-lbl fw-bold text-muted mt-2" style="font-size: 13px;"><?php echo e(__('إجمالي الطلبات المغلقة')); ?></div>
                <div class="stat-val text-danger fw-black fs-2 mt-1"><?php echo e(number_format($totalClosed)); ?></div>
            </div>
        </div>

        <!-- Closed Statuses Breakdowns -->
        <div class="col-12 col-md-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important; border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart me-1"></i> <?php echo e(__('نسب الحالات المغلقة')); ?></h6>
                </div>
                <div class="card-body py-2 px-3">
                    <div class="row g-2">
                        <?php $__currentLoopData = $statsByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusKey => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="p-2 rounded-3 text-start border" style="background: #F8FAFC; border-color: #E2E8F0 !important;">
                                    <div class="text-muted small text-truncate fw-semibold" title="<?php echo e($statusData['label']); ?>">
                                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--bs-<?php echo e($statusData['color'] ?? 'secondary'); ?>);"></span>
                                        <?php echo e($statusData['label']); ?>

                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span class="fw-bold text-dark fs-6"><?php echo e(number_format($statusData['count'])); ?></span>
                                        <span class="badge bg-light text-dark border small"><?php echo e($statusData['percentage']); ?>%</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-3 mb-4" style="border:1px solid var(--crm-border)!important;">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value=""><?php echo e(__('المصدر — الكل')); ?></option>
                        <option value="booking" <?php echo e(request('source')==='booking'?'selected':''); ?>><?php echo e(__('طلبات فقط')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>><?php echo e(__('عملاء حاسبة فقط')); ?></option>
                    </select>
                    
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:160px;">
                        <option value="newest" <?php echo e(request('sort','newest')==='newest'?'selected':''); ?>><?php echo e(__('الأحدث أولاً')); ?></option>
                        <option value="oldest" <?php echo e(request('sort','newest')==='oldest'?'selected':''); ?>><?php echo e(__('الأقدم أولاً')); ?></option>
                    </select>
                    
                    <select name="status" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;">
                        <option value=""><?php echo e(__('الحالة المغلقة — الكل')); ?></option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status')===$key?'selected':''); ?>><?php echo e($s['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('بحث بالاسم أو الهاتف...')); ?>"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>
                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;"><?php echo e(__('تصفية')); ?></button>
                    <a href="<?php echo e(route('crm.bookings.closed')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;">#</th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('العميل')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('السيارة')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('المندوب')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('الحالة')); ?></th>
                        <th class="px-3 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('تاريخ الإغلاق')); ?></th>
                        <th class="px-4 py-3 text-muted" style="font-size:12px;font-weight:700;"><?php echo e(__('الإجراء')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);">#<?php echo e($b->id); ?></a>
                        </td>
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-bold" style="font-size:13px;">
                                        <?php echo e($b->client_name); ?>

                                        <?php if($b->source === 'calculator'): ?>
                                            <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 5px; border-radius: 4px; font-weight: bold;">
                                                <i class="bi bi-calculator me-1"></i><?php echo e(__('حاسبة')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($b->car?->brand?->name); ?> <?php echo e($b->car?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3" style="font-size:13px;">
                            <?php echo e($b->employee?->name ?? '—'); ?>

                        </td>
                        <td class="px-3 py-3">
                            <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <?php
                                    $badgeColor = $b->status === 'received' ? 'success' : 'danger';
                                ?>
                                <select name="status" class="form-select form-select-sm border shadow-none bg-<?php echo e($badgeColor); ?> bg-opacity-10 text-<?php echo e($badgeColor); ?> fw-bold p-1" style="font-size:12px; width:auto; border-radius: 6px; border-color: rgba(var(--bs-<?php echo e($badgeColor); ?>-rgb), 0.25) !important;" onchange="this.form.submit()">
                                    <optgroup label="<?php echo e(__('إرجاع إلى المندوب (Active)')); ?>">
                                        <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval'): ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                    <optgroup label="<?php echo e(__('الحالات المغلقة (Closed)')); ?>">
                                        <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(($s['is_close'] ?? false) === true): ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                </select>
                            </form>
                        </td>
                        <td class="px-3 py-3 text-muted small" style="font-size:12px;">
                            <?php echo e($b->updated_at->format('Y-m-d H:i')); ?>

                        </td>
                        <td class="px-4 py-3">
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2" style="font-size:12px; font-weight:600; padding:6px 12px;">
                                    <i class="bi bi-eye me-1"></i><?php echo e(__('عرض')); ?>

                                </a>
                                <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 text-success" style="font-size:12px; font-weight:600; padding:6px 12px;">
                                    <i class="bi bi-whatsapp me-1"></i><?php echo e(__('واتساب')); ?>

                                </a>
                                <?php if($isAdmin): ?>
                                <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                                      onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light border fw-bold rounded-2 text-danger" style="font-size:12px; padding:6px 10px;">
                                        <i class="bi bi-trash"></i>
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
                            <?php echo e(__('لا توجد طلبات مغلقة حالياً مطابقة لخيارات التصفية')); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="d-block d-md-none">
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 p-3 rounded-3 bg-white border-bottom" style="border:1px solid var(--crm-border);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);">
                            <?php echo e($b->client_name); ?>

                            <?php if($b->source === 'calculator'): ?>
                                <span class="badge ms-1" style="background-color: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; font-size: 10px; padding: 2px 5px; border-radius: 4px;">
                                    <?php echo e(__('حاسبة')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                    <form action="<?php echo e(route('crm.bookings.status', $b)); ?>" method="POST" class="m-0">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <?php
                            $badgeColor = $b->status === 'received' ? 'success' : 'danger';
                        ?>
                        <select name="status" class="form-select form-select-sm border shadow-none bg-<?php echo e($badgeColor); ?> bg-opacity-10 text-<?php echo e($badgeColor); ?> fw-bold p-1" style="font-size:11px; width:auto; border-radius: 6px;" onchange="this.form.submit()">
                            <optgroup label="<?php echo e(__('إرجاع إلى المندوب (Active)')); ?>">
                                <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($s['group'] ?? '') === 'active' && !($s['is_close'] ?? false) && $key !== 'waiting_supervisor_approval'): ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($b->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="<?php echo e(__('الحالات المغلقة (Closed)')); ?>">
                                <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($s['is_close'] ?? false) === true): ?>
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
                    <div class="small">
                        <?php echo e($b->updated_at->format('Y-m-d H:i')); ?>

                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 flex-fill text-center" style="font-size:12px;">
                        <i class="bi bi-eye"></i> <?php echo e(__('عرض')); ?>

                    </a>
                    <a href="https://wa.me/<?php echo e($b->client_phone); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 flex-fill text-center text-success" style="font-size:12px;">
                        <i class="bi bi-whatsapp"></i> <?php echo e(__('واتساب')); ?>

                    </a>
                    <?php if($isAdmin): ?>
                    <form action="<?php echo e(route('crm.bookings.destroy', $b)); ?>" method="POST" class="m-0"
                          onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا الطلب نهائياً؟')); ?>')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-light border text-danger" style="font-size:12px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5 bg-white">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                <?php echo e(__('لا توجد طلبات مغلقة حالياً')); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/closed.blade.php ENDPATH**/ ?>