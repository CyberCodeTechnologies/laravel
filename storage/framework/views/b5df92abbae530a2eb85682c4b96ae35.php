

<?php $__env->startSection('title', 'Language Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure language settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Language Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Default Language -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-globe text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Default Language</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(config('app.locale', 'en')); ?></p>
                        <p class="text-xs text-blue-700 mt-1">System default</p>
                    </div>
                </div>
            </div>

            <!-- Available Languages -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-list text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Available Languages</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(count($enabledLanguages ?? ['en', 'my'])); ?></p>
                        <p class="text-xs text-green-700 mt-1">Enabled locales</p>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Multi-language</p>
                        <p class="text-2xl font-bold text-purple-900">Active</p>
                        <p class="text-xs text-purple-700 mt-1">Feature enabled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Language Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Area -->
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('admin.settings.language.update')); ?>" class="bg-white rounded-lg shadow-md">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Language Configuration</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="default_language" class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                                <select id="default_language" name="default_language" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="en" <?php echo e(($defaultLanguage ?? config('app.locale')) === 'en' ? 'selected' : ''); ?>>English</option>
                                    <option value="my" <?php echo e(($defaultLanguage ?? config('app.locale')) === 'my' ? 'selected' : ''); ?>>Myanmar (Burmese)</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">The default language for the platform.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Languages</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_languages[]" value="en" 
                                               <?php echo e(in_array('en', $enabledLanguages ?? ['en', 'my']) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">English</span>
                                            <p class="text-xs text-gray-500">English language support</p>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_languages[]" value="my" 
                                               <?php echo e(in_array('my', $enabledLanguages ?? ['en', 'my']) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Myanmar (Burmese)</span>
                                            <p class="text-xs text-gray-500">Myanmar language support</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Select which languages should be available to users.</p>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_detect" value="1" 
                                           <?php echo e($autoDetect ?? false ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Auto-detect User Language</span>
                                        <p class="text-xs text-gray-500">Automatically detect user's browser language preference</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Language Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Language Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Language Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Current Locale</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(app()->getLocale()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Fallback Locale</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('app.fallback_locale', 'en')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Translation Files</span>
                            <span class="text-sm font-medium text-gray-900">2 (en, my)</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.settings.translations')); ?>" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-edit text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Edit Translations</span>
                        </a>
                        <a href="<?php echo e(route('admin.settings.cache')); ?>" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                            <i class="fas fa-sync text-orange-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Clear Translation Cache</span>
                        </a>
                        <a href="/lang" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition" target="_blank">
                            <i class="fas fa-folder text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">View Translation Files</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\language.blade.php ENDPATH**/ ?>