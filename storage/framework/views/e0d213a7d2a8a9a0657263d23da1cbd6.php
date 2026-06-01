<?php $__env->startSection('title', cms_content('faq_title', __('messages.faq_title')) . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', cms_content('faq_subtitle', __('messages.faq_meta_description'))); ?>
<?php $__env->startSection('meta-keywords', 'Panchi Gallery FAQ, art gallery questions, buying art FAQ, selling art FAQ, art certificates FAQ, Myanmar art FAQ, art marketplace support'); ?>
<?php $__env->startSection('meta-image', asset('images/og-default.jpg')); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "name": "Panchi Gallery FAQ",
    "url": "<?php echo e(route('faq')); ?>",
    "description": "Find answers to frequently asked questions about buying art, selling artwork, shipping, and using Panchi Gallery."
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="bg-black text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-light mb-6"><?php echo e(cms_content('faq_title', __('messages.faq_title'))); ?></h1>
            <p class="text-xl font-light opacity-90"><?php echo e(cms_content('faq_subtitle', __('messages.faq_subtitle'))); ?></p>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- General Questions -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-8"><?php echo e(__('messages.general_questions')); ?></h2>
                    
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_what_is_panchi')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_what_is_panchi_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_how_authentic')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_how_authentic_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_ship_countries')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_ship_countries_answer')); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- For Artists -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-8"><?php echo e(__('messages.for_artists')); ?></h2>
                    
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_become_seller')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_become_seller_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_commission')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_commission_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_price_artworks')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_price_artworks_answer')); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- For Collectors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-8"><?php echo e(__('messages.for_collectors')); ?></h2>
                    
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_purchase_artwork')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_purchase_artwork_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_payment_methods')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_payment_methods_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_resell_artworks')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_resell_artworks_answer')); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Returns -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-8"><?php echo e(__('messages.faq_shipping_returns')); ?></h2>
                    
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_packaging')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_packaging_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_return_policy')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_return_policy_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_customs')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_customs_answer')); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Technical Support -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-8"><?php echo e(__('messages.faq_technical_support')); ?></h2>
                    
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_verify_certificate')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_verify_certificate_answer')); ?>

                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-xl font-medium mb-3"><?php echo e(__('messages.faq_info_secure')); ?></h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo e(__('messages.faq_info_secure_answer')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="mt-16 bg-gray-50 rounded-lg p-8 text-center">
                <h2 class="text-2xl font-light mb-4"><?php echo e(__('messages.faq_still_questions')); ?></h2>
                <p class="text-gray-600 mb-6"><?php echo e(__('messages.faq_support_team')); ?></p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('contact')); ?>" 
                       class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <?php echo e(__('messages.contact_support')); ?>

                    </a>
                    <a href="mailto:support@panchigallery.com" 
                       class="px-6 py-3 border border-black text-black rounded-lg hover:bg-gray-100 transition-colors">
                        <?php echo e(__('messages.email_us')); ?>

                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\faq.blade.php ENDPATH**/ ?>