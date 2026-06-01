<?php $__env->startSection('title', 'Exhibitions Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Exhibitions Management</h1>
    <p class="text-gray-600">Manage all exhibitions</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo e(route('admin.exhibitions.index')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search exhibitions..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
        </div>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <option value="">All Status</option>
            <option value="upcoming" <?php echo e(request('status') === 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
            <option value="ongoing" <?php echo e(request('status') === 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
        </select>
        <select name="is_published" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <option value="">All</option>
            <option value="1" <?php echo e(request('is_published') === '1' ? 'selected' : ''); ?>>Published</option>
            <option value="0" <?php echo e(request('is_published') === '0' ? 'selected' : ''); ?>>Unpublished</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            Filter
        </button>
        <a href="<?php echo e(route('admin.exhibitions.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            Clear
        </a>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Total: <?php echo e($exhibitions->total()); ?> exhibitions
        </div>
        <a href="<?php echo e(route('admin.exhibitions.create')); ?>" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            Add Exhibition
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artworks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $exhibitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exhibition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="<?php echo e($exhibition->first_image_url); ?>" alt="<?php echo e($exhibition->title); ?>" class="w-16 h-16 object-cover rounded-lg">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($exhibition->title); ?></div>
                        <?php if($exhibition->venue): ?>
                        <div class="text-sm text-gray-500"><?php echo e($exhibition->venue); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo e($exhibition->date_range); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo e($exhibition->status_color); ?>-100 text-<?php echo e($exhibition->status_color); ?>-800">
                            <?php echo e(ucfirst($exhibition->status)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($exhibition->is_published): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Yes</span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo e($exhibition->artworks_count ?? 0); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?php echo e(route('exhibitions.show', $exhibition->slug)); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <a href="<?php echo e(route('admin.exhibitions.edit', $exhibition)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="<?php echo e(route('admin.exhibitions.destroy', $exhibition)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this exhibition?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-4 border-t">
        <?php echo e($exhibitions->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\exhibitions\index.blade.php ENDPATH**/ ?>