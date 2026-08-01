<?php $__env->startSection('title', __('إدارة السيارات') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold"> <?php echo e(__('إدارة أسطول السيارات')); ?></h4>
                <p class="text-muted mb-0 small"><?php echo e(__('إجمالي')); ?> <?php echo e($cars->total()); ?> <?php echo e(__('سيارة مسجلة')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-cars')): ?>
            <a href="<?php echo e(route('crm.cars.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('إضافة سيارة جديدة')); ?>

            </a>
            <?php endif; ?>
        </div>


        
        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('البحث عن سيارة')); ?></label>
                        <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="<?php echo e(__('الاسم، الموديل...')); ?>"
                            value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('الماركة')); ?></label>
                        <select name="brand_id" class="form-select bg-light border-0 shadow-none">
                            <option value=""><?php echo e(__('كل الماركات')); ?></option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>" <?php if(request('brand_id') == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('التصنيف')); ?></label>
                        <select name="category_id" class="form-select bg-light border-0 shadow-none">
                            <option value=""><?php echo e(__('كل التصنيفات')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php if(request('category_id') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3"><?php echo e(__('تصفية')); ?></button>
                        <a href="<?php echo e(route('crm.cars.index')); ?>" class="btn btn-light rounded-3 px-3"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 car-card-premium overflow-hidden">
                        
                        <div class="car-badges">
                            <?php if($car->is_featured): ?>
                                <span class="badge-premium featured"><i class="bi bi-star-fill"></i> <?php echo e(__('مميز')); ?></span>
                            <?php endif; ?>
                            <?php if(!$car->is_active): ?>
                                <span class="badge-premium hidden"><i class="bi bi-eye-slash"></i> <?php echo e(__('مخفي')); ?></span>
                            <?php endif; ?>
                        </div>

                        
                        <div class="car-img-wrapper">
                            <?php if($car->thumbnail): ?>
                                <img src="<?php echo e(asset('storage/' . $car->thumbnail)); ?>" alt="<?php echo e($car->name); ?>" class="car-img-main">
                            <?php else: ?>
                                <div class="car-no-img">
                                    <i class="bi bi-car-front"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($car->brand && $car->brand->logo): ?>
                                <div class="car-brand-mini">
                                    <img src="<?php echo e(asset('storage/' . $car->brand->logo)); ?>" alt="<?php echo e($car->brand->name); ?>">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-danger fw-bold x-small text-uppercase"><?php echo e($car->brand->name ?? ''); ?></span>
                                    <span class="text-muted x-small"><?php echo e($car->year); ?></span>
                                </div>
                                <h5 class="car-title" title="<?php echo e($car->name); ?>"><?php echo e($car->name); ?></h5>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="car-type-tag"><?php echo e($car->category->name ?? __('سيارة')); ?></span>
                                    <span class="text-muted small">•</span>
                                    <span class="text-muted x-small"><?php echo e($car->model); ?></span>
                                </div>
                            </div>

                            
                            <div class="car-price-grid mb-4">
                                <div class="price-item">
                                    <label><?php echo e(__('سعر الكاش')); ?></label>
                                    <div class="value">
                                        <span class="num"><?php echo e(number_format($car->cash_price)); ?></span>
                                        <span class="gr-currency"></span>
                                    </div>
                                </div>
                                <div class="price-item accent">
                                    <label><?php echo e(__('أقل قسط')); ?></label>
                                    <div class="value">
                                        <span class="num"><?php echo e(number_format($car->min_installment)); ?></span>
                                        <span class="gr-currency"></span>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="car-actions mt-auto">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-cars')): ?>
                                <a href="<?php echo e(route('crm.cars.edit', $car)); ?>" class="btn-action edit flex-grow-1">
                                    <i class="bi bi-pencil-square"></i> <?php echo e(__('تعديل')); ?>

                                </a>
                                <form action="<?php echo e(route('crm.cars.destroy', $car)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo e(__("هل أنت متأكد من حذف هذه السيارة؟")); ?>')" class="d-inline-flex">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn-action delete" title="<?php echo e(__('حذف')); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm opacity-50">
                        <i class="bi bi-car-front fs-1 d-block mb-3"></i>
                        <h6 class="fw-bold"><?php echo e(__('لا توجد سيارات مسجلة حالياً')); ?></h6>
                        <p class="small"><?php echo e(__('ابدأ بإضافة أول سيارة لأسطولك المعروض')); ?></p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-cars')): ?>
                        <a href="<?php echo e(route('crm.cars.create')); ?>" class="btn btn-primary btn-sm rounded-pill mt-3 px-4"><?php echo e(__('إضافة سيارة')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-5 d-flex justify-content-center"><?php echo e($cars->links('pagination::bootstrap-5')); ?></div>
    </div>

    <style>
        :root {
            --car-card-red: #16254F;
            --car-card-dark: #1A1C21;
            --car-card-bg: #FFFFFF;
            --car-card-shadow: 0 10px 30px rgba(0,0,0,0.06);
            --car-card-radius: 20px;
        }

        .car-card-premium {
            background: var(--car-card-bg);
            border-radius: var(--car-card-radius);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #F0F0F0 !important;
            position: relative;
        }

        .car-card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            border-color: #eee !important;
        }

        .car-badges {
            position: absolute;
            top: 15px;
            inset-inline-end: 15px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 10;
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .badge-premium.featured { background: rgba(255, 193, 7, 0.9); color: #000; }
        .badge-premium.hidden { background: rgba(227, 6, 19, 0.9); color: #fff; }

        .car-img-wrapper {
            height: 200px;
            position: relative;
            overflow: hidden;
            background: #F8F9FA;
        }

        .car-img-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .car-card-premium:hover .car-img-main {
            transform: scale(1.1);
        }

        .car-no-img {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ddd;
            font-size: 3rem;
        }

        .car-brand-mini {
            position: absolute;
            bottom: 12px;
            inset-inline-start: 15px;
            background: #fff;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .car-brand-mini img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .car-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--car-card-dark);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .car-type-tag {
            background: #F0F2F5;
            color: #666;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        .car-price-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #F8F9FB;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #EDF0F5;
        }

        .price-item {
            padding: 12px;
            display: flex;
            flex-direction: column;
            border-inline-end: 1px solid #EDF0F5;
        }

        .price-item:last-child { border: none; }

        .price-item label {
            font-size: 9px;
            font-weight: 700;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .price-item .value {
            display: flex;
            align-items: baseline;
            gap: 3px;
        }

        .price-item .num {
            font-size: 15px;
            font-weight: 900;
            color: var(--car-card-dark);
        }

        .price-item .gr-currency {
            width: 14px !important;
            height: 14px !important;
            opacity: 0.6;
        }

        .price-item.accent .num { color: var(--car-card-red); }
        .price-item.accent .gr-currency { color: var(--car-card-red) !important; opacity: 1; }

        .car-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 13px;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
        }

        .btn-action.edit {
            background: var(--car-card-dark);
            color: #fff;
        }

        .btn-action.edit:hover {
            background: #000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-action.delete {
            width: 42px;
            background: #FFF0F0;
            color: var(--car-card-red);
        }

        .btn-action.delete:hover {
            background: var(--car-card-red);
            color: #fff;
        }

        .x-small { font-size: 10px; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/cars/index.blade.php ENDPATH**/ ?>