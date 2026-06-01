<?php $__env->startSection('title', 'Collection Analytics - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View insights and analytics about your art collection.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Collection Analytics</h1>
        <p class="text-gray-300">Insights about your art collection</p>
    </div>
</section>

<!-- Analytics Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Total Artworks</p>
                <p class="text-2xl font-bold"><?php echo e($stats['total_artworks'] ?? 0); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Collection Value</p>
                <p class="text-2xl font-bold">$<?php echo e(number_format($stats['total_value'] ?? 0, 0)); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Avg. Purchase Price</p>
                <p class="text-2xl font-bold">$<?php echo e(number_format($stats['avg_purchase_price'] ?? 0, 0)); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Categories</p>
                <p class="text-2xl font-bold"><?php echo e(count($collectionByCategory ?? [])); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Collection by Category -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-6">Collection by Category</h2>
                <?php if(count($collectionByCategory ?? []) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $collectionByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700"><?php echo e($category); ?></span>
                            <div class="flex items-center gap-4">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gray-900 h-2 rounded-full" style="width: <?php echo e(($count / array_sum($collectionByCategory)) * 100); ?>%"></div>
                                </div>
                                <span class="text-sm text-gray-600 w-8"><?php echo e($count); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No category data available</p>
                <?php endif; ?>
            </div>

            <!-- Monthly Purchases -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-6">Purchase Activity</h2>
                <?php if(count($monthlyPurchases ?? []) > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $monthlyPurchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-700"><?php echo e($month); ?></span>
                            <div class="text-right">
                                <p class="font-semibold">$<?php echo e(number_format($data['amount'] ?? 0, 0)); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($data['count'] ?? 0); ?> artwork<?php echo e(($data['count'] ?? 0) > 1 ? 's' : ''); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No purchase history available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Valued Artworks -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
            <h2 class="text-lg font-serif font-bold mb-6">Most Valuable Artworks</h2>
            <?php if(count($topValuedArtworks ?? []) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $topValuedArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                        <img src="<?php echo e($artwork->image_url ?? asset('images/placeholder-artwork.jpg')); ?>" 
                             alt="<?php echo e($artwork->title); ?>" 
                             class="w-20 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold truncate"><?php echo e($artwork->title); ?></h3>
                            <p class="text-sm text-gray-600">by <?php echo e($artwork->artist->name ?? 'Unknown'); ?></p>
                            <p class="text-sm font-medium mt-1">$<?php echo e(number_format($artwork->current_value ?? $artwork->purchase_price, 2)); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No artworks in your collection yet</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\collector\analytics.blade.php ENDPATH**/ ?>