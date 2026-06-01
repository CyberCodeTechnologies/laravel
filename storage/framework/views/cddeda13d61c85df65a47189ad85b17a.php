<?php $__env->startSection('title', 'Ownership Transfer - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Transfer Ownership</h1>
            <p class="text-gray-600 mt-2">Transfer artwork ownership to a new collector</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8">
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-4">Artwork Details</h3>
                <div class="flex items-center gap-4">
                    <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" alt="" class="w-24 h-24 rounded object-cover">
                    <div>
                        <h4 class="font-semibold text-lg text-gray-900"><?php echo e($artwork->title); ?></h4>
                        <p class="text-gray-500"><?php echo e($artwork->artist->name ?? 'Unknown Artist'); ?></p>
                        <p class="text-gray-900 font-medium mt-1">$<?php echo e(number_format($artwork->price, 2)); ?></p>
                    </div>
                </div>
            </div>

            <form action="<?php echo e(route('collector.ownership.transfer', $artwork)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="new_owner_name" class="block text-sm font-medium text-gray-700 mb-2">New Owner Name <span class="text-red-500">*</span></label>
                    <input type="text" id="new_owner_name" name="new_owner_name" value="<?php echo e(old('new_owner_name')); ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                </div>

                <div>
                    <label for="new_owner_email" class="block text-sm font-medium text-gray-700 mb-2">New Owner Email <span class="text-red-500">*</span></label>
                    <input type="email" id="new_owner_email" name="new_owner_email" value="<?php echo e(old('new_owner_email')); ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                </div>

                <div>
                    <label for="transfer_price" class="block text-sm font-medium text-gray-700 mb-2">Transfer Price (if applicable)</label>
                    <input type="number" id="transfer_price" name="transfer_price" value="<?php echo e(old('transfer_price')); ?>" min="0" step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                           placeholder="Leave blank if gifted">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                              placeholder="Any additional information about this transfer"><?php echo e(old('notes')); ?></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="confirm" name="confirm" required class="rounded border-gray-300">
                    <label for="confirm" class="text-sm text-gray-700">
                        I confirm that I want to transfer ownership of this artwork. This action cannot be undone.
                    </label>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="<?php echo e(route('collector.artworks')); ?>" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition" onclick="return confirm('Are you sure you want to transfer ownership?')">
                        Transfer Ownership
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\ownership\transfer-document.blade.php ENDPATH**/ ?>