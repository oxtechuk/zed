<?php $__env->startSection('title', __('طلبات تم التسليم') . ' | Zad Capital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <a href="<?php echo e(route('crm.bookings.index')); ?>"><?php echo e(__('الطلبات')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('طلبات تم التسليم')); ?></span>
    </nav>

    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#DCFCE7;color:#16A34A;">
                    <i class="bi bi-check2-circle"></i>
                </span>
                <?php echo e(__('طلبات تم التسليم (المستلمة)')); ?>

            </h4>
            <p class="text-muted small mb-0"><?php echo e(__('سجل الصفقات الناجحة والطلبات المسلّمة للعملاء ومتابعة الإيرادات والعمولات')); ?></p>
        </div>
        <a href="<?php echo e(route('crm.bookings.index')); ?>" class="btn btn-sm btn-outline-secondary rounded-3 fw-bold px-3">
            <i class="bi bi-arrow-right me-1"></i> <?php echo e(__('العودة للطلبات النشطة')); ?>

        </a>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #F0FDF4);">
                <div class="stat-icon green"><i class="bi bi-trophy"></i></div>
                <div class="stat-lbl"><?php echo e(__('إجمالي المسلّم')); ?></div>
                <div class="stat-val text-success"><?php echo e(number_format($stats['total_delivered'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #EFF6FF);">
                <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-lbl"><?php echo e(__('تسليمات هذا الشهر')); ?></div>
                <div class="stat-val text-primary"><?php echo e(number_format($stats['month_delivered'])); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #FDF4FF);">
                <div class="stat-icon purple"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-lbl"><?php echo e(__('عمولات هذا الشهر')); ?></div>
                <div class="stat-val" style="color:#9333EA;font-size:1.4rem;"><?php echo e(number_format($stats['month_commission'], 2)); ?> <small style="font-size:11px;">ر.س</small></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="crm-stat-new" style="background: linear-gradient(135deg, #FFF, #FEFCE8);">
                <div class="stat-icon orange"><i class="bi bi-wallet2"></i></div>
                <div class="stat-lbl"><?php echo e(__('إجمالي صافي العمولات')); ?></div>
                <div class="stat-val text-warning-dark" style="font-size:1.4rem;"><?php echo e(number_format($stats['total_commission'], 2)); ?> <small style="font-size:11px;">ر.س</small></div>
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
                               onchange="this.form.submit()" title="<?php echo e(__('تصفية بشهر التسليم')); ?>">
                    </div>

                    
                    <?php if(!request('month') || request('month') !== now()->format('Y-m')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['month' => now()->format('Y-m')])); ?>" class="btn btn-sm btn-light border fw-bold" style="font-size:12px;padding:8px 12px;">
                        <i class="bi bi-calendar-event me-1"></i> <?php echo e(__('تسليمات هذا الشهر')); ?>

                    </a>
                    <?php endif; ?>

                    
                    <select name="source" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:180px;" onchange="this.form.submit()">
                        <option value=""><?php echo e(__('المصدر والنوع — الكل')); ?></option>
                        <option value="cars" <?php echo e(request('source')==='cars'?'selected':''); ?>><?php echo e(__('طلبات السيارات (حجز وشراء)')); ?></option>
                        <option value="calculator" <?php echo e(request('source')==='calculator'?'selected':''); ?>><?php echo e(__('عملاء حاسبة التمويل')); ?></option>
                    </select>

                    
                    <select name="sort" style="border:1px solid var(--crm-border);border-radius:8px;padding:8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;min-width:170px;">
                        <option value="newest" <?php echo e(request('sort','newest')==='newest'?'selected':''); ?>><?php echo e(__('الأحدث تسليماً')); ?></option>
                        <option value="highest_commission" <?php echo e(request('sort')==='highest_commission'?'selected':''); ?>><?php echo e(__('الأعلى عمولة')); ?></option>
                        <option value="highest_price" <?php echo e(request('sort')==='highest_price'?'selected':''); ?>><?php echo e(__('الأعلى سعراً')); ?></option>
                        <option value="oldest" <?php echo e(request('sort')==='oldest'?'selected':''); ?>><?php echo e(__('الأقدم تسليماً')); ?></option>
                    </select>

                    
                    <div style="position:relative;flex:1;min-width:180px;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('بحث بالاسم أو الهاتف...')); ?>"
                               style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:8px 36px 8px 14px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;">
                        <i class="bi bi-search" style="position:absolute;<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>:12px;top:50%;transform:translateY(-50%);color:var(--crm-text-muted);"></i>
                    </div>

                    <button type="submit" class="btn-crm-primary" style="padding:8px 20px;"><?php echo e(__('تصفية')); ?></button>
                    <a href="<?php echo e(route('crm.bookings.delivered')); ?>" class="fw-bold text-decoration-none" style="font-size:13px;color:var(--crm-red);"><?php echo e(__('حذف الفلاتر')); ?></a>
                </div>
            </div>
        </div>
    </form>

    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border:1px solid var(--crm-border)!important;">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-check2-circle me-1 text-success"></i> <?php echo e(__('قائمة الطلبات المسلّمة')); ?></h6>
            <span style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(__('إجمالي المسلّم')); ?>: <strong><?php echo e($bookings->total()); ?></strong></span>
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
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('سعر الشراء')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('سعر التعميد')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('المصروفات')); ?></th>
                        <th class="py-3 text-muted fw-bold text-success" style="font-size:12px;"><?php echo e(__('صافي العمولة')); ?></th>
                        <th class="py-3 text-muted fw-bold" style="font-size:12px;"><?php echo e(__('تاريخ التسليم')); ?></th>
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
                        <td style="font-size:12px;">
                            <?php if($b->purchase_price): ?>
                                <span class="fw-bold"><?php echo e(number_format($b->purchase_price, 2)); ?></span> <small class="text-muted">ر.س</small>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;">
                            <?php if($b->authorization_price): ?>
                                <span class="fw-bold"><?php echo e(number_format($b->authorization_price, 2)); ?></span> <small class="text-muted">ر.س</small>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;">
                            <?php if($b->expenses): ?>
                                <span class="fw-bold text-danger"><?php echo e(number_format($b->expenses, 2)); ?></span> <small class="text-muted">ر.س</small>
                            <?php else: ?>
                                <span class="text-muted">0.00</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:13px;">
                            <?php if($b->net_commission !== null): ?>
                                <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:12px;font-weight:800;border:1px solid #BBF7D0;padding:5px 10px;">
                                    <?php echo e(number_format($b->net_commission, 2)); ?> ر.س
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;">
                            <?php
                                $deliveryDate = $b->delivered_at ?? $b->updated_at;
                            ?>
                            <div><i class="bi bi-calendar3 me-1 text-muted"></i> <?php echo e($deliveryDate->format('d/m/Y')); ?></div>
                            <div class="text-muted" style="font-size:11px;"><?php echo e($deliveryDate->diffForHumans()); ?></div>
                            <?php if($b->delivery_note_text): ?>
                                <div class="text-muted mt-1" style="font-size:11px;" title="<?php echo e($b->delivery_note_text); ?>">
                                    <i class="bi bi-chat-square-text me-1 text-success"></i><?php echo e(Str::limit($b->delivery_note_text, 25)); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4">
                            <div class="d-flex gap-1 align-items-center">
                                <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="btn btn-sm btn-light rounded-2 border" title="<?php echo e(__('عرض وتعديل التفاصيل')); ?>">
                                    <i class="bi bi-pencil-square" style="font-size:14px;color:var(--crm-text);"></i>
                                </a>
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
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="bi bi-check2-all fs-1 d-block mb-2 opacity-25 text-success"></i>
                            <div class="fw-bold"><?php echo e(__('لا توجد طلبات مسلّمة حالياً')); ?></div>
                            <small class="text-muted"><?php echo e(__('عند تحويل أي طلب إلى "تم التسليم" وإدخال بياناته المالية، سيظهر في هذه القائمة تلقائياً')); ?></small>
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
                        <a href="<?php echo e(route('crm.bookings.show', $b)); ?>" class="fw-bold text-decoration-none" style="color:var(--crm-red);font-size:14px;">#<?php echo e($b->id); ?></a>
                        <div class="fw-bold mt-1" style="font-size:14px;color:var(--crm-text);"><?php echo e($b->client_name); ?></div>
                        <div style="font-size:12px;color:var(--crm-text-muted);" dir="ltr"><?php echo e($b->client_phone); ?></div>
                    </div>
                    <span class="badge" style="background:#DCFCE7;color:#16A34A;font-size:11px;">
                        <i class="bi bi-check2-circle me-1"></i> <?php echo e(__('تم التسليم')); ?>

                    </span>
                </div>

                
                <div class="p-2 rounded-3 mb-2" style="background:#F8FAFC;border:1px solid var(--crm-border);font-size:12px;">
                    <div class="row g-2">
                        <div class="col-6">
                            <span class="text-muted small"><?php echo e(__('سعر الشراء:')); ?></span>
                            <div class="fw-bold"><?php echo e($b->purchase_price ? number_format($b->purchase_price, 2).' ر.س' : '—'); ?></div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small"><?php echo e(__('سعر التعميد:')); ?></span>
                            <div class="fw-bold"><?php echo e($b->authorization_price ? number_format($b->authorization_price, 2).' ر.س' : '—'); ?></div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small"><?php echo e(__('المصروفات:')); ?></span>
                            <div class="fw-bold text-danger"><?php echo e($b->expenses ? number_format($b->expenses, 2).' ر.س' : '0.00 ر.س'); ?></div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small"><?php echo e(__('صافي العمولة:')); ?></span>
                            <div class="fw-bold text-success"><?php echo e($b->net_commission !== null ? number_format($b->net_commission, 2).' ر.س' : '—'); ?></div>
                        </div>
                    </div>
                </div>

                <?php if($b->delivery_note_text): ?>
                <div class="p-2 rounded-3 mb-2" style="background:#F0FDF4;border:1px solid #BBF7D0;font-size:11.5px;color:#166534;">
                    <div class="fw-bold mb-1"><i class="bi bi-chat-square-text me-1"></i><?php echo e(__('ملاحظة التسليم')); ?>:</div>
                    <div class="text-dark"><?php echo e($b->delivery_note_text); ?></div>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                    <div><i class="bi bi-person me-1"></i> <?php echo e($b->employee?->name ?? __('غير معين')); ?></div>
                    <div><i class="bi bi-calendar3 me-1"></i> <?php echo e(($b->delivered_at ?? $b->updated_at)->format('d/m/Y')); ?></div>
                </div>

                <div class="d-flex gap-2 mt-2">
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
                <i class="bi bi-check2-all fs-1 d-block mb-2 opacity-25 text-success"></i>
                <div><?php echo e(__('لا توجد طلبات مسلّمة حالياً')); ?></div>
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

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/delivered.blade.php ENDPATH**/ ?>