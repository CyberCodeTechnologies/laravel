<?php $__env->startSection('title', __('messages.commission_custom_artwork') . ' - Panchi Gallery'); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/order-form.js', 'resources/js/artist-selection-fix.js']); ?>
<script>
// DIRECT FIX FOR ESTIMATED TOTAL CALCULATION
document.addEventListener('DOMContentLoaded', function() {
    function calculateTotal() {
        const proposedPrice = parseFloat(document.getElementById('proposed_price').value) || 0;
        const shippingFee = 150;
        const additionalCharges = 100;
        const total = proposedPrice + shippingFee + additionalCharges;
        
        const estimatedTotalEl = document.getElementById('estimated_total');
        if (estimatedTotalEl) {
            estimatedTotalEl.textContent = '$' + total.toFixed(2);
            console.log('DIRECT FIX: Updated total to $' + total.toFixed(2) + ' from proposed price $' + proposedPrice);
        }
    }
    
    // Attach to proposed price input
    const priceInput = document.getElementById('proposed_price');
    if (priceInput) {
        priceInput.addEventListener('input', calculateTotal);
        priceInput.addEventListener('keyup', calculateTotal);
        priceInput.addEventListener('change', calculateTotal);
        
        // Calculate immediately on load
        calculateTotal();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-red-50 to-amber-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-900 to-red-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4"><?php echo e(__('messages.commission_custom_artwork')); ?></h1>
            <p class="text-xl text-red-100"><?php echo e(__('messages.work_with_talented_artists')); ?></p>
        </div>
    </div>

    <!-- Order Form -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="bg-red-900 text-white p-6">
                <h2 class="text-2xl font-bold text-center"><?php echo e(__('messages.custom_artwork_commission')); ?></h2>
            </div>
            
            <div class="p-8">
                <?php if(session('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-medium"><?php echo e(session('success')); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="step-indicator w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold" data-step="1">1</div>
                            <div class="step-line flex-1 h-1 bg-gray-300 mx-2"></div>
                            <div class="step-indicator w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold" data-step="2">2</div>
                            <div class="step-line flex-1 h-1 bg-gray-300 mx-2"></div>
                            <div class="step-indicator w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold" data-step="3">3</div>
                            <div class="step-line flex-1 h-1 bg-gray-300 mx-2"></div>
                            <div class="step-indicator w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold" data-step="4">4</div>
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span><?php echo e(__('messages.artist_selection')); ?></span>
                        <span><?php echo e(__('messages.artwork_details_step')); ?></span>
                        <span><?php echo e(__('messages.pricing_step')); ?></span>
                        <span><?php echo e(__('messages.review_submit')); ?></span>
                    </div>
                </div>

                <form action="<?php echo e(route('orders.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="order_form">
                    <?php echo csrf_field(); ?>

                    <!-- Artist Selection -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.select_your_artist')); ?></h2>
                        
                        <!-- Search Bar -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1 relative">
                                    <input type="text" id="artist_search" placeholder="<?php echo e(__('messages.search_artists')); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                </div>
                                <div class="relative">
                                    <select id="sort_artists" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                        <option value="name"><?php echo e(__('messages.sort_by_name')); ?></option>
                                        <option value="artworks"><?php echo e(__('messages.most_artworks')); ?></option>
                                        <option value="verified"><?php echo e(__('messages.verified_first')); ?></option>
                                    </select>
                                </div>
                                <button type="button" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" data-filter-btn="all">
                                    <?php echo e(__('messages.all_artists')); ?>

                                </button>
                                <button type="button" class="px-6 py-3 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors" data-filter-btn="popular">
                                    <?php echo e(__('messages.popular')); ?>

                                </button>
                            </div>
                            <div id="active_filters" class="mt-4 flex flex-wrap gap-2"></div>
                        </div>
                        
                        <!-- Hidden select for form submission -->
                        <select name="artist_id" id="artist_id" required class="hidden">
                            <option value=""><?php echo e(__('messages.select_an_artist')); ?></option>
                        </select>
                        
                        <!-- Selected Artist Summary -->
                        <div id="selected_artist_summary" class="hidden bg-green-50 border-2 border-green-500 rounded-lg p-6">
                            <div class="flex items-center space-x-4">
                                <img id="selected_artist_avatar" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-green-500">
                                <div>
                                    <h3 class="text-lg font-bold text-green-900"><?php echo e(__('messages.selected_artist')); ?></h3>
                                    <p id="selected_artist_name" class="text-green-800 font-medium"></p>
                                    <p id="selected_artist_specialty" class="text-green-600 text-sm"></p>
                                </div>
                                <button type="button" class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" data-change-artist-btn>
                                    <?php echo e(__('messages.change_artist')); ?>

                                </button>
                            </div>
                        </div>
                        
                        <!-- Artist Cards Grid -->
                        <div id="artist_cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="artist-card bg-white border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-red-400 hover:shadow-lg transition-all" 
                                     data-artist-id="<?php echo e($artist->id); ?>"
                                     data-artist-name="<?php echo e($artist->name); ?>"
                                     data-artist-specialty="<?php echo e($artist->specialization ?? 'Traditional Arts'); ?>"
                                     data-artist-artworks="<?php echo e($artist->artworks_count ?? 0); ?>"
                                     data-artist-verified="<?php echo e($artist->is_verified ? 'true' : 'false'); ?>">
                                     
                                    <div class="text-center mb-4">
                                        <img src="<?php echo e($artist->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($artist->name) . '&background=random'); ?>" alt="<?php echo e($artist->name); ?>" 
                                             class="w-20 h-20 rounded-full object-cover mx-auto border-2 border-gray-300">
                                        <h3 class="text-lg font-bold text-gray-900 mt-2"><?php echo e($artist->name); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo e($artist->specialization ?? 'Traditional Arts'); ?></p>
                                        <?php if($artist->is_verified): ?>
                                            <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?php echo e(__('messages.verified')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex justify-around text-center py-3 border-t border-gray-100">
                                        <div>
                                            <div class="text-lg font-bold text-red-600"><?php echo e($artist->artworks_count ?? 0); ?></div>
                                            <div class="text-xs text-gray-600"><?php echo e(__('messages.artworks')); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-amber-600"><?php echo e($artist->years_active ?? '5+'); ?></div>
                                            <div class="text-xs text-gray-600"><?php echo e(__('messages.years')); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-4">
                                        <button type="button" class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm" data-portfolio-btn="<?php echo e($artist->id); ?>">
                                            <?php echo e(__('messages.portfolio')); ?>

                                        </button>
                                        <button type="button" class="flex-1 px-3 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm" data-details-btn="<?php echo e($artist->id); ?>">
                                            <?php echo e(__('messages.details')); ?>

                                        </button>
                                    </div>
                                    
                                    <div class="artist-selection-indicator absolute top-2 right-2 hidden">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white">✓</div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <div id="no_results" class="hidden text-center py-8">
                            <p class="text-gray-600"><?php echo e(__('messages.no_artists_found')); ?></p>
                        </div>
                        
                        <?php $__errorArgs = ['artist_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-800"><?php echo e($message); ?></p>
                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Artwork Details -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.artwork_details_title')); ?></h2>
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.artwork_title')); ?> *</label>
                            <input type="text" name="title" id="title" required
                                   value="<?php echo e(old('title')); ?>"
                                   placeholder="<?php echo e(__('messages.enter_artwork_title')); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <?php $__errorArgs = ['title'];
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

                        <!-- Shape Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4"><?php echo e(__('messages.dimensions')); ?> *</label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="rectangle">
                                    <div class="w-12 h-8 mx-auto mb-2 bg-gray-200 rounded"></div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.rectangle')); ?></p>
                                </div>
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="square">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-gray-200 rounded"></div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.square')); ?></p>
                                </div>
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="circle">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-gray-200 rounded-full"></div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.circle')); ?></p>
                                </div>
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="oval">
                                    <div class="w-12 h-6 mx-auto mb-2 bg-gray-200 rounded-full"></div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.oval')); ?></p>
                                </div>
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="triangle">
                                    <div class="w-0 h-0 border-l-[20px] border-l-transparent border-r-[20px] border-r-transparent border-b-[30px] border-b-gray-200 mx-auto mb-2"></div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.triangle')); ?></p>
                                </div>
                                <div class="shape-option cursor-pointer text-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-colors" data-shape="custom">
                                    <div class="text-2xl text-gray-400 mx-auto mb-2">?</div>
                                    <p class="text-sm font-medium"><?php echo e(__('messages.custom')); ?></p>
                                </div>
                            </div>
                            
                            <input type="hidden" name="artwork_shape" id="artwork_shape" value="" required>
                            
                            <div id="shape_dimensions" class="space-y-4">
                                <div id="rectangle_dims" class="hidden grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="rect_width" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.width_inches')); ?></label>
                                        <input type="number" name="rect_width" id="rect_width" value="<?php echo e(old('rect_width') ?? '16'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="rect_height" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.height_inches')); ?></label>
                                        <input type="number" name="rect_height" id="rect_height" value="<?php echo e(old('rect_height') ?? '20'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div id="square_dims" class="hidden">
                                    <label for="square_size" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.side_length_inches')); ?></label>
                                    <input type="number" name="square_size" id="square_size" value="<?php echo e(old('square_size') ?? '18'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                </div>
                                
                                <div id="circle_dims" class="hidden">
                                    <label for="circle_diameter" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.diameter_inches')); ?></label>
                                    <input type="number" name="circle_diameter" id="circle_diameter" value="<?php echo e(old('circle_diameter') ?? '16'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                </div>
                                
                                <div id="oval_dims" class="hidden grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="oval_width" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.width_inches')); ?></label>
                                        <input type="number" name="oval_width" id="oval_width" value="<?php echo e(old('oval_width') ?? '20'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="oval_height" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.height_inches')); ?></label>
                                        <input type="number" name="oval_height" id="oval_height" value="<?php echo e(old('oval_height') ?? '12'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div id="triangle_dims" class="hidden grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="triangle_base" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.base_inches')); ?></label>
                                        <input type="number" name="triangle_base" id="triangle_base" value="<?php echo e(old('triangle_base') ?? '16'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="triangle_height" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.height_inches')); ?></label>
                                        <input type="number" name="triangle_height" id="triangle_height" value="<?php echo e(old('triangle_height') ?? '14'); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div id="custom_dims" class="hidden space-y-4">
                                    <div>
                                        <label for="custom_description" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.describe_custom_shape')); ?></label>
                                        <textarea name="custom_description" id="custom_description" rows="2" placeholder="<?php echo e(__('messages.custom_shape_placeholder')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"><?php echo e(old('custom_description')); ?></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="custom_max_width" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.max_width_inches')); ?></label>
                                            <input type="number" name="custom_max_width" id="custom_max_width" value="<?php echo e(old('custom_max_width')); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label for="custom_max_height" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.max_height_inches')); ?></label>
                                            <input type="number" name="custom_max_height" id="custom_max_height" value="<?php echo e(old('custom_max_height')); ?>" min="4" max="96" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="medium" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.medium')); ?> *</label>
                                <select name="medium" id="medium" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value=""><?php echo e(__('messages.select_medium')); ?></option>
                                    <?php $__currentLoopData = $mediums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $medium): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('medium') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($medium['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['medium'];
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

                            <div>
                                <label for="style" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.style')); ?> *</label>
                                <select name="style" id="style" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value=""><?php echo e(__('messages.select_style')); ?></option>
                                    <?php $__currentLoopData = $styles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('style') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($style['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['style'];
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

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.description')); ?> *</label>
                            <textarea name="description" id="description" rows="4" required
                                      placeholder="<?php echo e(__('messages.describe_your_vision')); ?>"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
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

                    <!-- Pricing -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.pricing')); ?></h2>
                        
                        <div>
                            <label for="proposed_price" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.proposed_price')); ?> *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="proposed_price" id="proposed_price" required
                                       value="<?php echo e(old('proposed_price')); ?>"
                                       min="200" max="10000" step="0.01"
                                       placeholder="200.00"
                                       class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <?php $__errorArgs = ['proposed_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600"><?php echo e(__('messages.shipping_fee')); ?></span>
                                    <span class="text-lg font-medium text-gray-900">$150.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600"><?php echo e(__('messages.additional_charges')); ?></span>
                                    <span class="text-lg font-medium text-gray-900">$100.00</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-800 font-semibold"><?php echo e(__('messages.estimated_total')); ?></span>
                                        <span class="text-xl font-bold text-gray-900" id="estimated_total">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Image -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.reference_materials')); ?></h2>
                        
                        <div>
                            <label for="reference_images" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.reference_images')); ?></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 transition-colors">
                                <input type="file" name="reference_images[]" id="reference_images" accept="image/*" multiple class="hidden">
                                <label for="reference_images" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600"><?php echo e(__('messages.click_to_upload')); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e(__('messages.png_jpg_gif_max_5mb')); ?></p>
                                </label>
                            </div>
                            <div id="image_preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.additional_notes')); ?></h2>
                        
                        <div>
                            <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.special_requirements')); ?></label>
                            <textarea name="customer_notes" id="customer_notes" rows="3"
                                      placeholder="<?php echo e(__('messages.special_requirements_placeholder')); ?>"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"><?php echo e(old('customer_notes')); ?></textarea>
                            <?php $__errorArgs = ['customer_notes'];
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

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="w-full px-8 py-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-4 focus:ring-red-300">
                            <?php echo e(__('messages.submit_commission_request')); ?>

                        </button>
                        <p class="mt-4 text-sm text-gray-600 text-center">
                            <?php echo e(__('messages.request_review_timeline')); ?>

                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\orders\create.blade.php ENDPATH**/ ?>