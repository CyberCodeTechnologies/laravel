<?php $__env->startSection('title', __('messages.privacy_title') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.privacy_meta_description')); ?>
<?php $__env->startSection('meta-keywords', 'privacy policy, data protection, Panchi Gallery privacy, personal information, Myanmar art gallery privacy, GDPR'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="bg-black text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-light mb-6"><?php echo e(__('messages.privacy_policy')); ?></h1>
            <p class="text-xl font-light opacity-90"><?php echo e(__('messages.privacy_subtitle')); ?></p>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-8"><?php echo e(__('messages.last_updated')); ?>: <?php echo e(date('F j, Y')); ?></p>

                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_intro_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_intro_p1')); ?>

                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_intro_p2')); ?>

                    </p>
                </div>

                <!-- Information We Collect -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_info_collect_title')); ?></h2>
                    
                    <h3 class="text-xl font-medium mb-4"><?php echo e(__('messages.privacy_personal_info')); ?></h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_personal_info_desc')); ?>

                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        <?php $__currentLoopData = __('messages.privacy_personal_info_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <h3 class="text-xl font-medium mb-4"><?php echo e(__('messages.privacy_artwork_info')); ?></h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_artwork_info_desc')); ?>

                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        <?php $__currentLoopData = __('messages.privacy_artwork_info_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <h3 class="text-xl font-medium mb-4"><?php echo e(__('messages.privacy_technical_info')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_technical_info_desc')); ?>

                    </p>
                </div>

                <!-- How We Use Your Information -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_use_info_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4"><?php echo e(__('messages.privacy_use_info_desc')); ?></p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <?php $__currentLoopData = __('messages.privacy_use_info_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Information Sharing -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_sharing_title')); ?></h2>
                    
                    <h3 class="text-xl font-medium mb-4"><?php echo e(__('messages.privacy_no_sell')); ?></h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        <?php echo e(__('messages.privacy_no_sell_desc')); ?>

                    </p>

                    <h3 class="text-xl font-medium mb-4"><?php echo e(__('messages.privacy_when_share')); ?></h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        <?php $__currentLoopData = __('messages.privacy_when_share_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo $item; ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Data Security -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_security_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_security_desc')); ?>

                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <?php $__currentLoopData = __('messages.privacy_security_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Your Rights -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_rights_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4"><?php echo e(__('messages.privacy_rights_desc')); ?></p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <?php $__currentLoopData = __('messages.privacy_rights_list'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Cookies and Tracking -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_cookies_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_cookies_p1')); ?>

                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_cookies_p2')); ?>

                    </p>
                </div>

                <!-- International Data Transfers -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_transfers_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_transfers_desc')); ?>

                    </p>
                </div>

                <!-- Children's Privacy -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_children_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_children_desc')); ?>

                    </p>
                </div>

                <!-- Changes to This Policy -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_changes_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.privacy_changes_desc')); ?>

                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6"><?php echo e(__('messages.privacy_contact_title')); ?></h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        <?php echo e(__('messages.privacy_contact_desc')); ?>

                    </p>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-600">
                            <strong><?php echo e(__('messages.privacy_email_label')); ?>:</strong> privacy@panchigallery.com<br>
                            <strong><?php echo e(__('messages.privacy_address_label')); ?>:</strong> [Your Business Address]<br>
                            <strong><?php echo e(__('messages.privacy_phone_label')); ?>:</strong> [Your Phone Number]
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\privacy.blade.php ENDPATH**/ ?>