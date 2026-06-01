<?php $__env->startSection('title', __('messages.sign_in') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.sign_in_meta_description')); ?>
<?php $__env->startSection('meta-keywords', 'login to Panchi Gallery, sign in art gallery, artist login, collector login, Myanmar art account'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <a href="<?php echo e(route('home')); ?>" class="flex justify-center items-center space-x-2 mb-6">
                <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                    <span class="text-white font-serif text-lg font-bold">P</span>
                </div>
                <span class="font-serif text-2xl font-bold text-black">Panchi Gallery</span>
            </a>
            <h2 class="text-3xl font-bold text-gray-900"><?php echo e(__('messages.sign_in_to_account')); ?></h2>
            <p class="mt-2 text-sm text-gray-600">
                <?php echo e(__('messages.or')); ?>

                <a href="<?php echo e(route('register')); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo e(__('messages.create_new_account')); ?>

                </a>
            </p>
        </div>

        <!-- Login Form -->
        <form class="mt-8 space-y-6" action="<?php echo e(route('login')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            <p class="font-medium"><?php echo e(__('messages.please_fix_errors')); ?></p>
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
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        <?php echo e(__('messages.email_address')); ?>

                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="<?php echo e(old('email')); ?>"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.email_address')); ?>">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <?php echo e(__('messages.password_label')); ?>

                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           autocomplete="current-password"
                           required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.password_label')); ?>">
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            <?php echo e(__('messages.remember_me')); ?>

                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?php echo e(route('password.request')); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                            <?php echo e(__('messages.forgot_password')); ?>

                        </a>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock group-hover:text-indigo-400 text-indigo-500"></i>
                    </span>
                    <?php echo e(__('messages.sign_in')); ?>

                </button>
            </div>

                    </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\auth\login.blade.php ENDPATH**/ ?>