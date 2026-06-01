<?php $__env->startSection('title', __('messages.order_success_title') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.order_success_meta')); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Message -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.order_placed_success')); ?></h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                <?php echo e(__('messages.thank_you_purchase')); ?>

            </p>
        </div>

        <!-- Order Details Card -->
        <?php if($order): ?>
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php echo e(__('messages.order_details')); ?></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Order Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e(__('messages.order_information')); ?></h3>
                        <div class="space-y-2 text-gray-600">
                            <p><strong><?php echo e(__('messages.order_number')); ?>:</strong> <?php echo e($order->formatted_order_number); ?></p>
                            <p><strong><?php echo e(__('messages.email')); ?>:</strong> <?php echo e($order->email); ?></p>
                            <p><strong><?php echo e(__('messages.total_amount')); ?>:</strong> <?php echo e($order->formatted_total); ?></p>
                            <p><strong><?php echo e(__('messages.payment_method')); ?>:</strong> <?php echo e(__('messages.' . strtolower($order->payment_method))); ?></p>
                            <p><strong><?php echo e(__('messages.shipping_method')); ?>:</strong> <?php echo e(__('messages.' . ($order->shipping_method ?? 'standard'))); ?></p>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e(__('messages.shipping_information')); ?></h3>
                        <div class="space-y-2 text-gray-600">
                            <p><strong><?php echo e(__('messages.name')); ?>:</strong> <?php echo e($order->full_name); ?></p>
                            <p><strong><?php echo e(__('messages.phone')); ?>:</strong> <?php echo e($order->phone); ?></p>
                            <p><strong><?php echo e(__('messages.address')); ?>:</strong> <?php echo e($order->address); ?></p>
                            <p><?php echo e($order->city); ?>, <?php echo e($order->state ?? ''); ?> <?php echo e($order->postal_code); ?></p>
                            <p><?php echo e($order->country); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <?php if($order->items->count() > 0): ?>
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e(__('messages.order_items')); ?></h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <?php if($item->artwork && $item->artwork->primary_image): ?>
                                            <img src="<?php echo e(asset($item->artwork->primary_image)); ?>" 
                                                 alt="<?php echo e($item->artwork_title); ?>" 
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0 2.828 2.828 2 2.828L16 16m-4-4.586a2 2 0 00-2.828-2.828-2.828-2.828L8 8m4 4.586a2 2 0 012.828 0 2.828 2.828 2.828L16 16z"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900"><?php echo e($item->artwork_title); ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo e(__('messages.quantity')); ?>: <?php echo e($item->quantity); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($item->formatted_price); ?> <?php echo e(__('messages.each')); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900"><?php echo e($item->formatted_subtotal); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8 text-center">
                <p class="text-gray-600"><?php echo e(__('messages.order_details_not_available')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Next Steps -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6"><?php echo e(__('messages.whats_next')); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a42 42 0 01.93.54 2.07 2.07 2.07s1.51.01 2.07-.07.07-.07-.07-.07-.07-.07zM15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900"><?php echo e(__('messages.order_confirmation')); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e(__('messages.email_confirmation_shortly')); ?></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2l2-2m-2 2l-4-4m6 2l2-2m-2 2l-4-4m14 6V4a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900"><?php echo e(__('messages.artwork_processing')); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e(__('messages.team_prepare_shipping')); ?></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h1M4 8h8m-8 0v8h8M4 16h.01"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900"><?php echo e(__('messages.certificate_of_authenticity')); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e(__('messages.includes_verified_certificate')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <a href="<?php echo e(route('home')); ?>" 
               class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 00-1-1H6a1 1 0 00-1 1v4a1 1 0 001 1h3a1 1 0 001 1z"></path>
                </svg>
                <?php echo e(__('messages.continue_shopping')); ?>

            </a>
            
            <?php if(auth()->check()): ?>
                <a href="<?php echo e(route('collector.purchases')); ?>" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2v0a2 2 0 00-2-2H9z"></path>
                    </svg>
                    <?php echo e(__('messages.view_my_orders')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\checkout\success.blade.php ENDPATH**/ ?>