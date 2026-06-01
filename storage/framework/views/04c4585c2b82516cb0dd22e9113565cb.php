

<?php $__env->startSection('title', 'Customers - Panchi ERP'); ?>

<?php $__env->startSection('header', 'Manage Customers'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="space-y-6">
    <!-- Quick Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::count())); ?></h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-emerald-600">Active</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::where('status', 'approved')->count())); ?></h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-amber-600">Pending</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::where('status', 'pending')->count())); ?></h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Artists</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::where('role', 'artist')->count())); ?></h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Admins</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::where('role', 'admin')->count())); ?></h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Collectors</p>
            <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo e(number_format(App\Models\User::where('role', 'collector')->count())); ?></h3>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[250px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Search by name or email..."
                       class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 w-full bg-gray-50">
            </div>
            <select name="role" class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                <option value="">All Roles</option>
                <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                <option value="artist" <?php echo e(request('role') == 'artist' ? 'selected' : ''); ?>>Artist</option>
                <option value="collector" <?php echo e(request('role') == 'collector' ? 'selected' : ''); ?>>Collector</option>
            </select>
            <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                <option value="">All Status</option>
                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
            </select>
            <div class="flex items-center gap-2">
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                <span class="text-gray-400">to</span>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
            </div>
            <select name="sort_by" class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>Sort by Date</option>
                <option value="name" <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>>Sort by Name</option>
                <option value="email" <?php echo e(request('sort_by') == 'email' ? 'selected' : ''); ?>>Sort by Email</option>
            </select>
            <select name="sort_order" class="px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>Descending</option>
                <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Ascending</option>
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 text-sm font-semibold rounded-md hover:bg-indigo-700 shadow-sm">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <?php if(request()->anyFilled(['search', 'role', 'status', 'date_from', 'date_to', 'sort_by', 'sort_order'])): ?>
                <a href="<?php echo e(route('admin.users')); ?>" class="text-xs text-gray-500 hover:text-gray-700 font-semibold underline flex items-center gap-1">
                    <i class="fas fa-times"></i> Reset
                </a>
            <?php endif; ?>
        </form>

        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.users.pending')); ?>" class="flex items-center bg-amber-500 text-white px-4 py-2 text-sm font-semibold rounded-md hover:bg-amber-600 shadow-sm">
                <i class="fas fa-clock mr-2 text-xs"></i> Pending (<?php echo e(App\Models\User::where('status', 'pending')->count()); ?>)
            </a>
            <div id="bulkActions" class="hidden animate-fade-in flex items-center gap-2 mr-4 border-r pr-4 border-gray-200">
                <button onclick="bulkApprove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 px-3 py-2 rounded border border-emerald-100 uppercase tracking-tighter">Approve Selected</button>
                <button onclick="bulkDelete()" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 px-3 py-2 rounded border border-rose-100 uppercase tracking-tighter">Delete Selected</button>
            </div>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="flex items-center bg-emerald-600 text-white px-4 py-2 text-sm font-semibold rounded-md hover:bg-emerald-700 shadow-sm">
                <i class="fas fa-plus mr-2 text-xs"></i> New Customer
            </a>
            <a href="<?php echo e(route('admin.users.export')); ?>" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md" title="Export to Excel">
                <i class="fas fa-file-export"></i>
            </a>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">User Profile</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Access Role</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Verification</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px]">Registered On</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[10px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_users[]" value="<?php echo e($user->id); ?>" class="user-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 border-2 border-white shadow-sm flex items-center justify-center text-indigo-700 font-bold overflow-hidden">
                                        <?php if($user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <?php echo e(substr($user->name, 0, 1)); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 leading-none mb-1"><?php echo e($user->name); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter
                                    <?php echo e($user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ''); ?>

                                    <?php echo e($user->role === 'artist' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                    <?php echo e($user->role === 'collector' ? 'bg-emerald-100 text-emerald-700' : ''); ?>">
                                    <?php echo e($user->role); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($user->status === 'approved'): ?>
                                    <div class="flex items-center text-emerald-600 font-semibold text-xs">
                                        <i class="fas fa-check-circle mr-1.5"></i> Approved
                                    </div>
                                <?php elseif($user->status === 'pending'): ?>
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-600 font-semibold text-xs">
                                            <i class="fas fa-clock mr-1.5"></i> Awaiting Review
                                        </span>
                                        <div class="flex gap-1">
                                            <button onclick="quickApprove(<?php echo e($user->id); ?>, this)" class="text-[10px] font-bold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-2 py-1 rounded border border-emerald-200 transition-colors" title="Quick Approve">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                            <button onclick="quickReject(<?php echo e($user->id); ?>, this)" class="text-[10px] font-bold bg-rose-100 text-rose-700 hover:bg-rose-200 px-2 py-1 rounded border border-rose-200 transition-colors" title="Quick Reject">
                                                <i class="fas fa-times mr-1"></i>Reject
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center text-rose-600 font-semibold text-xs">
                                        <i class="fas fa-times-circle mr-1.5"></i> Suspended
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <?php echo e($user->created_at->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-1">
                                    <?php if($user->status === 'pending'): ?>
                                        <button onclick="quickApprove(<?php echo e($user->id); ?>, this)" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded" title="Quick Approve">
                                            <i class="fas fa-check-double text-xs"></i>
                                        </button>
                                        <button onclick="quickReject(<?php echo e($user->id); ?>, this)" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded" title="Quick Reject">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user->id)); ?>" onsubmit="return confirm('<?php echo e($user->is_active ?? true ? 'Suspend this user account?' : 'Activate this user account?'); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <button class="p-1.5 <?php echo e($user->is_active ?? true ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50'); ?> rounded" title="<?php echo e($user->is_active ?? true ? 'Suspend' : 'Activate'); ?>">
                                                <i class="fas fa-<?php echo e($user->is_active ?? true ? 'pause' : 'play'); ?> text-xs"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.users.impersonate', $user->id)); ?>" onsubmit="return confirm('Impersonate this user? You will be logged in as them.')">
                                            <?php echo csrf_field(); ?>
                                            <button class="p-1.5 text-purple-600 hover:bg-purple-50 rounded" title="Impersonate">
                                                <i class="fas fa-user-secret text-xs"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="View">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded" title="Edit">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.delete', $user->id)); ?>" onsubmit="return confirm('Delete this user account?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="p-1.5 text-rose-600 hover:bg-rose-50 rounded" title="Delete">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-4">
                                        <i class="fas fa-users text-4xl text-gray-200"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No customers matching your criteria.</p>
                                    <a href="<?php echo e(route('admin.users')); ?>" class="text-indigo-600 font-bold text-sm mt-2 underline">Clear all filters</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($users->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulkActions');

    function toggleBulkActions() {
        const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        toggleBulkActions();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleBulkActions);
    });

    // Keyboard shortcuts for quick approval
    document.addEventListener('keydown', function(e) {
        // Only trigger if not typing in an input
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }

        // Ctrl/Cmd + A: Approve all pending users
        if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
            e.preventDefault();
            quickApproveAll();
        }
    });
});

function bulkApprove() {
    const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    if (!confirm(`Approve ${ids.length} selected customers?`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route('admin.users.bulk-approve')); ?>';
    form.innerHTML = `<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">`;
    ids.forEach(id => {
        form.innerHTML += `<input type="hidden" name="user_ids[]" value="${id}">`;
    });
    document.body.appendChild(form);
    form.submit();
}

function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    if (!confirm(`Permanently delete ${ids.length} selected customers?`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route('admin.users.bulk-delete')); ?>';
    form.innerHTML = `<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="_method" value="DELETE">`;
    ids.forEach(id => {
        form.innerHTML += `<input type="hidden" name="user_ids[]" value="${id}">`;
    });
    document.body.appendChild(form);
    form.submit();
}

function quickApproveAll() {
    const pendingCount = <?php echo e(App\Models\User::where('status', 'pending')->count()); ?>;
    if (pendingCount === 0) {
        alert('No pending users to approve.');
        return;
    }
    if (!confirm(`Approve all ${pendingCount} pending users?`)) return;

    window.location.href = '<?php echo e(route('admin.users.pending')); ?>';
}

function quickApprove(userId, button) {
    if (!confirm('Approve this user?')) return;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    
    fetch(`<?php echo e(route('admin.users.approve', ':id')); ?>`.replace(':id', userId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show updated status
            window.location.reload();
        } else {
            alert('Failed to approve user. Please try again.');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double text-xs"></i>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-check-double text-xs"></i>';
    });
}

function quickReject(userId, button) {
    const reason = prompt('Enter rejection reason (optional):');
    if (reason === null) return; // User cancelled
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
    
    const formData = new FormData();
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    if (reason) formData.append('reason', reason);
    
    fetch(`<?php echo e(route('admin.users.reject', ':id')); ?>`.replace(':id', userId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: formData
    })
    .then(response => response.redirected ? response : response.json())
    .then(data => {
        // Reload page to show updated status
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-times text-xs"></i>';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\users\index.blade.php ENDPATH**/ ?>