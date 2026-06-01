<?php $__env->startSection('title', $order->title . ' - Order #' . $order->formatted_order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($order->title); ?></h1>
                    <p class="text-gray-600 mt-1">Order #<?php echo e($order->formatted_order_number); ?></p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-<?php echo e($order->custom_status_color); ?>-100 text-<?php echo e($order->custom_status_color); ?>-800">
                        <?php echo e($order->custom_status_label); ?>

                    </span>
                    <a href="<?php echo e(route('orders.track', $order)); ?>" 
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Track Progress
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Artwork Details -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Artwork Details</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3"><?php echo e($order->title); ?></h3>
                            <p class="text-gray-600 leading-relaxed"><?php echo e($order->description); ?></p>
                        </div>

                        <!-- Reference Image -->
                        <?php if($order->reference_image): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Reference Image</h4>
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <img src="<?php echo e($order->reference_image_url); ?>" alt="Reference Image" 
                                         class="w-full h-auto object-cover">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Specifications -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Size</div>
                                <div class="font-medium text-gray-900"><?php echo e($order->size); ?></div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Medium</div>
                                <div class="font-medium text-gray-900"><?php echo e($order->medium); ?></div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Style</div>
                                <div class="font-medium text-gray-900"><?php echo e($order->style); ?></div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Budget</div>
                                <div class="font-medium text-gray-900">$<?php echo e(number_format($order->proposed_price, 2)); ?></div>
                            </div>
                        </div>

                        <!-- Customer Notes -->
                        <?php if($order->customer_notes): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Customer Notes</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700"><?php echo e($order->customer_notes); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Artist Information -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Artist Information</h2>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-semibold text-gray-700">
                                <?php echo e(strtoupper(substr($order->artist->name, 0, 1))); ?>

                            </span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900"><?php echo e($order->artist->name); ?></h3>
                            <p class="text-gray-600 mb-2"><?php echo e($order->artist->specialty ?? 'Professional Artist'); ?></p>
                            <p class="text-sm text-gray-500">
                                Member since <?php echo e($order->artist->created_at->format('F Y')); ?>

                            </p>
                            
                            <?php if($order->artist->bio): ?>
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">About the Artist</h4>
                                    <p class="text-sm text-gray-600"><?php echo e($order->artist->bio); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Artist Notes -->
                    <?php if($order->artist_notes): ?>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Artist Notes</h4>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-gray-700"><?php echo e($order->artist_notes); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Communication -->
                <?php if($order->custom_status === 'pending_artist_approval' || $order->custom_status === 'artist_accepted'): ?>
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Communication</h2>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <?php if($order->custom_status === 'pending_artist_approval'): ?>
                                    The artist is currently reviewing your order request. You will receive a notification once they have made a decision.
                                <?php elseif($order->custom_status === 'artist_accepted'): ?>
                                    The artist has accepted your order and will begin work soon. You can track progress using the tracking page.
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?php echo e(route('orders.track', $order)); ?>" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Track Order Progress
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Order Status -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Current Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?php echo e($order->custom_status_color); ?>-100 text-<?php echo e($order->custom_status_color); ?>-800">
                                <?php echo e($order->custom_status_label); ?>

                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Order Date</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($order->created_at->format('M j, Y')); ?></span>
                        </div>
                        <?php if($order->artist_accepted_at): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Accepted Date</span>
                                <span class="text-sm font-medium text-gray-900"><?php echo e($order->artist_accepted_at->format('M j, Y')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($order->completed_at): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Completed Date</span>
                                <span class="text-sm font-medium text-gray-900"><?php echo e($order->completed_at->format('M j, Y')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Proposed Budget</span>
                            <span class="text-lg font-bold text-gray-900">$<?php echo e(number_format($order->proposed_price, 2)); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Currency</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($order->currency); ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            Final price may be adjusted based on complexity and artist requirements.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <!-- Customer Actions -->
                        <?php if(auth()->id() === $order->user_id): ?>
                            <?php if($order->canCustomerApprove()): ?>
                                <form action="<?php echo e(route('orders.customer-approve', $order)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                        Approve Artwork
                                    </button>
                                </form>
                                <button onclick="openRejectModal(<?php echo e($order->id); ?>)" 
                                        class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    Request Changes
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Artist Actions -->
                        <?php if(auth()->id() === $order->artist_id): ?>
                            <?php if($order->canArtistAccept()): ?>
                                <form action="<?php echo e(route('orders.accept', $order)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                        Accept Order
                                    </button>
                                </form>
                                <button onclick="openRejectModal(<?php echo e($order->id); ?>)" 
                                        class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    Reject Order
                                </button>
                            <?php endif; ?>

                            <?php if($order->canStartProgress()): ?>
                                <form action="<?php echo e(route('orders.start-progress', $order)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                        Start Progress
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if($order->canMarkReadyForReview()): ?>
                                <form action="<?php echo e(route('orders.ready-review', $order)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                                        Mark Ready for Review
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Common Actions -->
                        <a href="<?php echo e(route('orders.track', $order)); ?>" 
                           class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-center">
                            Track Progress
                        </a>
                        
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('orders.admin.index')); ?>" 
                               class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-center">
                                View All Orders
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject/Request Changes Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <?php echo e(auth()->id() === $order->artist_id ? 'Reject Order' : 'Request Changes'); ?>

            </h3>
            <form id="rejectForm" action="" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="order_id" id="reject_order_id">
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php echo e(auth()->id() === $order->artist_id ? 'Reason for Rejection' : 'What changes would you like?'); ?> *
                    </label>
                    <textarea name="<?php echo e(auth()->id() === $order->artist_id ? 'artist_notes' : 'customer_notes'); ?>" 
                              id="notes" rows="4" required
                              placeholder="<?php echo e(auth()->id() === $order->artist_id ? 'Please explain why you\'re rejecting this order...' : 'Please describe the changes you\'d like...'); ?>"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        <?php echo e(auth()->id() === $order->artist_id ? 'Reject Order' : 'Request Changes'); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(orderId) {
    document.getElementById('reject_order_id').value = orderId;
    const isArtist = <?php echo e(auth()->id() === $order->artist_id ? 'true' : 'false'); ?>;
    const action = isArtist ? `/orders/${orderId}/reject` : `/orders/${orderId}/customer-reject`;
    document.getElementById('rejectForm').action = action;
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\orders\show.blade.php ENDPATH**/ ?>