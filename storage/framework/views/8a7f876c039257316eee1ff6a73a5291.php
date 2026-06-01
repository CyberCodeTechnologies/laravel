

<?php $__env->startSection('title', 'SEO Settings - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'SEO Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<?php
    $get = fn ($key, $default = '') => $seoSettings['seo_'.$key]->value ?? $seoSettings[$key]->value ?? $default;
?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">SEO Settings</h1>
            <p class="text-gray-600 mt-1">Meta tags, analytics, and search engine optimization</p>
        </div>
        <a href="<?php echo e(route('admin.frontend.dashboard')); ?>" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="<?php echo e(route('admin.frontend.seo.update')); ?>" method="POST" class="card-luxury rounded-xl p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
            <input type="text" name="seo[meta_title]" value="<?php echo e(old('seo.meta_title', $get('meta_title', 'Panchi Gallery'))); ?>" maxlength="60" required class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
            <textarea name="seo[meta_description]" rows="3" maxlength="160" required class="w-full border-gray-300 rounded-lg"><?php echo e(old('seo.meta_description', $get('meta_description', 'Myanmar art marketplace'))); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
            <input type="text" name="seo[meta_keywords]" value="<?php echo e(old('seo.meta_keywords', $get('meta_keywords'))); ?>" class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Canonical URL</label>
            <input type="url" name="seo[canonical_url]" value="<?php echo e(old('seo.canonical_url', $get('canonical_url', config('app.url')))); ?>" class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Robots</label>
            <select name="seo[robots]" class="w-full border-gray-300 rounded-lg">
                <?php $__currentLoopData = ['index,follow','noindex,nofollow']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($opt); ?>" <?php echo e(old('seo.robots', $get('robots', 'index,follow')) === $opt ? 'selected' : ''); ?>><?php echo e($opt); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
            <input type="text" name="seo[google_analytics]" value="<?php echo e(old('seo.google_analytics', $get('google_analytics'))); ?>" class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook Pixel ID</label>
            <input type="text" name="seo[facebook_pixel]" value="<?php echo e(old('seo.facebook_pixel', $get('facebook_pixel'))); ?>" class="w-full border-gray-300 rounded-lg">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save SEO Settings</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\frontend\seo.blade.php ENDPATH**/ ?>