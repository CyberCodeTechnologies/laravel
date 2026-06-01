<?php $__env->startSection('title', 'Categories - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Browse artwork categories at Panchi Gallery. Find your perfect piece from our curated collection of Myanmar art.'); ?>
<?php $__env->startSection('meta-keywords', 'art categories, Myanmar art categories, painting styles, art types, art mediums, browse art by category, Panchi Gallery categories'); ?>
<?php $__env->startSection('meta-image', asset('images/og-default.jpg')); ?>

<?php $__env->startSection('schema'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Art Categories - Panchi Gallery",
    "url": "<?php echo e(route('categories.index')); ?>",
    "description": "Browse artwork categories at Panchi Gallery. Find your perfect piece from our curated collection of Myanmar art."
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Art Categories</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Explore our diverse collection of artworks organized by category
            </p>
        </div>

        <!-- Categories Grid -->
        <?php if(isset($categories) && $categories->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                        <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="block">
                            <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-black transition-colors">
                                        <?php echo e($category->name); ?>

                                    </h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600">
                                    <?php echo e($category->artworks()->count()); ?> artworks
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-600 mb-6">Categories will appear here once they are created.</p>
                <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.categories')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Manage Categories
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\categories\index.blade.php ENDPATH**/ ?>