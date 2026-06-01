<?php $__env->startSection('title', __('messages.exhibitions_title')); ?>
<?php $__env->startSection('meta-description', __('messages.exhibitions_meta_description')); ?>
<?php $__env->startSection('meta-keywords', 'art exhibitions, gallery exhibitions, Myanmar art, contemporary art exhibitions, art shows'); ?>
<?php $__env->startSection('meta-image', asset('images/og-default.jpg')); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                <?php echo e(__('messages.exhibitions_hero_title')); ?>

            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                <?php echo e(__('messages.exhibitions_hero_description')); ?>

            </p>
        </div>
    </div>
</section>

<!-- Featured Exhibition -->
<?php if($featuredExhibition): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e(__('messages.featured_exhibition')); ?></h2>
            <div class="w-20 h-1 bg-red-600"></div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <div class="relative h-64 lg:h-auto">
                    <img src="<?php echo e($featuredExhibition->first_image_url); ?>" alt="<?php echo e($featuredExhibition->title); ?>" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo e($featuredExhibition->status_color); ?>-100 text-<?php echo e($featuredExhibition->status_color); ?>-800">
                            <?php echo e(ucfirst($featuredExhibition->status)); ?>

                        </span>
                    </div>
                </div>
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($featuredExhibition->title); ?></h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span><?php echo e($featuredExhibition->date_range); ?></span>
                        </div>
                        <?php if($featuredExhibition->venue): ?>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><?php echo e($featuredExhibition->full_location); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if($featuredExhibition->description): ?>
                    <p class="text-gray-600 mb-6 line-clamp-3"><?php echo e($featuredExhibition->description); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo e(route('exhibitions.show', $featuredExhibition->slug)); ?>" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <?php echo e(__('messages.view_exhibition')); ?>

                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Filters -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center gap-4">
            <form method="GET" action="<?php echo e(route('exhibitions.index')); ?>" class="flex-1 min-w-0">
                <div class="relative">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.search_exhibitions')); ?>" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
            
            <div class="flex gap-2">
                <a href="<?php echo e(route('exhibitions.index', ['status' => 'upcoming'])); ?>" class="px-4 py-2 rounded-lg <?php echo e(request('status') === 'upcoming' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.upcoming')); ?>

                </a>
                <a href="<?php echo e(route('exhibitions.index', ['status' => 'ongoing'])); ?>" class="px-4 py-2 rounded-lg <?php echo e(request('status') === 'ongoing' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.ongoing')); ?>

                </a>
                <a href="<?php echo e(route('exhibitions.index', ['status' => 'completed'])); ?>" class="px-4 py-2 rounded-lg <?php echo e(request('status') === 'completed' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.completed')); ?>

                </a>
            </div>
            
            <select onchange="window.location.href=this.value" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="<?php echo e(route('exhibitions.index', ['sort' => 'latest'])); ?>" <?php echo e(request('sort') === 'latest' ? 'selected' : ''); ?>><?php echo e(__('messages.latest')); ?></option>
                <option value="<?php echo e(route('exhibitions.index', ['sort' => 'oldest'])); ?>" <?php echo e(request('sort') === 'oldest' ? 'selected' : ''); ?>><?php echo e(__('messages.oldest')); ?></option>
                <option value="<?php echo e(route('exhibitions.index', ['sort' => 'start_date'])); ?>" <?php echo e(request('sort') === 'start_date' ? 'selected' : ''); ?>><?php echo e(__('messages.start_date')); ?></option>
                <option value="<?php echo e(route('exhibitions.index', ['sort' => 'title'])); ?>" <?php echo e(request('sort') === 'title' ? 'selected' : ''); ?>><?php echo e(__('messages.title')); ?></option>
            </select>
        </div>
    </div>
</section>

<!-- Exhibitions Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($exhibitions->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $exhibitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exhibition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="relative h-48">
                            <img src="<?php echo e($exhibition->first_image_url); ?>" alt="<?php echo e($exhibition->title); ?>" class="w-full h-full object-cover">
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?php echo e($exhibition->status_color); ?>-100 text-<?php echo e($exhibition->status_color); ?>-800">
                                    <?php echo e(ucfirst($exhibition->status)); ?>

                                </span>
                            </div>
                            <?php if($exhibition->is_featured): ?>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <?php echo e(__('messages.featured')); ?>

                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1"><?php echo e($exhibition->title); ?></h3>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span><?php echo e($exhibition->date_range); ?></span>
                                </div>
                                <?php if($exhibition->venue): ?>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="line-clamp-1"><?php echo e($exhibition->venue); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if($exhibition->artworks_count > 0): ?>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><?php echo e($exhibition->artworks_count); ?> <?php echo e(__('messages.artworks')); ?></span>
                            </div>
                            <?php endif; ?>
                            <a href="<?php echo e(route('exhibitions.show', $exhibition->slug)); ?>" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                                <?php echo e(__('messages.view_details')); ?>

                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                <?php echo e($exhibitions->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e(__('messages.no_exhibitions')); ?></h3>
                <p class="text-gray-600"><?php echo e(__('messages.no_exhibitions_description')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\exhibitions\index.blade.php ENDPATH**/ ?>