

<?php $__env->startSection('title', 'Edit User - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Edit User'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- User Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full object-cover border-3 border-white shadow-lg"
                         src="<?php echo e($user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128'); ?>"
                         alt="<?php echo e($user->name); ?>">
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-white"><?php echo e($user->name); ?></h1>
                        <p class="text-sm text-blue-100"><?php echo e($user->email); ?></p>
                        <div class="mt-1 flex gap-2">
                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full
                                <?php echo e($user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                <?php echo e($user->role === 'artist' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($user->role === 'collector' ? 'bg-green-100 text-green-800' : ''); ?>">
                                <?php echo e(ucfirst($user->role)); ?>

                            </span>
                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full
                                <?php echo e($user->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e($user->is_approved ? 'Approved' : 'Pending'); ?>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-blue-100">User ID</p>
                    <p class="text-sm font-semibold text-white">#<?php echo e($user->id); ?></p>
                    <p class="text-xs text-blue-100 mt-1">Joined</p>
                    <p class="text-sm font-semibold text-white"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
        </div>

        <form action="<?php echo e(route('admin.users.update', $user->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="p-6 space-y-8">
                <!-- Profile Images Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images mr-2 text-blue-600"></i>
                        Profile Images
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Avatar -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0" id="avatar-preview-container">
                                    <?php if($user->avatar): ?>
                                        <img class="h-24 w-24 rounded-full object-cover border-2 border-gray-200" src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>">
                                    <?php else: ?>
                                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                            <span class="text-gray-500 font-medium text-xl"><?php echo e(substr($user->name, 0, 1)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" 
                                           id="avatar" 
                                           name="avatar" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, GIF, WebP. Max 2MB.</p>
                                    <?php $__errorArgs = ['avatar'];
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
                        </div>

                        <!-- Cover Image -->
                        <div>
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0" id="cover-preview-container">
                                    <?php if($user->cover_image): ?>
                                        <img class="h-24 w-32 rounded-lg object-cover border-2 border-gray-200" src="<?php echo e(asset('storage/' . $user->cover_image)); ?>" alt="<?php echo e($user->name); ?> cover">
                                    <?php else: ?>
                                        <div class="h-24 w-32 rounded-lg bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                            <span class="text-gray-500 font-medium text-xs">No Cover</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" 
                                           id="cover_image" 
                                           name="cover_image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, GIF, WebP. Max 2MB.</p>
                                    <?php $__errorArgs = ['cover_image'];
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
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Basic Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="<?php echo e(old('first_name', $user->first_name)); ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['first_name'];
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
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="<?php echo e(old('last_name', $user->last_name)); ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['last_name'];
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
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e(old('email', $user->email)); ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['email'];
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
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo e(old('phone', $user->phone)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['phone'];
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
                </div>

                <!-- Contact Information Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        Contact Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="<?php echo e(old('address', $user->address)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['address'];
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
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="<?php echo e(old('city', $user->city)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['city'];
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
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   value="<?php echo e(old('state', $user->state)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['state'];
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
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="<?php echo e(old('postal_code', $user->postal_code)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['postal_code'];
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
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <input type="text" 
                                   id="country" 
                                   name="country" 
                                   value="<?php echo e(old('country', $user->country)); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['country'];
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
                </div>

                <!-- Role & Status Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                        Role & Status
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                            <select id="role" 
                                    name="role" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="artist" <?php echo e($user->role === 'artist' ? 'selected' : ''); ?>>Artist</option>
                                <option value="collector" <?php echo e($user->role === 'collector' ? 'selected' : ''); ?>>Collector</option>
                            </select>
                            <?php $__errorArgs = ['role'];
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
                        <div class="flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_approved" 
                                       value="1"
                                       <?php echo e($user->is_approved ? 'checked' : ''); ?>

                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Approved</span>
                            </label>
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       <?php echo e($user->is_active ? 'checked' : ''); ?>

                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_verified" 
                                       value="1"
                                       <?php echo e($user->is_verified ? 'checked' : ''); ?>

                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Verified</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Artist Specific Section -->
                <div id="artist-section" class="<?php echo e($user->role === 'artist' ? '' : 'hidden'); ?> border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Artist Information
                    </h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                                <input type="text" 
                                       id="specialization" 
                                       name="specialization" 
                                       value="<?php echo e(old('specialization', $user->specialization)); ?>"
                                       placeholder="e.g., Oil Painting, Sculpture, Digital Art"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['specialization'];
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
                            <div>
                                <label for="years_active" class="block text-sm font-medium text-gray-700 mb-2">Years Active</label>
                                <input type="number" 
                                       id="years_active" 
                                       name="years_active" 
                                       value="<?php echo e(old('years_active', $user->years_active)); ?>"
                                       min="0"
                                       max="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['years_active'];
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
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Studio Location</label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="<?php echo e(old('location', $user->location)); ?>"
                                       placeholder="e.g., Yangon, Myanmar"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['location'];
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
                            <div class="flex items-center pt-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox"
                                           name="participate_in_orders"
                                           value="1"
                                           <?php echo e(old('participate_in_orders', $user->participate_in_orders ?? true) ? 'checked' : ''); ?>

                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Participate in Custom Orders</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Artist Bio</label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      maxlength="1000"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('bio', $user->bio)); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1"><span id="bio-char-count"><?php echo e(strlen($user->bio ?? '')); ?></span>/1000 characters</p>
                            <?php $__errorArgs = ['bio'];
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

                        <div>
                            <label for="artist_statement" class="block text-sm font-medium text-gray-700 mb-2">Artist Statement</label>
                            <textarea id="artist_statement"
                                      name="artist_statement"
                                      rows="4"
                                      maxlength="2000"
                                      placeholder="Describe your artistic vision and philosophy"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('artist_statement', $user->artist_statement)); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1"><span id="statement-char-count"><?php echo e(strlen($user->artist_statement ?? '')); ?></span>/2000 characters</p>
                            <?php $__errorArgs = ['artist_statement'];
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

                        <!-- Dynamic Array Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Education</label>
                                <div id="education-container" class="space-y-2">
                                    <?php
                                        $education = $user->education ?? [];
                                        if (!is_array($education)) $education = [];
                                    ?>
                                    <?php $__currentLoopData = $education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex gap-2">
                                            <input type="text" 
                                                   name="education[]" 
                                                   value="<?php echo e($edu); ?>"
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-field" onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button type="button" id="add-education" class="mt-2 text-sm text-blue-600 hover:text-blue-700">+ Add Education</button>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Exhibitions</label>
                                <div id="exhibitions-container" class="space-y-2">
                                    <?php
                                        $exhibitions = $user->exhibitions ?? [];
                                        if (!is_array($exhibitions)) $exhibitions = [];
                                    ?>
                                    <?php $__currentLoopData = $exhibitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exhibition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex gap-2">
                                            <input type="text" 
                                                   name="exhibitions[]" 
                                                   value="<?php echo e($exhibition); ?>"
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-field" onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button type="button" id="add-exhibition" class="mt-2 text-sm text-blue-600 hover:text-blue-700">+ Add Exhibition</button>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Awards</label>
                                <div id="awards-container" class="space-y-2">
                                    <?php
                                        $awards = $user->awards ?? [];
                                        if (!is_array($awards)) $awards = [];
                                    ?>
                                    <?php $__currentLoopData = $awards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex gap-2">
                                            <input type="text" 
                                                   name="awards[]" 
                                                   value="<?php echo e($award); ?>"
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-field" onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button type="button" id="add-award" class="mt-2 text-sm text-blue-600 hover:text-blue-700">+ Add Award</button>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Press/Media</label>
                                <div id="press-container" class="space-y-2">
                                    <?php
                                        $press = $user->press ?? [];
                                        if (!is_array($press)) $press = [];
                                    ?>
                                    <?php $__currentLoopData = $press; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pressItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex gap-2">
                                            <input type="text" 
                                                   name="press[]" 
                                                   value="<?php echo e($pressItem); ?>"
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-field" onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button type="button" id="add-press" class="mt-2 text-sm text-blue-600 hover:text-blue-700">+ Add Press</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Security
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-4">Leave password fields blank to keep current password.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" 
                                       id="password" 
                                       name="password"
                                       minlength="8"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['password'];
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
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-4">
                    <a href="<?php echo e(route('admin.users')); ?>" 
                       class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Users
                    </a>
                    <div class="flex gap-3">
                        <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" 
                           class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced image preview with drag-and-drop support
    function setupImagePreview(inputId, previewContainerId, defaultClass) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(previewContainerId);

        if (!input || !container) return;

        // Handle file selection
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                validateAndPreviewImage(file, container, defaultClass);
            }
        });

        // Drag and drop support
        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            container.classList.add('border-blue-500', 'bg-blue-50');
        });

        container.addEventListener('dragleave', function(e) {
            e.preventDefault();
            container.classList.remove('border-blue-500', 'bg-blue-50');
        });

        container.addEventListener('drop', function(e) {
            e.preventDefault();
            container.classList.remove('border-blue-500', 'bg-blue-50');
            
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                input.files = e.dataTransfer.files;
                validateAndPreviewImage(file, container, defaultClass);
            }
        });
    }

    function validateAndPreviewImage(file, container, defaultClass) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size too large. Maximum size is 2MB.');
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type. Only JPEG, PNG, JPG, GIF, and WebP images are allowed.');
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `<img class="${defaultClass} object-cover border-2 border-gray-200" src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }

    setupImagePreview('avatar', 'avatar-preview-container', 'h-24 w-24 rounded-full');
    setupImagePreview('cover_image', 'cover-preview-container', 'h-24 w-32 rounded-lg');

    // Toggle artist-specific sections based on role
    const roleSelect = document.getElementById('role');
    const artistSection = document.getElementById('artist-section');

    if (roleSelect && artistSection) {
        // Initialize based on current value
        if (roleSelect.value === 'artist') {
            artistSection.classList.remove('hidden');
        }

        roleSelect.addEventListener('change', function() {
            if (this.value === 'artist') {
                artistSection.classList.remove('hidden');
            } else {
                artistSection.classList.add('hidden');
            }
        });
    }

    // Enhanced character counters with visual feedback
    function setupCharacterCounter(textareaId, counterId, maxLength) {
        const textarea = document.getElementById(textareaId);
        const counter = document.getElementById(counterId);

        if (!textarea || !counter) return;

        function updateCounter() {
            const current = textarea.value.length;
            counter.textContent = current;
            
            // Visual feedback when approaching limit
            if (current >= maxLength * 0.9) {
                counter.classList.add('text-red-600');
                counter.classList.remove('text-gray-500');
            } else {
                counter.classList.remove('text-red-600');
                counter.classList.add('text-gray-500');
            }
        }

        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initialize
    }

    setupCharacterCounter('bio', 'bio-char-count', 1000);
    setupCharacterCounter('artist_statement', 'statement-char-count', 2000);

    // Enhanced dynamic field addition with animation
    function addField(containerId, buttonId, placeholder) {
        const container = document.getElementById(containerId);
        const button = document.getElementById(buttonId);
        
        button.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'flex gap-2 animate-fade-in';
            div.innerHTML = `
                <input type="text" 
                       name="${containerId.replace('-container', '')}[]" 
                       placeholder="${placeholder}"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-field transition" title="Remove field">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
            
            div.querySelector('.remove-field').addEventListener('click', function() {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 200);
            });

            // Focus on new input
            div.querySelector('input').focus();
        });
    }

    addField('education-container', 'add-education', 'Degree, School, Year');
    addField('exhibitions-container', 'add-exhibition', 'Exhibition Name, Year');
    addField('awards-container', 'add-award', 'Award Name, Year');
    addField('press-container', 'add-press', 'Publication, Date');

    // Enhanced password validation with strength indicator
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            // You could add a visual strength indicator here
        });
    }
    
    if (passwordConfirmInput) {
        passwordConfirmInput.addEventListener('input', function() {
            if (passwordInput.value && this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('border-red-500');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
            }
        });
    }

    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        return strength;
    }

    // Form submission confirmation for critical changes
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const roleChanged = roleSelect && roleSelect.value !== '<?php echo e($user->role); ?>';
            const passwordFilled = passwordInput && passwordInput.value;
            
            if (roleChanged) {
                if (!confirm('Changing the user role will affect their permissions. Continue?')) {
                    e.preventDefault();
                    return;
                }
            }
            
            if (passwordFilled) {
                if (!confirm('You are changing the user password. Continue?')) {
                    e.preventDefault();
                    return;
                }
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\users\edit.blade.php ENDPATH**/ ?>