<?php $__env->startSection('title', 'Pending Approval - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-3xl text-yellow-600"></i>
            </div>
            <h2 class="text-2xl font-serif font-semibold text-gray-900 mb-4">Account Pending Approval</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Your account is currently under review. Our team is verifying your information. You will be notified via email once your account is approved.
            </p>
            <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto mb-8">
                <h3 class="font-semibold text-gray-900 mb-2">What happens next?</h3>
                <ul class="text-left text-gray-600 space-y-2 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                        <span>Our admin team reviews your application</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                        <span>You receive an email notification</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                        <span>Access all features upon approval</span>
                    </li>
                </ul>
            </div>
            <a href="<?php echo e(route('home')); ?>" class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition inline-block">
                Return to Home
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\dashboard\pending.blade.php ENDPATH**/ ?>