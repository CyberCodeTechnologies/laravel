<?php $__env->startSection('title', 'Pending Approval - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-2xl text-yellow-600"></i>
        </div>
        
        <h1 class="text-2xl font-serif font-bold text-gray-900 mb-4">Account Pending Approval</h1>
        
        <p class="text-gray-600 mb-6">
            Your artist account is currently under review. Our team is verifying your information to ensure quality and authenticity on our platform.
        </p>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-500">
                This usually takes 1-2 business days. You'll receive an email notification once your account is approved.
            </p>
        </div>
        
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\auth\pending-approval.blade.php ENDPATH**/ ?>