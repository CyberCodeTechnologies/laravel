<?php $__env->startSection('title', __('messages.terms_title')); ?>
<?php $__env->startSection('meta-description', __('messages.terms_meta_description') ?? 'Panchi Gallery terms of service. Read our terms and conditions for buying, selling, and collecting authentic Myanmar artwork on our platform.'); ?>
<?php $__env->startSection('meta-keywords', 'terms of service, terms and conditions, art gallery terms, buy art terms, sell art terms, Panchi Gallery legal'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8"><?php echo e(__('messages.terms_of_service')); ?></h1>
            
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_acceptance_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_acceptance_text')); ?>

                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_license_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_license_text')); ?>

                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_sales_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_sales_text')); ?>

                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_artist_responsibilities_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_artist_responsibilities_text')); ?>

                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_privacy_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_privacy_text')); ?>

                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4"><?php echo e(__('messages.terms_contact_title')); ?></h2>
                <p class="text-gray-600 mb-4">
                    <?php echo e(__('messages.terms_contact_text')); ?>

                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\terms.blade.php ENDPATH**/ ?>