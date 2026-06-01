

<?php $__env->startSection('title', 'Homepage Management - Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Homepage Management'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Homepage Sections</h1>
            <p class="text-gray-600 mt-1">Manage all homepage content sections</p>
        </div>
        <a href="<?php echo e(route('admin.frontend.dashboard')); ?>" class="btn-elegant px-4 py-2 rounded-lg text-sm">
            Back to Dashboard
        </a>
    </div>

    <!-- Sections Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Hero Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Hero Section
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Main banner with headline, subtitle, and call-to-action</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['hero']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No hero content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'hero')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit Hero Section
                    </button>
                </form>
            </div>
        </div>

        <!-- Featured Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Featured Artworks
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Showcase featured artworks and collections</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['featured']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No featured content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'featured')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit Featured Section
                    </button>
                </form>
            </div>
        </div>

        <!-- About Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    About Gallery
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Gallery description, mission, and values</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['about']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No about content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'about')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit About Section
                    </button>
                </form>
            </div>
        </div>

        <!-- Artists Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-pink-600 to-rose-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Featured Artists
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Showcase featured artists and their work</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['artists']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No artists content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'artists')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit Artists Section
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Art Categories
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Display art categories and collections</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No categories content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'categories')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit Categories Section
                    </button>
                </form>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="card-luxury rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-pink-600 p-4">
                <h3 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    Call-to-Action
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Final conversion section with strong CTA</p>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $sections['cta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium"><?php echo e($content->key); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($content->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm italic">No CTA content configured</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.frontend.homepage.update', 'cta')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-luxury w-full py-2 rounded-lg text-white text-sm">
                        Edit CTA Section
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Update Section -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="font-semibold text-lg mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('admin.page-contents.index')); ?>?page=home" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                View All Homepage Content
            </a>
            <a href="<?php echo e(route('admin.cache.clear')); ?>" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                Clear Cache
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\frontend\homepage.blade.php ENDPATH**/ ?>