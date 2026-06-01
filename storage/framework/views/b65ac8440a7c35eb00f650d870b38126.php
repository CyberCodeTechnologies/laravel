<?php $__env->startSection('title', 'Verify Email - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-envelope text-2xl text-blue-600"></i>
        </div>
        
        <h1 class="text-2xl font-serif font-bold text-gray-900 mb-4">Verify Your Email</h1>
        
        <p class="text-gray-600 mb-6">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>
        
        <?php if(session('status') == 'verification-link-sent'): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                A new verification link has been sent to your email address.
            </div>
        <?php endif; ?>
        
        <form action="<?php echo e(route('verification.send')); ?>" method="POST" class="mb-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium">
                Resend Verification Email
            </button>
        </form>
        
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">
                Log Out
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>