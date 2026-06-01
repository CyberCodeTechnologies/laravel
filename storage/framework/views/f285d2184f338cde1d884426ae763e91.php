

<?php $__env->startSection('title', 'View User - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'View User Details'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-6xl mx-auto py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?php echo e(route('admin.users')); ?>" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Users
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                         src="<?php echo e($user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128'); ?>"
                         alt="<?php echo e($user->name); ?>">
                    <div class="ml-5">
                        <h1 class="text-2xl font-bold text-white"><?php echo e($user->name); ?></h1>
                        <p class="text-sm text-blue-100"><?php echo e($user->email); ?></p>
                        <div class="mt-2 flex gap-2 flex-wrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                <?php echo e($user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                <?php echo e($user->role === 'artist' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($user->role === 'collector' ? 'bg-green-100 text-green-800' : ''); ?>">
                                <i class="fas fa-<?php echo e($user->role === 'admin' ? 'user-shield' : ($user->role === 'artist' ? 'palette' : 'shopping-bag')); ?> mr-1"></i>
                                <?php echo e(ucfirst($user->role)); ?>

                            </span>
                            <?php if($user->is_verified): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            <?php endif; ?>
                            <?php if($user->status === 'approved'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </span>
                            <?php elseif($user->status === 'rejected'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-blue-100">User ID</p>
                    <p class="text-lg font-semibold text-white">#<?php echo e($user->id); ?></p>
                    <p class="text-xs text-blue-100 mt-1">Joined</p>
                    <p class="text-sm font-semibold text-white"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php if($user->role === 'artist'): ?>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Artworks</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($user->artworks()->count()); ?></p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Sales</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($user->sales()->count()); ?></p>
                    </div>
                <?php elseif($user->role === 'collector'): ?>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Purchases</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($user->purchases()->count()); ?></p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Collection</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($user->currentArtworks()->count()); ?></p>
                    </div>
                <?php endif; ?>
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Followers</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($user->followers()->count()); ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Following</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($user->following()->count()); ?></p>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Account Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Account Information
                    </h3>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-sm text-gray-900 font-medium"><?php echo e($user->name); ?></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-gray-900 font-medium"><?php echo e($user->email); ?></p>
                    </div>

                    <?php if($user->phone): ?>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                            <p class="text-sm text-gray-900 font-medium"><?php echo e($user->phone); ?></p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Role</p>
                        <p class="text-sm text-gray-900 font-medium"><?php echo e(ucfirst($user->role)); ?></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                        <?php if($user->status === 'approved'): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        <?php elseif($user->status === 'rejected'): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Activity Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Activity Information
                    </h3>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Joined Date</p>
                        <p class="text-sm text-gray-900 font-medium"><?php echo e($user->created_at->format('M d, Y H:i')); ?></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Last Login</p>
                        <p class="text-sm text-gray-900 font-medium"><?php echo e($user->last_login_at?->format('M d, Y H:i') ?? 'Never'); ?></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Email Verified</p>
                        <?php if($user->email_verified_at): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Yes (<?php echo e($user->email_verified_at->format('M d, Y')); ?>)
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> No
                            </span>
                        <?php endif; ?>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Account Active</p>
                        <?php if($user->is_active ?? true): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        Contact Information
                    </h3>

                    <?php if($user->address): ?>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                            <p class="text-sm text-gray-900 font-medium"><?php echo e($user->address); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->city): ?>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">City</p>
                            <p class="text-sm text-gray-900 font-medium"><?php echo e($user->city); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->country): ?>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Country</p>
                            <p class="text-sm text-gray-900 font-medium"><?php echo e($user->country); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($user->location): ?>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Location</p>
                            <p class="text-sm text-gray-900 font-medium"><?php echo e($user->location); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Artist Specific Information -->
            <?php if($user->role === 'artist' && ($user->bio || $user->specialization || $user->years_active)): ?>
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Artist Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if($user->specialization): ?>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Specialization</p>
                                <p class="text-sm text-gray-900 font-medium"><?php echo e($user->specialization); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if($user->years_active): ?>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Years Active</p>
                                <p class="text-sm text-gray-900 font-medium"><?php echo e($user->years_active); ?> years</p>
                            </div>
                        <?php endif; ?>
                        <?php if($user->bio): ?>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Bio</p>
                                <p class="text-sm text-gray-900 font-medium"><?php echo e($user->bio); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap gap-2">
                <?php if($user->status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.approve', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2" onclick="return confirm('Approve this user?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.users.reject', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition flex items-center gap-2" onclick="return confirm('Reject this user? This action cannot be undone.')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                <?php elseif($user->status === 'rejected'): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.approve', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2" onclick="return confirm('Approve this user?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                <?php endif; ?>
                <?php if(!$user->email_verified_at): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.resend-verification', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i class="fas fa-envelope"></i> Resend Verification
                        </button>
                    </form>
                <?php endif; ?>
                <?php if($user->id !== auth()->id()): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="<?php echo e($user->is_active ?? true ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white px-4 py-2 text-sm rounded-lg transition flex items-center gap-2" onclick="return confirm('<?php echo e($user->is_active ?? true ? 'Suspend this user account?' : 'Activate this user account?'); ?>')">
                            <i class="fas fa-<?php echo e($user->is_active ?? true ? 'pause' : 'play'); ?>"></i> <?php echo e($user->is_active ?? true ? 'Suspend' : 'Activate'); ?>

                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.users.impersonate', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-purple-700 transition flex items-center gap-2" onclick="return confirm('Impersonate this user? You will be logged in as them.')">
                            <i class="fas fa-user-secret"></i> Impersonate
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <?php if($user->id !== auth()->id()): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.delete', $user->id)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition flex items-center gap-2" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\users\show.blade.php ENDPATH**/ ?>