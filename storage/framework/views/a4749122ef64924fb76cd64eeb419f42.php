

<?php $__env->startSection('title', 'Manage Orders - Admin'); ?>
<?php $__env->startSection('meta-description', 'Manage and track orders on Panchi Gallery platform'); ?>

<?php $__env->startSection('header', 'Manage Orders'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Orders -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Orders</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(App\Models\Order::count()); ?></p>
                        <p class="text-xs text-blue-700 mt-1">
                            <?php
                                $newOrders = App\Models\Order::where('created_at', '>=', now()->subDays(7))->count();
                            ?>
                            +<?php echo e($newOrders); ?> this week
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\Order::where('status', 'pending')->count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting processing</p>
                    </div>
                </div>
            </div>

            <!-- Processing Orders -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-cog text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Processing</p>
                        <p class="text-2xl font-bold text-purple-900"><?php echo e(App\Models\Order::whereIn('status', ['processing', 'paid'])->count()); ?></p>
                        <p class="text-xs text-purple-700 mt-1">In progress</p>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Completed</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\Order::where('status', 'completed')->count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Successfully delivered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="min-w-64">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Search orders..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                        <option value="shipped" <?php echo e(request('status') == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <select name="is_custom" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="1" <?php echo e(request('is_custom') == '1' ? 'selected' : ''); ?>>Custom Orders</option>
                        <option value="0" <?php echo e(request('is_custom') == '0' ? 'selected' : ''); ?>>Regular Orders</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
        </div>
    </div>
</section>

<!-- Orders Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($order->order_number); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($order->first_name); ?> <?php echo e($order->last_name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($order->email); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($order->currency); ?> <?php echo e(number_format($order->total_amount, 2)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($order->is_custom_order): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Custom
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Regular
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo e($order->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($order->status === 'paid' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($order->status === 'processing' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                        <?php echo e($order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : ''); ?>

                                        <?php echo e($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : ''); ?>">
                                        <?php echo e(ucfirst($order->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($order->shipping_method ?? 'N/A'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($order->created_at->format('M j, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($order->status === 'paid' || $order->status === 'processing'): ?>
                                            <a href="<?php echo e(route('admin.orders.ship', $order->id)); ?>" class="text-green-600 hover:text-green-900" title="Create Shipment">
                                                <i class="fas fa-shipping-fast"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-shopping-bag text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No orders found</p>
                                        <p class="text-sm">Get started by adjusting your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($orders->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>