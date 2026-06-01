<?php $__env->startSection('title', 'My Artworks - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your owned artworks.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">My Artworks</h1>
        <p class="text-gray-300">View and manage your collection</p>
    </div>
</section>

<!-- Artworks Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($ownerships->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $ownerships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <img src="<?php echo e($ownership->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                         alt="<?php echo e($ownership->artwork->title); ?>" 
                         class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="font-serif text-lg font-bold mb-1"><?php echo e($ownership->artwork->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-4">by <?php echo e($ownership->artwork->artist->name ?? 'Unknown'); ?></p>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span>Acquired:</span>
                                <span><?php echo e($ownership->acquired_at?->format('M d, Y') ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Certificate:</span>
                                <span class="text-green-600"><?php echo e($ownership->certificate ? 'Verified' : 'N/A'); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('public.artworks.show', $ownership->artwork)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                View
                            </a>
                            <?php if($ownership->certificate): ?>
                            <a href="<?php echo e(route('ownership.certificate', $ownership)); ?>" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                                Certificate
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
                <p class="text-gray-500 mb-4">No artworks in your collection yet.</p>
                <a href="<?php echo e(route('public.artworks.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\ownership\my-artworks.blade.php ENDPATH**/ ?>