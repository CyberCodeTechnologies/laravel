<?php $__env->startSection('title', 'My Resales - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Manage your artwork resale listings on the secondary market.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl font-bold mb-2">My Resales</h1>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $resales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="relative">
                        <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                             alt="<?php echo e($resale->artwork->title); ?>" 
                             class="w-full h-48 object-cover">
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 text-sm rounded-full 
                            <?php echo e($resale->status === 'listed' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($resale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                            <?php echo e($resale->status === 'sold' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            <?php echo e($resale->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>">
                            <?php echo e(ucfirst($resale->status)); ?>

                        </span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-serif text-lg font-bold mb-1"><?php echo e($resale->artwork->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-4">by <?php echo e($resale->artwork->artist->name ?? 'Unknown'); ?></p>
                        
                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Asking Price:</span>
                                <span class="font-semibold">$<?php echo e(number_format($resale->asking_price, 2)); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Listed On:</span>
                                <span><?php echo e($resale->created_at->format('M d, Y')); ?></span>
                            </div>
                            <?php if($resale->approved_at): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Approved:</span>
                                <span><?php echo e($resale->approved_at->format('M d, Y')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($resale->status === 'listed'): ?>
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('resales.edit', $resale)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                Edit
                            </a>
                            <form action="<?php echo e(route('resales.destroy', $resale)); ?>" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to remove this listing?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                    Remove
                                </button>
                            </form>
                        </div>
                        <?php elseif($resale->status === 'rejected' && $resale->rejection_reason): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm">
                            <p class="text-red-800 font-medium">Rejection Reason:</p>
                            <p class="text-red-600"><?php echo e($resale->rejection_reason); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8">
                <?php echo e($resales->links()); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\collector\resales.blade.php ENDPATH**/ ?>