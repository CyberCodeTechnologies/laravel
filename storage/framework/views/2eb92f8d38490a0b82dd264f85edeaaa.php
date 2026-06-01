<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => null,
    'data' => [],
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
    'type' => null,
    'data' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($type && $data): ?>
    <?php
        $schema = match($type) {
            'organization' => \App\Helpers\SchemaHelper::organization(),
            'artwork' => \App\Helpers\SchemaHelper::artwork($data),
            'artist' => \App\Helpers\SchemaHelper::artist($data),
            'collection' => \App\Helpers\SchemaHelper::collection($data),
            'blog' => \App\Helpers\SchemaHelper::blogPost($data),
            'product' => \App\Helpers\SchemaHelper::product($data),
            'localBusiness' => \App\Helpers\SchemaHelper::localBusiness(),
            'breadcrumb' => \App\Helpers\SchemaHelper::breadcrumb($data),
            default => null,
        };
    ?>

    <?php if($schema): ?>
        <?php echo \App\Helpers\SchemaHelper::render($schema); ?>

    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/components/schema-markup.blade.php ENDPATH**/ ?>