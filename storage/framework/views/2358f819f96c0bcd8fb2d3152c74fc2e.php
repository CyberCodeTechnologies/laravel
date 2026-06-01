

<?php $__env->startSection('title', 'Contact Details - Admin'); ?>
<?php $__env->startSection('header', 'Contact Details'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold"><?php echo e($contact->subject); ?></h2>
                <p class="text-sm text-gray-500 mt-1">Received <?php echo e($contact->created_at->format('M d, Y h:i A')); ?></p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                <?php echo e($contact->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                <?php echo e($contact->status === 'responded' ? 'bg-blue-100 text-blue-800' : ''); ?>

                <?php echo e($contact->status === 'resolved' ? 'bg-green-100 text-green-800' : ''); ?>">
                <?php echo e(ucfirst($contact->status)); ?>

            </span>
        </div>

        <div class="p-6 border-b bg-gray-50 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">From</p>
                <p class="font-medium"><?php echo e($contact->name); ?></p>
                <p class="text-sm text-gray-500"><?php echo e($contact->email); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Subject</p>
                <p class="font-medium"><?php echo e($contact->subject); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Read</p>
                <p class="font-medium"><?php echo e($contact->is_read ? 'Yes' : 'No'); ?></p>
            </div>
        </div>

        <div class="p-6 border-b">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Message</h3>
            <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($contact->message); ?></p>
        </div>

        <?php if($contact->admin_notes): ?>
            <div class="p-6 border-b bg-blue-50">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Admin notes</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($contact->admin_notes); ?></p>
            </div>
        <?php endif; ?>

        <div class="p-6 space-y-4">
            <h3 class="text-sm font-medium text-gray-700">Admin response</h3>
            <form action="<?php echo e(route('admin.support.contacts.respond', $contact)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <textarea name="response" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-4"
                          placeholder="Type your response notes..."><?php echo e(old('response', $contact->admin_notes)); ?></textarea>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('admin.support.contacts')); ?>" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Back</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save response</button>
                </div>
            </form>
            <form action="<?php echo e(route('admin.support.contacts.resolve', $contact)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Mark resolved</button>
            </form>
            <form action="<?php echo e(route('admin.support.contacts.delete', $contact)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this message?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\support\show.blade.php ENDPATH**/ ?>