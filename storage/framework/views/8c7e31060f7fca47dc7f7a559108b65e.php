<?php $__env->startSection('title', 'Artists I Follow - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage the artists you follow.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Artists I Follow</h1>
        <p class="text-gray-300">Stay updated with your favorite artists</p>
    </div>
</section>

<!-- Artists Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($following->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $following; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $artist = $follow->following; ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="<?php echo e($artist->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($artist->name) . '&background=random&size=128'); ?>" 
                                 alt="<?php echo e($artist->name); ?>" 
                                 class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <h3 class="font-serif font-bold text-lg"><?php echo e($artist->name); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo e($artist->artworks_count ?? 0); ?> artworks</p>
                            </div>
                        </div>
                        
                        <?php if($artist->bio): ?>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo e($artist->bio); ?></p>
                        <?php endif; ?>
                        
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('public.artists.show', $artist->slug ?? $artist->id)); ?>"
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                                View Profile
                            </a>
                            <form action="<?php echo e(route('artist.follow', $artist)); ?>" method="POST" class="flex-1">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                    Unfollow
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8">
                <?php echo e($following->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Not following any artists yet</h3>
                <p class="text-gray-500 mb-6">Follow artists to stay updated with their latest works</p>
                <a href="<?php echo e(route('public.artists.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Discover Artists
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\collector\following.blade.php ENDPATH**/ ?>