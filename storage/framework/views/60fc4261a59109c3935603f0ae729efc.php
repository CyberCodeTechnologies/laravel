

<?php $__env->startSection('title', 'Shipping Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure shipping settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Shipping Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Shipments -->
            <div class="bg-orange-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-truck text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-orange-600 font-medium">Active Shipments</p>
                        <p class="text-2xl font-bold text-orange-900"><?php echo e(App\Models\Shipment::where('status', 'in_transit')->count()); ?></p>
                        <p class="text-xs text-orange-700 mt-1">In transit</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Methods -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Shipping Methods</p>
                        <p class="text-2xl font-bold text-blue-900">3</p>
                        <p class="text-xs text-blue-700 mt-1">Available options</p>
                    </div>
                </div>
            </div>

            <!-- Delivered -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Delivered</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\Shipment::where('status', 'delivered')->count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shipping Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('admin.settings.shipping.update')); ?>" class="bg-white rounded-lg shadow-md">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Shipping Configuration</h2>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="standard_shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">Standard Shipping Cost (USD)</label>
                                    <input type="number" id="standard_shipping_cost" name="standard_shipping_cost" value="<?php echo e(App\Models\GeneralSetting::getValue('standard_shipping_cost', 15)); ?>" step="0.01" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div>
                                    <label for="express_shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">Express Shipping Cost (USD)</label>
                                    <input type="number" id="express_shipping_cost" name="express_shipping_cost" value="<?php echo e(App\Models\GeneralSetting::getValue('express_shipping_cost', 30)); ?>" step="0.01" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>

                            <div>
                                <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-2">Free Shipping Threshold (USD)</label>
                                <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" value="<?php echo e(App\Models\GeneralSetting::getValue('free_shipping_threshold', 500)); ?>" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <p class="mt-1 text-sm text-gray-500">Orders above this amount get free shipping.</p>
                            </div>

                            <div>
                                <label for="processing_days" class="block text-sm font-medium text-gray-700 mb-2">Processing Time (Days)</label>
                                <input type="number" id="processing_days" name="processing_days" value="<?php echo e(App\Models\GeneralSetting::getValue('processing_days', 3)); ?>" min="1" max="30"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <p class="mt-1 text-sm text-gray-500">Days required to process orders before shipping.</p>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="international_shipping" value="1" <?php echo e(App\Models\GeneralSetting::getValue('international_enabled', true) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Enable International Shipping</span>
                                        <p class="text-xs text-gray-500">Allow shipping to international destinations</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Shipping Settings
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Pending Shipments</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(App\Models\Shipment::where('status', 'pending')->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Average Delivery Time</span>
                            <span class="text-sm font-medium text-gray-900">7-14 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\shipping.blade.php ENDPATH**/ ?>