<?php $__env->startSection('title', __('messages.artist_dashboard.orders') . ' - ' . __('messages.artist_dashboard.title') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.artist_dashboard.orders_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    <?php echo e(__('messages.artist_dashboard.orders_title', ['name' => auth()->user()->name])); ?>

                </h1>
                <p class="text-gray-300">
                    <?php echo e(__('messages.artist_dashboard.orders_subtitle')); ?>

                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <div class="text-center px-4">
                    <div class="text-3xl font-bold"><?php echo e($orders->total()); ?></div>
                    <div class="text-gray-300 text-sm"><?php echo e(__('messages.artist_dashboard.total_orders')); ?></div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e($orders->where('custom_status', 'pending_artist_approval')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.pending')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e($orders->where('custom_status', 'artist_accepted')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.accepted')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e($orders->where('custom_status', 'in_progress')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.in_progress')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e($orders->where('custom_status', 'completed')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.completed')); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <a href="<?php echo e(route('dashboard')); ?>" id="artworks-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.my_artworks')); ?>

                </a>
                <a href="<?php echo e(route('artist.analytics')); ?>" id="analytics-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.analytics')); ?>

                </a>
                <button id="orders-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    <?php echo e(__('messages.artist_dashboard.orders')); ?>

                </button>
                <a href="<?php echo e(route('artist.sales')); ?>" id="sales-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.sales_certificates')); ?>

                </a>
                <a href="<?php echo e(route('artist.profile')); ?>" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.artist_profile')); ?>

                </a>
            </nav>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo e(route('artist.orders')); ?>"
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(!request('status') ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    <?php echo e(__('messages.artist_dashboard.all_orders')); ?>

                </a>
                <a href="<?php echo e(route('artist.orders', ['status' => 'pending_artist_approval'])); ?>"
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'pending_artist_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    <?php echo e(__('messages.artist_dashboard.pending_approval')); ?>

                </a>
                <a href="<?php echo e(route('artist.orders', ['status' => 'artist_accepted'])); ?>"
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'artist_accepted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    <?php echo e(__('messages.artist_dashboard.accepted')); ?>

                </a>
                <a href="<?php echo e(route('artist.orders', ['status' => 'in_progress'])); ?>"
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    <?php echo e(__('messages.artist_dashboard.in_progress')); ?>

                </a>
                <a href="<?php echo e(route('artist.orders', ['status' => 'ready_for_review'])); ?>"
                   class="px-4 py-2 text-sm font-medium rounded-lg <?php echo e(request('status') == 'ready_for_review' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> transition-colors">
                    <?php echo e(__('messages.artist_dashboard.ready_for_review')); ?>

                </a>
            </div>
        </div>

        <!-- Orders List -->
        <div id="orders-content" class="tab-content">
        <?php if($orders->count() > 0): ?>
            <div class="space-y-6">
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="text-sm font-medium text-gray-500">
                                            Order #<?php echo e($order->formatted_order_number); ?>

                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?php echo e($order->custom_status_color); ?>-100 text-<?php echo e($order->custom_status_color); ?>-800">
                                            <?php echo e($order->custom_status_label); ?>

                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <?php echo e($order->created_at->format('M j, Y')); ?>

                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <?php echo e($order->title); ?>

                                    </h3>
                                    <p class="text-gray-600 line-clamp-2">
                                        <?php echo e($order->description); ?>

                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">
                                        $<?php echo e(number_format($order->proposed_price, 2)); ?>

                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Proposed Budget
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <div class="text-sm text-gray-500">Size</div>
                                    <div class="font-medium text-gray-900"><?php echo e($order->size); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Medium</div>
                                    <div class="font-medium text-gray-900"><?php echo e($order->medium); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Style</div>
                                    <div class="font-medium text-gray-900"><?php echo e($order->style); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Customer</div>
                                    <div class="font-medium text-gray-900"><?php echo e($order->user->name); ?></div>
                                </div>
                            </div>

                            <!-- Reference Image -->
                            <?php if($order->reference_image): ?>
                                <div class="mb-4">
                                    <div class="text-sm text-gray-500 mb-2">Reference Image</div>
                                    <img src="<?php echo e($order->reference_image_url); ?>" alt="Reference" 
                                         class="h-32 w-32 object-cover rounded-lg border border-gray-200">
                                </div>
                            <?php endif; ?>

                            <!-- Customer Notes -->
                            <?php if($order->customer_notes): ?>
                                <div class="mb-4">
                                    <div class="text-sm text-gray-500 mb-1">Customer Notes</div>
                                    <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">
                                        <?php echo e($order->customer_notes); ?>

                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <a href="<?php echo e(route('orders.show', $order)); ?>" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        View Details
                                    </a>
                                    <a href="<?php echo e(route('orders.track', $order)); ?>" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        Track Progress
                                    </a>
                                </div>

                                <!-- Status-specific Actions -->
                                <div class="flex space-x-3">
                                    <?php if($order->canArtistAccept()): ?>
                                        <form action="<?php echo e(route('orders.accept', $order)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                                Accept Order
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal(<?php echo e($order->id); ?>)" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                            Reject Order
                                        </button>
                                    <?php endif; ?>

                                    <?php if($order->canStartProgress()): ?>
                                        <form action="<?php echo e(route('orders.start-progress', $order)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                Start Progress
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if($order->canMarkReadyForReview()): ?>
                                        <form action="<?php echo e(route('orders.ready-review', $order)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                                                Mark Ready for Review
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <?php echo e($orders->links()); ?>

            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No custom orders</h3>
                <p class="mt-1 text-sm text-gray-500">
                    <?php echo e(request('status') ? 'No orders found with this status.' : 'You haven\'t received any custom orders yet.'); ?>

                </p>
            </div>
        <?php endif; ?>

        <!-- Back to Dashboard -->
        <div class="mt-8">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-black hover:text-gray-700 font-medium">
                &larr; Back to Dashboard
            </a>
        </div>
        </div>
    </div>
</section>

<!-- Reject Order Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Order</h3>
            <form id="rejectForm" action="" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="order_id" id="reject_order_id">
                <div class="mb-4">
                    <label for="artist_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection *
                    </label>
                    <textarea name="artist_notes" id="artist_notes" rows="4" required
                              placeholder="Please explain why you're rejecting this order..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        Reject Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(orderId) {
    document.getElementById('reject_order_id').value = orderId;
    document.getElementById('rejectForm').action = `/orders/${orderId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target == modal) {
        closeRejectModal();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\orders\artist\index.blade.php ENDPATH**/ ?>