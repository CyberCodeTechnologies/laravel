

<?php $__env->startSection('title', 'Collections Management - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Manage curated collections in Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Collections'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Collections</h1>
                    <p class="text-sm text-gray-500">Curated artwork collections</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('admin.collections.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Create Collection
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <?php if($collection->featured_image): ?>
                        <img src="<?php echo e(asset('storage/' . $collection->featured_image)); ?>" alt="<?php echo e($collection->title); ?>" 
                             class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-images text-gray-400 text-3xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e($collection->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-4"><?php echo e(Str::limit($collection->description, 100)); ?></p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-image mr-1"></i><?php echo e($collection->artworks->count()); ?> artworks</span>
                            <span><i class="fas fa-user mr-1"></i><?php echo e($collection->curator->name); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($collection->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($collection->is_featured ? 'Featured' : 'Standard'); ?>

                            </span>
                            
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('admin.collections.edit', $collection)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.collections.delete', $collection)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this collection?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No collections found</h3>
                    <p class="text-gray-500">Get started by creating your first collection.</p>
                    <a href="<?php echo e(route('admin.collections.create')); ?>" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Create Collection
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($collections->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($collections->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\collections\index.blade.php ENDPATH**/ ?>