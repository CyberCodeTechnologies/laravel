

<?php $__env->startSection('title', 'Reports Dashboard - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Reports Dashboard'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Key Metrics Overview -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Period Selector -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Key Performance Indicators</h2>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">This Month</button>
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Last Month</button>
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">This Year</button>
            </div>
        </div>

        <!-- Revenue Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Revenue This Month</p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($revenueThisMonth, 2)); ?></p>
                        <p class="text-sm mt-2">
                            <?php if($revenueLastMonth > 0): ?>
                                <?php echo e((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100) > 0 ? '+' : ''); ?>

                                <?php echo e(number_format(($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100, 1)); ?>% vs last month
                            <?php else: ?>
                                No previous data
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="text-4xl opacity-80">$</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Total Users</p>
                        <p class="text-3xl font-bold"><?php echo e($totalUsers); ?></p>
                        <p class="text-sm mt-2">+<?php echo e($newUsersThisMonth); ?> this month</p>
                    </div>
                    <div class="text-4xl opacity-80">👥</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">Total Artworks</p>
                        <p class="text-3xl font-bold"><?php echo e($totalArtworks); ?></p>
                        <p class="text-sm mt-2"><?php echo e($approvedArtworks); ?> approved</p>
                    </div>
                    <div class="text-4xl opacity-80">🎨</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100">Transactions</p>
                        <p class="text-3xl font-bold"><?php echo e($completedTransactions); ?></p>
                        <p class="text-sm mt-2"><?php echo e($pendingTransactions); ?> pending</p>
                    </div>
                    <div class="text-4xl opacity-80">💳</div>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Active Artists</h3>
                <p class="text-2xl font-bold text-gray-900"><?php echo e($totalArtists); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($approvedArtists); ?> approved</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Collectors</h3>
                <p class="text-2xl font-bold text-gray-900"><?php echo e($totalCollectors); ?></p>
                <p class="text-sm text-gray-600">Active buyers</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Marketplace Activity</h3>
                <p class="text-2xl font-bold text-gray-900"><?php echo e($activeResales); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($pendingResales); ?> pending listings</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Avg Order Value</h3>
                <p class="text-2xl font-bold text-gray-900">$<?php echo e(number_format($averageOrderValue, 2)); ?></p>
                <p class="text-sm text-gray-600">Per transaction</p>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Top Categories -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Categories by Artwork Count</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $topCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700"><?php echo e($category->name); ?></span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($topCategories->first()->artworks_count > 0 ? ($category->artworks_count / $topCategories->first()->artworks_count) * 100 : 0); ?>%"></div>
                                </div>
                                <span class="text-sm text-gray-600"><?php echo e($category->artworks_count); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Engagement Metrics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($totalLikes); ?></p>
                        <p class="text-sm text-gray-600">Total Likes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600"><?php echo e($totalWishlists); ?></p>
                        <p class="text-sm text-gray-600">Wishlists</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600"><?php echo e($activeCarts); ?></p>
                        <p class="text-sm text-gray-600">Active Carts</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-orange-600"><?php echo e($soldArtworks); ?></p>
                        <p class="text-sm text-gray-600">Sold Artworks</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Transactions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 text-sm font-medium text-gray-600">ID</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-600">Buyer</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-600">Amount</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b">
                                    <td class="py-2 text-sm"><?php echo e($transaction->transaction_id); ?></td>
                                    <td class="py-2 text-sm"><?php echo e($transaction->buyer->name); ?></td>
                                    <td class="py-2 text-sm">$<?php echo e(number_format($transaction->amount, 2)); ?></td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            <?php echo e($transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                            <?php echo e(ucfirst($transaction->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">New Users</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($user->email); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php echo e($user->role === 'artist' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($user->created_at->format('M d')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\dashboard.blade.php ENDPATH**/ ?>