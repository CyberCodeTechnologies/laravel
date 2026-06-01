

<?php $__env->startSection('title', 'Cache Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure cache settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Cache Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cache Driver -->
            <div class="bg-teal-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-teal-100 rounded-full">
                        <i class="fas fa-bolt text-teal-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-teal-600 font-medium">Cache Driver</p>
                        <p class="text-2xl font-bold text-teal-900"><?php echo e(strtoupper(config('cache.default'))); ?></p>
                        <p class="text-xs text-teal-700 mt-1">Current driver</p>
                    </div>
                </div>
            </div>

            <!-- Cache Status -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Cache Status</p>
                        <p class="text-2xl font-bold text-green-900">Active</p>
                        <p class="text-xs text-green-700 mt-1">System ready</p>
                    </div>
                </div>
            </div>

            <!-- Cache Size -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-hdd text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Cache Size</p>
                        <p class="text-2xl font-bold text-blue-900">~10 MB</p>
                        <p class="text-xs text-blue-700 mt-1">Estimated</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cache Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('admin.settings.cache.update')); ?>" class="bg-white rounded-lg shadow-md mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Cache Configuration</h2>

                        <div class="space-y-6">
                            <div>
                                <label for="cache_driver" class="block text-sm font-medium text-gray-700 mb-2">Cache Driver</label>
                                <select id="cache_driver" name="cache_driver"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="file" <?php echo e($cacheDriver === 'file' ? 'selected' : ''); ?>>File</option>
                                    <option value="database" <?php echo e($cacheDriver === 'database' ? 'selected' : ''); ?>>Database</option>
                                    <option value="redis" <?php echo e($cacheDriver === 'redis' ? 'selected' : ''); ?>>Redis</option>
                                    <option value="memcached" <?php echo e($cacheDriver === 'memcached' ? 'selected' : ''); ?>>Memcached</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Select the cache driver for the application.</p>
                            </div>

                            <div>
                                <label for="cache_ttl" class="block text-sm font-medium text-gray-700 mb-2">Default TTL (Minutes)</label>
                                <input type="number" id="cache_ttl" name="cache_ttl" value="<?php echo e($cacheTtl); ?>" min="1" max="10080"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <p class="mt-1 text-sm text-gray-500">Default time-to-live for cached items.</p>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="enable_page_cache" value="1" <?php echo e($enablePageCache ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Enable Page Cache</span>
                                        <p class="text-xs text-gray-500">Cache static pages for better performance</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Cache Settings
                        </button>
                    </div>
                </form>

                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Cache Management</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <form method="POST" action="<?php echo e(route('admin.settings.cache.clear')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="flex items-center w-full p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                                    <i class="fas fa-bolt text-orange-600 text-xl mr-3"></i>
                                    <div>
                                        <div class="font-medium text-gray-900">Clear Application Cache</div>
                                        <div class="text-sm text-gray-600">Clear all cached data</div>
                                    </div>
                                </button>
                            </form>

                            <button onclick="clearConfigCache()" class="flex items-center w-full p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                <i class="fas fa-cog text-red-600 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Clear Config Cache</div>
                                    <div class="text-sm text-gray-600">Clear configuration cache</div>
                                </div>
                            </button>

                            <button onclick="clearRouteCache()" class="flex items-center w-full p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <i class="fas fa-route text-blue-600 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Clear Route Cache</div>
                                    <div class="text-sm text-gray-600">Clear route cache</div>
                                </div>
                            </button>

                            <button onclick="clearViewCache()" class="flex items-center w-full p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                <i class="fas fa-eye text-purple-600 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Clear View Cache</div>
                                    <div class="text-sm text-gray-600">Clear compiled views</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Queue Driver</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('queue.default')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Session Driver</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('session.driver')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Cache Prefix</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(config('cache.prefix', 'laravel_cache')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function clearConfigCache() {
    alert('Config cache clear requires backend command execution.');
}

function clearRouteCache() {
    alert('Route cache clear requires backend command execution.');
}

function clearViewCache() {
    alert('View cache clear requires backend command execution.');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\cache.blade.php ENDPATH**/ ?>