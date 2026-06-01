<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'artwork' => null,
    'showArtist' => true,
    'showPrice' => true,
    'showWishlist' => true,
    'compact' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'artwork' => null,
    'showArtist' => true,
    'showPrice' => true,
    'showWishlist' => true,
    'compact' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if(!$artwork): ?>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury">
        <div class="skeleton h-64"></div>
        <div class="p-4">
            <div class="skeleton h-4 mb-2"></div>
            <div class="skeleton h-3 w-3/4"></div>
        </div>
    </div>
<?php else: ?>
    <div class="artwork-card group bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury <?php echo e($compact ? 'compact' : ''); ?>" 
         data-status="<?php echo e((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'approved'))); ?>"
         data-price="<?php echo e(is_object($artwork) ? $artwork->price : ($artwork['price'] ?? 0)); ?>"
         data-views="<?php echo e(is_object($artwork) ? $artwork->views_count : ($artwork['views_count'] ?? 0)); ?>"
         data-date="<?php echo e(is_object($artwork) ? $artwork->created_at->timestamp : (strtotime($artwork['created_at'] ?? 'now'))); ?>">
        <!-- Image Container -->
        <div class="relative image-hover-zoom <?php echo e($compact ? 'h-48' : 'h-64'); ?>">
            <a href="<?php echo e(route('public.artworks.show', is_object($artwork) ? ($artwork->slug ?? $artwork->id) : ($artwork['slug'] ?? $artwork['id'] ?? '#'))); ?>" class="block w-full h-full">
                <img 
                    src="<?php echo e(is_object($artwork) ? ($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')) : ($artwork['image'] ?? asset('images/placeholder-artwork.jpg'))); ?>" 
                    alt="<?php echo e(is_object($artwork) ? $artwork->title : ($artwork['title'] ?? __('messages.untitled'))); ?>"
                    class="w-full h-full object-cover"
                    loading="lazy"
                >
            </a>
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex space-x-2">
                    <?php if($showWishlist): ?>
                        <button 
                            onclick="toggleWishlist(<?php echo e(is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 'null')); ?>)"
                            class="p-2 bg-white rounded-full hover:bg-gray-100 transition-colors"
                            title="<?php echo e(auth()->check() && auth()->user()->wishlistItems()->where('artwork_id', is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0))->exists() ? __('messages.remove_from_wishlist') : __('messages.add_to_wishlist')); ?>"
                            data-artwork-id="<?php echo e(is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0)); ?>"
                        >
                            <svg class="w-5 h-5 <?php echo e(auth()->check() && auth()->user()->wishlistItems()->where('artwork_id', is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0))->exists() ? 'text-red-600' : 'text-gray-700'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Status Badges -->
            <?php if((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'sold'): ?>
                <div class="absolute top-2 left-2">
                    <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => 'sold','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'sold','size' => 'sm']); ?><?php echo e(__('messages.sold')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                </div>
            <?php elseif((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'new'): ?>
                <div class="absolute top-2 left-2">
                    <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => 'new','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'new','size' => 'sm']); ?><?php echo e(__('messages.new_badge')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($artwork->is_verified) && $artwork->is_verified): ?>
                <div class="absolute top-2 right-2">
                    <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => 'verified','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'verified','size' => 'sm']); ?><?php echo e(__('messages.verified')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Content -->
        <div class="p-4">
            <!-- Title -->
            <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1 group-hover:text-black transition-colors">
                <a href="<?php echo e(route('public.artworks.show', is_object($artwork) ? ($artwork->slug ?? $artwork->id) : ($artwork['slug'] ?? $artwork['id'] ?? '#'))); ?>" class="hover:underline">
                    <?php echo e(is_object($artwork) ? $artwork->title : ($artwork['title'] ?? __('messages.untitled'))); ?>

                </a>
            </h3>
            
            <!-- Artist -->
            <?php if($showArtist && isset($artwork->artist) && $artwork->artist): ?>
                <p class="text-sm text-gray-600 mb-2">
                    <?php if(is_object($artwork)): ?>
                        <a href="<?php echo e(route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id)); ?>" class="hover:text-black transition-colors">
                            <?php echo e($artwork->artist->name); ?>

                        </a>
                    <?php else: ?>
                        <span class="text-gray-600"><?php echo e($artwork['artist'] ?? __('messages.unknown_artist')); ?></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            
            <!-- Meta Info -->
            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                <span><?php echo e(is_object($artwork) ? ($artwork->medium ?? 'Mixed Media') : ($artwork['medium'] ?? 'Mixed Media')); ?></span>
                <span><?php echo e(is_object($artwork) ? ($artwork->year ?? date('Y')) : ($artwork['year'] ?? date('Y'))); ?></span>
            </div>
            
            <!-- Price -->
            <?php if($showPrice): ?>
                <div class="flex items-center justify-between">
                    <div>
                        <?php if(is_object($artwork) ? $artwork->price : ($artwork['price'] ?? null)): ?>
                            <span class="text-xl font-bold text-black">
                                <?php echo e(\App\Helpers\CurrencyHelper::formatArtworkPrice($artwork)); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-xl font-bold text-black"><?php echo e(__('messages.price_on_request')); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'available'): ?>
                        <button type="button"
                                onclick="buyNow(<?php echo e(is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0)); ?>)"
                                class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                            <?php echo e(__('messages.buy_now')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/components/artwork-card.blade.php ENDPATH**/ ?>