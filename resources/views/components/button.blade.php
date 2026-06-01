@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'fullWidth' => false,
    'class' => ''
])

@php
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
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $widthClass . ' ' . $disabledClass . ' ' . $class]) }}
       {{ $disabled ? 'tabindex="-1" aria-disabled="true"' : '' }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $widthClass . ' ' . $disabledClass . ' ' . $class]) }}
            {{ $disabled ? 'disabled' : '' }}>
        {{ $slot }}
    </button>
@endif
