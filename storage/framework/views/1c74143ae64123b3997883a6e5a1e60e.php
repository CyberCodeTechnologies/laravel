


<div id="<?php echo e($id); ?>" 
     class="fixed inset-0 z-50 overflow-y-auto hidden" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             onclick="closeModal('<?php echo e($id); ?>')"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full
            <?php if($size === 'small'): ?>
                sm:max-w-md
            <?php elseif($size === 'large'): ?>
                sm:max-w-4xl
            <?php elseif($size === 'full'): ?>
                sm:max-w-6xl
            <?php else: ?>
                sm:max-w-2xl
            <?php endif; ?>
            ">
            <!-- Modal Header -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        <?php echo e($title); ?>

                    </h3>
                    <button onclick="closeModal('<?php echo e($id); ?>')" 
                            type="button" 
                            class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <?php echo e($slot ?? ''); ?>

            </div>

            <!-- Modal Footer (if provided) -->
            <?php if(isset($footer)): ?>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <?php echo e($footer); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModal = document.querySelector('.fixed.inset-0.z-50:not(.hidden)');
        if (openModal) {
            closeModal(openModal.id);
        }
    }
});
</script>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\modal.blade.php ENDPATH**/ ?>