

<?php $__env->startSection('title', 'Manage Artworks - Admin'); ?>
<?php $__env->startSection('meta-description', 'Manage and approve artworks on Panchi Gallery platform'); ?>

<?php $__env->startSection('header', 'Manage Artworks'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Artworks -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-image text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Artworks</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->count()); ?></p>
                        <p class="text-xs text-blue-700 mt-1">
                            <?php
                                $newArtworks = App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('created_at', '>=', now()->subDays(7))->count();
                            ?>
                            +<?php echo e($newArtworks); ?> this week
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approved Artworks -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Approved</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'approved')->count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Live on platform</p>
                    </div>
                </div>
            </div>

            <!-- Pending Artworks -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'pending')->count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting approval</p>
                    </div>
                </div>
            </div>

            <!-- Sold Artworks -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Sold</p>
                        <p class="text-2xl font-bold text-purple-900"><?php echo e(App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'sold')->count()); ?></p>
                        <p class="text-xs text-purple-700 mt-1">Successfully sold</p>
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
                           placeholder="Search artworks..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                        <option value="sold" <?php echo e(request('status') == 'sold' ? 'selected' : ''); ?>>Sold</option>
                    </select>
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
                <a href="<?php echo e(route('admin.artworks')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <div class="flex gap-3">
                <a href="<?php echo e(route('admin.artworks.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add Artwork
                </a>
                <a href="<?php echo e(route('admin.artworks.pending')); ?>" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-clock mr-2"></i>Pending Artworks
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Artworks Grid -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($artworks->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__currentLoopData = $artworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Artwork Image -->
                        <div class="relative h-48 bg-gray-200">
                            <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                 alt="<?php echo e($artwork->title); ?>" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    <?php echo e($artwork->status === 'approved' ? 'bg-green-100 text-green-800' : ''); ?>

                                    <?php echo e($artwork->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                    <?php echo e($artwork->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>

                                    <?php echo e($artwork->status === 'sold' ? 'bg-purple-100 text-purple-800' : ''); ?>">
                                    <?php echo e(ucfirst($artwork->status)); ?>

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
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('public.artworks.show', $artwork)); ?>" 
                                   class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition text-center">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="<?php echo e(route('admin.artworks.edit', $artwork->id)); ?>" 
                                   class="flex-1 bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <?php if($artwork->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.artworks.approve', $artwork->id)); ?>" class="flex-1">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-image text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No artworks found</h3>
                <p class="text-gray-500">No artworks match your current filters.</p>
            </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if($artworks->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($artworks->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\artworks\index.blade.php ENDPATH**/ ?>