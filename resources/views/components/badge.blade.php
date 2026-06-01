@props([
    'variant' => 'default',
    'size' => 'md',
    'class' => ''
])

@php
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
@endphp

<span class="{{ $baseClasses }} {{ $variantClasses }} {{ $sizeClasses }} {{ $class }}">
    @if($variant === 'verified')
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    @endif
    {{ $slot }}
</span>
