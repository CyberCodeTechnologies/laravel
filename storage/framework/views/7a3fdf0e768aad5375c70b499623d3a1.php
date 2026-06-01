<?php $__env->startSection('title', 'My Wishlist - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your wishlist items.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">My Wishlist</h1>
        <p class="text-gray-300">Artworks you're interested in</p>
    </div>
</section>

<!-- Wishlist Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($likes->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__currentLoopData = $likes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $like): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
                    <div class="relative">
                        <img src="<?php echo e($like->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                             alt="<?php echo e($like->artwork->title); ?>" 
                             class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                        <?php if($like->artwork->status === 'sold'): ?>
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="px-4 py-2 bg-gray-900 text-white rounded-full text-sm">Sold</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-serif font-bold mb-1 truncate"><?php echo e($like->artwork->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-3">by <?php echo e($like->artwork->artist->name ?? 'Unknown'); ?></p>
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">$<?php echo e(number_format($like->artwork->price, 2)); ?></span>
                            <form action="<?php echo e(route('wishlist.remove', $like)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <?php if($like->artwork->status !== 'sold'): ?>
                        <a href="<?php echo e(route('public.artworks.show', $like->artwork)); ?>" 
                           class="mt-3 block w-full text-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                            View Details
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8">
                <?php echo e($likes->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-6">Save artworks you love to your wishlist</p>
                <a href="<?php echo e(route('public.artworks.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\collector\wishlist.blade.php ENDPATH**/ ?>