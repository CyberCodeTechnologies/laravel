

<?php $__env->startSection('title', 'Transaction Details - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View transaction details in Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Transaction Details'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="<?php echo e(route('admin.transactions')); ?>" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Transactions
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Transaction Details -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                            <p class="mt-1 text-sm text-gray-900">#<?php echo e($transaction->id); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php switch($transaction->status):
                                    case ('pending'): ?>
                                        bg-yellow-100 text-yellow-800
                                        <?php break; ?>
                                    <?php case ('completed'): ?>
                                        bg-green-100 text-green-800
                                        <?php break; ?>
                                    <?php case ('failed'): ?>
                                        bg-red-100 text-red-800
                                        <?php break; ?>
                                    <?php case ('refunded'): ?>
                                        bg-gray-100 text-gray-800
                                        <?php break; ?>
                                <?php endswitch; ?>
                            ">
                                <?php echo e(ucfirst($transaction->status)); ?>

                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <p class="mt-1 text-sm text-gray-900">$<?php echo e(number_format($transaction->amount, 2)); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e(ucfirst($transaction->type)); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->payment_method ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->created_at->format('M d, Y H:i')); ?></p>
                        </div>
                    </div>

                    <?php if($transaction->notes): ?>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->notes); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Artwork Information -->
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Artwork Information</h2>
                    
                    <div class="flex items-center space-x-4">
                        <?php if($transaction->artwork->image): ?>
                            <img src="<?php echo e(asset('storage/' . $transaction->artwork->image)); ?>" alt="<?php echo e($transaction->artwork->title); ?>" 
                                 class="h-20 w-20 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900"><?php echo e($transaction->artwork->title); ?></h3>
                            <p class="text-sm text-gray-500">by <?php echo e($transaction->artwork->artist->name); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($transaction->artwork->category->name); ?></p>
                            <p class="text-sm font-medium text-gray-900">$<?php echo e(number_format($transaction->artwork->price, 2)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer & Actions -->
            <div class="lg:col-span-1">
                <!-- Buyer Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Buyer Information</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->buyer->name); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->buyer->email); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($transaction->buyer->phone ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <?php if($transaction->status === 'pending'): ?>
                            <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-check mr-2"></i>Approve Payment
                            </button>
                        <?php endif; ?>
                        
                        <?php if($transaction->status === 'completed'): ?>
                            <form method="POST" action="<?php echo e(route('admin.transactions.refund', $transaction)); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition" onclick="return confirm('Process refund for this transaction?')">
                                    <i class="fas fa-undo mr-2"></i>Process Refund
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <button class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-download mr-2"></i>Download Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\transactions\show.blade.php ENDPATH**/ ?>