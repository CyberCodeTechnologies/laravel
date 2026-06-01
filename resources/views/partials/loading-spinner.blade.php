{{-- Loading Spinner Component --}}
{{-- Usage: @include('partials.loading-spinner', ['size' => 'medium', 'color' => 'indigo']) --}}

<div class="flex items-center justify-center {{ $class ?? '' }}">
    <div class="animate-spin rounded-full 
        @if($size === 'small')
            h-4 w-4 border-2
        @elseif($size === 'large')
            h-12 w-12 border-4
        @else
            h-8 w-8 border-3
        @endif
        
        border-{{ $color ?? 'gray' }}-200 
        border-t-{{ $color ?? 'indigo' }}-600">
    </div>
    @if(isset($text))
        <span class="ml-3 text-{{ $color ?? 'gray' }}-600 {{ $size === 'small' ? 'text-sm' : '' }}">
            {{ $text }}
        </span>
    @endif
</div>
