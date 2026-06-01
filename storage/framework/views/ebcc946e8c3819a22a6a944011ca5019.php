<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'variant' => 'default'
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
    'items' => [],
    'variant' => 'default'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $variants = [
        'default' => 'border-gray-300',
        'ownership' => 'border-green-300',
        'verification' => 'border-blue-300',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['default'];
?>

<div class="relative">
    <!-- Timeline Line -->
    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-<?php echo e($variantClass); ?>"></div>
    
    <!-- Timeline Items -->
    <div class="space-y-6">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative flex items-start">
                <!-- Timeline Dot -->
                <div class="flex items-center justify-center w-8 h-8 bg-white border-2 border-<?php echo e($variantClass); ?> rounded-full z-10">
                    <?php if($item['status'] ?? null === 'completed'): ?>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <?php elseif($item['status'] ?? null === 'current'): ?>
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                    <?php else: ?>
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="ml-6 flex-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 card-luxury">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-900"><?php echo e($item['title'] ?? ''); ?></h4>
                            <span class="text-sm text-gray-500"><?php echo e($item['date'] ?? ''); ?></span>
                        </div>
                        
                        <!-- Description -->
                        <?php if(isset($item['description'])): ?>
                            <p class="text-sm text-gray-600 mb-3"><?php echo e($item['description']); ?></p>
                        <?php endif; ?>
                        
                        <!-- Meta Information -->
                        <?php if(isset($item['meta'])): ?>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <?php if(isset($item['meta']['price'])): ?>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e(session('currency', 'USD') === 'MMK' ? number_format($item['meta']['price'] * 2100) . ' MMK' : '$' . number_format($item['meta']['price'])); ?>

                                    </span>
                                <?php endif; ?>
                                
                                <?php if(isset($item['meta']['location'])): ?>
                                    <span>📍 <?php echo e($item['meta']['location']); ?></span>
                                <?php endif; ?>
                                
                                <?php if(isset($item['meta']['certificate'])): ?>
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge -->
                        <?php if(isset($item['badge'])): ?>
                            <div class="mt-3">
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['variant' => ''.e($item['badge']['variant'] ?? 'default').'','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($item['badge']['variant'] ?? 'default').'','size' => 'sm']); ?>
                                    <?php echo e($item['badge']['text']); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Actions -->
                        <?php if(isset($item['actions'])): ?>
                            <div class="mt-3 flex space-x-2">
                                <?php $__currentLoopData = $item['actions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => ''.e($action['variant'] ?? 'outline').'','size' => 'sm','href' => ''.e($action['url'] ?? null).'','onclick' => ''.e($action['onclick'] ?? null).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($action['variant'] ?? 'outline').'','size' => 'sm','href' => ''.e($action['url'] ?? null).'','onclick' => ''.e($action['onclick'] ?? null).'']); ?>
                                        <?php echo e($action['text']); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <?php if(empty($items)): ?>
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p>No timeline items available</p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\timeline.blade.php ENDPATH**/ ?>