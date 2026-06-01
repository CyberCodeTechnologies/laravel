<?php $__env->startSection('title', 'My Resale Listings - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your artwork resale listings.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl font-bold mb-2">My Resale Listings</h1>
                <p class="text-gray-300">Manage your secondary market listings</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?php echo e(route('collector.artworks')); ?>" class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition">
                    List New Artwork
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Resales List -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($resales->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Artwork</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Listed</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $resales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                                             alt="{{ $resale->artwork->title); ?>" 
                                             loading="lazy"
                                             class="w-12 h-12 object-cover rounded">
                                        <div>
                                            <p class="font-medium"><?php echo e($resale->artwork->title); ?></p>
                                            <p class="text-xs text-gray-500">by <?php echo e($resale->artwork->artist->name ?? 'Unknown'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    $<?php echo e(number_format($resale->asking_price, 2)); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs rounded-full 
                                        <?php echo e($resale->status === 'listed' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($resale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($resale->status === 'sold' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($resale->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>">
                                        <?php echo e(ucfirst($resale->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo e($resale->created_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <?php if($resale->status === 'listed'): ?>
                                        <a href="<?php echo e(route('resales.edit', $resale)); ?>" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Edit
                                        </a>
                                        <form action="<?php echo e(route('resales.destroy', $resale)); ?>" method="POST" class="inline" onsubmit="return confirm('Remove this listing?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Remove
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('marketplace.show', $resale)); ?>" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    <?php echo e($resales->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No resale listings</h3>
                <p class="text-gray-500 mb-6">List artworks from your collection for resale</p>
                <a href="<?php echo e(route('collector.artworks')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    View My Collection
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\resales\my-resales.blade.php ENDPATH**/ ?>