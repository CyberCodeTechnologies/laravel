<?php $__env->startSection('title', 'Custom Orders - Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Custom Orders Management</h1>
                    <p class="text-gray-600 mt-1">Monitor and manage all custom artwork orders</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Statistics -->
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900"><?php echo e($orders->total()); ?></div>
                            <div class="text-gray-500">Total Orders</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600"><?php echo e($orders->where('custom_status', 'pending_artist_approval')->count()); ?></div>
                            <div class="text-gray-500">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($orders->where('custom_status', 'in_progress')->count()); ?></div>
                            <div class="text-gray-500">In Progress</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo e(route('orders.admin.index')); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(!request('status') ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    All Orders
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'pending_artist_approval'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'pending_artist_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    Pending Approval
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'artist_accepted'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'artist_accepted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    Artist Accepted
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'in_progress'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    In Progress
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'ready_for_review'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'ready_for_review' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    Ready for Review
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'delivered'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'delivered' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    Delivered
                </a>
                <a href="<?php echo e(route('orders.admin.index', ['status' => 'artist_rejected'])); ?>" 
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'artist_rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    Artist Rejected
                </a>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if($orders->count() > 0): ?>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Artist
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Artwork
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Budget
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            #<?php echo e($order->formatted_order_number); ?>

                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($order->title); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-semibold text-gray-700">
                                                    <?php echo e(strtoupper(substr($order->user->name, 0, 1))); ?>

                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($order->user->name); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($order->user->email); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-semibold text-gray-700">
                                                    <?php echo e(strtoupper(substr($order->artist->name, 0, 1))); ?>

                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($order->artist->name); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($order->artist->specialty ?? 'Artist'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php echo e($order->size); ?> - <?php echo e($order->medium); ?>

                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($order->style); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            $<?php echo e(number_format($order->proposed_price, 2)); ?>

                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($order->currency); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?php echo e($order->custom_status_color); ?>-100 text-<?php echo e($order->custom_status_color); ?>-800">
                                            <?php echo e($order->custom_status_label); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($order->created_at->format('M j, Y')); ?>

                                        <?php if($order->artist_accepted_at): ?>
                                            <br>
                                            <span class="text-xs text-green-600">Accepted: <?php echo e($order->artist_accepted_at->format('M j')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="<?php echo e(route('orders.show', $order)); ?>" 
                                               class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                            <a href="<?php echo e(route('orders.track', $order)); ?>" 
                                               class="text-gray-600 hover:text-gray-900">
                                                Track
                                            </a>
                                            <?php if($order->user_id === auth()->id()): ?>
                                                <a href="<?php echo e(route('orders.customer.index')); ?>" 
                                                   class="text-purple-600 hover:text-purple-900">
                                                    Customer
                                                </a>
                                            <?php endif; ?>
                                            <?php if($order->artist_id === auth()->id()): ?>
                                                <a href="<?php echo e(route('artist.orders')); ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Artist
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($orders->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No custom orders found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    <?php echo e(request('status') ? 'No orders found with this status.' : 'No custom orders have been placed yet.'); ?>

                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($orders->where('custom_status', 'pending_artist_approval')->count()); ?></div>
                        <div class="text-sm text-gray-500">Pending Approval</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($orders->where('custom_status', 'artist_accepted')->count()); ?></div>
                        <div class="text-sm text-gray-500">Artist Accepted</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($orders->where('custom_status', 'in_progress')->count()); ?></div>
                        <div class="text-sm text-gray-500">In Progress</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($orders->where('custom_status', 'ready_for_review')->count()); ?></div>
                        <div class="text-sm text-gray-500">Ready for Review</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\orders\admin\index.blade.php ENDPATH**/ ?>