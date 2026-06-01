<?php $__env->startSection('title', __('messages.marketplace_title')); ?>
<?php $__env->startSection('meta-description', __('messages.marketplace_meta_description')); ?>
<?php $__env->startSection('meta-keywords', 'art resale marketplace, buy pre-owned art, sell artwork, secondary art market, authenticated art, art investment, Panchi Gallery marketplace, Myanmar art resale'); ?>
<?php $__env->startSection('meta-image', asset('images/og-default.jpg')); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Art Marketplace - Panchi Gallery",
    "url": "<?php echo e(route('marketplace.index')); ?>",
    "description": "Buy and sell authenticated pre-owned artwork on the Panchi Gallery marketplace. Verified ownership history and certificates of authenticity."
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in-up">
            <p class="text-elegant text-sm font-medium text-white/60 mb-4 tracking-widest uppercase"><?php echo e(__('messages.secondary_market')); ?></p>
            <h1 class="font-serif text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
                <?php echo e(__('messages.art_marketplace')); ?>

            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                <?php echo e(__('messages.marketplace_hero_description')); ?>

            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','size' => 'lg','href' => ''.e(route('artist.artworks.create')).'','class' => 'btn-elegant']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => ''.e(route('artist.artworks.create')).'','class' => 'btn-elegant']); ?>
                    <?php echo e(__('messages.sell_your_art')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'outline','size' => 'lg','class' => 'border-white text-white hover:bg-white hover:text-black transition-all duration-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'lg','class' => 'border-white text-white hover:bg-white hover:text-black transition-all duration-300']); ?>
                    <?php echo e(__('messages.browse_collection')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2"><?php echo e(__('messages.verified_100')); ?></h3>
                <p class="text-gray-400 text-sm"><?php echo e(__('messages.verified_artworks_text')); ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2"><?php echo e(__('messages.secure_transactions')); ?></h3>
                <p class="text-gray-400 text-sm"><?php echo e(__('messages.secure_transactions_text')); ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2"><?php echo e(__('messages.ownership_history_title')); ?></h3>
                <p class="text-gray-400 text-sm"><?php echo e(__('messages.ownership_history_text')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar Filters -->
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-serif text-lg font-semibold text-gray-900"><?php echo e(__('messages.filters')); ?></h2>
                        <button onclick="clearFilters()" class="text-sm text-gray-600 hover:text-black transition-colors font-medium">
                            <?php echo e(__('messages.clear_all')); ?>

                        </button>
                    </div>

                    <form id="filter-form" onsubmit="applyFilters(event)">
                        <!-- Verified Only -->
                        <div class="mb-6">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="verified" value="1" checked class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors"><?php echo e(__('messages.verified_artworks_only')); ?></span>
                            </label>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm"><?php echo e(__('messages.price_range')); ?></h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="min_price" placeholder="<?php echo e(__('messages.min_price_placeholder')); ?>" class="input-elegant w-full px-4 py-2.5 rounded-lg text-sm">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="max_price" placeholder="<?php echo e(__('messages.max_price_placeholder')); ?>" class="input-elegant w-full px-4 py-2.5 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm"><?php echo e(__('messages.category')); ?></h3>
                            <div class="space-y-3">
                                <?php $__currentLoopData = [__('messages.painting'), __('messages.photography'), __('messages.sculpture'), __('messages.digital_art')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="categories[]" value="<?php echo e($category); ?>" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                        <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors"><?php echo e($category); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Seller Type -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm"><?php echo e(__('messages.seller_type')); ?></h3>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="artist" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors"><?php echo e(__('messages.original_artist')); ?></span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="gallery" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors"><?php echo e(__('messages.gallery')); ?></span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="collector" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors"><?php echo e(__('messages.collector_seller')); ?></span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </aside>
            
            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <p class="text-gray-600">
                            <?php echo e(__('messages.showing_results_of', ['count' => $resales->count(), 'total' => $resales->total()])); ?>

                        </p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Sort -->
                        <select name="sort" class="input-elegant px-4 py-2.5 rounded-lg text-sm" onchange="applyFilters()">
                            <option value="featured"><?php echo e(__('messages.sort_by_featured')); ?></option>
                            <option value="price-low"><?php echo e(__('messages.sort_price_low_high')); ?></option>
                            <option value="price-high"><?php echo e(__('messages.sort_price_high_low')); ?></option>
                            <option value="newest"><?php echo e(__('messages.sort_newest_first')); ?></option>
                            <option value="ending-soon"><?php echo e(__('messages.sort_ending_soon')); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Marketplace Grid -->
                <?php if($resales->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php $__currentLoopData = $resales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-elegant rounded-2xl overflow-hidden group">
                            <!-- Image Container -->
                            <div class="relative image-hover-zoom h-72">
                                <a href="<?php echo e(route('marketplace.show', $resale->artwork->slug)); ?>" class="block w-full h-full">
                                    <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" alt="<?php echo e($resale->artwork->title); ?>" class="w-full h-full object-cover">
                                </a>

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-4">
                                    <div class="flex space-x-3">
                                        <button onclick="event.preventDefault(); quickView(<?php echo e($resale->artwork->id); ?>)" class="p-3 bg-white/95 backdrop-blur-sm rounded-full hover:bg-white transition-colors shadow-lg" title="Quick View">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="event.preventDefault(); toggleWishlist(<?php echo e($resale->artwork->id); ?>)" class="p-3 bg-white/95 backdrop-blur-sm rounded-full hover:bg-white transition-colors shadow-lg" title="Add to Wishlist">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="absolute top-3 left-3 space-y-2">
                                    <?php if($resale->is_verified): ?>
                                        <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => 'verified','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'verified','size' => 'sm']); ?><?php echo e(__('messages.verified_badge')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($resale->minimum_price && $resale->asking_price > $resale->minimum_price): ?>
                                        <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => 'trending','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'trending','size' => 'sm']); ?><?php echo e(__('messages.negotiable')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <!-- Title and Artist -->
                                <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1 group-hover:text-black transition-colors">
                                    <a href="<?php echo e(route('marketplace.show', $resale->artwork->slug)); ?>" class="hover:underline">
                                        <?php echo e($resale->artwork->title); ?>

                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mb-3"><?php echo e(__('messages.by_artist')); ?> <?php echo e($resale->artwork->artist ? $resale->artwork->artist->first_name . ' ' . $resale->artwork->artist->last_name : __('messages.unknown_artist')); ?></p>

                                <!-- Seller Info -->
                                <div class="flex items-center text-xs text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php echo e($resale->owner ? $resale->owner->first_name . ' ' . $resale->owner->last_name : __('messages.unknown_seller')); ?>

                                </div>

                                <!-- Price and Stats -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <?php if($resale->minimum_price): ?>
                                            <div class="flex flex-col">
                                                <span class="text-xl font-bold text-black">
                                                    <?php echo e(session('currency', 'USD') === 'MMK' ? number_format($resale->asking_price * 2100) . ' MMK' : '$' . number_format($resale->asking_price)); ?>

                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?php echo e(__('messages.min_price_label')); ?> <?php echo e(session('currency', 'USD') === 'MMK' ? number_format($resale->minimum_price * 2100) . ' MMK' : '$' . number_format($resale->minimum_price)); ?>

                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xl font-bold text-black">
                                                <?php echo e(session('currency', 'USD') === 'MMK' ? number_format($resale->asking_price * 2100) . ' MMK' : '$' . number_format($resale->asking_price)); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Activity Stats -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                                    <span><?php echo e($resale->artwork->views_count ?? 0); ?> <?php echo e(__('messages.views_count')); ?></span>
                                    <span><?php echo e(__('messages.listed_time')); ?> <?php echo e($resale->created_at ? $resale->created_at->diffForHumans() : __('messages.recently')); ?></span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <a href="<?php echo e(route('marketplace.show', $resale->artwork->slug)); ?>" class="btn-luxury w-full text-center px-4 py-2.5 rounded-lg text-sm font-medium text-white">
                                        <?php echo e(__('messages.view_details')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <?php if($resales->hasPages()): ?>
                <div class="mt-12">
                    <?php echo e($resales->links()); ?>

                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-16">
                    <p class="text-gray-500 text-lg"><?php echo e(__('messages.no_resale_listings')); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase"><?php echo e(__('messages.simple_process')); ?></p>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                <?php echo e(__('messages.how_it_works_title')); ?>

            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                <?php echo e(__('messages.how_it_works_description')); ?>

            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php $__currentLoopData = [
                ['step' => '1', 'title' => __('messages.step_1_title'), 'description' => __('messages.step_1_description')],
                ['step' => '2', 'title' => __('messages.step_2_title'), 'description' => __('messages.step_2_description')],
                ['step' => '3', 'title' => __('messages.step_3_title'), 'description' => __('messages.step_3_description')],
                ['step' => '4', 'title' => __('messages.step_4_title'), 'description' => __('messages.step_4_description')]
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-16 h-16">
                        <div class="absolute inset-0 bg-black rounded-full transform group-hover:scale-110 transition-transform duration-300"></div>
                        <div class="relative w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            <?php echo e($item['step']); ?>

                        </div>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-black transition-colors"><?php echo e($item['title']); ?></h3>
                    <p class="text-gray-600 text-sm leading-relaxed"><?php echo e($item['description']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function applyFilters(event) {
    if (event) {
        event.preventDefault();
    }

    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Add form data
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }

    // Add sort parameter
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        params.append('sort', sortSelect.value);
    }

    // Build URL with parameters
    const queryString = params.toString();
    const newUrl = queryString ? `<?php echo e(route('marketplace.index')); ?>?${queryString}` : '<?php echo e(route('marketplace.index')); ?>';

    // Show loading state
    const container = document.querySelector('.grid');
    if (container) {
        container.style.opacity = '0.5';
    }

    // Navigate to new URL
    window.location.href = newUrl;
}

function clearFilters() {
    // Reset all form inputs
    document.getElementById('filter-form').reset();

    // Reset sort select
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.selectedIndex = 0;
    }

    // Navigate to base URL
    window.location.href = '<?php echo e(route('marketplace.index')); ?>';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\marketplace\index.blade.php ENDPATH**/ ?>