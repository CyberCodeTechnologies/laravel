<?php $__env->startSection('title', 'Shipment Details - Order #' . $order->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">Shipment Details</h1>
        <a href="<?php echo e(route('orders.show', $order)); ?>" class="text-gray-600 hover:text-black">Back to Order</a>
    </div>

    <?php if($shipment): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tracking Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Tracking Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Carrier</p>
                            <p class="font-medium"><?php echo e($shipment->carrier); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tracking Number</p>
                            <p class="font-medium"><?php echo e($shipment->tracking_number); ?></p>
                        </div>
                        <?php if($shipment->service): ?>
                            <div>
                                <p class="text-sm text-gray-500">Service</p>
                                <p class="font-medium"><?php echo e($shipment->service); ?></p>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <?php
                                $statusClass = match($shipment->status) {
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'in_transit' => 'bg-blue-100 text-blue-800',
                                    'out_for_delivery' => 'bg-purple-100 text-purple-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statusClass); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                            </span>
                        </div>
                    </div>

                    <?php if($shipment->estimated_delivery): ?>
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-500">Estimated Delivery</p>
                            <p class="font-medium"><?php echo e($shipment->estimated_delivery->format('F d, Y')); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($shipment->shipped_at): ?>
                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-500">Shipped On</p>
                            <p class="font-medium"><?php echo e($shipment->shipped_at->format('F d, Y')); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($shipment->delivered_at): ?>
                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-500">Delivered On</p>
                            <p class="font-medium text-green-600"><?php echo e($shipment->delivered_at->format('F d, Y')); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($shipment->getTrackingUrl()): ?>
                        <div class="mt-6">
                            <a href="<?php echo e($shipment->getTrackingUrl()); ?>" target="_blank" class="inline-block bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                                Track on <?php echo e($shipment->carrier); ?> Website
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tracking History -->
                <?php if($shipment->tracking_history && count($shipment->tracking_history) > 0): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Tracking History</h2>
                        
                        <div class="space-y-4">
                            <?php $__currentLoopData = $shipment->tracking_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                                    <div>
                                        <p class="font-medium"><?php echo e($event['event']); ?></p>
                                        <?php if(isset($event['location'])): ?>
                                            <p class="text-sm text-gray-500"><?php echo e($event['location']); ?></p>
                                        <?php endif; ?>
                                        <?php if(isset($event['description'])): ?>
                                            <p class="text-sm text-gray-600"><?php echo e($event['description']); ?></p>
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-400"><?php echo e(\Carbon\Carbon::parse($event['timestamp'])->format('M d, Y H:i')); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Shipping Address -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                    <?php if(is_array($shipment->shipping_address)): ?>
                        <p><?php echo e($shipment->shipping_address['address'] ?? ''); ?></p>
                        <p><?php echo e($shipment->shipping_address['city'] ?? ''); ?>, <?php echo e($shipment->shipping_address['state'] ?? ''); ?> <?php echo e($shipment->shipping_address['postal_code'] ?? ''); ?></p>
                        <p><?php echo e($shipment->shipping_address['country'] ?? ''); ?></p>
                    <?php else: ?>
                        <p><?php echo e($shipment->shipping_address ?? $order->address); ?></p>
                        <p><?php echo e($order->city); ?>, <?php echo e($order->state); ?> <?php echo e($order->postal_code); ?></p>
                        <p><?php echo e($order->country); ?></p>
                    <?php endif; ?>
                </div>

                <?php if($shipment->notes): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Notes</h2>
                        <p><?php echo e($shipment->notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 mb-4">No shipment has been created for this order yet.</p>
            
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->role === 'artist' || Auth::user()->role === 'admin'): ?>
                    <form action="<?php echo e(route('shipments.create', $order)); ?>" method="POST" class="max-w-md mx-auto text-left">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                            <select name="carrier" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="DHL">DHL</option>
                                <option value="FedEx">FedEx</option>
                                <option value="UPS">UPS</option>
                                <option value="USPS">USPS</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service (Optional)</label>
                            <input type="text" name="service" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery</label>
                            <input type="date" name="estimated_delivery" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800">
                            Create Shipment
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\shipments\show.blade.php ENDPATH**/ ?>