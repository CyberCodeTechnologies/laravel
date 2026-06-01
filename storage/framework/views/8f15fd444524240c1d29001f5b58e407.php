<?php $__env->startSection('title', 'Wishlist - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your wishlist of artworks from Panchi Gallery.'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
            <p class="mt-2 text-gray-600">Artworks you've saved for later</p>
        </div>

        <?php if(auth()->check() && auth()->user()->wishlistItems->count() > 0): ?>
            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__currentLoopData = auth()->user()->wishlistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wishlistItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($wishlistItem->artwork): ?>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Artwork Image -->
                            <div class="aspect-square bg-gray-100">
                                <?php if($wishlistItem->artwork->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $wishlistItem->artwork->image)); ?>" 
                                         alt="<?php echo e($wishlistItem->artwork->title); ?>"
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Artwork Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1"><?php echo e($wishlistItem->artwork->title); ?></h3>
                                <p class="text-sm text-gray-600 mb-2"><?php echo e($wishlistItem->artwork->artist->name ?? 'Unknown Artist'); ?></p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">
                                        <?php echo e($wishlistItem->artwork->formatted_price ?? '$0.00'); ?>

                                    </span>
                                    <div class="flex space-x-2">
                                        <!-- Remove from Wishlist Button -->
                                        <form action="<?php echo e(route('wishlist.remove', $wishlistItem->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    aria-label="Remove from wishlist"
                                                    title="Remove from wishlist">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        
                                        <!-- Add to Cart Button -->
                                        <button type="button"
                                                onclick="buyNow(<?php echo e($wishlistItem->artwork->id); ?>)"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                aria-label="Add to cart"
                                                title="Add to cart">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Empty Wishlist -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Start adding artworks you love to your wishlist</p>
                <a href="<?php echo e(route('public.artworks.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    Browse Artworks
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\wishlist\index.blade.php ENDPATH**/ ?>