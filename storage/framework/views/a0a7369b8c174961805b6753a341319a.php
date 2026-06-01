

<?php $__env->startSection('title', 'Platform Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure platform-wide settings and preferences on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Platform Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- System Status -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-server text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">System Status</p>
                        <p class="text-2xl font-bold text-blue-900">Online</p>
                        <p class="text-xs text-blue-700 mt-1"><?php echo e(config('app.env')); ?> environment</p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Total Users</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\User::count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Registered accounts</p>
                    </div>
                </div>
            </div>

            <!-- Total Artworks -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-palette text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Total Artworks</p>
                        <p class="text-2xl font-bold text-purple-900"><?php echo e(App\Models\Artwork::count()); ?></p>
                        <p class="text-xs text-purple-700 mt-1">In marketplace</p>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Total Sales</p>
                        <p class="text-2xl font-bold text-yellow-900">$<?php echo e(number_format(App\Models\Transaction::where('status', 'completed')->sum('amount'), 0)); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Completed transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Area -->
            <div class="lg:col-span-2">
                <!-- Site Settings -->
                <form method="POST" action="<?php echo e(route('admin.settings.site')); ?>" class="bg-white rounded-lg shadow-md mb-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">General Settings</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="<?php echo e(\App\Models\GeneralSetting::getValue('site_name', config('app.name'))); ?>" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                                <textarea id="site_description" name="site_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(\App\Models\GeneralSetting::getValue('site_description')); ?></textarea>
                            </div>
                            
                            <div>
                                <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-1">SEO Keywords</label>
                                <input type="text" id="site_keywords" name="site_keywords" value="<?php echo e(\App\Models\GeneralSetting::getValue('site_keywords')); ?>"
                                       placeholder="art, gallery, myanmar, paintings"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                                    <input type="email" id="contact_email" name="contact_email" value="<?php echo e(\App\Models\GeneralSetting::getValue('contact_email', config('mail.from.address'))); ?>" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                                    <input type="tel" id="contact_phone" name="contact_phone" value="<?php echo e(\App\Models\GeneralSetting::getValue('contact_phone')); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                                <textarea id="address" name="address" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(\App\Models\GeneralSetting::getValue('address')); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Save General Settings
                        </button>
                    </div>
                </form>

                <!-- Settings Navigation -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">More Settings</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="<?php echo e(route('admin.settings.language')); ?>" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <i class="fas fa-language text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Language</h3>
                                    <p class="text-sm text-gray-600">Configure language settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.currency')); ?>" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <div class="p-3 bg-green-100 rounded-full">
                                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Currency</h3>
                                    <p class="text-sm text-gray-600">Configure currency settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.email')); ?>" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <i class="fas fa-envelope text-purple-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Email</h3>
                                    <p class="text-sm text-gray-600">Configure email settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.payment')); ?>" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                <div class="p-3 bg-yellow-100 rounded-full">
                                    <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Payment</h3>
                                    <p class="text-sm text-gray-600">Configure payment settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.shipping')); ?>" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                                <div class="p-3 bg-orange-100 rounded-full">
                                    <i class="fas fa-truck text-orange-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Shipping</h3>
                                    <p class="text-sm text-gray-600">Configure shipping settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.security')); ?>" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                <div class="p-3 bg-red-100 rounded-full">
                                    <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Security</h3>
                                    <p class="text-sm text-gray-600">Configure security settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.backup')); ?>" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                <div class="p-3 bg-indigo-100 rounded-full">
                                    <i class="fas fa-database text-indigo-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Backup</h3>
                                    <p class="text-sm text-gray-600">Configure backup settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.logs')); ?>" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="p-3 bg-gray-200 rounded-full">
                                    <i class="fas fa-file-alt text-gray-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Logs</h3>
                                    <p class="text-sm text-gray-600">View system logs</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.cache')); ?>" class="flex items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition">
                                <div class="p-3 bg-teal-100 rounded-full">
                                    <i class="fas fa-bolt text-teal-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Cache</h3>
                                    <p class="text-sm text-gray-600">Configure cache settings</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.general')); ?>" class="flex items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
                                <div class="p-3 bg-pink-100 rounded-full">
                                    <i class="fas fa-image text-pink-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">General</h3>
                                    <p class="text-sm text-gray-600">Manage frontend images</p>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.settings.images')); ?>" class="flex items-center p-4 bg-rose-50 rounded-lg hover:bg-rose-100 transition">
                                <div class="p-3 bg-rose-100 rounded-full">
                                    <i class="fas fa-images text-rose-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Images</h3>
                                    <p class="text-sm text-gray-600">Manage gallery images</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- System Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Laravel Version</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(app()->version()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">PHP Version</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(PHP_VERSION); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Environment</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('app.env')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Debug Mode</span>
                            <span class="text-sm font-medium <?php echo e(config('app.debug') ? 'text-green-600' : 'text-gray-600'); ?>">
                                <?php echo e(config('app.debug') ? 'Enabled' : 'Disabled'); ?>

                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Cache Driver</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('cache.default')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Queue Driver</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('queue.default')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Platform Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Platform Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Users</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(App\Models\User::count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Artworks</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(App\Models\Artwork::count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Sales</span>
                            <span class="text-sm font-medium text-gray-900">$<?php echo e(number_format(App\Models\Transaction::where('status', 'completed')->sum('amount'), 0)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Storage Used</span>
                            <span class="text-sm font-medium text-gray-900">2.3 GB</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        <?php
                            $recentUsers = App\Models\User::latest()->take(3)->get();
                        ?>
                        <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-3">
                                <img src="<?php echo e($user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128'); ?>" 
                                     alt="<?php echo e($user->name); ?>" 
                                     class="h-8 w-8 rounded-full object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($user->name); ?></p>
                                    <p class="text-xs text-gray-500">New <?php echo e($user->role); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>