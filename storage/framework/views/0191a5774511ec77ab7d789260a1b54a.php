<?php $__env->startSection('title', __('تعديل المقالة') . ' | AutoCRM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?php echo e(route('crm.blog.index')); ?>" class="btn btn-light btn-sm rounded-circle shadow-xs">
            <i class="bi bi-arrow-<?php echo e(app()->getLocale() == 'ar' ? 'right' : 'left'); ?>"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold"><?php echo e(__('تعديل المقالة')); ?></h4>
            <p class="text-muted small mb-0"><?php echo e(__('تحديث محتوى المقالة الحالية وتحسين ظهورها')); ?></p>
        </div>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small fw-bold">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('crm.blog.update', $blog->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="row g-4">
            
            
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-fill text-primary me-2"></i> <?php echo e(__('محتوى المقالة')); ?></h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('عنوان المقالة (بالعربية)')); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="title[ar]" class="form-control bg-light border-0 shadow-none fs-5 fw-bold" placeholder="<?php echo e(__('عنوان المقالة باللغة العربية...')); ?>" value="<?php echo e(old('title.ar', $blog->getTranslation('title', 'ar'))); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('عنوان المقالة (بالإنجليزية)')); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="title[en]" class="form-control bg-light border-0 shadow-none fs-5 fw-bold" placeholder="Article title in English..." value="<?php echo e(old('title.en', $blog->getTranslation('title', 'en'))); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('مقتطف تعريفي (عربي)')); ?></label>
                                <textarea name="excerpt[ar]" class="form-control bg-light border-0 shadow-none" rows="2"><?php echo e(old('excerpt.ar', $blog->getTranslation('excerpt', 'ar'))); ?></textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('مقتطف تعريفي (EN)')); ?></label>
                                <textarea name="excerpt[en]" class="form-control bg-light border-0 shadow-none" rows="2"><?php echo e(old('excerpt.en', $blog->getTranslation('excerpt', 'en'))); ?></textarea>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('المحتوى الكامل (عربي)')); ?> <span class="text-danger">*</span></label>
                            <textarea name="content[ar]" class="form-control bg-light border-0 shadow-none" rows="10"><?php echo e(old('content.ar', $blog->getTranslation('content', 'ar'))); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('المحتوى الكامل (EN)')); ?> <span class="text-danger">*</span></label>
                            <textarea name="content[en]" class="form-control bg-light border-0 shadow-none" rows="10"><?php echo e(old('content.en', $blog->getTranslation('content', 'en'))); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-lg-4">
                
                
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-primary text-white overflow-hidden shadow-lg">
                    <div class="card-body p-4 position-relative"style="background-color: black !important;">
                        <i class="bi bi-cloud-upload-fill position-absolute opacity-10" style="font-size: 80px; right: -10px; bottom: -20px;"></i>
                        <h6 class="mb-3 fw-bold"><?php echo e(__('تحديث الحالة')); ?></h6>
                        
                        <div class="form-check form-switch mb-4 p-3 bg-white bg-opacity-10 rounded-3 border-0">
                            <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_published" value="1" id="isPublished" <?php echo e(old('is_published', $blog->is_published) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold ms-2" for="isPublished"><?php echo e(__('تم النشر (أونلاين)')); ?></label>
                        </div>

                        <div class="form-check form-switch mb-4 p-3 bg-white bg-opacity-10 rounded-3 border-0">
                            <input class="form-check-input <?php echo e(app()->getLocale() == 'ar' ? 'ms-0 me-2 float-none' : ''); ?>" type="checkbox" name="is_featured" value="1" id="isFeatured" <?php echo e(old('is_featured', $blog->is_featured) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold ms-2" for="isFeatured"><?php echo e(__('تمييز المقالة (تثبيت في المميزة)')); ?></label>
                        </div>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-blog')): ?>
                        <button type="submit" class="btn btn-white w-100 py-3 fw-black text-primary border-0 rounded-3 shadow-sm">
                            <i class="bi bi-arrow-repeat me-1" style="color: #ee1b24 !important;"></i> <?php echo e(__('تحديث البيانات الآن')); ?>

                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bookmark-star text-info me-2"></i> <?php echo e(__('وسم المقالة')); ?></h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <select name="tag" class="form-select bg-light border-0 shadow-none">
                            <option value=""><?php echo e(__('بدون وسم')); ?></option>
                            <option value="new" <?php echo e(old('tag', $blog->tag) == 'new' ? 'selected' : ''); ?>><?php echo e(__('جديد')); ?></option>
                            <option value="popular" <?php echo e(old('tag', $blog->tag) == 'popular' ? 'selected' : ''); ?>><?php echo e(__('شائع')); ?></option>
                            <option value="exclusive" <?php echo e(old('tag', $blog->tag) == 'exclusive' ? 'selected' : ''); ?>><?php echo e(__('حصري')); ?></option>
                            <option value="limited" <?php echo e(old('tag', $blog->tag) == 'limited' ? 'selected' : ''); ?>><?php echo e(__('محدود')); ?></option>
                        </select>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-image text-danger me-2"></i> <?php echo e(__('الصورة البارزة')); ?></h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <?php if($blog->thumbnail): ?>
                        <div class="mb-3 text-center bg-light p-2 rounded-3">
                            <img src="<?php echo e(asset('storage/' . $blog->thumbnail)); ?>" class="img-fluid rounded shadow-xs" style="max-height: 150px;">
                            <p class="text-muted small mt-2 mb-0"><?php echo e(__('الصورة الحالية')); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="bg-light rounded-4 p-3 mb-2 border border-dashed text-center position-relative overflow-hidden" style="min-height: 120px;">
                            <div id="thumbnailPreview" class="d-none">
                                <img id="thumbImg" src="" alt="preview" class="img-fluid rounded shadow-xs mb-2">
                            </div>
                            <div id="uploadPlaceholder">
                                <i class="bi bi-camera fs-2 opacity-25 d-block mb-1"></i>
                                <span class="small fw-bold text-muted"><?php echo e(__('رفع صورة جديدة')); ?></span>
                            </div>
                            <input type="file" name="thumbnail" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" id="thumbnailInput">
                        </div>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tags text-warning me-2"></i> <?php echo e(__('تصنيفات المقالة')); ?></h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <?php if(isset($categories) && $categories->isNotEmpty()): ?>
                            <?php $selectedIds = old('categories', $blog->categories->pluck('id')->toArray()); ?>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                            value="<?php echo e($category->id); ?>" id="cat<?php echo e($category->id); ?>"
                                            <?php echo e(in_array($category->id, $selectedIds) ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="cat<?php echo e($category->id); ?>">
                                            <?php if($category->icon): ?>
                                                <i class="bi bi-<?php echo e($category->icon); ?> me-1"></i>
                                            <?php endif; ?>
                                            <?php echo e($category->name); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-0"><?php echo e(__('لا توجد تصنيفات متاحة بعد.')); ?></p>
                            <a href="<?php echo e(route('crm.blog-categories.index')); ?>" class="small"><?php echo e(__('إضافة تصنيفات جديدة')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-search text-success me-2"></i> <?php echo e(__('تحسين محركات البحث (SEO)')); ?></h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('عنوان Meta')); ?></label>
                            <input type="text" name="meta_title" class="form-control bg-light border-0 shadow-none" value="<?php echo e(old('meta_title', $blog->meta_title)); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('وصف Meta')); ?></label>
                            <textarea name="meta_description" class="form-control bg-light border-0 shadow-none" rows="3"><?php echo e(old('meta_description', $blog->meta_description)); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted"><?php echo e(__('الكلمات المفتاحية')); ?></label>
                            <input type="text" name="meta_keywords" class="form-control bg-light border-0 shadow-none" value="<?php echo e(old('meta_keywords', $blog->meta_keywords)); ?>">
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </form>
</div>

<style>
    .btn-white { background: #fff; }
    .fw-black { font-weight: 900; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .cursor-pointer { cursor: pointer; }
    .ck-editor__editable {
        min-height: 350px !important;
        background-color: #fff !important;
        color: #1e293b !important;
        border-radius: 0 0 12px 12px !important;
    }
    .ck-editor {
        border-radius: 12px !important;
        overflow: hidden;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
// Initialize CKEditor 5
ClassicEditor
    .create(document.querySelector('textarea[name="content[ar]"]'), {
        language: 'ar',
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
    })
    .catch(error => {
        console.error(error);
    });

ClassicEditor
    .create(document.querySelector('textarea[name="content[en]"]'), {
        language: 'en',
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
    })
    .catch(error => {
        console.error(error);
    });

// رفع وعرض الصورة
document.getElementById('thumbnailInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        document.getElementById('thumbImg').src = ev.target.result;
        document.getElementById('thumbnailPreview').classList.remove('d-none');
        document.getElementById('uploadPlaceholder').classList.add('d-none');
    };
    reader.readAsDataURL(file);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.Layouts.crm-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zed\resources\views/crm/blog/edit.blade.php ENDPATH**/ ?>