<?php $__env->startSection('title', __('messages.payouts') . ' - Artist Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold"><?php echo e(__('messages.payouts') ?? 'Payouts'); ?></h1>
        <a href="<?php echo e(route('artist.earnings')); ?>" class="text-gray-600 hover:text-black"><?php echo e(__('messages.back_to_earnings') ?? 'Back to Earnings'); ?></a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if($payouts->count() > 0): ?>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.date') ?? 'Date'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.amount') ?? 'Amount'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.method') ?? 'Method'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.commissions') ?? 'Commissions'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.status') ?? 'Status'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.reference') ?? 'Reference'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo e($payout->created_at->format('M d, Y')); ?></td>
                            <td class="px-6 py-4 font-bold">$<?php echo e(number_format($payout->amount, 2)); ?></td>
                            <td class="px-6 py-4"><?php echo e(ucfirst(str_replace('_', ' ', $payout->method))); ?></td>
                            <td class="px-6 py-4"><?php echo e($payout->commission_count); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusClass = match($payout->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                    <?php echo e(ucfirst($payout->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo e($payout->payment_reference ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t">
                <?php echo e($payouts->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-500"><?php echo e(__('messages.no_payouts_yet') ?? 'No payouts yet'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.artist', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\earnings\payouts.blade.php ENDPATH**/ ?>