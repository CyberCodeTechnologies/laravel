<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'amount',
    'currency' => null,
    'showCode' => false,
    'class' => '',
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
    'amount',
    'currency' => null,
    'showCode' => false,
    'class' => '',
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
    $displayCurrency = $currency ?? $currencyService->getCurrentCurrency();
    
    if ($convertToCurrent && $currency && $currency !== $displayCurrency) {
        $displayAmount = $currencyService->convert($amount, $currency, $displayCurrency);
    } else {
        $displayAmount = $amount;
    }
    
    $formattedAmount = $currencyService->format($displayAmount, $displayCurrency);
    $symbol = $currencyService->getSymbol($displayCurrency);
?>

<span <?php echo e($attributes->merge(['class' => $class])); ?>>
    <?php echo e($formattedAmount); ?>

    <?php if($showCode): ?>
        <span class="text-xs text-gray-500 ml-1"><?php echo e($displayCurrency); ?></span>
    <?php endif; ?>
</span>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\components\currency.blade.php ENDPATH**/ ?>