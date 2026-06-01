

<?php $__env->startSection('title', 'Social Media - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Social Media Links'); ?>

<?php $__env->startSection('admin_content'); ?>
<?php
    $links = $socialLinks->keyBy(fn ($s) => str_replace('social_', '', $s->key));
    $platforms = ['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'pinterest'];
?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Social Media</h1>
            <p class="text-gray-600 mt-1">Links shown in the site header and footer</p>
        </div>
        <a href="<?php echo e(route('admin.frontend.dashboard')); ?>" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="<?php echo e(route('admin.frontend.social.update')); ?>" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 capitalize"><?php echo e($platform); ?> URL</label>
                <input type="url" name="social[<?php echo e($platform); ?>]" value="<?php echo e(old("social.{$platform}", $links[$platform]->value ?? '')); ?>" placeholder="https://" class="w-full border-gray-300 rounded-lg">
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Social Links</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\frontend\social.blade.php ENDPATH**/ ?>