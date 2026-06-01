<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="zoho-admin-html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — Panchi Gallery ERP</title>
    
    <base href="<?php echo e(rtrim(str_replace('/public/public', '/public', url('/')), '/')); ?>/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin-zoho.css', 'resources/css/app.css', 'resources/css/loader.css', 'resources/js/admin-zoho.js']); ?>

    
    <?php if(str_contains(request()->getRequestUri(), '/public/public') && file_exists(public_path('build/manifest.json'))): ?>
        <?php $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true); ?>
        <?php if(isset($manifest['resources/css/admin-zoho.css'])): ?>
            <link rel="stylesheet" href="<?php echo e(asset('build/'.$manifest['resources/css/admin-zoho.css']['file'])); ?>">
        <?php endif; ?>
        <?php if(isset($manifest['resources/css/app.css'])): ?>
            <link rel="stylesheet" href="<?php echo e(asset('build/'.$manifest['resources/css/app.css']['file'])); ?>">
        <?php endif; ?>
        <?php if(isset($manifest['resources/js/admin-zoho.js'])): ?>
            <script src="<?php echo e(asset('build/'.$manifest['resources/js/admin-zoho.js']['file'])); ?>" defer></script>
        <?php endif; ?>
    <?php endif; ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="zoho-admin-body">
    <div class="zoho-app">
        <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="zoho-main">
            <?php echo $__env->make('admin.partials.topbar', [
                'moduleTitle' => trim($__env->yieldContent('header')) ?: ($moduleTitle ?? 'Home'),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="zoho-body <?php echo e(($showWidgets ?? request()->routeIs('admin.dashboard*')) ? 'has-widgets' : ''); ?>">
                <main class="zoho-content">
                    <?php if(session('success')): ?>
                        <div class="zoho-alert zoho-alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="zoho-alert zoho-alert-error">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('admin_content'); ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </main>

                <?php if($showWidgets ?? request()->routeIs('admin.dashboard*')): ?>
                    <?php echo $__env->make('admin.partials.widgets-pane', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\layouts\app.blade.php ENDPATH**/ ?>