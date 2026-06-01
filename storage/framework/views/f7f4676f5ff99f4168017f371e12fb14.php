<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'artwork' => null,
    'show' => false
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
    'artwork' => null,
    'show' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!-- Artwork Details Modal -->
<div id="artwork-quick-view-modal" 
     class="fixed inset-0 z-50 overflow-y-auto hidden">
    
    <!-- Background -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeArtworkModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl transform transition-all">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900" id="modal-artwork-title"><?php echo e(__('messages.artwork_details_title')); ?></h3>
                <button type="button" 
                        onclick="closeArtworkModal()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="flex flex-col lg:flex-row">
                <!-- Image Gallery -->
                <div class="lg:w-1/2 p-6">
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        <img id="modal-artwork-image" src="" alt="Artwork" class="w-full h-full object-cover">
                    </div>
                    <div id="modal-artwork-images-grid" class="grid grid-cols-4 gap-2 mt-4 hidden"></div>
                </div>
                
                <!-- Details -->
                <div class="lg:w-1/2 p-6 space-y-6">
                    <!-- Title and Artist -->
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2" id="modal-artwork-display-title"></h2>
                        <p class="text-lg text-gray-600">
                            <?php echo e(__('messages.by_artist')); ?> <span class="font-medium" id="modal-artwork-artist"></span>
                        </p>
                    </div>
                    
                    <!-- Price and Status -->
                    <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.price')); ?></p>
                            <p class="text-3xl font-bold text-black" id="modal-artwork-price"></p>
                        </div>
                        <div id="modal-artwork-status"></div>
                    </div>
                    
                    <!-- Artwork Information -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200" id="modal-artwork-medium-row">
                            <span class="text-sm font-medium text-gray-700"><?php echo e(__('messages.medium')); ?></span>
                            <span class="text-sm text-gray-600" id="modal-artwork-medium"></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200" id="modal-artwork-size-row">
                            <span class="text-sm font-medium text-gray-700"><?php echo e(__('messages.dimensions')); ?></span>
                            <span class="text-sm text-gray-600" id="modal-artwork-size"></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200" id="modal-artwork-year-row">
                            <span class="text-sm font-medium text-gray-700"><?php echo e(__('messages.year_created')); ?></span>
                            <span class="text-sm text-gray-600" id="modal-artwork-year"></span>
                        </div>
                        <div class="py-3" id="modal-artwork-description-row">
                            <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.description')); ?></h4>
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal-artwork-description"></p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-4 pt-6">
                        <button type="button"
                                id="modal-buy-now-btn"
                                class="flex-1 py-3 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            <?php echo e(__('messages.buy_now')); ?>

                        </button>
                        
                        <button id="modal-wishlist-btn"
                                class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <?php echo e(__('messages.add_to_wishlist')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function closeArtworkModal() {
        const modal = document.getElementById('artwork-quick-view-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\artwork-modal.blade.php ENDPATH**/ ?>