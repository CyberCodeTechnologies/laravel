<?php $__env->startSection('title', 'Purchase Resale Artwork - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Complete your purchase of a resale artwork.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Purchase Resale Artwork</h1>
        <p class="text-gray-300">Complete your secondary market purchase</p>
    </div>
</section>

<!-- Purchase Form -->
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Artwork Preview -->
            <div class="p-6 border-b">
                <div class="flex gap-4">
                    <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                         alt="{{ $resale->artwork->title); ?>" 
                         loading="lazy"
                         class="w-32 h-32 object-cover rounded-lg">
                    <div class="flex-1">
                        <h2 class="font-serif text-xl font-bold mb-1"><?php echo e($resale->artwork->title); ?></h2>
                        <p class="text-gray-600 mb-2">by <?php echo e($resale->artwork->artist->name ?? 'Unknown'); ?></p>
                        <p class="text-gray-500 text-sm"><?php echo e($resale->artwork->dimensions); ?> | <?php echo e($resale->artwork->medium); ?></p>
                        
                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <span class="font-medium">Condition:</span> <?php echo e(ucfirst($resale->condition)); ?>

                            </p>
                            <?php if($resale->condition_description): ?>
                            <p class="text-sm text-yellow-700 mt-1"><?php echo e($resale->condition_description); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-2xl font-bold mt-3">$<?php echo e(number_format($resale->asking_price, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Seller Info -->
            <div class="p-6 border-b bg-gray-50">
                <div class="flex items-center gap-4">
                    <img src="<?php echo e($resale->owner->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($resale->owner->name) . '&background=random&size=128'); ?>" 
                         alt="<?php echo e($resale->owner->name); ?>" 
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="text-sm text-gray-600">Sold by</p>
                        <p class="font-semibold"><?php echo e($resale->owner->name); ?></p>
                    </div>
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="p-6 bg-gray-50 border-b">
                <h3 class="font-medium mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Asking Price</span>
                        <span>$<?php echo e(number_format($resale->asking_price, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Platform Fee (<?php echo e(\App\Models\Transaction::getPlatformFeePercentage()); ?>%)</span>
                        <span>$<?php echo e(number_format($platformFee, 2)); ?></span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-semibold text-base">
                            <span>Total</span>
                            <span>$<?php echo e(number_format($resale->asking_price + $platformFee, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seller Earnings -->
            <div class="p-6 border-b">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm text-green-800 font-medium">Seller Earnings</p>
                        <p class="text-xs text-green-600">The current owner will receive this amount</p>
                    </div>
                    <p class="text-xl font-bold text-green-800">$<?php echo e(number_format($sellerEarnings, 2)); ?></p>
                </div>
            </div>

            <!-- Form -->
            <form action="<?php echo e(route('marketplace.purchase', $resale)); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>

                <div class="space-y-6">
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="credit_card" class="w-4 h-4 text-blue-600">
                                <span class="ml-3">Credit Card</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="paypal" class="w-4 h-4 text-blue-600">
                                <span class="ml-3">PayPal</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 text-blue-600">
                                <span class="ml-3">Bank Transfer</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="mobile_payment" class="w-4 h-4 text-blue-600">
                                <span class="ml-3">Mobile Payment</span>
                            </label>
                        </div>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Shipping Address</label>
                        <textarea name="shipping_address" 
                                  rows="3"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Enter your complete shipping address..."></textarea>
                        <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="terms" class="text-sm text-gray-600">
                            I understand this is a resale purchase from a private seller. I have reviewed the artwork condition 
                            and agree to the <a href="<?php echo e(route('terms')); ?>" class="text-blue-600 hover:underline" target="_blank">Terms of Service</a>.
                        </label>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <a href="<?php echo e(route('marketplace.show', $resale)); ?>" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                        Complete Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\resales\purchase.blade.php ENDPATH**/ ?>