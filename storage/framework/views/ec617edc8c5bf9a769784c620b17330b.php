<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model',
    'class' => '',
    'showCode' => false,
    'convertToCurrent' => true
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
    'model',
    'class' => '',
    'showCode' => false,
    'convertToCurrent' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $currencyService = app(\App\Services\CurrencyService::class);
    $currentCurrency = $currencyService->getCurrentCurrency();
    
    if ($model && method_exists($model, 'getPriceInCurrency')) {
        $price = $convertToCurrent ? $model->getPriceInCurrency($currentCurrency) : $model->price;
        $currency = $convertToCurrent ? $currentCurrency : ($model->currency ?? 'USD');
        $formattedPrice = $currencyService->format($price, $currency);
    } elseif ($model) {
        $price = $model->price ?? 0;
        $currency = $model->currency ?? 'USD';
        $formattedPrice = $currencyService->format($price, $currency);
    } else {
        $formattedPrice = $currencyService->format(0, 'USD');
        $currency = 'USD';
    }
?>

<span <?php echo e($attributes->merge(['class' => $class])); ?> data-base-amount="<?php echo e($model->price ?? 0); ?>" data-base-currency="<?php echo e($model->currency ?? 'USD'); ?>">
    <?php echo e($formattedPrice); ?>

    <?php if($showCode): ?>
        <span class="text-xs text-gray-500 ml-1"><?php echo e($currency); ?></span>
    <?php endif; ?>
</span>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\price.blade.php ENDPATH**/ ?>