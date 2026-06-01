@props([
    'model',
    'class' => '',
    'showCode' => false,
    'convertToCurrent' => true
])

@php
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
@endphp

<span {{ $attributes->merge(['class' => $class]) }} data-base-amount="{{ $model->price ?? 0 }}" data-base-currency="{{ $model->currency ?? 'USD' }}">
    {{ $formattedPrice }}
    @if($showCode)
        <span class="text-xs text-gray-500 ml-1">{{ $currency }}</span>
    @endif
</span>
