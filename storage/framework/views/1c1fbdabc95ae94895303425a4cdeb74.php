<?php $__env->startSection('title', __('الملف الشخصي') . ' | ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="crm-page-header">
        <h1 class="crm-page-title"><?php echo e(__('إعدادات الملف الشخصي')); ?></h1>
        <p class="crm-page-sub"><?php echo e(__('قم بتحديث بياناتك الشخصية وصورتك من هنا')); ?></p>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="crm-card text-center mb-4">
                <div class="position-relative d-inline-block mb-3">
                    <div class="crm-user-avatar shadow-sm" style="width: 120px; height: 120px; font-size: 40px; border: 4px solid #fff;">
                        <?php if($user->avatar): ?>
                            <img src="<?php echo e(asset('storage/'.$user->avatar)); ?>" id="avatar-preview" alt="<?php echo e($user->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div id="avatar-placeholder" class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-danger fw-bold">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <img src="" id="avatar-preview" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <label for="avatar-input" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-2 shadow" style="width: 35px; height: 35px; cursor: pointer;">
                        <i class="bi bi-camera"></i>
                    </label>
                </div>
                <h5 class="fw-bold mb-1"><?php echo e($user->name); ?></h5>
                <p class="text-muted small mb-3"><?php echo e($user->role === 'admin' ? __('مدير النظام') : ($user->role === 'sales-rep' ? __('مندوب مبيعات') : ($user->role === 'sales' ? __('موظف مبيعات') : __($user->role)))); ?></p>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge <?php echo e($user->is_active ? 'bg-success' : 'bg-danger'); ?> rounded-pill px-3">
                        <?php echo e($user->is_active ? __('نشط') : __('غير نشط')); ?>

                    </span>
                </div>
            </div>

            <div class="crm-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i><?php echo e(__('أمان الحساب')); ?></h6>
                <p class="text-muted small mb-0"><?php echo e(__('تم تسجيل دخولك كـ')); ?>: <span class="fw-bold text-dark"><?php echo e($user->username); ?></span></p>
                <hr class="my-3 opacity-50">
                <p class="text-muted small"><?php echo e(__('تأكد من استخدام كلمة مرور قوية وفريدة لحماية حسابك من الاختراق.')); ?></p>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <form action="<?php echo e(route('crm.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*" onchange="previewAvatar(this)">

                <div class="crm-card mb-4">
                    <h5 class="crm-card-title"><?php echo e(__('البيانات الأساسية')); ?></h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('الاسم الكامل')); ?></label>
                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $user->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('البريد الإلكتروني')); ?></label>
                            <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', $user->email)); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('رقم الهاتف')); ?></label>
                            <input type="text" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('phone', $user->phone)); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('اسم المستخدم')); ?></label>
                            <input type="text" class="form-control" value="<?php echo e($user->username); ?>" disabled>
                            <small class="text-muted"><?php echo e(__('لا يمكن تغيير اسم المستخدم')); ?></small>
                        </div>
                    </div>
                </div>

                <div class="crm-card mb-4">
                    <h5 class="crm-card-title"><?php echo e(__('تغيير كلمة المرور')); ?></h5>
                    <p class="text-muted small mb-4"><?php echo e(__('اترك الحقول فارغة إذا كنت لا ترغب في تغيير كلمة المرور الحالية')); ?></p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('كلمة المرور الجديدة')); ?></label>
                            <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo e(__('تأكيد كلمة المرور')); ?></label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" class="btn btn-crm-light px-4"><?php echo e(__('إعادة تعيين')); ?></button>
                    <button type="submit" class="btn btn-crm-primary px-5"><?php echo e(__('حفظ التغييرات')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if(placeholder) placeholder.classList.add('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/profile/index.blade.php ENDPATH**/ ?>