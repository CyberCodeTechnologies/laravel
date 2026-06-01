

<?php $__env->startSection('title', __('messages.payment_verification') . ' - Admin'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold"><?php echo e(__('messages.payment_verification')); ?></h1>
        <div class="flex space-x-4">
            <a href="<?php echo e(route('admin.payments.verifications')); ?>?status=pending" 
                class="px-4 py-2 <?php echo e(request('status') === 'pending' || !request('status') ? 'bg-black text-white' : 'bg-gray-200'); ?> rounded-lg">
                <?php echo e(__('messages.pending_approval')); ?> (<?php echo e(\App\Models\PaymentProof::pending()->count()); ?>)
            </a>
            <a href="<?php echo e(route('admin.payments.verifications')); ?>?status=approved" 
                class="px-4 py-2 <?php echo e(request('status') === 'approved' ? 'bg-black text-white' : 'bg-gray-200'); ?> rounded-lg">
                <?php echo e(__('messages.approved')); ?>

            </a>
            <a href="<?php echo e(route('admin.payments.verifications')); ?>?status=rejected" 
                class="px-4 py-2 <?php echo e(request('status') === 'rejected' ? 'bg-black text-white' : 'bg-gray-200'); ?> rounded-lg">
                <?php echo e(__('messages.rejected')); ?>

            </a>
        </div>
    </div>

    <?php if($proofs->count() > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php $__currentLoopData = $proofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Order #<?php echo e($proof->order->order_number); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($proof->paymentMethod->name); ?></p>
                            </div>
                            <?php
                                $statusClass = match($proof->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    default => 'bg-red-100 text-red-800'
                                };
                            ?>
                            <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo e($statusClass); ?>">
                                <?php echo e(ucfirst($proof->status)); ?>

                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="text-2xl font-bold"><?php echo e(\App\Helpers\CurrencyHelper::format($proof->order->total_amount)); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($proof->order->user->name); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($proof->order->user->email); ?></p>
                        </div>

                        <?php if($proof->transaction_reference): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(__('messages.transaction_reference')); ?>:</p>
                                <p class="text-sm"><?php echo e($proof->transaction_reference); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($proof->notes): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(__('messages.notes')); ?>:</p>
                                <p class="text-sm"><?php echo e($proof->notes); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($proof->admin_notes): ?>
                            <div class="mb-4 bg-gray-50 p-3 rounded">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(__('messages.admin_notes')); ?>:</p>
                                <p class="text-sm"><?php echo e($proof->admin_notes); ?></p>
                                <p class="text-xs text-gray-500 mt-1">Verified by <?php echo e($proof->verifier->name); ?> on <?php echo e($proof->verified_at->format('Y-m-d H:i')); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($proof->screenshot): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.payment_screenshot')); ?>:</p>
                                <a href="<?php echo e(asset('storage/' . $proof->screenshot)); ?>" target="_blank" class="block">
                                    <img src="<?php echo e(asset('storage/' . $proof->screenshot)); ?>" 
                                        alt="Payment Proof" 
                                        class="max-h-48 rounded-lg border hover:opacity-90 transition-opacity">
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($proof->isPending()): ?>
                            <div class="flex space-x-4 mt-4">
                                <form action="<?php echo e(route('admin.payments.approve', $proof)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <input type="text" name="admin_notes" placeholder="Admin notes (optional)" 
                                        class="w-full mb-2 px-3 py-2 border border-gray-300 rounded text-sm">
                                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition-colors">
                                        <?php echo e(__('messages.approve_payment')); ?>

                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('admin.payments.reject', $proof)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <input type="text" name="admin_notes" placeholder="Reason for rejection (required)" required
                                        class="w-full mb-2 px-3 py-2 border border-gray-300 rounded text-sm">
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition-colors">
                                        <?php echo e(__('messages.reject_payment')); ?>

                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8">
            <?php echo e($proofs->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-4 text-gray-600"><?php echo e(__('messages.no_pending_payments') ?? 'No pending payments to verify.'); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\payments\verifications.blade.php ENDPATH**/ ?>