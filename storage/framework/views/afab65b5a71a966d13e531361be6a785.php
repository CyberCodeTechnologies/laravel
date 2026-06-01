

<?php $__env->startSection('title', 'Create FAQ - Admin'); ?>
<?php $__env->startSection('meta-description', 'Create new FAQ for Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Create FAQ'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-gray-900">Dashboard</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="<?php echo e(route('admin.support')); ?>" class="hover:text-gray-900">Support</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="<?php echo e(route('admin.support.faq')); ?>" class="hover:text-gray-900">FAQ</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-900">Create</span>
            </li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-900">Create New FAQ</h2>
            <p class="text-sm text-gray-600 mt-1">Add a new frequently asked question to help users find answers quickly.</p>
        </div>

        <form action="<?php echo e(route('admin.support.faq.store')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>

            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-gray-400">(Optional)</span>
                </label>
                <input 
                    type="text" 
                    id="category" 
                    name="category" 
                    value="<?php echo e(old('category')); ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., General, Artists, Collectors, Payments"
                >
                <p class="mt-1 text-sm text-gray-500">Group related FAQs together by category</p>
            </div>

            <!-- English Content -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-globe-americas mr-2 text-blue-600"></i>English Content
                </h3>
                
                <!-- Question English -->
                <div class="mb-6">
                    <label for="question_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="question_en" 
                        name="question_en" 
                        value="<?php echo e(old('question_en')); ?>"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the FAQ question in English"
                    >
                </div>

                <!-- Answer English -->
                <div class="mb-6">
                    <label for="answer_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Answer <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="answer_en" 
                        name="answer_en" 
                        rows="5" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the detailed answer in English"
                    ><?php echo e(old('answer_en')); ?></textarea>
                </div>
            </div>

            <!-- Myanmar Content -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-globe-asia mr-2 text-green-600"></i>Myanmar Content
                </h3>
                
                <!-- Question Myanmar -->
                <div class="mb-6">
                    <label for="question_my" class="block text-sm font-medium text-gray-700 mb-2">
                        မေးခွန်း <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input 
                        type="text" 
                        id="question_my" 
                        name="question_my" 
                        value="<?php echo e(old('question_my')); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the FAQ question in Myanmar"
                    >
                </div>

                <!-- Answer Myanmar -->
                <div class="mb-6">
                    <label for="answer_my" class="block text-sm font-medium text-gray-700 mb-2">
                        အဖြေ <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea 
                        id="answer_my" 
                        name="answer_my" 
                        rows="5" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the detailed answer in Myanmar"
                    ><?php echo e(old('answer_my')); ?></textarea>
                </div>
            </div>

            <!-- Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-gray-600"></i>Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input 
                            type="number" 
                            id="order" 
                            name="order" 
                            value="<?php echo e(old('order', 0)); ?>"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    </div>

                    <!-- Published Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Published Status
                        </label>
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_published" 
                                name="is_published" 
                                value="1"
                                <?php echo e(old('is_published', true) ? 'checked' : ''); ?>

                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                Published (visible on public FAQ page)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="<?php echo e(route('admin.support.faq')); ?>" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Create FAQ
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\support\faq\create.blade.php ENDPATH**/ ?>