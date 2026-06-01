<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'default',
    'size' => 'md',
    'class' => ''
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
    'variant' => 'default',
    'size' => 'md',
    'class' => ''
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
        'default' => 'bg-gray-100 text-gray-800 border border-gray-200',
        'verified' => 'bg-green-50 text-green-700 border border-green-200',
        'sold' => 'bg-red-50 text-red-700 border border-red-200',
        'new' => 'bg-blue-50 text-blue-700 border border-blue-200',
        'luxury' => 'bg-black text-white border border-black',
        'trending' => 'bg-orange-50 text-orange-700 border border-orange-200',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $baseClasses = 'inline-flex items-center font-medium rounded-full';
    $variantClasses = $variants[$variant] ?? $variants['default'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
?>

<span class="<?php echo e($baseClasses); ?> <?php echo e($variantClasses); ?> <?php echo e($sizeClasses); ?> <?php echo e($class); ?>">
    <?php if($variant === 'verified'): ?>
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    <?php endif; ?>
    <?php echo e($slot); ?>

</span>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\badge.blade.php ENDPATH**/ ?>