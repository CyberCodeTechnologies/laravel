<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'fullWidth' => false,
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
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'fullWidth' => false,
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
        'primary' => 'bg-black text-white hover:bg-gray-800 border border-black',
        'secondary' => 'bg-white text-black hover:bg-gray-50 border border-black',
        'outline' => 'bg-transparent text-black hover:bg-black hover:text-white border border-black',
        'ghost' => 'bg-transparent text-black hover:bg-gray-100 border border-transparent',
        'luxury' => 'bg-black text-white hover:bg-gray-900 border border-black shadow-lg hover:shadow-xl',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        'xl' => 'px-8 py-4 text-xl',
    ];

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $widthClass = $fullWidth ? 'w-full' : '';
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" 
       <?php echo e($attributes->merge(['class' => $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $widthClass . ' ' . $disabledClass . ' ' . $class])); ?>

       <?php echo e($disabled ? 'tabindex="-1" aria-disabled="true"' : ''); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" 
            <?php echo e($attributes->merge(['class' => $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $widthClass . ' ' . $disabledClass . ' ' . $class])); ?>

            <?php echo e($disabled ? 'disabled' : ''); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\button.blade.php ENDPATH**/ ?>