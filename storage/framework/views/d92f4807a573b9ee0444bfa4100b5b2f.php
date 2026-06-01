

<?php $__env->startSection('title', 'Pending Artists - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Review and approve pending artist applications in Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Pending Artists'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pending Artists</h1>
                    <p class="text-sm text-gray-500">Review and approve artist applications</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Artists List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Artist
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Applied
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if($artist->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $artist->avatar)); ?>" alt="<?php echo e($artist->name); ?>" 
                                                 class="h-10 w-10 rounded-full object-cover mr-3">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($artist->name); ?></div>
                                            <div class="text-sm text-gray-500">{{ $artist->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($artist->email); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500"><?php echo e(Str::limit($artist->bio, 100)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($artist->created_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?php echo e(route('admin.users.edit', $artist->id)); ?>"
                                           class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition" title="Review Artist">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span>Review</span>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.artists.approve', $artist->id)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition" onclick="return confirm('Approve this artist?')" title="Approve Artist">
                                                <i class="fas fa-check mr-1"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.artists.reject', $artist->id)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Reject this artist?')" title="Reject Artist">
                                                <i class="fas fa-times mr-1"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No pending artists found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($artists->hasPages()): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <?php echo e($artists->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\artists\pending.blade.php ENDPATH**/ ?>