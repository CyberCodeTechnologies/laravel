

<?php $__env->startSection('title', 'Currency Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure currency settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Currency Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Default Currency -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Default Currency</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(config('currency.default', 'USD')); ?></p>
                        <p class="text-xs text-green-700 mt-1">System default</p>
                    </div>
                </div>
            </div>

            <!-- Available Currencies -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-coins text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Available Currencies</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(count(json_decode($enabledCurrencies ?? '[]', true) ?: config('currency.supported', []))); ?></p>
                        <p class="text-xs text-blue-700 mt-1">Supported currencies</p>
                    </div>
                </div>
            </div>

            <!-- Exchange Rate -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-exchange-alt text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">USD to MMK</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(number_format($exchangeRateMMK ?? config('currency.exchange_rates.MMK', 4400), 0)); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Current rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Currency Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Area -->
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('admin.settings.currency.update')); ?>" class="bg-white rounded-lg shadow-md">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Currency Configuration</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="default_currency" class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                                <select id="default_currency" name="default_currency" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="USD" <?php echo e(($defaultCurrency ?? config('currency.default', 'USD')) === 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
                                    <option value="MMK" <?php echo e(($defaultCurrency ?? config('currency.default', 'USD')) === 'MMK' ? 'selected' : ''); ?>>MMK - Myanmar Kyat</option>
                                    <option value="EUR" <?php echo e(($defaultCurrency ?? config('currency.default', 'USD')) === 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                                    <option value="GBP" <?php echo e(($defaultCurrency ?? config('currency.default', 'USD')) === 'GBP' ? 'selected' : ''); ?>>GBP - British Pound</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">The default currency for the platform.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Currencies</label>
                                <div class="space-y-2">
                                    <?php
                                        $enabledCurrenciesArray = json_decode($enabledCurrencies ?? '[]', true) ?: ['USD', 'MMK'];
                                    ?>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_currencies[]" value="USD" <?php echo e(in_array('USD', $enabledCurrenciesArray) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">USD - US Dollar</span>
                                            <p class="text-xs text-gray-500">United States Dollar</p>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_currencies[]" value="MMK" <?php echo e(in_array('MMK', $enabledCurrenciesArray) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">MMK - Myanmar Kyat</span>
                                            <p class="text-xs text-gray-500">Myanmar Kyat</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_currencies[]" value="EUR" <?php echo e(in_array('EUR', $enabledCurrenciesArray) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">EUR - Euro</span>
                                            <p class="text-xs text-gray-500">European Union Euro</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enabled_currencies[]" value="GBP" <?php echo e(in_array('GBP', $enabledCurrenciesArray) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">GBP - British Pound</span>
                                            <p class="text-xs text-gray-500">United Kingdom Pound</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Select which currencies should be available to users.</p>
                            </div>

                            <div>
                                <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate (USD to MMK)</label>
                                <input type="number" id="exchange_rate" name="exchange_rate"
                                       value="<?php echo e($exchangeRateMMK ?? config('currency.exchange_rates.MMK', 4400)); ?>" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <p class="mt-1 text-sm text-gray-500">Current exchange rate for currency conversion (1 USD = X MMK).</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Currency Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Currency Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Currency Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Current Session</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(session('currency', 'USD')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">Today</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Rate Source</span>
                            <span class="text-sm font-medium text-gray-900">Manual</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button type="button" onclick="updateRates()" class="flex items-center w-full p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                            <i class="fas fa-sync text-orange-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Update Exchange Rates</span>
                        </button>
                        <a href="<?php echo e(route('admin.settings.cache')); ?>" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-bolt text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Clear Currency Cache</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateRates() {
    alert('Exchange rates update functionality will be implemented with API integration.');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\currency.blade.php ENDPATH**/ ?>