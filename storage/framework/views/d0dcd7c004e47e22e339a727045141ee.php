<?php $__env->startSection('title', 'Admin Dashboard - Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Platform overview and management</p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Total Users</div>
                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_users'] ?? 0); ?></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Total Artworks</div>
                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_artworks'] ?? 0); ?></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Total Sales</div>
                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_sales'] ?? 0); ?></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Revenue</div>
                <div class="text-2xl font-bold text-gray-900">$<?php echo e(number_format($stats['total_revenue'] ?? 0, 2)); ?></div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Pending Artists</h2>
                    <a href="<?php echo e(route('admin.pending-artists')); ?>" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="p-6">
                    <?php if(isset($pendingArtists) && count($pendingArtists) > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $pendingArtists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <span class="font-medium text-gray-900"><?php echo e($artist->name); ?></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="<?php echo e(route('admin.artists.approve', $artist->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-green-600 hover:text-green-800"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="<?php echo e(route('admin.artists.reject', $artist->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No pending artists</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Pending Artworks</h2>
                    <a href="<?php echo e(route('admin.artworks.pending')); ?>" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="p-6">
                    <?php if(isset($pendingArtworks) && count($pendingArtworks) > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $pendingArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center gap-3">
                                        <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" alt="" class="w-10 h-10 rounded object-cover">
                                        <span class="font-medium text-gray-900"><?php echo e($artwork->title); ?></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="<?php echo e(route('admin.artworks.approve', $artwork->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-green-600 hover:text-green-800"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="<?php echo e(route('admin.artworks.reject', $artwork->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No pending artworks</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
            </div>
            <div class="p-6">
                <?php if(isset($recentActivity) && count($recentActivity) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-4 py-2 border-b border-gray-100 last:border-0">
                                <div class="text-sm text-gray-500"><?php echo e($activity->created_at->diffForHumans()); ?></div>
                                <div class="flex-1"><?php echo e($activity->description); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No recent activity</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\dashboard\admin.blade.php ENDPATH**/ ?>