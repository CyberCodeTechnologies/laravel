

<?php $__env->startSection('title', 'Edit Page Content - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Edit Page Content'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="<?php echo e(route('admin.page-contents.update', $pageContent)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="space-y-6">
                <!-- Page -->
                <div>
                    <label for="page" class="block text-sm font-medium text-gray-700 mb-2">Page <span class="text-red-500">*</span></label>
                    <select id="page" name="page" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Page</option>
                        <option value="home" <?php echo e(old('page', $pageContent->page) == 'home' ? 'selected' : ''); ?>>Home</option>
                        <option value="about" <?php echo e(old('page', $pageContent->page) == 'about' ? 'selected' : ''); ?>>About</option>
                        <option value="contact" <?php echo e(old('page', $pageContent->page) == 'contact' ? 'selected' : ''); ?>>Contact</option>
                        <option value="faq" <?php echo e(old('page', $pageContent->page) == 'faq' ? 'selected' : ''); ?>>FAQ</option>
                        <option value="privacy" <?php echo e(old('page', $pageContent->page) == 'privacy' ? 'selected' : ''); ?>>Privacy Policy</option>
                        <option value="terms" <?php echo e(old('page', $pageContent->page) == 'terms' ? 'selected' : ''); ?>>Terms of Service</option>
                    </select>
                    <?php $__errorArgs = ['page'];
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

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                    <input type="text" id="section" name="section" value="<?php echo e(old('section', $pageContent->section)); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., hero, features, mission">
                    <?php $__errorArgs = ['section'];
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

                <!-- Key -->
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 mb-2">Content Key <span class="text-red-500">*</span></label>
                    <input type="text" id="key" name="key" value="<?php echo e(old('key', $pageContent->key)); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., home_hero_title">
                    <p class="text-sm text-gray-500 mt-1">Unique identifier for this content piece</p>
                    <?php $__errorArgs = ['key'];
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

                <!-- English Content -->
                <div>
                    <label for="content_en" class="block text-sm font-medium text-gray-700 mb-2">English Content <span class="text-red-500">*</span></label>
                    <textarea id="content_en" name="content_en" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter English content"><?php echo e(old('content_en', $pageContent->content_en)); ?></textarea>
                    <?php $__errorArgs = ['content_en'];
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

                <!-- Myanmar Content -->
                <div>
                    <label for="content_my" class="block text-sm font-medium text-gray-700 mb-2">Myanmar Content</label>
                    <textarea id="content_my" name="content_my" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter Myanmar content (optional)"><?php echo e(old('content_my', $pageContent->content_my)); ?></textarea>
                    <?php $__errorArgs = ['content_my'];
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

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Content Type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="text" <?php echo e(old('type', $pageContent->type) == 'text' ? 'selected' : ''); ?>>Text</option>
                        <option value="html" <?php echo e(old('type', $pageContent->type) == 'html' ? 'selected' : ''); ?>>HTML</option>
                        <option value="image" <?php echo e(old('type', $pageContent->type) == 'image' ? 'selected' : ''); ?>>Image URL</option>
                    </select>
                    <?php $__errorArgs = ['type'];
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

                <!-- Active -->
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $pageContent->is_active) ? 'checked' : ''); ?>

                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1">Inactive content won't be displayed on the site</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" value="<?php echo e(old('sort_order', $pageContent->sort_order)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="<?php echo e(route('admin.page-contents.index')); ?>" 
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Content
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\page-contents\edit.blade.php ENDPATH**/ ?>