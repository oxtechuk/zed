<?php $__env->startSection('title', __('تفاصيل الطلب') . ' #' . $booking->id . ' | Zad Capital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <?php
        $dotClass = match($booking->status) {
            'new','pending'  => 'planned',
            'contacted'      => 'waiting',
            'sold','done'    => 'done',
            'rejected'       => 'late',
            default          => 'confirmed',
        };
        $historyNotes = $booking->notes_list->where('type', 'status_change');
        $comments = $booking->notes_list->whereIn('type', ['note', 'call']);
        $taskDot = fn ($task) => $task->status === 'done' ? 'done' : ($task->is_late ? 'late' : 'waiting');
    ?>

    
    <nav class="crm-breadcrumb">
        <a href="<?php echo e(route('crm.dashboard')); ?>"><?php echo e(__('الرئيسية')); ?></a>
        <span class="sep">›</span>
        <a href="<?php echo e(route('crm.bookings.index')); ?>"><?php echo e(__('الطلبات')); ?></a>
        <span class="sep">›</span>
        <span class="current"><?php echo e(__('تفاصيل الطلب')); ?> #<?php echo e($booking->id); ?></span>
    </nav>

    
    <div class="rounded-4 mb-3 p-4" style="background:#14234d;box-shadow:0 8px 20px rgba(249,115,22,0.25);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <h5 class="fw-bold mb-0 text-white"><?php echo e(__('عرض بيانات الطلب')); ?> <span style="opacity:0.85;">#<?php echo e($booking->id); ?></span></h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('crm.bookings.index')); ?>" class="btn btn-sm rounded-3 fw-bold" style="background:rgba(255,255,255,0.18);color:#fff;padding:8px 14px;">
                    <i class="bi bi-arrow-right"></i>
                    <span class="d-none d-md-inline"><?php echo e(__('العودة للطلبات')); ?></span>
                </a>
                <button onclick="window.print()" class="btn btn-sm rounded-3 fw-bold" style="background:#fff;color:var(--crm-orange-dark);padding:8px 14px;">
                    <i class="bi bi-printer"></i>
                    <span class="d-none d-md-inline"><?php echo e(__('طباعة تفاصيل الطلب')); ?></span>
                </button>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-4 text-white" style="font-size:13px;opacity:0.95;">
            <span><i class="bi bi-person-circle me-1"></i><?php echo e(__('العميل')); ?>: <strong><?php echo e($booking->client_name); ?></strong></span>
            <span dir="ltr"><i class="bi bi-telephone me-1"></i><?php echo e($booking->client_phone); ?></span>
            <span><i class="bi bi-flag me-1"></i><?php echo e(__('الحالة')); ?>: <strong><?php echo e($booking->status_label); ?></strong></span>
            <span><i class="bi bi-calendar3 me-1"></i><?php echo e($booking->created_at->format('d/m/Y H:i')); ?></span>
        </div>
    </div>

    
    <ul class="nav nav-tabs mb-3" style="border-bottom:1px solid var(--crm-border);" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-data" type="button">
                <i class="bi bi-file-text me-1"></i><?php echo e(__('بيانات الطلب')); ?>

            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-offer" type="button">
                <i class="bi bi-cash-coin me-1"></i><?php echo e(__('تفاصيل العرض')); ?>

            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-history" type="button">
                <i class="bi bi-clock-history me-1"></i><?php echo e(__('سجل تغيير الحالات')); ?>

                <span class="badge bg-light text-dark ms-1"><?php echo e($historyNotes->count()); ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-comments" type="button">
                <i class="bi bi-chat-left-dots me-1"></i><?php echo e(__('التعليقات')); ?>

                <span class="badge bg-light text-dark ms-1"><?php echo e($comments->count()); ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" style="font-size:13px;" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button">
                <i class="bi bi-list-check me-1"></i><?php echo e(__('المهام')); ?>

                <span class="badge bg-light text-dark ms-1"><?php echo e($booking->tasks->count()); ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content">

        
        <div class="tab-pane fade show active" id="tab-data">
            <div class="row g-3 mb-3">
                
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                            <h6 class="fw-bold mb-0"><?php echo e(__('بيانات العميل والتواصل')); ?></h6>
                        </div>
                        <div class="card-body px-4 py-3">
                            <?php
                                $orderRows = [
                                    __('اسم العميل')      => $booking->client_name,
                                    __('جوال العميل')     => $booking->client_phone,
                                    __('البريد الإلكتروني') => $booking->client_email ?: '—',
                                    __('رقم الطلب')       => '#' . $booking->id,
                                    __('تاريخ إنشاء الطلب') => $booking->created_at->format('d/m/Y • H:i'),
                                    __('نوع الطلب')       => $booking->booking_type ? (\App\Models\Booking::BOOKING_TYPES_LABELS[$booking->booking_type] ?? '—') : '—',
                                    __('الموقع الجغرافي') => $booking->location ?: '—',
                                ];
                            ?>
                            <?php $__currentLoopData = $orderRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e($label); ?></span>
                                <span style="font-size:13px;font-weight:700;color:var(--crm-text);" dir="<?php echo e(in_array($label, [__('جوال العميل')]) ? 'ltr' : 'inherit'); ?>"><?php echo e($value); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between py-2 align-items-center">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('حالة الطلب الحالية')); ?></span>
                                <span class="status-dot <?php echo e($dotClass); ?>"><?php echo e($booking->status_label); ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 align-items-center">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('الموظف المسؤول')); ?></span>
                                <span style="font-size:13px;font-weight:700;color:var(--crm-text);"><?php echo e($booking->employee->name ?? __('غير معين')); ?></span>
                            </div>

                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                            <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                                <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;"><?php echo e(__('إسناد المسؤول')); ?></label>
                                <form action="<?php echo e(route('crm.bookings.assign', $booking)); ?>" method="POST" class="d-flex align-items-center gap-2 w-100">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <select name="employee_id" class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;">
                                        <option value=""><?php echo e(__('غير معين')); ?></option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($emp->id); ?>" <?php echo e($booking->assigned_to == $emp->id ? 'selected' : ''); ?>><?php echo e($emp->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-2 text-white flex-shrink-0" style="background:var(--crm-text);font-size:12px;white-space:nowrap;padding: 6px 12px;">
                                        <?php echo e(__('تحويل')); ?>

                                    </button>
                                </form>
                            </div>

                            
                            <div class="mt-3 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                                <label style="font-size:12px;font-weight:700;margin-bottom:8px;display:block;"><?php echo e(__('تغيير حالة الطلب')); ?></label>
                                <form action="<?php echo e(route('crm.bookings.status', $booking)); ?>" method="POST" class="d-flex align-items-center gap-2 w-100">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <select name="status" class="form-select form-select-sm border-0 shadow-none" style="background:#fff;border-radius:8px;font-size:13px;font-weight:700;">
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($booking->status === $key ? 'selected' : ''); ?>><?php echo e($s['label']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm fw-bold rounded-2 text-white flex-shrink-0" style="background:var(--crm-orange);font-size:12px;white-space:nowrap;">
                                        <?php echo e(__('حفظ')); ?>

                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="border:1px solid var(--crm-border)!important;">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                            <h6 class="fw-bold mb-0"><?php echo e(__('بيانات السيارة وجهة التمويل')); ?></h6>
                        </div>
                        <div class="card-body px-4 py-3">
                            <?php if($booking->car): ?>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('كود السيارة')); ?></span>
                                <span style="font-size:13px;font-weight:700;">#<?php echo e($booking->car->id); ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('نوع السيارة')); ?></span>
                                <span style="font-size:13px;font-weight:700;"><?php echo e($booking->car->brand->name ?? ''); ?> <?php echo e($booking->car->name); ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('اللون المطلوب')); ?></span>
                                <span style="font-size:13px;font-weight:700;"><?php echo e($booking->car->color ?? __('غير محدد')); ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('سعر السيارة')); ?></span>
                                <span style="font-size:13px;font-weight:700;"><?php echo e(number_format($booking->car->cash_price)); ?> <?php echo __('ريال'); ?></span>
                            </div>
                            <?php else: ?>
                            <div class="text-center text-muted py-3" style="font-size:13px;"><?php echo e(__('لا توجد سيارة مرتبطة بالطلب')); ?></div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between py-2 mt-2">
                                <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('جهة التمويل')); ?></span>
                                <span style="font-size:13px;font-weight:700;"><?php echo e($booking->financingBank->name ?? __('غير محددة')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('المستندات والتصاريح')); ?></h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('crm.bookings.documents.store', $booking)); ?>" method="POST" enctype="multipart/form-data" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="fw-bold mb-1" style="font-size:12px;"><?php echo e(__('اسم المستند (اختياري)')); ?></label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="<?php echo e(__('مثال: الهوية الوطنية، تصريح المرور...')); ?>" style="border-radius:8px;font-size:13px;padding:8px 12px;">
                            </div>
                            <div class="col-md-5">
                                <label class="fw-bold mb-1" style="font-size:12px;"><?php echo e(__('الملف')); ?></label>
                                <input type="file" name="file" class="form-control form-control-sm" required style="border-radius:8px;font-size:13px;padding:8px 12px;">
                            </div>
                            <div class="col-md-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                                <button type="submit" class="btn-crm-primary w-100" style="padding:8px 16px;">
                                    <i class="bi bi-upload"></i> <?php echo e(__('رفع')); ?>

                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <?php if($booking->documents && $booking->documents->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="border:1px solid var(--crm-border);border-radius:8px;overflow:hidden;">
                            <thead style="background:#F8F9FC;">
                                <tr>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);"><?php echo e(__('المستند')); ?></th>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);"><?php echo e(__('بواسطة')); ?></th>
                                    <th class="py-2 px-3 text-muted fw-bold" style="font-size:12px;border-bottom:1px solid var(--crm-border);"><?php echo e(__('التاريخ')); ?></th>
                                    <th class="py-2 px-3 text-muted fw-bold text-end" style="font-size:12px;border-bottom:1px solid var(--crm-border);"><?php echo e(__('إجراء')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $booking->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;background:#F1F5F9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#475467;">
                                                <?php if(in_array(strtolower($doc->file_type), ['png','jpg','jpeg','gif'])): ?>
                                                    <i class="bi bi-file-image fs-5"></i>
                                                <?php elseif(strtolower($doc->file_type) == 'pdf'): ?>
                                                    <i class="bi bi-file-pdf fs-5" style="color:var(--crm-red);"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-file-earmark fs-5"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size:13px;color:var(--crm-text);"><?php echo e($doc->title); ?></div>
                                                <div style="font-size:11px;color:var(--crm-text-muted);">.<?php echo e(strtoupper($doc->file_type)); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:12px;color:var(--crm-text-muted);"><?php echo e($doc->employee->name ?? __('النظام')); ?></td>
                                    <td style="font-size:12px;color:var(--crm-text-muted);"><?php echo e($doc->created_at->format('Y-m-d H:i')); ?></td>
                                    <td class="text-end px-3">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="<?php echo e(asset('storage/'.$doc->file_path)); ?>" target="_blank" class="btn btn-sm btn-light rounded-2 text-primary" title="<?php echo e(__('عرض')); ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                                            <form action="<?php echo e(route('crm.bookings.documents.destroy', $doc)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل تريد حذف هذا المستند؟')); ?>')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-light rounded-2" style="color:var(--crm-red);" title="<?php echo e(__('حذف')); ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4 opacity-50">
                        <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                        <p class="mb-0 small"><?php echo e(__('لا توجد مستندات مرفوعة بعد')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="tab-pane fade" id="tab-offer">
            <div class="card border-0 shadow-sm rounded-4 mb-3" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('تفاصيل العرض')); ?></h6>
                </div>
                <div class="card-body px-4 py-3">
                    <?php
                        $commission = $booking->monthly_installment * 0.035;
                        $delivery   = 125;
                        $total      = $booking->monthly_installment + $commission + $delivery;
                    ?>
                    <div class="row g-0">
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('سعر السيارة')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e(number_format($booking->car->cash_price ?? $booking->total_price)); ?> <?php echo __('ريال'); ?></div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('الدفعة الأولى')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e(number_format($booking->down_payment)); ?> <?php echo __('ريال'); ?></div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('مدة التمويل')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e($booking->duration_years); ?> <?php echo e(__('سنوات')); ?></div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('القسط الشهري')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e(number_format($booking->monthly_installment)); ?> <?php echo __('ريال'); ?></div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('الدفعة الأخيرة')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e($booking->balloon_payment ? number_format($booking->balloon_payment) . ' ' . __('ريال') : '—'); ?></div>
                        </div>
                        <div class="col-6 col-md-4 py-2" style="border-bottom:1px solid var(--crm-border);">
                            <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('جهة التمويل')); ?></div>
                            <div style="font-size:14px;font-weight:800;"><?php echo e($booking->financingBank->name ?? '—'); ?></div>
                        </div>
                    </div>

                    <div class="row g-0 mt-2">
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                            <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('عمولة البنك')); ?> (3.5%)</span>
                            <span style="font-size:13px;font-weight:700;"><?php echo e(number_format($commission)); ?> <?php echo __('ريال'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--crm-border);">
                            <span style="font-size:13px;color:var(--crm-text-muted);"><?php echo e(__('رسوم التوصيل')); ?></span>
                            <span style="font-size:13px;font-weight:700;"><?php echo e(number_format($delivery)); ?> <?php echo __('ريال'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-3">
                            <span style="font-size:14px;font-weight:800;color:var(--crm-text);"><?php echo e(__('الإجمالي')); ?></span>
                            <span style="font-size:14px;font-weight:900;color:var(--crm-orange-dark);"><?php echo e(number_format($total)); ?> <?php echo __('ريال'); ?></span>
                        </div>
                    </div>

                    <?php if($booking->offer_notes): ?>
                    <div class="mt-2 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <div style="font-size:12px;color:var(--crm-text-muted);margin-bottom:4px;"><?php echo e(__('تفاصيل العرض المرسل للعميل')); ?></div>
                        <div style="font-size:13px;font-weight:600;white-space:pre-line;"><?php echo e($booking->offer_notes); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('تعديل تفاصيل العرض')); ?></h6>
                </div>
                <div class="card-body px-4 py-3">
                    <form action="<?php echo e(route('crm.bookings.offer', $booking)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="font-size:12px;"><?php echo e(__('جهة التمويل')); ?></label>
                                <select name="calculator_bank_id" class="form-select form-select-sm" style="border-radius:8px;">
                                    <option value=""><?php echo e(__('— اختر —')); ?></option>
                                    <?php $__currentLoopData = $calculatorBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bank->id); ?>" <?php echo e($booking->calculator_bank_id == $bank->id ? 'selected' : ''); ?>><?php echo e($bank->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="font-size:12px;"><?php echo e(__('الدفعة الأخيرة')); ?></label>
                                <input type="number" name="balloon_payment" min="0" value="<?php echo e($booking->balloon_payment); ?>" class="form-control form-control-sm" style="border-radius:8px;">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn-crm-primary w-100" style="padding:8px 16px;"><?php echo e(__('حفظ')); ?></button>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold" style="font-size:12px;"><?php echo e(__('تفاصيل العرض المرسل للعميل')); ?></label>
                                <textarea name="offer_notes" rows="3" class="form-control form-control-sm" style="border-radius:8px;"><?php echo e($booking->offer_notes); ?></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="tab-pane fade" id="tab-history">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('سجل تغيير الحالات')); ?></h6>
                </div>
                <div class="card-body p-4">
                    <div style="position:relative;padding-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:20px;border-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:2px solid var(--crm-border);">
                        <?php $__empty_1 = true; $__currentLoopData = $historyNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <div class="position-absolute" style="<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:-9px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid var(--crm-blue);"></div>
                            <div class="flex-grow-1">
                                <div class="p-3 rounded-3 border" style="background:#fff;border-color:var(--crm-border)!important;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="status-dot planned" style="font-size:11px;"><?php echo e(\App\Models\Booking::STATUSES[$note->old_status]['label'] ?? $note->old_status); ?></span>
                                        <i class="bi bi-arrow-<?php echo e(app()->getLocale()=='ar'?'left':'right'); ?>"></i>
                                        <span class="status-dot done" style="font-size:11px;"><?php echo e(\App\Models\Booking::STATUSES[$note->new_status]['label'] ?? $note->new_status); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border" style="font-size:11px;font-weight:600;"><?php echo e($note->employee->name ?? __('النظام')); ?></span>
                                        <span style="font-size:11px;color:var(--crm-text-muted);"><i class="bi bi-clock me-1"></i><?php echo e($note->created_at->format('d/m/Y H:i')); ?> — <?php echo e($note->created_at->diffForHumans()); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            <p class="mb-0 small"><?php echo e(__('لا توجد تغييرات في الحالة بعد')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="tab-pane fade" id="tab-comments">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('التعليقات')); ?></h6>
                </div>
                <div class="card-body p-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-bookings')): ?>
                    <form action="<?php echo e(route('crm.bookings.note', $booking)); ?>" method="POST" class="mb-4 p-3 rounded-3" style="background:#F8F9FC;border:1px solid var(--crm-border);">
                        <?php echo csrf_field(); ?>
                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <label class="fw-bold mb-1" style="font-size:12px;"><?php echo e(__('إضافة تعليق جديد')); ?></label>
                                <textarea name="note" rows="2" required placeholder="<?php echo e(__('اكتب تعليقاً...')); ?>"
                                          style="width:100%;border:1px solid var(--crm-border);border-radius:8px;padding:10px 14px;font-size:13px;font-family:'Cairo',sans-serif;outline:none;resize:none;"></textarea>
                            </div>
                            <div>
                                <select name="type" style="border:1px solid var(--crm-border);border-radius:8px;padding:9px 12px;font-size:13px;outline:none;font-family:'Cairo',sans-serif;margin-bottom:4px;display:block;">
                                    <option value="note">📌 <?php echo e(__('ملاحظة')); ?></option>
                                    <option value="call">📞 <?php echo e(__('مكالمة')); ?></option>
                                </select>
                                <button type="submit" class="btn-crm-primary w-100" style="padding:9px 16px;"><?php echo e(__('إضافة')); ?></button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>

                    <div style="position:relative;padding-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:20px;border-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:2px solid var(--crm-border);">
                        <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <div class="position-absolute" style="<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:-9px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid <?php echo e($note->type === 'call' ? '#12B76A' : 'var(--crm-orange)'); ?>;"></div>
                            <div class="flex-grow-1">
                                <div class="p-3 rounded-3 border" style="background:#fff;border-color:var(--crm-border)!important;">
                                    <p class="mb-2" style="font-size:13px;font-weight:600;color:var(--crm-text);">
                                        <?php echo e($note->type === 'call' ? '📞 ' : '📌 '); ?><?php echo e($note->note); ?>

                                    </p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border" style="font-size:11px;font-weight:600;"><?php echo e($note->employee->name ?? __('النظام')); ?></span>
                                        <span style="font-size:11px;color:var(--crm-text-muted);"><i class="bi bi-clock me-1"></i><?php echo e($note->created_at->diffForHumans()); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-chat-left-dots fs-1 d-block mb-2"></i>
                            <p class="mb-0 small"><?php echo e(__('لا توجد تعليقات بعد')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="tab-pane fade" id="tab-tasks">
            <div class="card border-0 shadow-sm rounded-4" style="border:1px solid var(--crm-border)!important;">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--crm-border)!important;">
                    <h6 class="fw-bold mb-0"><?php echo e(__('المهام الخاصة بالطلب')); ?></h6>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                    <button class="btn-crm-primary" style="padding:7px 14px;" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="bi bi-plus-lg"></i> <?php echo e(__('إضافة مهمة جديدة')); ?>

                    </button>
                    <?php endif; ?>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <?php $__empty_1 = true; $__currentLoopData = $booking->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background:#F8F9FC;border:1px solid var(--crm-border);border-<?php echo e(app()->getLocale()=='ar'?'right':'left'); ?>:3px solid <?php echo e($task->priority === 'high' ? '#DC2626' : ($task->priority === 'medium' ? 'var(--crm-orange)' : 'var(--crm-green)')); ?>;">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <strong style="font-size:13px;<?php echo e($task->status === 'done' ? 'text-decoration:line-through;opacity:0.6;' : ''); ?>"><?php echo e($task->title); ?></strong>
                                    <span class="status-dot <?php echo e($taskDot($task)); ?>"><?php echo e($task->display_status_label); ?></span>
                                </div>
                                <?php if($task->description): ?>
                                <p class="mb-2" style="font-size:12px;color:var(--crm-text-muted);"><?php echo e(Str::limit($task->description, 90)); ?></p>
                                <?php endif; ?>
                                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:11px;color:var(--crm-text-muted);">
                                    <?php if($task->assignedTo): ?>
                                    <span><i class="bi bi-person-circle me-1"></i><?php echo e($task->assignedTo->name); ?></span>
                                    <?php endif; ?>
                                    <?php if($task->due_date): ?>
                                    <span><i class="bi bi-calendar3 me-1"></i><?php echo e($task->due_date->format('d/m/Y')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
                                <div class="d-flex gap-1 flex-wrap">
                                    <?php if($task->status !== 'done'): ?>
                                    <form action="<?php echo e(route('crm.tasks.complete', $task)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm rounded-2 fw-bold text-white" style="font-size:11px;background:var(--crm-green);"><i class="bi bi-check-lg"></i> <?php echo e(__('إنهاء')); ?></button>
                                    </form>
                                    <form action="<?php echo e(route('crm.tasks.postpone', $task)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="days" value="1">
                                        <button class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;"><i class="bi bi-clock-history"></i> <?php echo e(__('تأجيل')); ?></button>
                                    </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-light rounded-2 fw-bold" style="font-size:11px;"
                                            data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                            data-task-url="<?php echo e(route('crm.tasks.update', $task)); ?>"
                                            data-task-title="<?php echo e($task->title); ?>"
                                            data-task-description="<?php echo e($task->description); ?>"
                                            data-task-priority="<?php echo e($task->priority); ?>"
                                            data-task-status="<?php echo e($task->status); ?>"
                                            data-task-due="<?php echo e($task->due_date?->format('Y-m-d')); ?>"
                                            data-task-assigned="<?php echo e($task->assigned_to); ?>">
                                        <i class="bi bi-pencil"></i> <?php echo e(__('تعديل')); ?>

                                    </button>
                                    <form action="<?php echo e(route('crm.tasks.destroy', $task)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('حذف هذه المهمة؟')); ?>')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-light rounded-2" style="font-size:11px;color:var(--crm-red);"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12 text-center py-5 opacity-50">
                            <i class="bi bi-list-check fs-1 d-block mb-2"></i>
                            <p class="mb-0 small"><?php echo e(__('لا توجد مهام مرتبطة بهذا الطلب')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-tasks')): ?>
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><?php echo e(__('إضافة مهمة جديدة')); ?></h5>
                    <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('crm.tasks.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
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

    
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><?php echo e(__('تعديل المهمة')); ?></h5>
                    <button type="button" class="btn-close <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-auto' : ''); ?>" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTaskForm" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?php echo e(__('عنوان المهمة')); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="editTaskTitle" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?php echo e(__('الوصف')); ?></label>
                            <textarea name="description" id="editTaskDescription" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('الأولوية')); ?></label>
                                <select name="priority" id="editTaskPriority" class="form-select rounded-3">
                                    <option value="low"><?php echo e(__('منخفضة')); ?></option>
                                    <option value="medium"><?php echo e(__('متوسطة')); ?></option>
                                    <option value="high"><?php echo e(__('عالية')); ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('الحالة')); ?></label>
                                <select name="status" id="editTaskStatus" class="form-select rounded-3">
                                    <option value="new"><?php echo e(__('جديدة')); ?></option>
                                    <option value="in_progress"><?php echo e(__('قيد التنفيذ')); ?></option>
                                    <option value="done"><?php echo e(__('مكتملة')); ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('تاريخ الاستحقاق')); ?></label>
                                <input type="date" name="due_date" id="editTaskDue" class="form-control rounded-3">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><?php echo e(__('إسناد إلى')); ?></label>
                                <select name="assigned_to" id="editTaskAssigned" class="form-select rounded-3">
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
                        <button type="submit" class="btn-crm-primary"><?php echo e(__('حفظ التعديلات')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
window.onbeforeprint = () => document.title = 'طلب #<?php echo e($booking->id); ?> — Zad Capital';

document.getElementById('editTaskModal')?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    const form = document.getElementById('editTaskForm');
    form.action = btn.getAttribute('data-task-url');
    document.getElementById('editTaskTitle').value = btn.getAttribute('data-task-title') || '';
    document.getElementById('editTaskDescription').value = btn.getAttribute('data-task-description') || '';
    document.getElementById('editTaskPriority').value = btn.getAttribute('data-task-priority') || 'medium';
    document.getElementById('editTaskStatus').value = btn.getAttribute('data-task-status') || 'new';
    document.getElementById('editTaskDue').value = btn.getAttribute('data-task-due') || '';
    document.getElementById('editTaskAssigned').value = btn.getAttribute('data-task-assigned') || '';
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/bookings/show.blade.php ENDPATH**/ ?>