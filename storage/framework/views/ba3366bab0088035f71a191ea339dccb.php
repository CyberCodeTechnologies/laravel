

<?php $__env->startSection('title', 'Pending Artworks - Admin'); ?>
<?php $__env->startSection('meta-description', 'Review and approve pending artwork submissions on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Pending Artworks'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Artworks -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending Artworks</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\Artwork::where('status', 'pending')->count()); ?></p>
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
                                $todayArtworks = App\Models\Artwork::where('status', 'pending')->where('created_at', '>=', now()->startOfDay())->count();
                            ?>
                            <?php echo e($todayArtworks); ?>

                        </p>
                        <p class="text-xs text-blue-700 mt-1">New today</p>
                    </div>
                </div>
            </div>

            <!-- Pending Artists -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-palette text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Pending Artists</p>
                        <p class="text-2xl font-bold text-purple-900">
                            <?php
                                $pendingArtists = App\Models\Artwork::where('status', 'pending')->distinct('artist_id')->count('artist_id');
                            ?>
                            <?php echo e($pendingArtists); ?>

                        </p>
                        <p class="text-xs text-purple-700 mt-1">Unique artists</p>
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
                           placeholder="Search pending artworks..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.artworks.pending')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <div class="flex gap-3">
                <a href="<?php echo e(route('admin.artworks')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-image mr-2"></i>All Artworks
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pending Artworks Grid -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $artworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <!-- Artwork Image -->
                    <div class="relative h-48 bg-gray-200">
                        <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                             alt="<?php echo e($artwork->title); ?>" 
                             class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </div>
                    </div>
                    
                    <!-- Artwork Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 truncate"><?php echo e($artwork->title); ?></h3>
                        <p class="text-sm text-gray-600 mb-2">by <?php echo e($artwork->artist?->name ?? 'Unknown Artist'); ?></p>
                        <p class="text-sm text-gray-500 mb-3"><?php echo e($artwork->category?->name ?? 'Uncategorized'); ?></p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-lg font-bold text-gray-900">$<?php echo e(number_format($artwork->price, 2)); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($artwork->created_at->format('M d, Y')); ?></span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col gap-2">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('public.artworks.show', $artwork)); ?>" 
                                   class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition text-center">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="<?php echo e(route('admin.artworks.edit', $artwork->id)); ?>" 
                                   class="flex-1 bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                            <div class="flex space-x-2">
                                <form method="POST" action="<?php echo e(route('admin.artworks.approve', $artwork->id)); ?>" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                </form>
                                <button type="button" 
                                        onclick="rejectArtwork(<?php echo e($artwork->id); ?>)"
                                        class="flex-1 bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-1"></i>Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No pending artworks</h3>
                    <p class="text-gray-500">All artworks have been reviewed and processed.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($artworks->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($artworks->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Rejection Reason Modal (Hidden Form) -->
<form id="reject-form" method="POST" action="" class="hidden">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="reason" id="rejection-reason">
</form>

<script>
    function rejectArtwork(id) {
        const reason = prompt('Please enter a reason for rejecting this artwork:');
        if (reason && reason.trim() !== '') {
            const form = document.getElementById('reject-form');
            form.action = `<?php echo e(url('admin/artworks')); ?>/${id}/reject`;
            document.getElementById('rejection-reason').value = reason;
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\artworks\pending.blade.php ENDPATH**/ ?>