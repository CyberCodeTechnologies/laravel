<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.our_artists') . ' - Panchi Gallery'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-description'); ?>
    <?php echo e(__('messages.artists_meta_description')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-keywords'); ?>
    Myanmar artists, contemporary artists, traditional artists, art gallery, Panchi Gallery, buy art from artists, original artwork, artist portfolio
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta-image'); ?>
    <?php echo e(asset('images/og-default.jpg')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Our Artists - Panchi Gallery",
    "url": "<?php echo e(route('public.artists.index')); ?>",
    "description": "<?php echo e(__('messages.artists_meta_description')); ?>"
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.animate-float { animation: float 6s ease-in-out infinite; }
.animate-gradient { 
    background-size: 200% 200%;
    animation: gradient 8s ease infinite;
}
</style>

<!-- Header Section -->
<section class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-20 relative overflow-hidden animate-gradient">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-black/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-gray-400/10 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-gray-300/10 rounded-full blur-3xl animate-float" style="animation-delay: -4s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-gray-200/30 to-white/50 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.05]" style="background-image: linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center space-x-2 bg-black/5 backdrop-blur-md px-6 py-2 rounded-full mb-8 border border-black/10">
                <span class="w-2 h-2 bg-black rounded-full animate-pulse"></span>
                <span class="text-black/80 text-sm font-medium"><?php echo e($artists->count()); ?> <?php echo e(__('messages.talented_artists')); ?></span>
            </div>
            
            <h1 class="font-serif text-6xl md:text-7xl lg:text-8xl font-bold text-gray-900 mb-6 tracking-tight leading-tight">
                <?php echo __('messages.discover_exceptional_artists'); ?>

            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-12 leading-relaxed">
                <?php echo e(__('messages.artists_description')); ?>

            </p>
            
            <!-- Search Bar with Glassmorphism -->
            <div class="max-w-2xl mx-auto">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="artistSearch" 
                            placeholder="<?php echo e(__('messages.search_artists_placeholder')); ?>"
                            class="w-full px-8 py-5 pl-14 rounded-full bg-white/80 backdrop-blur-md border border-gray-200 text-gray-900 placeholder-gray-400 text-lg focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 shadow-lg"
                        >
                        <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                            <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-medium text-gray-400 bg-gray-100 rounded border border-gray-200">⌘K</kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Artists Grid -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle at 1px 1px, black 1px, transparent 0); background-size: 40px 40px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-6">
            <div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    <?php echo e(__('messages.featured_artists')); ?>

                </h2>
                <p class="text-gray-600 text-lg"><?php echo e(__('messages.featured_artists_subtitle')); ?></p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <select class="appearance-none px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 cursor-pointer pr-10 shadow-sm">
                        <option><?php echo e(__('messages.all_specializations')); ?></option>
                        <?php $__currentLoopData = $specializations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $specialization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($specialization); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Artists Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="artistsGrid">
            <?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="artist-card-wrapper opacity-0 transform translate-y-8 transition-all duration-700 ease-out">
                    <?php if (isset($component)) { $__componentOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.artist-card','data' => ['artist' => $artist]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('artist-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['artist' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($artist)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94)): ?>
<?php $attributes = $__attributesOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94; ?>
<?php unset($__attributesOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94)): ?>
<?php $component = $__componentOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94; ?>
<?php unset($__componentOriginalf423fa8aa89d0318cd1e1cf0cdf6ec94); ?>
<?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white text-gray-900 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="text-5xl md:text-6xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform duration-300">
                    <?php echo e($artists->count() ?? 0); ?>+
                </div>
                <div class="text-gray-600 text-lg font-medium"><?php echo e(__('messages.artists')); ?></div>
            </div>
            <div class="text-center group">
                <div class="text-5xl md:text-6xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform duration-300">
                    <?php echo e($artists->sum('artworks_count') ?? 0); ?>+
                </div>
                <div class="text-gray-600 text-lg font-medium"><?php echo e(__('messages.artworks')); ?></div>
            </div>
            <div class="text-center group">
                <div class="text-5xl md:text-6xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform duration-300">
                    <?php echo e($artists->sum('followers_count') ?? 0); ?>+
                </div>
                <div class="text-gray-600 text-lg font-medium"><?php echo e(__('messages.followers_count')); ?></div>
            </div>
            <div class="text-center group">
                <div class="text-5xl md:text-6xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform duration-300">
                    10+
                </div>
                <div class="text-gray-600 text-lg font-medium"><?php echo e(__('messages.years')); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- Artist Spotlight -->
<?php if($featuredArtist): ?>
<section class="py-32 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gray-300/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-gray-400/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center space-x-2 bg-black/5 backdrop-blur-md px-6 py-2 rounded-full mb-6 border border-black/10">
                <span class="w-2 h-2 bg-black rounded-full animate-pulse"></span>
                <span class="text-gray-800 text-sm font-medium"><?php echo e(__('messages.artist_spotlight')); ?></span>
            </div>
            <h2 class="font-serif text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                <?php echo e(__('messages.featured_artist_description')); ?>

            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                <?php echo e(__('messages.discover_featured_talent')); ?>

            </p>
        </div>
        
        <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-2xl transform hover:scale-[1.02] transition-transform duration-500">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="relative h-[500px] lg:h-[600px] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent z-10"></div>
                    <img src="<?php echo e($featuredArtist->avatar_url); ?>" 
                         alt="<?php echo e($featuredArtist->name); ?>" 
                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12 z-20">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-white/90 text-sm font-medium"><?php echo e(__('messages.available_for_commissions')); ?></span>
                        </div>
                        <h3 class="font-serif text-4xl md:text-5xl font-bold text-white mb-3"><?php echo e($featuredArtist->name); ?></h3>
                        <p class="text-xl text-white/90"><?php echo e($featuredArtist->specialization ?? __('messages.contemporary_artist')); ?></p>
                    </div>
                </div>
                
                <div class="p-8 lg:p-16 flex flex-col justify-center bg-gradient-to-b from-gray-50 to-white">
                    <h3 class="font-serif text-2xl md:text-3xl font-semibold text-gray-900 mb-8 italic leading-relaxed">
                        "<?php echo e($featuredArtist->bio ? Str::limit($featuredArtist->bio, 150) : __('messages.artist_quote_default')); ?>"
                    </h3>
                    <p class="text-gray-600 mb-10 leading-relaxed text-lg">
                        <?php echo e($featuredArtist->bio ?? __('messages.artist_bio_default')); ?>

                    </p>
                    
                    <div class="grid grid-cols-3 gap-6 mb-10">
                        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 group hover:bg-gray-100 transition-all duration-300">
                            <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform"><?php echo e($featuredArtist->artworks_count ?? 0); ?></div>
                            <div class="text-gray-600 text-sm font-medium"><?php echo e(__('messages.artworks_count')); ?></div>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 group hover:bg-gray-100 transition-all duration-300">
                            <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform"><?php echo e($featuredArtist->followers_count ?? 0); ?></div>
                            <div class="text-gray-600 text-sm font-medium"><?php echo e(__('messages.followers_count')); ?></div>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 group hover:bg-gray-100 transition-all duration-300">
                            <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:scale-110 transition-transform"><?php echo e($featuredArtist->years_active ?? 5); ?>+</div>
                            <div class="text-gray-600 text-sm font-medium"><?php echo e(__('messages.years_active')); ?></div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="<?php echo e(route('public.artists.show', $featuredArtist->slug ?? $featuredArtist->id)); ?>" class="group relative px-10 py-4 rounded-xl font-semibold inline-flex items-center justify-center space-x-2 bg-black text-white shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <span class="relative z-10"><?php echo e(__('messages.view_portfolio')); ?></span>
                            <svg class="relative z-10 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->id() !== $featuredArtist->id): ?>
                            <button onclick="toggleFollow(<?php echo e($featuredArtist->id); ?>)" class="px-10 py-4 rounded-xl font-semibold border-2 border-black text-black hover:bg-black hover:text-white transition-all duration-300">
                                <?php echo e(auth()->user()->isFollowing($featuredArtist) ? __('messages.following') : __('messages.follow_artist')); ?>

                            </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Animate artist cards on page load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.artist-card-wrapper');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.remove('opacity-0', 'translate-y-4');
        }, index * 100);
    });
});

// Search functionality
document.getElementById('artistSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.artist-card-wrapper');
    
    cards.forEach(card => {
        const artistName = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const artistSpecialization = card.querySelector('p')?.textContent.toLowerCase() || '';
        
        if (artistName.includes(searchTerm) || artistSpecialization.includes(searchTerm)) {
            card.style.display = 'block';
            card.classList.remove('opacity-0', 'translate-y-4');
        } else {
            card.style.display = 'none';
        }
    });
});

// Follow/Unfollow functionality
function toggleFollow(artistId) {
    const button = event.target;
    const originalText = button.textContent;
    
    // Show loading state
    button.disabled = true;
    button.textContent = 'Loading...';
    
    // Send AJAX request
    fetch(`<?php echo e(route('public.artists.follow', ':id')); ?>`.replace(':id', artistId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401) {
            return response.json().then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    showNotification(data.message || 'Authentication required. Please log in.', 'error');
                }
            });
        }
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update button state
            if (data.following) {
                button.textContent = '<?php echo e(__("messages.following")); ?>';
                button.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 bg-gray-100 text-gray-900 border border-gray-200 hover:bg-gray-200';
            } else {
                button.textContent = '<?php echo e(__("messages.follow")); ?>';
                button.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 bg-black text-white hover:bg-gray-800 shadow-lg';
            }
            
            // Show success message
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.message.includes('Unexpected token')) {
            showNotification('Authentication required. Please log in.', 'error');
        } else {
            showNotification('An error occurred. Please try again.', 'error');
        }
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Notification helper function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/artists/index.blade.php ENDPATH**/ ?>