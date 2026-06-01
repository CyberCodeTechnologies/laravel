<?php $__env->startSection('title', 'Test Artwork - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Test description'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Test Artwork Page</h1>
        <p>This is a test to verify the template structure works.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    console.log('Test page loaded');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artworks\show-test.blade.php ENDPATH**/ ?>