<?php $__env->startSection('title', $resale->artwork->title . ' - Marketplace - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Buy ' . $resale->artwork->title . ' by ' . $resale->artwork->artist->first_name . ' ' . $resale->artwork->artist->last_name . ' in our secure marketplace. Verified artwork with certificate of authenticity and complete ownership history.'); ?>
<?php $__env->startSection('meta-keywords', $resale->artwork->title . ', ' . $resale->artwork->artist->name . ', buy art resale, Myanmar art marketplace, authenticated artwork, art investment, Panchi Gallery'); ?>
<?php $__env->startSection('meta-image', $resale->artwork->primary_image ?? asset('images/og-default.jpg')); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?php echo e($resale->artwork->title); ?>",
    "description": "Buy <?php echo e($resale->artwork->title); ?> by <?php echo e($resale->artwork->artist->name ?? 'Unknown Artist'); ?> in our secure marketplace. Verified artwork with certificate of authenticity.",
    "image": "<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>",
    "url": "<?php echo e(route('marketplace.show', $resale->id)); ?>",
    "offers": {
        "@type": "Offer",
        "price": "<?php echo e($resale->asking_price); ?>",
        "priceCurrency": "<?php echo e($resale->currency ?? 'USD'); ?>",
        "availability": "https://schema.org/InStock",
        "url": "<?php echo e(route('marketplace.show', $resale->id)); ?>",
        "seller": {
            "@type": "Person",
            "name": "<?php echo e($resale->owner->first_name); ?> <?php echo e($resale->owner->last_name); ?>"
        }
    },
    "brand": {
        "@type": "Person",
        "name": "<?php echo e($resale->artwork->artist->name ?? 'Myanmar Artist'); ?>"
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav class="py-4 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-black transition-colors" itemprop="item">
                    <span itemprop="name"><?php echo e(__('messages.home')); ?></span>
                </a>
                <meta itemprop="position" content="1">
            </li>
            <li class="text-gray-300">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo e(route('marketplace.index')); ?>" class="text-gray-500 hover:text-black transition-colors" itemprop="item">
                    <span itemprop="name"><?php echo e(__('messages.marketplace')); ?></span>
                </a>
                <meta itemprop="position" content="2">
            </li>
            <li class="text-gray-300">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-gray-900 font-medium" itemprop="name"><?php echo e($resale->artwork->title); ?></span>
                <meta itemprop="position" content="3">
            </li>
        </ol>
    </div>
</nav>

<!-- Artwork Detail -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left: Image Gallery -->
            <div>
                <div class="relative">
                    <!-- Main Image -->
                    <div class="relative image-hover-zoom rounded-2xl overflow-hidden">
                        <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>"
                             alt="<?php echo e($resale->artwork->title); ?>"
                             class="w-full h-[600px] object-cover"
                             id="main-image">
                        
                        <!-- Image Actions -->
                        <div class="absolute top-4 right-4 flex space-x-2">
                            <button onclick="toggleFullscreen()" class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5h-4m4 0v-4m0 4l-5-5"></path>
                                </svg>
                            </button>
                            <button onclick="shareArtwork()" class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="absolute top-4 left-4 space-y-2">
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
<?php $component->withAttributes(['variant' => 'verified','size' => 'sm']); ?><?php echo e(__('messages.verified')); ?> ✔ <?php echo $__env->renderComponent(); ?>
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
                    
                    <!-- Thumbnail Gallery -->
                    <div class="grid grid-cols-4 gap-2 mt-4">
                        <?php
                            $images = $resale->artwork->image_urls ?? [$resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')];
                        ?>
                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button onclick="changeImage(<?php echo e($index); ?>)" class="relative image-hover-zoom rounded-lg overflow-hidden border-2 <?php echo e($index === 0 ? 'border-black' : 'border-transparent'); ?>">
                                <img src="<?php echo e($image); ?>" alt="Thumbnail <?php echo e($index + 1); ?>" class="w-full h-24 object-cover">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Right: Artwork Info -->
            <div>
                <!-- Title and Artist -->
                <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    <?php echo e($resale->artwork->title); ?>

                </h1>
                <p class="text-xl text-gray-600 mb-6">
                    by
                    <?php if($resale->artwork->artist): ?>
                        <a href="<?php echo e(route('public.artists.show', $resale->artwork->artist->slug ?? $resale->artwork->artist->id)); ?>" class="text-black hover:underline"><?php echo e($resale->artwork->artist->first_name); ?> <?php echo e($resale->artwork->artist->last_name); ?></a>
                    <?php else: ?>
                        <span class="text-gray-500"><?php echo e(__('messages.unknown_artist')); ?></span>
                    <?php endif; ?>
                </p>
                
                <!-- Price and Seller Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <?php if($resale->minimum_price && $resale->asking_price > $resale->minimum_price): ?>
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl font-bold text-black">
                                        <?php echo e(\App\Helpers\CurrencyHelper::format($resale->asking_price, 'USD')); ?>

                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Min: <?php echo e(\App\Helpers\CurrencyHelper::format($resale->minimum_price, 'USD')); ?>

                                    </span>
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
                                </div>
                            <?php else: ?>
                                <span class="text-3xl font-bold text-black">
                                    <?php echo e(\App\Helpers\CurrencyHelper::format($resale->asking_price, 'USD')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <button onclick="toggleWishlist(<?php echo e($resale->artwork->id); ?>)" class="p-3 border border-gray-300 rounded-lg hover:border-black transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Seller Information -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.sold_by')); ?></p>
                            <div class="flex items-center space-x-2">
                                <img src="<?php echo e($resale->owner->avatar_url); ?>"
                                     alt="<?php echo e($resale->owner->first_name); ?> <?php echo e($resale->owner->last_name); ?>"
                                     class="w-8 h-8 rounded-full object-cover">
                                <span class="font-medium text-gray-900"><?php echo e($resale->owner->first_name); ?> <?php echo e($resale->owner->last_name); ?></span>
                                <?php if($resale->owner->is_verified): ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.listed')); ?></p>
                            <p class="font-mono text-sm font-medium text-gray-900"><?php echo e($resale->created_at ? $resale->created_at->diffForHumans() : 'Recently'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Artwork Details -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4"><?php echo e(__('messages.artwork_details_title')); ?></h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.medium')); ?></p>
                            <p class="font-medium text-gray-900"><?php echo e($resale->artwork->medium ?? 'Oil on Canvas'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.dimensions')); ?></p>
                            <p class="font-medium text-gray-900"><?php echo e($resale->artwork->dimensions ?? '24x36 inches'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.year_created')); ?></p>
                            <p class="font-medium text-gray-900"><?php echo e($resale->artwork->year ?? date('Y')); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.condition')); ?></p>
                            <p class="font-medium text-gray-900"><?php echo e(__('messages.excellent')); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4"><?php echo e(__('messages.about_this_artwork')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e($resale->description ?? $resale->artwork->description ?? 'This remarkable piece showcases the artist\'s unique vision and technical mastery. Created with exceptional attention to detail, it represents a significant contribution to contemporary Myanmar art.'); ?>

                    </p>
                </div>
                
                <!-- Trust Features -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-green-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e(__('messages.verified_authentic')); ?>

                    </h3>
                    <ul class="space-y-2 text-sm text-green-700">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e(__('messages.certificate_included')); ?>

                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e(__('messages.ownership_history_tracked')); ?>

                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e(__('messages.expert_verified')); ?>

                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e(__('messages.secure_escrow')); ?>

                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'primary','size' => 'lg','fullWidth' => true,'onclick' => 'purchaseArtwork('.e($resale->id).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','full-width' => true,'onclick' => 'purchaseArtwork('.e($resale->id).')']); ?>
                        <?php echo e(__('messages.buy_now')); ?> - <?php echo e(session('currency', 'USD') === 'MMK' ? number_format($resale->asking_price * 2100) . ' MMK' : '$' . number_format($resale->asking_price)); ?>

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

                    <div class="grid grid-cols-2 gap-3">
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'outline','size' => 'lg','onclick' => 'makeOffer('.e($resale->id).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'lg','onclick' => 'makeOffer('.e($resale->id).')']); ?>
                            <?php echo e(__('messages.make_offer')); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'outline','size' => 'lg','onclick' => 'contactSeller('.e($resale->id).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'lg','onclick' => 'contactSeller('.e($resale->id).')']); ?>
                            <?php echo e(__('messages.contact_seller')); ?>

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
                
                <!-- Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-gray-900"><?php echo e($resale->artwork->views_count ?? 0); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.views')); ?></p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900"><?php echo e($resale->artwork->likes_count ?? 0); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.likes')); ?></p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900"><?php echo e($resale->created_at ? $resale->created_at->diffForHumans() : 'Recently'); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(__('messages.listed')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ownership History -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                <?php echo e(__('messages.ownership_history')); ?>

            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                <?php echo e(__('messages.provenance_tracking')); ?>

            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <?php if (isset($component)) { $__componentOriginal93f2afea2d7941ca7799292711b7f46f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f2afea2d7941ca7799292711b7f46f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.timeline','data' => ['variant' => 'ownership','items' => [
                [
                    'title' => __('messages.created_by_artist'),
                    'description' => __('messages.artwork_created_by') . ' ' . $resale->artwork->artist->first_name . ' ' . $resale->artwork->artist->last_name,
                    'date' => $resale->artwork->year ?? date('Y'),
                    'status' => 'completed',
                    'meta' => [
                        'location' => 'Myanmar',
                        'certificate' => true
                    ]
                ],
                [
                    'title' => __('messages.certificate_issued'),
                    'description' => __('messages.certificate_issued_desc'),
                    'date' => $resale->artwork->created_at ? $resale->artwork->created_at->format('F Y') : 'Recently',
                    'status' => 'completed',
                    'meta' => [
                        'certificate' => true
                    ],
                    'badge' => [
                        'variant' => 'verified',
                        'text' => __('messages.verified')
                    ]
                ],
                [
                    'title' => __('messages.current_owner'),
                    'description' => __('messages.currently_owned_by') . ' ' . $resale->owner->first_name . ' ' . $resale->owner->last_name,
                    'date' => $resale->created_at ? $resale->created_at->format('F Y') : 'Recently',
                    'status' => 'current',
                    'meta' => [
                        'location' => 'Myanmar'
                    ]
                ]
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ownership','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                [
                    'title' => __('messages.created_by_artist'),
                    'description' => __('messages.artwork_created_by') . ' ' . $resale->artwork->artist->first_name . ' ' . $resale->artwork->artist->last_name,
                    'date' => $resale->artwork->year ?? date('Y'),
                    'status' => 'completed',
                    'meta' => [
                        'location' => 'Myanmar',
                        'certificate' => true
                    ]
                ],
                [
                    'title' => __('messages.certificate_issued'),
                    'description' => __('messages.certificate_issued_desc'),
                    'date' => $resale->artwork->created_at ? $resale->artwork->created_at->format('F Y') : 'Recently',
                    'status' => 'completed',
                    'meta' => [
                        'certificate' => true
                    ],
                    'badge' => [
                        'variant' => 'verified',
                        'text' => __('messages.verified')
                    ]
                ],
                [
                    'title' => __('messages.current_owner'),
                    'description' => __('messages.currently_owned_by') . ' ' . $resale->owner->first_name . ' ' . $resale->owner->last_name,
                    'date' => $resale->created_at ? $resale->created_at->format('F Y') : 'Recently',
                    'status' => 'current',
                    'meta' => [
                        'location' => 'Myanmar'
                    ]
                ]
            ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93f2afea2d7941ca7799292711b7f46f)): ?>
<?php $attributes = $__attributesOriginal93f2afea2d7941ca7799292711b7f46f; ?>
<?php unset($__attributesOriginal93f2afea2d7941ca7799292711b7f46f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93f2afea2d7941ca7799292711b7f46f)): ?>
<?php $component = $__componentOriginal93f2afea2d7941ca7799292711b7f46f; ?>
<?php unset($__componentOriginal93f2afea2d7941ca7799292711b7f46f); ?>
<?php endif; ?>
        </div>
    </div>
</section>

<!-- Certificate Preview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                <?php echo e(__('messages.certificate_of_authenticity')); ?>

            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                <?php echo e(__('messages.verify_authenticity_qr')); ?>

            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl p-8">
                <!-- Certificate Header -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                            <span class="text-white font-serif text-2xl font-bold">P</span>
                        </div>
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-gray-900 mb-2"><?php echo e(__('messages.certificate_of_authenticity')); ?></h3>
                    <p class="text-gray-600"><?php echo e(__('messages.panchi_verification_system')); ?></p>
                </div>
                
                <!-- Certificate Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4"><?php echo e(__('messages.artwork_details_title')); ?></h4>
                        <div class="space-y-2">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.certificate_id')); ?></span>
                                <span class="font-mono font-medium"><?php echo e('PG-' . strtoupper(substr(md5($resale->id . $resale->artwork->id), 0, 13))); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.title')); ?></span>
                                <span class="font-medium"><?php echo e($resale->artwork->title); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.artist')); ?></span>
                                <span class="font-medium"><?php echo e($resale->artwork->artist->first_name); ?> <?php echo e($resale->artwork->artist->last_name); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.date_created')); ?></span>
                                <span class="font-medium"><?php echo e($resale->artwork->year ?? date('Y')); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4"><?php echo e(__('messages.verification_details')); ?></h4>
                        <div class="space-y-2">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.verification_date')); ?></span>
                                <span class="font-medium">June 15, 2024</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.verified_by')); ?></span>
                                <span class="font-medium">Dr. Aung Myint</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600"><?php echo e(__('messages.status')); ?></span>
                                <span class="font-medium text-green-600">✓ <?php echo e(__('messages.authentic')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QR Code -->
                <div class="text-center">
                    <div class="inline-block p-6 bg-white rounded-lg border-2 border-gray-300">
                        <img src="<?php echo e($qrCodeUrl); ?>"
                             alt="<?php echo e(__('messages.verify_authenticity')); ?>"
                             class="w-32 h-32">
                    </div>
                    <p class="text-sm text-gray-600 mt-4"><?php echo e(__('messages.scan_verify')); ?></p>
                    <p class="text-xs text-gray-500 mt-2"><?php echo e(__('messages.certificate_id')); ?>: <?php echo e('PG-' . strtoupper(substr(md5($resale->id . $resale->artwork->id), 0, 13))); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function changeImage(index) {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.grid button');
    
    // Update main image (in real app, this would change to the selected image)
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('border-black');
            thumb.classList.remove('border-transparent');
        } else {
            thumb.classList.remove('border-black');
            thumb.classList.add('border-transparent');
        }
    });
}

function toggleFullscreen() {
    const mainImage = document.getElementById('main-image');
    if (!document.fullscreenElement) {
        mainImage.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function shareArtwork() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e($resale->artwork->title); ?>',
            text: 'Check out this amazing artwork on Panchi Gallery',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

function purchaseArtwork(artworkId) {
    // Redirect to checkout
    window.location.href = `/checkout/${artworkId}`;
}

function makeOffer(artworkId) {
    // Open offer modal
    console.log('Make offer for artwork:', artworkId);
}

function contactSeller(artworkId) {
    // Open contact modal
    console.log('Contact seller for artwork:', artworkId);
}

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\marketplace\show.blade.php ENDPATH**/ ?>