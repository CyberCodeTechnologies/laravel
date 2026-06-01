<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'url' => null,
    'title' => null,
    'description' => null,
    'image' => null,
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
    'url' => null,
    'title' => null,
    'description' => null,
    'image' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="social-share-buttons flex flex-wrap gap-2">
    <!-- Facebook -->
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode($url)); ?>&title=<?php echo e(urlencode($title)); ?>&description=<?php echo e(urlencode($description)); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors"
       title="Share on Facebook">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
    </a>

    <!-- Messenger -->
    <a href="https://www.facebook.com/dialog/send?link=<?php echo e(urlencode($url)); ?>&app_id=<?php echo e(config('services.facebook.app_id')); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition-colors"
       title="Share on Messenger">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.03 2 11c0 2.94 1.55 5.55 4 7.22V22l4.5-2.25c.5.08 1 .25 1.5.25 5.52 0 10-4.03 10-9s-4.48-9-10-9zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
        </svg>
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/?text=<?php echo e(urlencode($title . ' ' . $url)); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors"
       title="Share on WhatsApp">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <!-- Telegram -->
    <a href="https://t.me/share/url?url=<?php echo e(urlencode($url)); ?>&text=<?php echo e(urlencode($title)); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-400 text-white hover:bg-blue-500 transition-colors"
       title="Share on Telegram">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
        </svg>
    </a>

    <!-- Viber -->
    <a href="viber://forward?text=<?php echo e(urlencode($title . ' ' . $url)); ?>"
       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-600 text-white hover:bg-purple-700 transition-colors"
       title="Share on Viber">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.398.002C9.473.04 5.331.354 3.014 2.467 1.294 4.182.68 6.828.6 9.73c-.06 2.22-.132 6.384 3.882 7.524h.004l-.004 1.726s-.024.698.434.84c.548.172.868-.352 1.392-.918.286-.31.68-.756 1.017-1.104 2.808.238 4.965-.304 5.213-.39.568-.19 3.782-.596 4.304-4.864.542-4.48-.262-7.32-1.718-8.61l-.006-.004c-.39-.388-2.818-1.528-6.818-1.528zM8.69 7.578c.416 0 .75.336.75.75v3.75c0 .414-.334.75-.75.75s-.75-.336-.75-.75v-3.75c0-.414.334-.75.75-.75zm4.5 0c.416 0 .75.336.75.75v3.75c0 .414-.334.75-.75.75s-.75-.336-.75-.75v-3.75c0-.414.334-.75.75-.75zm-2.22 7.312c1.532 0 2.438.732 2.438 1.328 0 .596-.906.596-2.438.596-1.532 0-2.438 0-2.438-.596 0-.596.906-1.328 2.438-1.328z"/>
        </svg>
    </a>

    <!-- Copy Link -->
    <button onclick="navigator.clipboard.writeText('<?php echo e($url); ?>'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy Link', 2000);"
            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-600 text-white hover:bg-gray-700 transition-colors"
            title="Copy Link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
    </button>
</div>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/components/social-share.blade.php ENDPATH**/ ?>