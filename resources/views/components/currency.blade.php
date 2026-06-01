@props([
    'amount',
    'currency' => null,
    'showCode' => false,
    'class' => '',
    'convertToCurrent' => true
])

@php
    $currencyService = app(\App\Services\CurrencyService::class);
    $displayCurrency = $currency ?? $currencyService->getCurrentCurrency();
    
    if ($convertToCurrent && $currency && $currency !== $displayCurrency) {
        $displayAmount = $currencyService->convert($amount, $currency, $displayCurrency);
    } else {
        $displayAmount = $amount;
    }
    
    $formattedAmount = $currencyService->format($displayAmount, $displayCurrency);
    $symbol = $currencyService->getSymbol($displayCurrency);
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $formattedAmount }}
    @if($showCode)
        <span class="text-xs text-gray-500 ml-1">{{ $displayCurrency }}</span>
    @endif
</span>
