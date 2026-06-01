<?php $__env->startSection('title', 'Edit Artwork - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Edit Artwork</h1>
            <p class="text-gray-600 mt-2">Update your artwork details</p>
        </div>

        <form action="<?php echo e(route('artist.artworks.update', $artwork)); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-8">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Artwork Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="<?php echo e(old('title', $artwork->title)); ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                        <option value="">Select a category</option>
                        <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $artwork->category_id) == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"><?php echo e(old('description', $artwork->description)); ?></textarea>
                </div>

                <!-- Medium & Dimensions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">Medium</label>
                        <select id="medium" name="medium" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                            <option value="">Select a medium</option>
                            <option value="oil" <?php echo e(old('medium', $artwork->medium) == 'oil' ? 'selected' : ''); ?>>Oil</option>
                            <option value="acrylic" <?php echo e(old('medium', $artwork->medium) == 'acrylic' ? 'selected' : ''); ?>>Acrylic</option>
                            <option value="watercolor" <?php echo e(old('medium', $artwork->medium) == 'watercolor' ? 'selected' : ''); ?>>Watercolor</option>
                            <option value="digital" <?php echo e(old('medium', $artwork->medium) == 'digital' ? 'selected' : ''); ?>>Digital</option>
                            <option value="photography" <?php echo e(old('medium', $artwork->medium) == 'photography' ? 'selected' : ''); ?>>Photography</option>
                            <option value="sculpture" <?php echo e(old('medium', $artwork->medium) == 'sculpture' ? 'selected' : ''); ?>>Sculpture</option>
                            <option value="mixed_media" <?php echo e(old('medium', $artwork->medium) == 'mixed_media' ? 'selected' : ''); ?>>Mixed Media</option>
                            <option value="other" <?php echo e(old('medium', $artwork->medium) == 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions</label>
                        <input type="text" id="dimensions" name="dimensions" value="<?php echo e(old('dimensions', $artwork->dimensions)); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                    </div>
                </div>

                <!-- Price & Year -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" value="<?php echo e(old('price', $artwork->price)); ?>" required min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year Created</label>
                        <input type="number" id="year" name="year" value="<?php echo e(old('year', $artwork->year)); ?>" min="1900" max="<?php echo e(date('Y')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                    </div>
                </div>

                <!-- Current Images -->
                <?php if($artwork->images && count($artwork->images) > 0): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <?php $__currentLoopData = $artwork->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group">
                                    <img src="<?php echo e($image); ?>" alt="" class="w-full h-32 object-cover rounded-lg">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black">
                </div>

                <!-- Submit -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="<?php echo e(route('artist.artworks')); ?>" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                        Update Artwork
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\artworks-edit.blade.php ENDPATH**/ ?>