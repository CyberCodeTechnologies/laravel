

<?php $__env->startSection('title', 'Pending Marketplace Listings - Admin'); ?>
<?php $__env->startSection('meta-description', 'Review and approve pending resale listings on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Pending Marketplace Listings'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Listings -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending Listings</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\Resale::where('status', 'pending')->count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting approval</p>
                    </div>
                </div>
            </div>

            <!-- Today's Submissions -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Today's Submissions</p>
                        <p class="text-2xl font-bold text-blue-900">
                            <?php
                                $todayListings = App\Models\Resale::where('status', 'pending')->where('created_at', '>=', now()->startOfDay())->count();
                            ?>
                            <?php echo e($todayListings); ?>

                        </p>
                        <p class="text-xs text-blue-700 mt-1">New today</p>
                    </div>
                </div>
            </div>

            <!-- Pending Sellers -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Pending Sellers</p>
                        <p class="text-2xl font-bold text-purple-900">
                            <?php
                                $pendingSellers = App\Models\Resale::where('status', 'pending')->distinct('owner_id')->count('owner_id');
                            ?>
                            <?php echo e($pendingSellers); ?>

                        </p>
                        <p class="text-xs text-purple-700 mt-1">Unique sellers</p>
                    </div>
                </div>
            </div>

            <!-- Average Processing Time -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-hourglass-half text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Avg. Processing</p>
                        <p class="text-2xl font-bold text-green-900">24h</p>
                        <p class="text-xs text-green-700 mt-1">Processing time</p>
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
                           placeholder="Search pending listings..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.marketplace.pending')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <div class="flex gap-3">
                <a href="<?php echo e(route('admin.marketplace')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-exchange-alt mr-2"></i>Marketplace Overview
                </a>
                <a href="<?php echo e(route('admin.marketplace.listings')); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-list mr-2"></i>Active Listings
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pending Resales Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artwork</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resale Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $resales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-12 w-12 rounded-lg object-cover mr-3" 
                                             src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                             alt="<?php echo e($resale->artwork->title); ?>">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($resale->artwork->title); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($resale->artwork->artist->name); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full object-cover mr-2" 
                                             src="<?php echo e($resale->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($resale->owner->name) . '&background=random&size=128'); ?>" 
                                             alt="<?php echo e($resale->owner->name); ?>">
                                        <div class="text-sm text-gray-900"><?php echo e($resale->owner->name); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $<?php echo e(number_format($resale->artwork->price, 2)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    $<?php echo e(number_format($resale->price, 2)); ?>

                                    <?php if($resale->price > $resale->artwork->price): ?>
                                        <span class="text-green-600 text-xs">+<?php echo e(round((($resale->price - $resale->artwork->price) / $resale->artwork->price) * 100, 1)); ?>%</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($resale->created_at->format('M d, Y H:i')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?php echo e(route('marketplace.show', $resale->artwork->slug)); ?>" 
                                           class="text-blue-600 hover:text-blue-900" title="View Listing">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.marketplace.approve', $resale)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Approve Listing" onclick="return confirm('Approve this listing?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.marketplace.reject', $resale)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Reject Listing" onclick="return confirm('Reject this listing? This action cannot be undone.')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No pending listings</p>
                                        <p class="text-sm">All resale listings have been reviewed and processed.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($resales->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($resales->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\marketplace\pending.blade.php ENDPATH**/ ?>