

<?php $__env->startSection('title', 'Announcements - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Announcements'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Site Announcements</h1>
            <p class="text-gray-600 mt-1">Promotional banners and alert messages</p>
        </div>
        <a href="<?php echo e(route('admin.frontend.dashboard')); ?>" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="<?php echo e(route('admin.frontend.announcements.update')); ?>" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <?php
            $items = $announcements->isNotEmpty() ? $announcements : collect([
                (object)['key' => 'main_banner', 'content_en' => '', 'content_my' => null, 'is_active' => false, 'sort_order' => 0],
            ]);
        ?>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                <input type="hidden" name="announcements[<?php echo e($index); ?>][key]" value="<?php echo e($item->key); ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message (EN) — <?php echo e($item->key); ?></label>
                    <textarea name="announcements[<?php echo e($index); ?>][content_en]" rows="2" required class="w-full border-gray-300 rounded-lg"><?php echo e(old("announcements.{$index}.content_en", $item->content_en)); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message (MY)</label>
                    <textarea name="announcements[<?php echo e($index); ?>][content_my]" rows="2" class="w-full border-gray-300 rounded-lg"><?php echo e(old("announcements.{$index}.content_my", $item->content_my)); ?></textarea>
                </div>
                <div class="flex gap-6 items-center">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="announcements[<?php echo e($index); ?>][is_active]" value="0">
                        <input type="checkbox" name="announcements[<?php echo e($index); ?>][is_active]" value="1" <?php echo e(old("announcements.{$index}.is_active", $item->is_active) ? 'checked' : ''); ?>> Active
                    </label>
                    <div>
                        <label class="text-sm text-gray-600 mr-2">Order</label>
                        <input type="number" name="announcements[<?php echo e($index); ?>][sort_order]" value="<?php echo e(old("announcements.{$index}.sort_order", $item->sort_order ?? 0)); ?>" class="w-20 border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Announcements</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\frontend\announcements.blade.php ENDPATH**/ ?>