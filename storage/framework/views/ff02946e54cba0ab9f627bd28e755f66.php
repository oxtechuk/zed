<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zed Capital</title>
    <?php if(file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/react/main.tsx'); ?>
    <?php else: ?>
        <?php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $entry = $manifest['resources/react/main.tsx'] ?? null;
        ?>
        <?php if($entry): ?>
            <?php if(!empty($entry['css'])): ?>
                <?php $__currentLoopData = $entry['css']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $css): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <link rel="stylesheet" href="/build/<?php echo e($css); ?>" />
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <script type="module" src="/build/<?php echo e($entry['file']); ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
</head>
<body>
    <div id="root"></div>
    <script src="https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js" async></script>
</body>
</html>
<?php /**PATH C:\wamp64\www\zed\resources\views/app.blade.php ENDPATH**/ ?>