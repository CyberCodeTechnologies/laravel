<?php $__env->startSection('title', 'Edit Resale Listing - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'Edit your artwork resale listing.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Edit Resale Listing</h1>
        <p class="text-gray-300">Update your listing details</p>
    </div>
</section>

<!-- Form -->
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Artwork Preview -->
            <div class="flex gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
                <img src="<?php echo e($resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                     alt="{{ $resale->artwork->title); ?>" 
                     class="w-24 h-24 object-cover rounded">
                <div>
                    <h3 class="font-serif font-bold"><?php echo e($resale->artwork->title); ?></h3>
                    <p class="text-gray-600 text-sm">by <?php echo e($resale->artwork->artist->name ?? 'Unknown'); ?></p>
                    <p class="text-gray-500 text-sm mt-1">Current Status: <span class="capitalize font-medium"><?php echo e($resale->status); ?></span></p>
                </div>
            </div>

            <form action="<?php echo e(route('resales.update', $resale)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-6">
                    <!-- Asking Price -->
                    <div>
                        <label for="asking_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Asking Price (USD) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" 
                                   id="asking_price" 
                                   name="asking_price" 
                                   step="0.01" 
                                   min="1"
                                   required
                                   value="<?php echo e(old('asking_price', $resale->asking_price)); ?>"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <?php $__errorArgs = ['asking_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                            Artwork Condition <span class="text-red-500">*</span>
                        </label>
                        <select id="condition" 
                                name="condition" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select condition</option>
                            <option value="excellent" <?php echo e($resale->condition === 'excellent' ? 'selected' : ''); ?>>Excellent - Like new</option>
                            <option value="very_good" <?php echo e($resale->condition === 'very_good' ? 'selected' : ''); ?>>Very Good - Minor wear</option>
                            <option value="good" <?php echo e($resale->condition === 'good' ? 'selected' : ''); ?>>Good - Some wear visible</option>
                            <option value="fair" <?php echo e($resale->condition === 'fair' ? 'selected' : ''); ?>>Fair - Significant wear</option>
                        </select>
                        <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Condition Description -->
                    <div>
                        <label for="condition_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Condition Description
                        </label>
                        <textarea id="condition_description" 
                                  name="condition_description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('condition_description', $resale->condition_description)); ?></textarea>
                    </div>

                    <!-- Reason for Selling -->
                    <div>
                        <label for="reason_for_selling" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Selling
                        </label>
                        <textarea id="reason_for_selling" 
                                  name="reason_for_selling" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('reason_for_selling', $resale->reason_for_selling)); ?></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <a href="<?php echo e(route('collector.resales')); ?>" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                        Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\resales\edit.blade.php ENDPATH**/ ?>