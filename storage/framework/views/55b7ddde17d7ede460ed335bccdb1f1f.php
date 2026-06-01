<?php $__env->startSection('title', __('messages.create_account') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.register_meta_description')); ?>
<?php $__env->startSection('meta-keywords', 'register Panchi Gallery, create art account, artist registration, collector registration, Myanmar art platform, join art gallery'); ?>

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
            <h2 class="text-3xl font-bold text-gray-900"><?php echo e(__('messages.create_account')); ?></h2>
            <p class="mt-2 text-sm text-gray-600">
                <?php echo e(__('messages.already_have_account')); ?>

                <a href="<?php echo e(route('login')); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo e(__('messages.sign_in_here')); ?>

                </a>
            </p>
        </div>

        <!-- Registration Form -->
        <form class="mt-8 space-y-6" action="<?php echo e(route('register')); ?>" method="POST">
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
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">
                        <?php echo e(__('messages.first_name')); ?>

                    </label>
                    <input id="first_name"
                           name="first_name"
                           type="text"
                           autocomplete="given-name"
                           required
                           value="<?php echo e(old('first_name')); ?>"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.first_name')); ?>">
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">
                        <?php echo e(__('messages.last_name')); ?>

                    </label>
                    <input id="last_name"
                           name="last_name"
                           type="text"
                           autocomplete="family-name"
                           required
                           value="<?php echo e(old('last_name')); ?>"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.last_name')); ?>">
                </div>

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
                        <?php echo e(__('messages.password')); ?>

                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           autocomplete="new-password"
                           required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.create_password')); ?>">
                    <p class="mt-1 text-xs text-gray-500"><?php echo e(__('messages.must_be_8_chars')); ?></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        <?php echo e(__('messages.confirm_password')); ?>

                    </label>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           type="password"
                           autocomplete="new-password"
                           required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('messages.confirm_password')); ?>">
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <?php echo e(__('messages.i_am_a')); ?>

                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="role" value="collector" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('messages.art_collector_role')); ?></span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="role" value="artist" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('messages.artist_role')); ?></span>
                        </label>
                    </div>
                </div>

                <!-- Terms and Privacy -->
                <div class="flex items-center">
                    <input id="agree_terms" 
                           name="agree_terms" 
                           type="checkbox" 
                           required
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                        <?php echo e(__('messages.i_agree_to')); ?>

                        <a href="<?php echo e(route('terms')); ?>" class="text-indigo-600 hover:text-indigo-500"><?php echo e(__('messages.terms_of_service_link')); ?></a>
                        <?php echo e(__('messages.and')); ?>

                        <a href="<?php echo e(route('privacy')); ?>" class="text-indigo-600 hover:text-indigo-500"><?php echo e(__('messages.privacy_policy_link')); ?></a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus group-hover:text-indigo-400 text-indigo-500"></i>
                    </span>
                    <?php echo e(__('messages.create_account')); ?>

                </button>
            </div>

                    </form>

        <!-- Benefits -->
        <div class="mt-8 bg-indigo-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-indigo-900 mb-3"><?php echo e(__('messages.why_join')); ?></h3>
            <ul class="space-y-2 text-sm text-indigo-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-indigo-600 mt-0.5 mr-2"></i>
                    <span><?php echo e(__('messages.access_exclusive')); ?></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-indigo-600 mt-0.5 mr-2"></i>
                    <span><?php echo e(__('messages.certificates_every_purchase')); ?></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-indigo-600 mt-0.5 mr-2"></i>
                    <span><?php echo e(__('messages.secure_payment_processing')); ?></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-indigo-600 mt-0.5 mr-2"></i>
                    <span><?php echo e(__('messages.connect_artists_enthusiasts')); ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\auth\register.blade.php ENDPATH**/ ?>