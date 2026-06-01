<?php $__env->startSection('title', 'Page Not Found - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'The page you are looking for could not be found. Browse our collection of authentic Myanmar artworks.'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center py-20 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- 404 Animation -->
        <div class="mb-8">
            <h1 class="font-serif text-9xl font-bold text-gray-900 mb-4">404</h1>
            <div class="w-32 h-1 bg-black mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="font-serif text-3xl md:text-4xl font-semibold text-gray-900 mb-4">
            <?php echo e(__('messages.page_not_found') ?? 'Page Not Found'); ?>

        </h2>
        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            <?php echo e(__('messages.page_not_found_description') ?? 'The artwork or page you are looking for has been moved, removed, or never existed.'); ?>

        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
            <a href="<?php echo e(route('home')); ?>" class="px-8 py-4 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                <?php echo e(__('messages.go_home') ?? 'Go Home'); ?>

            </a>
            <a href="<?php echo e(route('public.artworks.index')); ?>" class="px-8 py-4 border-2 border-black text-black rounded-xl font-semibold hover:bg-black hover:text-white transition-all duration-300">
                <?php echo e(__('messages.browse_artworks') ?? 'Browse Artworks'); ?>

            </a>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
            <h3 class="font-serif text-xl font-semibold text-gray-900 mb-6">
                <?php echo e(__('messages.explore_more') ?? 'Explore More'); ?>

            </h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="<?php echo e(route('public.artworks.index')); ?>" class="text-gray-600 hover:text-black transition-colors duration-300">
                    <?php echo e(__('messages.artworks') ?? 'Artworks'); ?>

                </a>
                <a href="<?php echo e(route('public.artists.index')); ?>" class="text-gray-600 hover:text-black transition-colors duration-300">
                    <?php echo e(__('messages.artists') ?? 'Artists'); ?>

                </a>
                <a href="<?php echo e(route('marketplace.index')); ?>" class="text-gray-600 hover:text-black transition-colors duration-300">
                    <?php echo e(__('messages.marketplace') ?? 'Marketplace'); ?>

                </a>
                <a href="<?php echo e(route('contact')); ?>" class="text-gray-600 hover:text-black transition-colors duration-300">
                    <?php echo e(__('messages.contact') ?? 'Contact'); ?>

                </a>
            </div>
        </div>
        
        <!-- Help Text -->
        <p class="text-gray-500 mt-8 text-sm">
            <?php echo e(__('messages.need_help') ?? 'Need help? '); ?> <a href="<?php echo e(route('contact')); ?>" class="text-black hover:underline"><?php echo e(__('messages.contact_support') ?? 'Contact our support team'); ?></a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\errors\404.blade.php ENDPATH**/ ?>