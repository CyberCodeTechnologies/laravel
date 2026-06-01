<?php $__env->startSection('title', 'Admin Login - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Admin login for Panchi Gallery management system'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <a href="<?php echo e(route('home')); ?>" class="flex justify-center items-center space-x-2 mb-6">
                <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center border-2 border-gray-700">
                    <span class="text-white font-serif text-xl font-bold">P</span>
                </div>
                <span class="font-serif text-3xl font-bold text-white">Panchi Gallery</span>
            </a>
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-900 text-red-200 border border-red-700">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Admin Access
                </span>
            </div>
            <h2 class="text-3xl font-bold text-white">Admin Login</h2>
            <p class="mt-2 text-sm text-gray-400">
                Access the administrative control panel
            </p>
        </div>

        <!-- Login Form -->
        <form class="mt-8 space-y-6" action="<?php echo e(route('admin.login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <div>
                            <p class="font-medium">Authentication Error</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">
                        Email Address
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="<?php echo e(old('email')); ?>"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-700 bg-gray-800 placeholder-gray-500 text-white rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm"
                           placeholder="admin@panchigallery.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">
                        Password
                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           autocomplete="current-password"
                           required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-700 bg-gray-800 placeholder-gray-500 text-white rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your admin password">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-600 rounded bg-gray-800">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                        Remember me on this device
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-shield-alt group-hover:text-red-400 text-red-500"></i>
                    </span>
                    Sign In to Admin Panel
                </button>
            </div>

            <!-- Security Notice -->
            <div class="mt-4 p-3 bg-gray-800 border border-gray-700 rounded-lg">
                <div class="flex">
                    <i class="fas fa-info-circle text-gray-400 mr-2 mt-0.5"></i>
                    <div class="text-sm text-gray-400">
                        <p class="font-medium text-gray-300">Security Notice</p>
                        <p>This area is restricted to authorized administrators only. All access attempts are logged.</p>
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer Links -->
        <div class="text-center space-y-2">
            <div class="text-sm text-gray-400">
                <a href="<?php echo e(route('login')); ?>" class="text-red-400 hover:text-red-300">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to User Login
                </a>
            </div>
            <div class="text-sm text-gray-500">
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-300">
                    <i class="fas fa-home mr-1"></i>
                    Return to Gallery
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\auth\admin-login.blade.php ENDPATH**/ ?>