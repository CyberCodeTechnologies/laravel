

<?php $__env->startSection('title', 'Manage Payouts - Admin'); ?>
<?php $__env->startSection('meta-description', 'Manage artist payouts and payments on Panchi Gallery platform'); ?>

<?php $__env->startSection('header', 'Manage Payouts'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Payouts -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Payouts</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(App\Models\Payout::count()); ?></p>
                        <p class="text-xs text-blue-700 mt-1">
                            <?php
                                $totalPayoutAmount = App\Models\Payout::sum('amount');
                            ?>
                            $<?php echo e(number_format($totalPayoutAmount, 2)); ?> total
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Payouts -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\Payout::where('status', 'pending')->count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">
                            <?php
                                $pendingAmount = App\Models\Payout::where('status', 'pending')->sum('amount');
                            ?>
                            $<?php echo e(number_format($pendingAmount, 2)); ?> to pay
                        </p>
                    </div>
                </div>
            </div>

            <!-- Processing -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-cog text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Processing</p>
                        <p class="text-2xl font-bold text-purple-900"><?php echo e(App\Models\Payout::where('status', 'processing')->count()); ?></p>
                        <p class="text-xs text-purple-700 mt-1">In progress</p>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Completed</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\Payout::where('status', 'completed')->count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Successfully paid</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="min-w-64">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Search by artist name..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                    </select>
                </div>
                <div>
                    <select name="method" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Methods</option>
                        <option value="bank_transfer" <?php echo e(request('method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                        <option value="paypal" <?php echo e(request('method') == 'paypal' ? 'selected' : ''); ?>>PayPal</option>
                        <option value="stripe" <?php echo e(request('method') == 'stripe' ? 'selected' : ''); ?>>Stripe</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.payouts.index')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <a href="<?php echo e(route('admin.payouts.pending')); ?>" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-clock mr-2"></i>View Pending
            </a>
        </div>
    </div>
</section>

<!-- Payouts Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center text-blue-700 font-bold overflow-hidden">
                                            <?php if($payout->artist->avatar): ?>
                                                <img src="<?php echo e(asset('storage/' . $payout->artist->avatar)); ?>" class="h-full w-full object-cover">
                                            <?php else: ?>
                                                <?php echo e(substr($payout->artist->name, 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($payout->artist->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($payout->artist->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900"><?php echo e($payout->currency); ?> <?php echo e(number_format($payout->amount, 2)); ?></div>
                                    <?php if($payout->payment_reference): ?>
                                        <div class="text-xs text-gray-500">Ref: <?php echo e($payout->payment_reference); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo e($payout->method === 'bank_transfer' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($payout->method === 'paypal' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($payout->method === 'stripe' ? 'bg-purple-100 text-purple-800' : ''); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $payout->method))); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($payout->commission_count); ?></div>
                                    <div class="text-xs text-gray-500">commissions</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo e($payout->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($payout->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($payout->status === 'processing' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                        <?php echo e($payout->status === 'failed' ? 'bg-red-100 text-red-800' : ''); ?>">
                                        <?php echo e(ucfirst($payout->status)); ?>

                                    </span>
                                    <?php if($payout->status === 'failed' && $payout->failure_reason): ?>
                                        <div class="text-xs text-red-500 mt-1"><?php echo e(Str::limit($payout->failure_reason, 30)); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($payout->requested_at ? $payout->requested_at->format('M j, Y') : $payout->created_at->format('M j, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if($payout->processed_at): ?>
                                        <?php echo e($payout->processed_at->format('M j, Y')); ?>

                                        <div class="text-xs text-gray-400">by <?php echo e($payout->processedBy?->name ?? 'System'); ?></div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?php echo e(route('admin.payouts.show', $payout->id)); ?>" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($payout->status === 'pending'): ?>
                                            <form method="POST" action="<?php echo e(route('admin.payouts.process', $payout->id)); ?>" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Process Payout" onclick="return confirm('Process this payout?')">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($payout->status === 'processing'): ?>
                                            <form method="POST" action="<?php echo e(route('admin.payouts.complete', $payout->id)); ?>" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Mark Complete" onclick="return confirm('Mark this payout as complete?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-money-bill-wave text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No payouts found</p>
                                        <p class="text-sm">Payouts are created when artists request withdrawals.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($payouts->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($payouts->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\payouts\index.blade.php ENDPATH**/ ?>