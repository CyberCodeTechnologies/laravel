<?php $__env->startSection('title', __('messages.commissions') . ' - Artist Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold"><?php echo e(__('messages.commissions') ?? 'Commissions'); ?></h1>
        <a href="<?php echo e(route('artist.earnings')); ?>" class="text-gray-600 hover:text-black"><?php echo e(__('messages.back_to_earnings') ?? 'Back to Earnings'); ?></a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if($commissions->count() > 0): ?>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.date') ?? 'Date'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.order') ?? 'Order'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.sale_amount') ?? 'Sale Amount'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.platform_fee') ?? 'Platform Fee'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.your_earnings') ?? 'Your Earnings'); ?></th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600"><?php echo e(__('messages.status') ?? 'Status'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo e($commission->created_at->format('M d, Y')); ?></td>
                            <td class="px-6 py-4">
                                <?php if($commission->order_id): ?>
                                    <a href="<?php echo e(route('orders.show', $commission->order_id)); ?>" class="text-blue-600 hover:underline">
                                        #<?php echo e(str_pad($commission->order_id, 6, '0', STR_PAD_LEFT)); ?>

                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">$<?php echo e(number_format($commission->sale_amount, 2)); ?></td>
                            <td class="px-6 py-4 text-red-600">-$<?php echo e(number_format($commission->platform_fee, 2)); ?></td>
                            <td class="px-6 py-4 font-semibold text-green-600">$<?php echo e(number_format($commission->artist_earnings, 2)); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusClass = $commission->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                    <?php echo e(ucfirst($commission->status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t">
                <?php echo e($commissions->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-500"><?php echo e(__('messages.no_commissions_yet') ?? 'No commissions yet'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.artist', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\earnings\commissions.blade.php ENDPATH**/ ?>