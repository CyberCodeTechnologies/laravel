

<?php $__env->startSection('title', 'System Logs - Admin'); ?>
<?php $__env->startSection('meta-description', 'View system logs on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'System Logs'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Error Logs -->
            <div class="bg-red-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-red-600 font-medium">Error Logs</p>
                        <p class="text-2xl font-bold text-red-900"><?php echo e(file_exists(storage_path('logs/laravel.log')) ? 'Active' : 'None'); ?></p>
                        <p class="text-xs text-red-700 mt-1">Recent errors</p>
                    </div>
                </div>
            </div>

            <!-- Log Size -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Log Size</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(file_exists(storage_path('logs/laravel.log')) ? number_format(filesize(storage_path('logs/laravel.log')) / 1024, 2) : '0'); ?> KB</p>
                        <p class="text-xs text-blue-700 mt-1">Current file</p>
                    </div>
                </div>
            </div>

            <!-- Log Level -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-layer-group text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Log Level</p>
                        <p class="text-2xl font-bold text-green-900">Debug</p>
                        <p class="text-xs text-green-700 mt-1"><?php echo e(config('app.log_level', 'debug')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Logs Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Recent Log Entries</h2>
                            <div class="flex gap-2">
                                <select id="log_level_filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="all">All Levels</option>
                                    <option value="error">Error</option>
                                    <option value="warning">Warning</option>
                                    <option value="info">Info</option>
                                </select>
                                <button onclick="refreshLogs()" class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                                    <i class="fas fa-sync mr-1"></i>Refresh
                                </button>
                                <button onclick="clearLogs()" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                                    <i class="fas fa-trash mr-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm text-gray-300 overflow-auto max-h-96">
                            <?php
                                $logFile = storage_path('logs/laravel.log');
                                if (file_exists($logFile)) {
                                    $logs = array_slice(file($logFile), -50);
                                    foreach (array_reverse($logs) as $log) {
                                        echo htmlspecialchars((string) $log) . "\n";
                                    }
                                } else {
                                    echo 'No log file found.';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Log Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Log Path</span>
                            <span class="text-sm font-medium text-gray-900 text-right">storage/logs/</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Last Modified</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(file_exists(storage_path('logs/laravel.log')) ? now()->diffInSeconds(\Carbon\Carbon::createFromTimestamp(filemtime(storage_path('logs/laravel.log')))) . 's ago' : 'Never'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Daily Rotation</span>
                            <span class="text-sm font-medium text-gray-900">Enabled</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.settings.cache')); ?>" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                            <i class="fas fa-bolt text-orange-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Clear Logs Cache</span>
                        </a>
                        <button onclick="downloadLogs()" class="flex items-center w-full p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-download text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Download Logs</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function refreshLogs() {
    location.reload();
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all logs?')) {
        alert('Log clearing functionality requires backend implementation.');
    }
}

function downloadLogs() {
    alert('Log download functionality requires backend implementation.');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\logs.blade.php ENDPATH**/ ?>