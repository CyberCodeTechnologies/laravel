 

<?php $__env->startSection('title', 'Edit Artwork - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Edit Artwork'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="<?php echo e(route('admin.artworks.update', ['id' => $artwork->id])); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="space-y-6">
                <!-- Artist Selection -->
                <div>
                    <label for="artist_id" class="block text-sm font-medium text-gray-700 mb-2">Artist <span class="text-red-500">*</span></label>
                    <select id="artist_id" 
                            name="artist_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Artist</option>
                        <?php $__currentLoopData = $artists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($artist->id); ?>" <?php echo e(old('artist_id', $artwork->artist_id) == $artist->id ? 'selected' : ''); ?>>
                            <?php echo e($artist->name); ?> (<?php echo e($artist->email); ?>)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['artist_id'];
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

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="<?php echo e(old('title', $artwork->title)); ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php $__errorArgs = ['title'];
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

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('description', $artwork->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
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

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select id="category_id" 
                            name="category_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $artwork->category_id) == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
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

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               step="0.01" 
                               min="0"
                               required
                               value="<?php echo e(old('price', $artwork->price)); ?>"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <?php $__errorArgs = ['price'];
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

                <!-- Dimensions -->
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions</label>
                    <input type="text" 
                           id="dimensions" 
                           name="dimensions" 
                           value="<?php echo e(old('dimensions', $artwork->dimensions)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., 24 x 36 inches">
                    <?php $__errorArgs = ['dimensions'];
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

                <!-- Medium -->
                <div>
                    <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">Medium</label>
                    <select id="medium" name="medium" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <?php $__errorArgs = ['medium'];
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

                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <input type="number" 
                           id="year" 
                           name="year" 
                           min="1900" 
                           max="<?php echo e(date('Y')); ?>"
                           value="<?php echo e(old('year', $artwork->year)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php $__errorArgs = ['year'];
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

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <select id="currency" 
                            name="currency" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="USD" <?php echo e(old('currency', $artwork->currency) === 'USD' ? 'selected' : ''); ?>>USD</option>
                        <option value="MMK" <?php echo e(old('currency', $artwork->currency) === 'MMK' ? 'selected' : ''); ?>>MMK</option>
                    </select>
                    <?php $__errorArgs = ['currency'];
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

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           min="0"
                           value="<?php echo e(old('stock', $artwork->stock ?? 1)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Set to 0 or leave empty for unlimited (digital artworks)</p>
                    <?php $__errorArgs = ['stock'];
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

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" 
                           id="weight" 
                           name="weight" 
                           min="0" 
                           step="0.1"
                           value="<?php echo e(old('weight', $artwork->weight)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., 2.5">
                    <?php $__errorArgs = ['weight'];
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

                <!-- Is Digital -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="is_digital" 
                               name="is_digital" 
                               value="1"
                               <?php echo e(old('is_digital', $artwork->is_digital) ? 'checked' : ''); ?>

                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Digital Artwork (no shipping required)</span>
                    </label>
                </div>

                <!-- Is Featured -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               <?php echo e(old('is_featured', $artwork->is_featured) ? 'checked' : ''); ?>

                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Artwork (displayed prominently)</span>
                    </label>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" <?php echo e($artwork->status === 'draft' ? 'selected' : ''); ?>>Draft</option>
                        <option value="pending" <?php echo e($artwork->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e($artwork->status === 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="sold" <?php echo e($artwork->status === 'sold' ? 'selected' : ''); ?>>Sold</option>
                        <option value="resale" <?php echo e($artwork->status === 'resale' ? 'selected' : ''); ?>>Resale</option>
                    </select>
                </div>

                <!-- Stats -->
                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Artwork Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold"><?php echo e($artwork->views ?? 0); ?></p>
                            <p class="text-sm text-gray-600">Views</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold"><?php echo e($artwork->likes_count ?? 0); ?></p>
                            <p class="text-sm text-gray-600">Likes</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold"><?php echo e(\App\Models\Wishlist::where('artwork_id', $artwork->id)->count()); ?></p>
                            <p class="text-sm text-gray-600">Wishlists</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold"><?php echo e($artwork->approved_at ? $artwork->approved_at->format('M j, Y') : 'N/A'); ?></p>
                            <p class="text-sm text-gray-600">Approved</p>
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <?php if($artwork->images && is_array($artwork->images) && count($artwork->images) > 0): ?>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            <?php $__currentLoopData = $artwork->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group">
                                    <img src="<?php echo e(asset('storage/' . $image)); ?>" 
                                         alt="<?php echo e($artwork->title); ?> - Image <?php echo e($index + 1); ?>" 
                                         class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center">
                                        <span class="text-white text-xs">Image <?php echo e($index + 1); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No images uploaded</p>
                    <?php endif; ?>
                </div>

                <!-- New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add/Replace Images</label>
                    <input type="file" 
                           id="images" 
                           name="images[]" 
                           multiple
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Upload 1-5 images. Max 5MB each. Allowed formats: JPG, PNG, GIF, WebP. Leave blank to keep current images.</p>
                    <?php $__errorArgs = ['images'];
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
            </div>

            <div class="flex gap-4 mt-8">
                <a href="<?php echo e(route('admin.artworks')); ?>"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\artworks\edit.blade.php ENDPATH**/ ?>