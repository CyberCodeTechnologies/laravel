

<?php $__env->startSection('title', 'Support Contacts - Admin'); ?>
<?php $__env->startSection('meta-description', 'Manage customer support inquiries on Panchi Gallery'); ?>

<?php $__env->startSection('header', 'Support Contacts'); ?>

<?php $__env->startSection('admin_content'); ?>
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Contacts -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Contacts</p>
                        <p class="text-2xl font-bold text-blue-900"><?php echo e(App\Models\ContactMessage::count()); ?></p>
                        <p class="text-xs text-blue-700 mt-1">All messages</p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900"><?php echo e(App\Models\ContactMessage::where('status', 'pending')->count()); ?></p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting response</p>
                    </div>
                </div>
            </div>

            <!-- Responded -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-reply text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Responded</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo e(App\Models\ContactMessage::where('status', 'responded')->count()); ?></p>
                        <p class="text-xs text-green-700 mt-1">Replied messages</p>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Resolved</p>
                        <p class="text-2xl font-bold text-purple-900"><?php echo e(App\Models\ContactMessage::where('status', 'resolved')->count()); ?></p>
                        <p class="text-xs text-purple-700 mt-1">Completed tickets</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contacts Management -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Filters and Search -->
            <div class="p-6 border-b">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-col md:flex-row gap-4 flex-1">
                        <!-- Search -->
                        <div class="relative flex-1 md:max-w-md">
                            <input type="text" 
                                   id="search" 
                                   placeholder="Search contacts..." 
                                   value="<?php echo e(request('search', '')); ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>

                        <!-- Status Filter -->
                        <select name="status" 
                                onchange="window.location.href='<?php echo e(route('admin.support.contacts')); ?>?status='+this.value+'&search=<?php echo e(request('search', '')); ?>"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="responded" <?php echo e(request('status') === 'responded' ? 'selected' : ''); ?>>Responded</option>
                            <option value="resolved" <?php echo e(request('status') === 'resolved' ? 'selected' : ''); ?>>Resolved</option>
                        </select>

                        <!-- Date Filter -->
                        <input type="date" 
                               name="date_filter" 
                               value="<?php echo e(request('date_filter', '')); ?>"
                               onchange="window.location.href='<?php echo e(route('admin.support.contacts')); ?>?date_filter='+this.value+'&status=<?php echo e(request('status', '')); ?>&search=<?php echo e(request('search', '')); ?>"
                               class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button onclick="markAllAsRead()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                            <i class="fas fa-envelope-open mr-2"></i>Mark All Read
                        </button>
                        <button onclick="exportContacts()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contacts Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" onclick="toggleAll(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                            $query = App\Models\ContactMessage::query();
                            
                            if (request('search')) {
                                $query->where(function($q) {
                                    $search = request('search');
                                    $q->where('name', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%")
                                      ->orWhere('subject', 'like', "%{$search}%")
                                      ->orWhere('message', 'like', "%{$search}%");
                                });
                            }
                            
                            if (request('status')) {
                                $query->where('status', request('status'));
                            }
                            
                            if (request('date_filter')) {
                                $query->whereDate('created_at', request('date_filter'));
                            }
                            
                            $contacts = $query->latest()->paginate(15);
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 <?php echo e($contact->is_read ? '' : 'bg-blue-50'); ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" value="<?php echo e($contact->id); ?>" class="contact-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="<?php echo e($contact->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($contact->name) . '&background=random&size=128'); ?>" alt="<?php echo e($contact->name); ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($contact->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($contact->email); ?></div>
                                        </div>
                                        <?php if(!$contact->is_read): ?>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                New
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium truncate max-w-xs"><?php echo e($contact->subject); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo e($contact->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($contact->status === 'responded' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($contact->status === 'resolved' ? 'bg-green-100 text-green-800' : ''); ?>">
                                        <?php echo e(ucfirst($contact->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($contact->created_at->format('M j, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewContact(<?php echo e($contact->id); ?>)" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="respondToContact(<?php echo e($contact->id); ?>)" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                        <button onclick="deleteContact(<?php echo e($contact->id); ?>)" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">No contacts found</p>
                                    <p class="text-gray-400 mt-2">Try adjusting your search or filter criteria</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($contacts->hasPages()): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 lg:px-8">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a href="<?php echo e($contacts->previousPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="<?php echo e($contacts->nextPageUrl()); ?>" class="relative ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:justify-between sm:items-center">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium"><?php echo e($contacts->firstItem()); ?></span>
                                to
                                <span class="font-medium"><?php echo e($contacts->lastItem()); ?></span>
                                of
                                <span class="font-medium"><?php echo e($contacts->total()); ?></span>
                                results
                            </p>
                        </div>
                        <div>
                            <?php echo e($contacts->appends(request()->except('page'))->links()); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contact Detail Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-medium text-gray-900">Contact Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
const contactShowUrl = <?php echo json_encode(route('admin.support.contacts.show', ['contact' => '__ID__']), 512) ?>;

function viewContact(id) {
    fetch(contactShowUrl.replace('__ID__', String(id)), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('contactModal').classList.remove('hidden');
        });
}

function respondToContact(id) {
    window.location.href = contactShowUrl.replace('__ID__', String(id));
}

function deleteContact(id) {
    if (confirm('Are you sure you want to delete this contact?')) {
        fetch(contactShowUrl.replace('__ID__', String(id)), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        }).then(() => location.reload());
    }
}

function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

function markAllAsRead() {
    const selectedIds = Array.from(document.querySelectorAll('.contact-checkbox:checked')).map(cb => cb.value);
    if (selectedIds.length === 0) {
        alert('Please select contacts to mark as read');
        return;
    }
    
    fetch(<?php echo json_encode(route('admin.support.contacts.mark-read'), 15, 512) ?>, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ ids: selectedIds })
    }).then(() => location.reload());
}

function exportContacts() {
    window.location.href = '<?php echo e(url("admin/support/contacts/export")); ?>';
}

function closeModal() {
    document.getElementById('contactModal').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\support\contacts.blade.php ENDPATH**/ ?>