<?php $__env->startSection('title'); ?>
    <?php echo e($artist->name . ' - Panchi Gallery'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-description'); ?>
    Discover artworks by <?php echo e($artist->name); ?>, <?php echo e($artist->specialization ?? 'Myanmar Artist'); ?>. Browse their collection and learn about their artistic journey.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-keywords'); ?>
    <?php echo e($artist->name); ?>, Myanmar artist, <?php echo e($artist->specialization ?? 'contemporary art'); ?>, <?php echo e($artist->location ?? 'Myanmar'); ?>, original artwork, art gallery, fine art
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-image'); ?>
    <?php echo e($artist->avatar_url ?? asset('images/og-default.jpg')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?php echo e($artist->name); ?>",
    "description": "<?php echo e($artist->bio ?? 'Myanmar artist specializing in ' . $artist->specialization . ' available at Panchi Gallery.'); ?>",
    "image": "<?php echo e($artist->avatar_url); ?>",
    "url": "<?php echo e(route('public.artists.show', $artist->slug ?? $artist->id)); ?>",
    "jobTitle": "Artist",
    "worksFor": {
        "@type": "Organization",
        "name": "Panchi Gallery",
        "url": "<?php echo e(url('/')); ?>"
    },
    "knowsAbout": "<?php echo e($artist->specialization); ?>",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "MM"
    },
    "sameAs": [
        <?php if(($artist->social_links['instagram'] ?? null) && is_string($artist->social_links['instagram'])): ?>
        "<?php echo e($artist->social_links['instagram']); ?>",
        <?php endif; ?>
        <?php if(($artist->social_links['facebook'] ?? null) && is_string($artist->social_links['facebook'])): ?>
        "<?php echo e($artist->social_links['facebook']); ?>",
        <?php endif; ?>
        <?php if(($artist->social_links['website'] ?? null) && is_string($artist->social_links['website'])): ?>
        "<?php echo e($artist->social_links['website']); ?>"
        <?php endif; ?>
    ]
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
                <a href="<?php echo e(route('public.artists.index')); ?>" class="text-gray-500 hover:text-black transition-colors" itemprop="item">
                    <span itemprop="name"><?php echo e(__('messages.artists')); ?></span>
                </a>
                <meta itemprop="position" content="2">
            </li>
            <li class="text-gray-300">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-gray-900 font-medium" itemprop="name"><?php echo e($artist->name); ?></span>
                <meta itemprop="position" content="3">
            </li>
        </ol>
    </div>
</nav>

<!-- Enhanced Cover Section -->
<section class="relative h-80 md:h-[32rem] overflow-hidden">
    <div class="absolute inset-0">
        <img src="<?php echo e($artist->cover_image_url ?? asset('images/placeholder-artist-cover.jpg')); ?>" 
             alt="<?php echo e($artist->name); ?>" 
             class="w-full h-full object-cover transform scale-105 hover:scale-110 transition-transform duration-1000">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
    </div>

    <!-- Enhanced Artist Name Overlay -->
    <div class="absolute bottom-0 left-0 right-0 text-white pb-8 pt-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-end md:items-center space-y-4 md:space-y-0 md:space-x-6">
                <div class="relative -mb-16 md:mb-0 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-75 blur transition-opacity duration-300"></div>
                    <img src="<?php echo e($artist->avatar_url); ?>" 
                         alt="<?php echo e($artist->name); ?>" 
                         class="relative w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-2xl object-cover bg-white group-hover:scale-105 transition-transform duration-300">
                    <?php if($artist->is_verified): ?>
                        <div class="absolute bottom-0 right-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-2 border-3 border-white shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                        <h1 class="font-serif text-3xl md:text-5xl font-bold mb-2 md:mb-0"><?php echo e($artist->name); ?></h1>
                        <div class="flex items-center space-x-2">
                            <?php if($artist->is_verified): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs font-full rounded-full">Verified Artist</span>
                            <?php endif; ?>
                            <?php if($artist->location): ?>
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-medium rounded-full"><?php echo e($artist->location); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="text-lg md:text-xl opacity-90 font-light mt-2"><?php echo e($artist->specialization ?? __('messages.visual_artist')); ?></p>
                    <?php if($artist->bio): ?>
                        <p class="text-sm md:text-base opacity-80 mt-2 line-clamp-2"><?php echo e(Str::limit($artist->bio, 150)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="absolute top-4 right-4 flex flex-col space-y-2">
        <button onclick="shareArtistProfile()" class="p-3 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
            </svg>
        </button>
        <?php if(auth()->check() && auth()->id() !== $artist->id): ?>
            <button onclick="toggleFollow(<?php echo e($artist->id); ?>)" class="p-3 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        <?php endif; ?>
    </div>
</section>

<!-- Enhanced Artist Information -->
<section class="py-12 md:py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Quick Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <div class="bg-white rounded-xl p-4 text-center shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                <div class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e($artist->artworks_count ?? 0); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mt-1">Artworks</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                <div class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e($artist->followers_count ?? 0); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mt-1">Followers</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                <div class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e($artist->years_active ?? 0); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mt-1">Years Active</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                <div class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e($artist->artworks->sum('views_count') ?? 0); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mt-1">Total Views</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Left Column - Enhanced Bio & Info -->
            <div class="lg:col-span-1">
                <!-- Enhanced Bio -->
                <div class="mb-8">
                    <h2 class="font-serif text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <?php echo e(__('messages.about')); ?>

                    </h2>
                    <div class="prose prose-sm max-w-none">
                        <?php if($artist->bio): ?>
                            <p class="text-gray-600 leading-relaxed"><?php echo e($artist->bio); ?></p>
                        <?php else: ?>
                            <p class="text-gray-500 italic"><?php echo e(__('messages.artist_bio_default')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Artist Specializations -->
                <?php if($artist->specialization): ?>
                    <div class="mb-8">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Specializations
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 text-sm font-medium rounded-full border border-purple-200">
                                <?php echo e($artist->specialization); ?>

                            </span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Enhanced Location -->
                <?php if($artist->location): ?>
                    <div class="mb-8">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo e(__('messages.location')); ?>

                        </h3>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium"><?php echo e($artist->location); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Enhanced Social Links -->
                <?php if($artist->social_links): ?>
                    <div class="mb-8">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <?php echo e(__('messages.connect')); ?>

                        </h3>
                        <div class="grid grid-cols-3 gap-3">
                            <?php if(($artist->social_links['instagram'] ?? null) && is_string($artist->social_links['instagram'])): ?>
                                <a href="<?php echo e($artist->social_links['instagram']); ?>" target="_blank" class="flex items-center justify-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg hover:from-purple-100 hover:to-pink-100 transition-all group">
                                    <svg class="w-5 h-5 text-purple-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if(($artist->social_links['facebook'] ?? null) && is_string($artist->social_links['facebook'])): ?>
                                <a href="<?php echo e($artist->social_links['facebook']); ?>" target="_blank" class="flex items-center justify-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg hover:from-blue-100 hover:to-indigo-100 transition-all group">
                                    <svg class="w-5 h-5 text-blue-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if(($artist->social_links['website'] ?? null) && is_string($artist->social_links['website'])): ?>
                                <a href="<?php echo e($artist->social_links['website']); ?>" target="_blank" class="flex items-center justify-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:from-green-100 hover:to-emerald-100 transition-all group">
                                    <svg class="w-5 h-5 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Enhanced Profile QR Code -->
                <div class="mb-8 p-6 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-2xl text-center shadow-xl border border-gray-700">
                    <h3 class="font-semibold text-white mb-4 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <?php echo e(__('messages.scan_to_visit')); ?>

                    </h3>
                    <div class="flex justify-center mb-4">
                        <div class="bg-white p-4 rounded-xl shadow-inner hover:shadow-lg transition-shadow">
                            <img src="<?php echo e($qrCodeUrl); ?>" 
                                 alt="<?php echo e(__('messages.artist_profile_qr_code')); ?>" 
                                 class="rounded-lg w-44 h-44">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-3"><?php echo e(__('messages.scan_to_view_profile', ['name' => $artist->name])); ?></p>
                    <button onclick="downloadQRCode()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download QR
                    </button>
                </div>
                
                <!-- Enhanced Actions -->
                <div class="flex flex-col space-y-3">
                    <?php if(auth()->check() && auth()->id() !== $artist->id): ?>
                        <button onclick="toggleFollow(<?php echo e($artist->id); ?>)" 
                                class="w-full px-6 py-3.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-medium hover:from-gray-800 hover:to-gray-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <?php echo e(auth()->user()->isFollowing($artist) ? __('messages.following') : __('messages.follow_artist')); ?>

                        </button>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('contact', ['artist' => $artist->slug])); ?>" 
                       class="inline-flex items-center justify-center w-full px-6 py-3.5 border-2 border-gray-900 text-gray-900 rounded-xl font-medium hover:bg-gray-900 hover:text-white transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?php echo e(__('messages.contact_artist')); ?>

                    </a>
                    
                    <button onclick="subscribeToArtist()" class="w-full px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        Subscribe to Updates
                    </button>
                </div>
            </div>
            
            <!-- Right Column - Enhanced Artworks -->
            <div class="lg:col-span-2">
                <!-- Enhanced Tabs -->
                <div class="mb-8">
                    <nav class="flex space-x-1 bg-gradient-to-r from-gray-50 to-gray-100 p-1.5 rounded-xl border border-gray-200">
                        <button onclick="switchTab('artworks')" id="artworks-tab" class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all bg-white text-gray-900 shadow-sm">
                            <?php echo e(__('messages.artworks')); ?>

                            <span class="ml-1.5 text-xs bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-2 py-0.5 rounded-full" id="artworks-count"><?php echo e($artist->artworks_count ?? 0); ?></span>
                        </button>
                        <button onclick="switchTab('about')" id="tab-about" class="py-3 px-4 font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-lg transition-all">
                            <?php echo e(__('messages.about')); ?>

                        </button>
                        <button onclick="switchTab('exhibitions')" id="tab-exhibitions" class="py-3 px-4 font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-lg transition-all">
                            <?php echo e(__('messages.exhibitions')); ?>

                        </button>
                        <button onclick="switchTab('press')" id="tab-press" class="py-3 px-4 font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-lg transition-all">
                            <?php echo e(__('messages.press')); ?>

                        </button>
                    </nav>
                </div>
                
                <!-- Enhanced Tab Content -->
                <div id="artworks-content" class="tab-content">
                    <!-- Filter and Sort Controls -->
                    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterArtworks('all')" id="filter-all" class="filter-btn px-4 py-2 bg-purple-600 border border-purple-600 rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition-colors">
                                All
                                <span class="ml-1.5 text-xs bg-white text-purple-600 px-1.5 py-0.5 rounded-full"><?php echo e($artist->artworks_count ?? 0); ?></span>
                            </button>
                            <button onclick="filterArtworks('available')" id="filter-available" class="filter-btn px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Available
                                <span class="ml-1.5 text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full"><?php echo e($artist->available_count ?? 0); ?></span>
                            </button>
                            <button onclick="filterArtworks('sold')" id="filter-sold" class="filter-btn px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Sold
                                <span class="ml-1.5 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full"><?php echo e($artist->sold_count ?? 0); ?></span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Sort by:</label>
                            <select onchange="sortArtworks(this.value)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="latest">Latest</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>
                    </div>

                    <?php if($artist->artworks && $artist->artworks->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="artworks-grid"
                             data-artist-slug="<?php echo e($artist->slug); ?>"
                             data-current-page="1"
                             data-has-more="<?php echo e($artist->artworks_count > $artist->artworks->count() ? 'true' : 'false'); ?>">
                            <?php $__currentLoopData = $artist->artworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if (isset($component)) { $__componentOriginald932ea613c602ae383527b9bc0ce2f5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald932ea613c602ae383527b9bc0ce2f5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.artwork-card','data' => ['artwork' => $artwork,'class' => 'hover:shadow-2xl transition-all duration-300 transform hover:scale-105']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('artwork-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['artwork' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($artwork),'class' => 'hover:shadow-2xl transition-all duration-300 transform hover:scale-105']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald932ea613c602ae383527b9bc0ce2f5a)): ?>
<?php $attributes = $__attributesOriginald932ea613c602ae383527b9bc0ce2f5a; ?>
<?php unset($__attributesOriginald932ea613c602ae383527b9bc0ce2f5a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald932ea613c602ae383527b9bc0ce2f5a)): ?>
<?php $component = $__componentOriginald932ea613c602ae383527b9bc0ce2f5a; ?>
<?php unset($__componentOriginald932ea613c602ae383527b9bc0ce2f5a); ?>
<?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div id="no-artworks-message" class="hidden text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600 text-lg mb-2" id="no-artworks-text"><?php echo e(__('messages.no_artworks_yet')); ?></p>
                        </div>

                        <?php if($artist->artworks_count > $artist->artworks->count()): ?>
                            <div class="text-center mt-12" id="load-more-container">
                                <button id="load-more-btn" onclick="loadMoreArtworks()" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                    <?php echo e(__('messages.load_more_artworks')); ?>

                                </button>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600 text-lg mb-2"><?php echo e(__('messages.no_artworks_yet')); ?></p>
                            <p class="text-gray-500 text-sm">Check back soon for new artworks from <?php echo e($artist->name); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div id="about-content" class="tab-content hidden">
                    <div class="prose max-w-none">
                        <?php if($artist->artist_statement): ?>
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 mb-8 border border-purple-200">
                                <h3 class="font-serif text-2xl font-semibold mb-4 text-purple-900 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <?php echo e(__('messages.artist_statement')); ?>

                                </h3>
                                <p class="text-gray-700 leading-relaxed italic">
                                    "<?php echo e($artist->artist_statement); ?>"
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($artist->education && is_array($artist->education) && count($artist->education) > 0): ?>
                            <div class="mb-8">
                                <h3 class="font-serif text-2xl font-semibold mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <?php echo e(__('messages.education_training')); ?>

                                </h3>
                                <div class="space-y-3">
                                    <?php $__currentLoopData = $artist->education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-purple-300 transition-colors">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-gray-700"><?php echo e($edu); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($artist->awards && is_array($artist->awards) && count($artist->awards) > 0): ?>
                            <div class="mb-8">
                                <h3 class="font-serif text-2xl font-semibold mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <?php echo e(__('messages.awards_recognition')); ?>

                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php $__currentLoopData = $artist->awards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-200 hover:border-yellow-300 transition-colors">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="text-gray-700 font-medium"><?php echo e($award); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!$artist->artist_statement && (!$artist->education || count($artist->education) == 0) && (!$artist->awards || count($artist->awards) == 0)): ?>
                            <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
                                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 text-lg mb-2"><?php echo e(__('messages.detailed_info_coming_soon')); ?></p>
                                <p class="text-gray-500 text-sm">We're working on bringing you more detailed information about <?php echo e($artist->name); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div id="exhibitions-content" class="tab-content hidden">
                    <?php if($artist->exhibitions && is_array($artist->exhibitions) && count($artist->exhibitions) > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $artist->exhibitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exhibition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200 hover:border-indigo-300 transition-all hover:shadow-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-2 text-lg"><?php echo e($exhibition['title'] ?? 'Exhibition'); ?></h4>
                                            <div class="flex items-center text-gray-600 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                <?php echo e($exhibition['venue'] ?? 'Venue TBD'); ?>

                                            </div>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <?php echo e($exhibition['year'] ?? date('Y')); ?>

                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-full">
                                                <?php echo e($exhibition['type'] ?? 'Solo'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200">
                            <svg class="w-20 h-20 mx-auto text-indigo-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-gray-600 text-lg mb-2"><?php echo e(__('messages.exhibition_history_coming_soon')); ?></p>
                            <p class="text-gray-500 text-sm"><?php echo e($artist->name); ?>'s exhibition history will be available soon</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div id="press-content" class="tab-content hidden">
                    <?php if($artist->press && is_array($artist->press) && count($artist->press) > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $artist->press; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200 hover:border-green-300 transition-all hover:shadow-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-2 text-lg"><?php echo e($article['title'] ?? 'Press Article'); ?></h4>
                                            <div class="flex items-center text-gray-600 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <?php echo e($article['publication'] ?? 'Publication'); ?>

                                            </div>
                                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <?php echo e($article['date'] ?? date('F Y')); ?>

                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <?php if($article['url'] ?? null): ?>
                                                <a href="<?php echo e($article['url']); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                    <?php echo e(__('messages.read_article')); ?>

                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200">
                            <svg class="w-20 h-20 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <p class="text-gray-600 text-lg mb-2"><?php echo e(__('messages.press_coverage_coming_soon')); ?></p>
                            <p class="text-gray-500 text-sm">Media coverage and press releases for <?php echo e($artist->name); ?> will be available soon</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Enhanced tab switching with smooth animations
function switchTab(tabName) {
    // Hide all content with fade effect
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
        content.style.opacity = '0';
    });
    
    // Reset all tabs to inactive state
    document.querySelectorAll('nav button').forEach(tab => {
        tab.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        tab.classList.add('text-gray-600', 'hover:bg-white/50');
    });
    
    // Show selected content with fade in
    const selectedContent = document.getElementById(tabName + '-content');
    selectedContent.classList.remove('hidden');
    setTimeout(() => {
        selectedContent.style.opacity = '1';
        selectedContent.style.transition = 'opacity 0.3s ease';
    }, 10);
    
    // Activate selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('text-gray-600', 'hover:bg-white/50');
    activeTab.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
}

// Enhanced follow functionality with better feedback
function toggleFollow(artistId) {
    const button = event.target;
    const originalText = button.textContent;
    
    // Show loading state
    button.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
    button.disabled = true;
    
    fetch(`<?php echo e(route('public.artists.follow', ':id')); ?>`.replace(':id', artistId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Update button with animation
            button.innerHTML = data.following 
                ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg><?php echo e(__('messages.following')); ?>'
                : '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg><?php echo e(__('messages.follow_artist')); ?>';
            
            // Add animation class
            button.classList.add('animate-pulse');
            setTimeout(() => button.classList.remove('animate-pulse'), 1000);
        } else {
            showToast(data.message || 'Error updating follow status', 'error');
            button.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error toggling follow:', error);
        showToast('Error updating follow status', 'error');
        button.textContent = originalText;
    })
    .finally(() => {
        button.disabled = false;
    });
}

// New functions for enhanced features
function shareArtistProfile() {
    const url = window.location.href;
    const title = document.title;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            showToast('Profile link copied to clipboard!', 'success');
        });
    }
}

function downloadQRCode() {
    const qrUrl = '<?php echo e($qrCodeUrl); ?>';
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = '<?php echo e($artist->slug); ?>-qrcode.png';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('QR Code downloaded!', 'success');
}

function subscribeToArtist() {
    showToast('Subscribing to artist updates...', 'info');
    // Implement subscription logic here
    setTimeout(() => {
        showToast('Successfully subscribed to <?php echo e($artist->name); ?>\'s updates!', 'success');
    }, 1000);
}

// Artwork filtering and sorting
function filterArtworks(filter) {
    // Update active filter button styling
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-purple-600', 'text-white', 'border-purple-600');
        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
    });
    
    const activeBtn = document.getElementById('filter-' + filter);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
        activeBtn.classList.add('bg-purple-600', 'text-white', 'border-purple-600');
    }
    
    // Get all artwork cards
    const artworksGrid = document.getElementById('artworks-grid');
    const noArtworksMessage = document.getElementById('no-artworks-message');
    const noArtworksText = document.getElementById('no-artworks-text');
    const artworkCards = document.querySelectorAll('.artwork-card');
    
    let visibleCount = 0;
    
    artworkCards.forEach(card => {
        const status = card.getAttribute('data-status');
        
        if (filter === 'all') {
            card.style.display = '';
            visibleCount++;
        } else if (filter === 'available' && status === 'approved') {
            card.style.display = '';
            visibleCount++;
        } else if (filter === 'sold' && status === 'sold') {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide no artworks message
    if (visibleCount === 0) {
        artworksGrid.style.display = 'none';
        noArtworksMessage.classList.remove('hidden');
        
        // Update message based on filter
        if (filter === 'available') {
            noArtworksText.textContent = 'No available artworks at the moment.';
        } else if (filter === 'sold') {
            noArtworksText.textContent = 'No sold artworks to display.';
        } else {
            noArtworksText.textContent = '<?php echo e(__('messages.no_artworks_yet')); ?>';
        }
    } else {
        artworksGrid.style.display = 'grid';
        noArtworksMessage.classList.add('hidden');
    }
    
    // Update artworks count in tab
    const countBadge = document.getElementById('artworks-count');
    if (countBadge) {
        countBadge.textContent = visibleCount;
    }
}

function sortArtworks(sortBy) {
    console.log('Sorting by:', sortBy);
    showToast(`Sorting artworks: ${sortBy}`, 'info');
    // Implement sorting logic here
}

function loadMoreArtworks() {
    showToast('Loading more artworks...', 'info');
    // Implement load more logic here
}

// Enhanced toast notification
function showToast(message, type = 'info') {
    if (typeof window.showToast === 'function' && window.showToast !== showToast) {
        window.showToast(message, type);
        return;
    }
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-full opacity-0`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-y-full', 'opacity-0');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Contact artist function
function contactArtist(artistSlug) {
    window.location.href = '/contact?artist=' + artistSlug;
}

// Initialize page with animations
document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animations to elements
    const elements = document.querySelectorAll('.artwork-card, .bg-white');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artists\show.blade.php ENDPATH**/ ?>