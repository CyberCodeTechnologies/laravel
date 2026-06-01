<?php $__env->startSection('title', 'My Collection - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your art collection. Track ownership, certificates, and resale value.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl font-bold mb-2">My Collection</h1>
                <p class="text-gray-300">Manage your artworks and ownership certificates</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-gray-300"><?php echo e($ownerships->total() ?? 0); ?> artworks owned</span>
            </div>
        </div>
    </div>
</section>

<!-- Collection Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($ownerships->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $ownerships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="relative">
                        <img src="<?php echo e($ownership->artwork->image_url ?? asset('images/placeholder-artwork.jpg')); ?>" 
                             alt="<?php echo e($ownership->artwork->title); ?>" 
                             class="w-full h-64 object-cover">
                        <?php if($ownership->artwork->is_resale_available): ?>
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-full">
                            Listed for Resale
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="font-serif text-xl font-bold mb-1"><?php echo e($ownership->artwork->title); ?></h3>
                        <p class="text-gray-600 mb-4">by <?php echo e($ownership->artwork->artist->name ?? 'Unknown'); ?></p>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span>Acquired:</span>
                                <span><?php echo e($ownership->acquired_at?->format('M d, Y') ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Purchase Price:</span>
                                <span>$<?php echo e(number_format($ownership->purchase_price ?? 0, 2)); ?></span>
                            </div>
                            <?php if($ownership->certificate): ?>
                            <div class="flex justify-between">
                                <span>Certificate:</span>
                                <span class="text-green-600">Verified</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('public.artworks.show', $ownership->artwork)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                View Artwork
                            </a>
                            <?php if($ownership->certificate): ?>
                            <a href="<?php echo e(route('collector.ownership.certificate', $ownership)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                Certificate
                            </a>
                            <?php endif; ?>
                            <?php if(!$ownership->artwork->is_resale_available): ?>
                            <a href="<?php echo e(route('collector.resales.create', $ownership->artwork)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                                Resell
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8">
                <?php echo e($ownerships->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No artworks in your collection yet</h3>
                <p class="text-gray-500 mb-6">Start building your collection by browsing our curated artworks</p>
                <a href="<?php echo e(route('public.artworks.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\collector\artworks.blade.php ENDPATH**/ ?>