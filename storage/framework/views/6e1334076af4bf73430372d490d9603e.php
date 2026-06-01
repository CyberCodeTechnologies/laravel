<?php $__env->startSection('title', $blog->title); ?>
<?php $__env->startSection('meta-description', $blog->meta_description ?? Str::limit(strip_tags($blog->excerpt), 160)); ?>
<?php $__env->startSection('meta-keywords', $blog->meta_keywords ?? 'art blog, ' . $blog->category . ', ' . $blog->title); ?>
<?php $__env->startSection('meta-image', $blog->featured_image_url); ?>

<?php $__env->startSection('schema'); ?>
<?php if (isset($component)) { $__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup','data' => ['type' => 'blog','data' => $blog]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema-markup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'blog','data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blog)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d)): ?>
<?php $attributes = $__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d; ?>
<?php unset($__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d)): ?>
<?php $component = $__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d; ?>
<?php unset($__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="relative h-96">
    <img src="<?php echo e($blog->featured_image_url); ?>" alt="<?php echo e($blog->title); ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 p-8">
        <div class="max-w-7xl mx-auto">
            <?php if($blog->category): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600 text-white mb-4">
                <?php echo e($blog->category); ?>

            </span>
            <?php endif; ?>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?php echo e($blog->title); ?></h1>
            <div class="flex flex-wrap items-center gap-4 text-white/80">
                <?php if($blog->author): ?>
                <div class="flex items-center">
                    <img src="<?php echo e($blog->author->avatar_url); ?>" alt="<?php echo e($blog->author->name); ?>" class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <p class="font-medium"><?php echo e($blog->author->name); ?></p>
                        <p class="text-sm"><?php echo e($blog->author->specialization ?? __('messages.author')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span><?php echo e($blog->published_at?->format('F j, Y')); ?></span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?php echo e($blog->reading_time); ?> min read</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span><?php echo e($blog->views_count); ?> views</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tags -->
        <?php if($blog->tags && count($blog->tags) > 0): ?>
        <div class="flex flex-wrap gap-2 mb-8">
            <?php $__currentLoopData = $blog->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                #<?php echo e($tag); ?>

            </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="prose prose-lg max-w-none text-gray-700 mb-12">
            <?php echo $blog->content; ?>

        </div>
        
        <!-- Share -->
        <div class="border-t pt-8 mb-12">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e(__('messages.share_post')); ?></h3>
            <div class="flex gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(route('blog.show', $blog->slug))); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(route('blog.show', $blog->slug))); ?>&text=<?php echo e(urlencode($blog->title)); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-500 text-white hover:bg-sky-600 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a href="https://www.linkedin.com/shareArticle?url=<?php echo e(urlencode(route('blog.show', $blog->slug))); ?>&title=<?php echo e(urlencode($blog->title)); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-700 text-white hover:bg-blue-800 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
                <a href="mailto:?subject=<?php echo e(urlencode($blog->title)); ?>&body=<?php echo e(urlencode(route('blog.show', $blog->slug))); ?>" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-600 text-white hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Author Bio -->
        <?php if($blog->author): ?>
        <div class="bg-gray-50 rounded-xl p-8 mb-12">
            <div class="flex items-start">
                <img src="<?php echo e($blog->author->avatar_url); ?>" alt="<?php echo e($blog->author->name); ?>" class="w-20 h-20 rounded-full mr-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($blog->author->name); ?></h3>
                    <p class="text-gray-600 mb-2"><?php echo e($blog->author->specialization ?? __('messages.author')); ?></p>
                    <?php if($blog->author->bio): ?>
                    <p class="text-gray-600"><?php echo e(Str::limit($blog->author->bio, 200)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Related Posts -->
<?php if($relatedBlogs && $relatedBlogs->count() > 0): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8"><?php echo e(__('messages.related_posts')); ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php $__currentLoopData = $relatedBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <a href="<?php echo e(route('blog.show', $related->slug)); ?>">
                    <div class="relative h-48">
                        <img src="<?php echo e($related->featured_image_url); ?>" alt="<?php echo e($related->title); ?>" class="w-full h-full object-cover">
                    </div>
                </a>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                        <a href="<?php echo e(route('blog.show', $related->slug)); ?>" class="hover:text-red-600 transition-colors">
                            <?php echo e($related->title); ?>

                        </a>
                    </h3>
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><?php echo e($related->reading_time); ?> min read</span>
                    </div>
                    <a href="<?php echo e(route('blog.show', $related->slug)); ?>" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                        <?php echo e(__('messages.read_more')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/blog/show.blade.php ENDPATH**/ ?>