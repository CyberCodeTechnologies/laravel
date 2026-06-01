

<?php $__env->startSection('title', 'Backup Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure backup settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Backup Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Last Backup -->
            <div class="bg-indigo-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fas fa-database text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-indigo-600 font-medium">Last Backup</p>
                        <p class="text-2xl font-bold text-indigo-900">Today</p>
                        <p class="text-xs text-indigo-700 mt-1"><?php echo e(now()->format('H:i')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Backup Size -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-hdd text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Backup Size</p>
                        <p class="text-2xl font-bold text-blue-900">~50 MB</p>
                        <p class="text-xs text-blue-700 mt-1">Database size</p>
                    </div>
                </div>
            </div>

            <!-- Auto Backup -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Auto Backup</p>
                        <p class="text-2xl font-bold text-green-900">Daily</p>
                        <p class="text-xs text-green-700 mt-1">Scheduled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Backup Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('settings.backup.create')); ?>" class="bg-white rounded-lg shadow-md mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Create Backup</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Type</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="backup_type" value="full" checked
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Full Backup</span>
                                            <p class="text-xs text-gray-500">Complete database backup</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="backup_type" value="structure"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Structure Only</span>
                                            <p class="text-xs text-gray-500">Database schema without data</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_files" value="1"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Include Files</span>
                                        <p class="text-xs text-gray-500">Backup uploaded files and images</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-download mr-2"></i>Create Backup
                        </button>
                    </div>
                </form>

                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Backup Schedule</h2>
                        <form method="POST" action="<?php echo e(route('settings.backup.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-4">
                                <div>
                                    <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                                    <select id="backup_frequency" name="backup_frequency"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="daily" <?php echo e($backupFrequency === 'daily' ? 'selected' : ''); ?>>Daily</option>
                                        <option value="weekly" <?php echo e($backupFrequency === 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                        <option value="monthly" <?php echo e($backupFrequency === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                        <option value="manual" <?php echo e($backupFrequency === 'manual' ? 'selected' : ''); ?>>Manual Only</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="backup_retention" class="block text-sm font-medium text-gray-700 mb-2">Retention Period (Days)</label>
                                    <input type="number" id="backup_retention" name="backup_retention" value="<?php echo e($backupRetention); ?>" min="1" max="365"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                    <i class="fas fa-save mr-2"></i>Save Schedule
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Backup Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Backups</span>
                            <span class="text-sm font-medium text-gray-900">7</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Storage Used</span>
                            <span class="text-sm font-medium text-gray-900">350 MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Next Backup</span>
                            <span class="text-sm font-medium text-gray-900">Tomorrow</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\backup.blade.php ENDPATH**/ ?>