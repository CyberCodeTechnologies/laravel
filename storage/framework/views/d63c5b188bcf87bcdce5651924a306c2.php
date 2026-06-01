<?php $__env->startSection('title', cms_content('about_title', __('messages.about_title')) . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', cms_content('about_hero_description', __('messages.about_hero_description'))); ?>
<?php $__env->startSection('meta-keywords', 'Panchi Gallery about, Myanmar art gallery, art marketplace, authentic artwork, art certificates, art platform mission, buy sell art Myanmar'); ?>
<?php $__env->startSection('meta-image', \App\Services\ImageSyncService::getSynchronizedImage('site_og_image', asset('images/og-default.jpg'))); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "AboutPage",
    "name": "About Panchi Gallery",
    "url": "<?php echo e(route('about')); ?>",
    "description": "<?php echo e(cms_content('about_hero_description', __('messages.about_hero_description'))); ?>",
    "mainEntity": {
        "@type": "Organization",
        "name": "Panchi Gallery",
        "url": "<?php echo e(url('/')); ?>"
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Enhanced Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo e(\App\Services\ImageSyncService::getSynchronizedImage('about_hero_background', asset('images/placeholder-artwork.jpg'))); ?>" 
                 alt="About Panchi Gallery" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
            <div class="animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-white mb-6 leading-tight">
                    <?php echo nl2br(e(general_setting('about_hero_title', __('messages.about_hero_title')))); ?>

                </h1>
                <p class="text-2xl md:text-3xl font-light text-white/90 mb-8 leading-relaxed">
                    <?php echo nl2br(e(general_setting('about_hero_subtitle_1', __('messages.about_hero_subtitle_1')))); ?><br><?php echo e(general_setting('about_hero_subtitle_2', __('messages.about_hero_subtitle_2'))); ?>

                </p>
                <p class="text-lg md:text-xl text-white/80 mb-12 max-w-3xl mx-auto leading-relaxed">
                    <?php echo e(general_setting('about_hero_description', __('messages.about_hero_description'))); ?>

                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('public.artworks.index')); ?>" 
                       class="px-8 py-4 bg-white text-black font-semibold rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <?php echo e(__('messages.explore_artworks')); ?>

                    </a>
                    <a href="<?php echo e(route('public.artists.index')); ?>" 
                       class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-black transition-all duration-300 transform hover:scale-105">
                        <?php echo e(__('messages.meet_artists')); ?>

                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Enhanced Mission Section -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center px-4 py-2 bg-black/10 rounded-full">
                        <span class="text-sm font-semibold text-black"><?php echo e(general_setting('about_mission_badge', __('messages.mission_badge'))); ?></span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 leading-tight">
                        <?php echo e(general_setting('about_mission_title', __('messages.mission_title'))); ?>

                    </h2>
                    <div class="space-y-4 text-lg text-gray-600 leading-relaxed">
                        <p>
                            <?php echo e(general_setting('about_mission_p1', __('messages.mission_p1'))); ?>

                        </p>
                        <p>
                            <?php echo e(general_setting('about_mission_p2', __('messages.mission_p2'))); ?>

                        </p>
                        <p>
                            <?php echo e(general_setting('about_mission_p3', __('messages.mission_p3'))); ?>

                        </p>
                    </div>
                </div>
                
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-2xl transform rotate-3 group-hover:rotate-6 transition-transform duration-300"></div>
                    <img src="<?php echo e(\App\Services\ImageSyncService::getSynchronizedImage('about_mission_image', asset('images/placeholder-artwork.jpg'))); ?>" 
                         alt="Art Gallery" 
                         class="relative rounded-2xl shadow-2xl w-full h-[500px] object-cover transform group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Values Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-black/10 rounded-full mb-6">
                    <span class="text-sm font-semibold text-black"><?php echo e(__('messages.values_badge')); ?></span>
                </div>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-6">
                    <?php echo e(__('messages.values_title')); ?>

                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e(__('messages.values_subtitle')); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.authenticity')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.authenticity_text')); ?>

                    </p>
                </div>
                
                <div class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.quality')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.quality_text')); ?>

                    </p>
                </div>
                
                <div class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.transparency')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.transparency_text')); ?>

                    </p>
                </div>
                
                <div class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-3.356l-2.828-2.828a3 3 0 00-.393.502l-4.244 4.243a3 3 0 00-.393.502l-2.828 2.828A3 3 0 004 17v2a2 2 0 002 2zm-7-4a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.empowerment')); ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo e(__('messages.empowerment_text')); ?>

                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced How It Works Section -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-black/10 rounded-full mb-6">
                    <span class="text-sm font-semibold text-black"><?php echo e(__('messages.how_it_works_badge')); ?></span>
                </div>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-6">
                    <?php echo e(__('messages.how_it_works_title')); ?>

                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e(__('messages.how_it_works_subtitle')); ?>

                </p>
            </div>
            
            <div class="relative">
                <!-- Connection line -->
                <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 transform -translate-y-1/2"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <div class="group text-center">
                        <div class="relative">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                <span class="text-3xl font-bold text-white">1</span>
                            </div>
                            <div class="hidden lg:block absolute top-12 left-full w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400"></div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.discover')); ?></h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            <?php echo e(__('messages.discover_text')); ?>

                        </p>
                    </div>
                    
                    <div class="group text-center">
                        <div class="relative">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                <span class="text-3xl font-bold text-white">2</span>
                            </div>
                            <div class="hidden lg:block absolute top-12 left-full w-full h-1 bg-gradient-to-r from-purple-400 to-pink-400"></div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.verify')); ?></h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            <?php echo e(__('messages.verify_text')); ?>

                        </p>
                    </div>
                    
                    <div class="group text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <span class="text-3xl font-bold text-white">3</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e(__('messages.collect')); ?></h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            <?php echo e(__('messages.collect_text')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Stats Section -->
    <section class="py-24 bg-black text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-black via-gray-900 to-black"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
                    <span class="text-sm font-semibold text-white"><?php echo e(__('messages.stats_badge')); ?></span>
                </div>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
                    <?php echo e(__('messages.stats_title')); ?>

                </h2>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    <?php echo e(__('messages.stats_subtitle')); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="group">
                    <div class="text-5xl md:text-6xl font-bold text-white mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?php echo e($stats['verified_artists']); ?>

                    </div>
                    <p class="text-white/70 text-lg"><?php echo e(__('messages.verified_artists')); ?></p>
                </div>
                <div class="group">
                    <div class="text-5xl md:text-6xl font-bold text-white mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?php echo e($stats['artworks_listed']); ?>

                    </div>
                    <p class="text-white/70 text-lg"><?php echo e(__('messages.artworks_listed')); ?></p>
                </div>
                <div class="group">
                    <div class="text-5xl md:text-6xl font-bold text-white mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?php echo e($stats['happy_collectors']); ?>

                    </div>
                    <p class="text-white/70 text-lg"><?php echo e(__('messages.happy_collectors')); ?></p>
                </div>
                <div class="group">
                    <div class="text-5xl md:text-6xl font-bold text-white mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?php echo e($stats['art_sales']); ?>

                    </div>
                    <p class="text-white/70 text-lg"><?php echo e(__('messages.art_sales')); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced CTA Section -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-100/30 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-black/10 rounded-full mb-6">
                <span class="text-sm font-semibold text-black"><?php echo e(__('messages.cta_badge')); ?></span>
            </div>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-6">
                <?php echo e(__('messages.cta_title')); ?>

            </h2>
            <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                <?php echo e(__('messages.cta_description')); ?>

            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="<?php echo e(route('register')); ?>?role=artist" 
                   class="group px-8 py-4 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <?php echo e(__('messages.join_as_artist')); ?>

                </a>
                <a href="<?php echo e(route('register')); ?>?role=collector" 
                   class="group px-8 py-4 border-2 border-black text-black font-semibold rounded-lg hover:bg-black hover:text-white transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <?php echo e(__('messages.join_as_collector')); ?>

                </a>
            </div>
        </div>
    </section>
</div>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\about.blade.php ENDPATH**/ ?>