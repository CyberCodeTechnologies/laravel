<?php $__env->startSection('title', __('messages.my_earnings') . ' - Artist Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold"><?php echo e(__('messages.my_earnings') ?? 'My Earnings'); ?></h1>
        <div class="flex space-x-4">
            <a href="<?php echo e(route('artist.earnings.commissions')); ?>" class="text-gray-600 hover:text-black"><?php echo e(__('messages.commissions') ?? 'Commissions'); ?></a>
            <a href="<?php echo e(route('artist.earnings.payouts')); ?>" class="text-gray-600 hover:text-black"><?php echo e(__('messages.payouts') ?? 'Payouts'); ?></a>
        </div>
    </div>

    <!-- Earnings Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1"><?php echo e(__('messages.pending_earnings') ?? 'Pending Earnings'); ?></p>
            <p class="text-2xl font-bold text-yellow-600">$<?php echo e(number_format($stats['pending_earnings'], 2)); ?></p>
            <p class="text-xs text-gray-400 mt-1"><?php echo e($stats['pending_count']); ?> <?php echo e(__('messages.orders') ?? 'orders'); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1"><?php echo e(__('messages.paid_earnings') ?? 'Paid Earnings'); ?></p>
            <p class="text-2xl font-bold text-green-600">$<?php echo e(number_format($stats['paid_earnings'], 2)); ?></p>
            <p class="text-xs text-gray-400 mt-1"><?php echo e($stats['paid_count']); ?> <?php echo e(__('messages.payouts') ?? 'payouts'); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1"><?php echo e(__('messages.total_earnings') ?? 'Total Earnings'); ?></p>
            <p class="text-2xl font-bold">$<?php echo e(number_format($stats['total_earnings'], 2)); ?></p>
            <p class="text-xs text-gray-400 mt-1"><?php echo e(__('messages.all_time') ?? 'All time'); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1"><?php echo e(__('messages.this_month') ?? 'This Month'); ?></p>
            <p class="text-2xl font-bold text-blue-600">$<?php echo e(number_format($stats['monthly_earnings'], 2)); ?></p>
            <p class="text-xs text-gray-400 mt-1"><?php echo e(now()->format('F Y')); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Request Payout Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4"><?php echo e(__('messages.request_payout') ?? 'Request Payout'); ?></h2>
                
                <?php if($stats['pending_earnings'] > 0): ?>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-600"><?php echo e(__('messages.available_for_payout') ?? 'Available for payout'); ?></p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($stats['pending_earnings'], 2)); ?></p>
                    </div>
                    
                    <form action="<?php echo e(route('artist.earnings.payouts.request')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.payout_method') ?? 'Payout Method'); ?></label>
                            <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="bank_transfer"><?php echo e(__('messages.bank_transfer') ?? 'Bank Transfer'); ?></option>
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                            <?php echo e(__('messages.request_payout') ?? 'Request Payout'); ?>

                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-wallet text-4xl mb-3"></i>
                        <p><?php echo e(__('messages.no_pending_earnings') ?? 'No pending earnings available'); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-2"><?php echo e(__('messages.platform_fee') ?? 'Platform Fee'); ?>: <?php echo e($stats['platform_fee_percentage']); ?>%</p>
                    <a href="<?php echo e(route('artist.earnings.tax-report', ['year' => now()->year])); ?>" class="text-sm text-blue-600 hover:underline">
                        <?php echo e(__('messages.download_tax_report') ?? 'Download Tax Report'); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4"><?php echo e(__('messages.pending_commissions') ?? 'Pending Commissions'); ?></h2>
                
                <?php if($pendingCommissions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 text-sm font-medium text-gray-600"><?php echo e(__('messages.date')); ?></th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-600"><?php echo e(__('messages.sale_amount')); ?></th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-600"><?php echo e(__('messages.platform_fee')); ?></th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-600"><?php echo e(__('messages.your_earnings')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingCommissions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b">
                                        <td class="py-3"><?php echo e($commission->created_at->format('M d, Y')); ?></td>
                                        <td class="py-3">$<?php echo e(number_format($commission->sale_amount, 2)); ?></td>
                                        <td class="py-3 text-red-600">-$<?php echo e(number_format($commission->platform_fee, 2)); ?></td>
                                        <td class="py-3 font-semibold text-green-600">$<?php echo e(number_format($commission->artist_earnings, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($pendingCommissions->count() > 5): ?>
                        <div class="mt-4 text-center">
                            <a href="<?php echo e(route('artist.earnings.commissions')); ?>" class="text-blue-600 hover:underline">
                                <?php echo e(__('messages.view_all') ?? 'View All'); ?> (<?php echo e($pendingCommissions->count()); ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8"><?php echo e(__('messages.no_pending_commissions') ?? 'No pending commissions'); ?></p>
                <?php endif; ?>
            </div>

            <!-- Recent Payouts -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-lg font-semibold mb-4"><?php echo e(__('messages.recent_payouts') ?? 'Recent Payouts'); ?></h2>
                
                <?php if($payoutHistory->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $payoutHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium"><?php echo e($payout->created_at->format('M d, Y')); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($payout->method); ?> • <?php echo e($payout->commission_count); ?> <?php echo e(__('messages.commissions') ?? 'commissions'); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">$<?php echo e(number_format($payout->amount, 2)); ?></p>
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
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8"><?php echo e(__('messages.no_payouts_yet') ?? 'No payouts yet'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.artist', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\earnings\dashboard.blade.php ENDPATH**/ ?>