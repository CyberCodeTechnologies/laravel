<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'showCloseButton' => true,
    'closeOnBackdrop' => true
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'showCloseButton' => true,
    'closeOnBackdrop' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    if (!$id) {
        $id = 'modal-' . uniqid();
    }
    
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-2xl',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
        'full' => 'max-w-full mx-4',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
?>

<!-- Modal Backdrop -->
<div id="<?php echo e($id); ?>" class="fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <?php if($closeOnBackdrop): ?>
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeModal('<?php echo e($id); ?>')"></div>
        <?php else: ?>
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>
        <?php endif; ?>

        <!-- Modal Panel -->
        <div class="inline-block w-full overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg <?php echo e($sizeClass); ?>">
            <!-- Header -->
            <?php if($title || $showCloseButton): ?>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <?php if($title): ?>
                        <h3 class="text-lg font-semibold text-gray-900 font-serif"><?php echo e($title); ?></h3>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    
                    <?php if($showCloseButton): ?>
                        <button 
                            onclick="closeModal('<?php echo e($id); ?>')"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Body -->
            <div class="px-6 py-4">
                <?php echo e($slot); ?>

            </div>
            
            <!-- Footer (optional) -->
            <?php if(isset($footer)): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <?php echo e($footer); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="modal-"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});
</script>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\modal.blade.php ENDPATH**/ ?>