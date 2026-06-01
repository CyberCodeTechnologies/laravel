<?php $__env->startSection('title', __('messages.artwork_create.title', ['default' => 'Create New Artwork']) . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.artwork_create.meta_description', ['default' => 'Upload your artwork to Panchi Gallery. Share your art with collectors worldwide and get verified certificates of authenticity.'])); ?>

<?php $__env->startSection('content'); ?>
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    <?php echo e(__('messages.artwork_create.title', ['default' => 'Create New Artwork'])); ?>

                </h1>
                <p class="text-gray-300">
                    <?php echo e(__('messages.artwork_create.subtitle', ['default' => 'Share your masterpiece with collectors worldwide'])); ?>

                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','href' => ''.e(route('artist.artworks')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','href' => ''.e(route('artist.artworks')).'']); ?>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <?php echo e(__('messages.artwork_create.back_to_artworks', ['default' => 'Back to Artworks'])); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium">1</span>
                    <span class="ml-2 text-sm font-medium text-gray-900"><?php echo e(__('messages.artwork_create.step_details', ['default' => 'Details'])); ?></span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">2</span>
                    <span class="ml-2 text-sm font-medium text-gray-500"><?php echo e(__('messages.artwork_create.step_images', ['default' => 'Images'])); ?></span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">3</span>
                    <span class="ml-2 text-sm font-medium text-gray-500"><?php echo e(__('messages.artwork_create.step_review', ['default' => 'Review'])); ?></span>
                </div>
            </div>
        </div>

        <!-- Error Summary -->
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold"><?php echo e(__('messages.artwork_create.errors_title', ['default' => 'Please fix the following errors:'])); ?></p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('artist.artworks.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <?php echo csrf_field(); ?>

            <!-- Section: Basic Information -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">1</span>
                    <?php echo e(__('messages.artwork_create.basic_info', ['default' => 'Basic Information'])); ?>

                </h2>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.title_label', ['default' => 'Artwork Title'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="<?php echo e(old('title')); ?>" required maxlength="255"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="<?php echo e(__('messages.artwork_create.title_placeholder', ['default' => 'Enter a compelling title for your artwork'])); ?>">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.category_label', ['default' => 'Category'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition bg-white">
                            <option value=""><?php echo e(__('messages.artwork_create.select_category', ['default' => 'Select a category'])); ?></option>
                            <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.description_label', ['default' => 'Description'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required maxlength="2000"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition resize-none"
                                  placeholder="<?php echo e(__('messages.artwork_create.description_placeholder', ['default' => 'Describe your artwork, inspiration, techniques used, and any story behind it...'])); ?>"><?php echo e(old('description')); ?></textarea>
                        <div class="flex justify-between mt-1">
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php else: ?>
                                <p></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-sm text-gray-500"><span id="desc-count">0</span> / 2000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Artwork Details -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">2</span>
                    <?php echo e(__('messages.artwork_create.artwork_details', ['default' => 'Artwork Details'])); ?>

                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Medium -->
                    <div>
                        <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.medium_label', ['default' => 'Medium'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <select id="medium" name="medium" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition bg-white">
                            <option value=""><?php echo e(__('messages.artwork_create.select_medium', ['default' => 'Select medium'])); ?></option>
                            <option value="oil" <?php echo e(old('medium') == 'oil' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.oil', ['default' => 'Oil'])); ?></option>
                            <option value="acrylic" <?php echo e(old('medium') == 'acrylic' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.acrylic', ['default' => 'Acrylic'])); ?></option>
                            <option value="watercolor" <?php echo e(old('medium') == 'watercolor' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.watercolor', ['default' => 'Watercolor'])); ?></option>
                            <option value="digital" <?php echo e(old('medium') == 'digital' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.digital', ['default' => 'Digital'])); ?></option>
                            <option value="photography" <?php echo e(old('medium') == 'photography' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.photography', ['default' => 'Photography'])); ?></option>
                            <option value="sculpture" <?php echo e(old('medium') == 'sculpture' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.sculpture', ['default' => 'Sculpture'])); ?></option>
                            <option value="mixed_media" <?php echo e(old('medium') == 'mixed_media' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.mixed_media', ['default' => 'Mixed Media'])); ?></option>
                            <option value="traditional" <?php echo e(old('medium') == 'traditional' ? 'selected' : ''); ?>><?php echo e(__('messages.mediums.traditional', ['default' => 'Traditional'])); ?></option>
                        </select>
                        <?php $__errorArgs = ['medium'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Dimensions -->
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.dimensions_label', ['default' => 'Dimensions'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="dimensions" name="dimensions" value="<?php echo e(old('dimensions')); ?>" required maxlength="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="<?php echo e(__('messages.artwork_create.dimensions_placeholder', ['default' => 'e.g., 24 x 36 inches'])); ?>">
                        <?php $__errorArgs = ['dimensions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.year_label', ['default' => 'Year Created'])); ?>

                        </label>
                        <input type="number" id="year" name="year" value="<?php echo e(old('year', date('Y'))); ?>" min="1900" max="<?php echo e(date('Y')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="<?php echo e(date('Y')); ?>">
                        <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.price_label', ['default' => 'Price (USD)'])); ?> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500">$</span>
                            <input type="number" id="price" name="price" value="<?php echo e(old('price')); ?>" required min="0" step="0.01"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                                   placeholder="0.00">
                        </div>
                        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                    <!-- Stock/Quantity -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.stock_label', ['default' => 'Stock Quantity'])); ?>

                        </label>
                        <input type="number" id="stock" name="stock" value="<?php echo e(old('stock', 1)); ?>" min="1" max="999"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="1">
                        <p class="mt-1 text-xs text-gray-500"><?php echo e(__('messages.artwork_create.stock_help', ['default' => 'Number of identical pieces available'])); ?></p>
                        <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('messages.artwork_create.weight_label', ['default' => 'Weight (kg)'])); ?>

                        </label>
                        <input type="number" id="weight" name="weight" value="<?php echo e(old('weight')); ?>" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="0.00">
                        <p class="mt-1 text-xs text-gray-500"><?php echo e(__('messages.artwork_create.weight_help', ['default' => 'For shipping cost calculation'])); ?></p>
                        <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Is Digital -->
                    <div class="flex items-end">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="is_digital" name="is_digital" value="1" <?php echo e(old('is_digital') ? 'checked' : ''); ?>

                                   class="w-5 h-5 text-black border-gray-300 rounded focus:ring-black">
                            <span class="ml-2 text-sm font-medium text-gray-700"><?php echo e(__('messages.artwork_create.is_digital_label', ['default' => 'Digital Artwork'])); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section: Images -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">3</span>
                    <?php echo e(__('messages.artwork_create.images_section', ['default' => 'Artwork Images'])); ?>

                    <span class="text-red-500 ml-1">*</span>
                </h2>

                <!-- Image Upload Area -->
                <div class="relative">
                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-black transition-colors cursor-pointer bg-gray-50">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-700 mb-2"><?php echo e(__('messages.artwork_create.dropzone_title', ['default' => 'Drag & drop your images here'])); ?></p>
                        <p class="text-sm text-gray-500 mb-4"><?php echo e(__('messages.artwork_create.dropzone_subtitle', ['default' => 'or click to browse from your device'])); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e(__('messages.artwork_create.image_requirements', ['default' => 'PNG, JPG, JPEG, GIF, WebP up to 5MB each. Minimum 1 image, maximum 5 images.'])); ?></p>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required
                               class="hidden" onchange="previewImages(this)">
                        <button type="button" onclick="document.getElementById('images').click()"
                                class="mt-4 px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                            <?php echo e(__('messages.artwork_create.select_files', ['default' => 'Select Files'])); ?>

                        </button>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="image-previews" class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 hidden">
                    </div>

                    <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Section: Review & Submit -->
            <div class="p-8 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">4</span>
                    <?php echo e(__('messages.artwork_create.review_submit', ['default' => 'Review & Submit'])); ?>

                </h2>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800"><?php echo e(__('messages.artwork_create.review_notice_title', ['default' => 'Important:'])); ?></p>
                            <p class="text-sm text-yellow-700 mt-1"><?php echo e(__('messages.artwork_create.review_notice_text', ['default' => 'Your artwork will be reviewed by our team before being published. This process typically takes 24-48 hours. Ensure all information is accurate and images are high quality.'])); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'outline','href' => ''.e(route('artist.artworks')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('artist.artworks')).'']); ?>
                        <?php echo e(__('messages.artwork_create.cancel', ['default' => 'Cancel'])); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'primary','type' => 'submit','id' => 'submit-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','type' => 'submit','id' => 'submit-btn']); ?>
                        <svg id="submit-spinner" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="submit-text"><?php echo e(__('messages.artwork_create.submit', ['default' => 'Submit for Approval'])); ?></span>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Character counter for description
const descInput = document.getElementById('description');
const descCount = document.getElementById('desc-count');

descInput.addEventListener('input', function() {
    descCount.textContent = this.value.length;
});

// Initialize count
descCount.textContent = descInput.value.length;

// Image preview functionality
function previewImages(input) {
    const previewContainer = document.getElementById('image-previews');
    const uploadArea = document.getElementById('upload-area');
    
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewContainer.classList.remove('hidden');
        uploadArea.classList.add('border-black', 'bg-gray-100');
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all"></div>
                    <span class="absolute top-2 left-2 bg-black text-white text-xs px-2 py-1 rounded font-medium">
                        ${index === 0 ? '<?php echo e(__("messages.artwork_create.primary", ["default" => "Primary"])); ?>' : index + 1}
                    </span>
                    <span class="absolute bottom-2 right-2 bg-white text-gray-800 text-xs px-2 py-1 rounded">
                        ${(file.size / 1024 / 1024).toFixed(2)} MB
                    </span>
                `;
                previewContainer.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        });
    } else {
        previewContainer.classList.add('hidden');
        uploadArea.classList.remove('border-black', 'bg-gray-100');
    }
}

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('images');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-black', 'bg-gray-100');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-black', 'bg-gray-100');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-black', 'bg-gray-100');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        previewImages(fileInput);
    }
});

// Form submission loading state
const form = document.querySelector('form');
const submitBtn = document.getElementById('submit-btn');
const submitSpinner = document.getElementById('submit-spinner');
const submitText = document.getElementById('submit-text');

form.addEventListener('submit', function() {
    submitBtn.disabled = true;
    submitSpinner.classList.remove('hidden');
    submitText.textContent = '<?php echo e(__("messages.artwork_create.submitting", ["default" => "Submitting..."])); ?>';
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\artworks-create.blade.php ENDPATH**/ ?>