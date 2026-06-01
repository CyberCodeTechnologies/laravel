


<div class="artwork-card bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
    <div class="relative group">
        <a href="<?php echo e(route('public.artworks.show', $artwork->slug)); ?>">
            <img src="<?php echo e($artwork->primary_image); ?>" 
                 alt="<?php echo e($artwork->title); ?>" 
                 class="w-full h-64 object-cover"
                 loading="lazy">
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="flex justify-between items-end">
                        <div class="text-white">
                            <h4 class="font-semibold text-lg"><?php echo e(Str::limit($artwork->title, 30)); ?></h4>
                            <p class="text-sm opacity-90"><?php echo e($artwork->artist->name); ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="event.preventDefault(); toggleLike(<?php echo e($artwork->id); ?>)" 
                                    class="bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition-colors">
                                <i class="fas fa-heart <?php echo e(auth()->check() && auth()->user()->likedArtworks->contains($artwork->id) ? 'text-red-500' : 'text-gray-600'); ?>"></i>
                            </button>
                            <button onclick="event.preventDefault(); quickView(<?php echo e($artwork->id); ?>)" 
                                    class="bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-white transition-colors">
                                <i class="fas fa-eye text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Badges -->
            <?php if($artwork->isSold()): ?>
                <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?php echo e(__('messages.sold')); ?>

                </div>
            <?php elseif($artwork->isFeatured()): ?>
                <div class="absolute top-4 left-4 bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-star mr-1"></i>
                    <?php echo e(__('messages.featured')); ?>

                </div>
            <?php endif; ?>
            
            <!-- Price Badge -->
            <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-medium">
                <?php echo e($artwork->formatted_price); ?>

            </div>
        </a>
    </div>
    
    <div class="p-6">
        <div class="mb-3">
            <a href="<?php echo e(route('public.artworks.show', $artwork->slug)); ?>">
                <h3 class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                    <?php echo e($artwork->title); ?>

                </h3>
            </a>
            <p class="text-gray-600">
                <?php echo e(__('messages.by')); ?>

                <?php if($artwork->artist): ?>
                    <a href="<?php echo e(route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id)); ?>" class="hover:text-indigo-600">
                        <?php echo e($artwork->artist->name); ?>

                    </a>
                <?php else: ?>
                    <span class="text-gray-500"><?php echo e(__('messages.unknown_artist')); ?></span>
                <?php endif; ?>
            </p>
        </div>
        
        <div class="flex items-center justify-between mb-4">
            <div class="text-xl font-bold text-gray-900"><?php echo e($artwork->formatted_price); ?></div>
            <div class="flex items-center space-x-3 text-sm text-gray-500">
                <span class="flex items-center">
                    <i class="fas fa-heart text-red-500 mr-1"></i>
                    <?php echo e($artwork->likes_count); ?>

                </span>
                <span class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <?php echo e(number_format($artwork->views_count)); ?>

                </span>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                <?php echo e($artwork->category->name); ?>

            </span>
            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                <?php echo e($artwork->medium); ?>

            </span>
            <?php if($artwork->year): ?>
                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                    <?php echo e($artwork->year); ?>

                </span>
            <?php endif; ?>
        </div>
        
        <div class="flex space-x-2">
            <button onclick="addToCart(<?php echo e($artwork->id); ?>)"
                    class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <i class="fas fa-shopping-cart mr-2"></i>
                <?php echo e(__('messages.add_to_cart')); ?>

            </button>
            <button onclick="makeOffer(<?php echo e($artwork->id); ?>)"
                    class="border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                <i class="fas fa-hand-holding-usd mr-2"></i>
                <?php echo e(__('messages.offer')); ?>

            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\artwork-card.blade.php ENDPATH**/ ?>