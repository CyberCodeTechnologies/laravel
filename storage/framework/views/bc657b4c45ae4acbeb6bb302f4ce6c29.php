

<?php $__env->startSection('title', 'Payment Settings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Configure payment settings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Payment Settings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Payment Methods -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Payment Methods</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\PaymentMethod::count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Available methods</p>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-receipt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Total Transactions</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\Transaction::count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">All time</p>
                    </div>
                </div>
            </div>

            <!-- Platform Fee -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-percentage text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Platform Fee</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e($platformFee); ?>%</p>
                        <p class="text-xs text-blue-700 mt-1">Commission rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Area -->
            <div class="lg:col-span-2">
                <form method="POST" action="<?php echo e(route('admin.settings.payment.update')); ?>" class="bg-white rounded-lg shadow-md mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Payment Configuration</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="platform_fee" class="block text-sm font-medium text-gray-700 mb-2">Platform Fee (%)</label>
                                <input type="number" id="platform_fee" name="platform_fee" value="<?php echo e($platformFee); ?>" step="0.1" min="0" max="100"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                <p class="mt-1 text-sm text-gray-500">Commission percentage charged on each sale.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Payment Gateways</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="payment_gateways[]" value="stripe" <?php echo e(in_array('stripe', $paymentGateways) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Stripe</span>
                                            <p class="text-xs text-gray-500">Credit card payments worldwide</p>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="payment_gateways[]" value="paypal" <?php echo e(in_array('paypal', $paymentGateways) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">PayPal</span>
                                            <p class="text-xs text-gray-500">PayPal payments</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="payment_gateways[]" value="kbzpay" <?php echo e(in_array('kbzpay', $paymentGateways) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">KBZPay</span>
                                            <p class="text-xs text-gray-500">Myanmar mobile payment</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="payment_gateways[]" value="wavepay" <?php echo e(in_array('wavepay', $paymentGateways) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">WavePay</span>
                                            <p class="text-xs text-gray-500">Myanmar mobile payment</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_approve_payments" value="1" <?php echo e($autoApprovePayments ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Auto-approve Manual Payments</span>
                                        <p class="text-xs text-gray-500">Automatically approve payment proofs (use with caution)</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Payment Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Pending Payments</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(App\Models\Transaction::where('status', 'pending')->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Revenue</span>
                            <span class="text-sm font-medium text-gray-900">$<?php echo e(number_format(App\Models\Transaction::where('status', 'completed')->sum('amount'), 0)); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.payments.verifications')); ?>" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-check-double text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Verify Payments</span>
                        </a>
                        <a href="<?php echo e(route('admin.transactions')); ?>" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <i class="fas fa-list text-green-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">View Transactions</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\payment.blade.php ENDPATH**/ ?>