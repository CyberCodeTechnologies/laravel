@props([
    'items' => [],
    'variant' => 'default'
])

@php
    $variants = [
        'default' => 'border-gray-300',
        'ownership' => 'border-green-300',
        'verification' => 'border-blue-300',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['default'];
@endphp

<div class="relative">
    <!-- Timeline Line -->
    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-{{ $variantClass }}"></div>
    
    <!-- Timeline Items -->
    <div class="space-y-6">
        @foreach($items as $index => $item)
            <div class="relative flex items-start">
                <!-- Timeline Dot -->
                <div class="flex items-center justify-center w-8 h-8 bg-white border-2 border-{{ $variantClass }} rounded-full z-10">
                    @if($item['status'] ?? null === 'completed')
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    @elseif($item['status'] ?? null === 'current')
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                    @else
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="ml-6 flex-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 card-luxury">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-900">{{ $item['title'] ?? '' }}</h4>
                            <span class="text-sm text-gray-500">{{ $item['date'] ?? '' }}</span>
                        </div>
                        
                        <!-- Description -->
                        @if(isset($item['description']))
                            <p class="text-sm text-gray-600 mb-3">{{ $item['description'] }}</p>
                        @endif
                        
                        <!-- Meta Information -->
                        @if(isset($item['meta']))
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                @if(isset($item['meta']['price']))
                                    <span class="font-medium text-gray-900">
                                        {{ session('currency', 'USD') === 'MMK' ? number_format($item['meta']['price'] * 2100) . ' MMK' : '$' . number_format($item['meta']['price']) }}
                                    </span>
                                @endif
                                
                                @if(isset($item['meta']['location']))
                                    <span>📍 {{ $item['meta']['location'] }}</span>
                                @endif
                                
                                @if(isset($item['meta']['certificate']))
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Badge -->
                        @if(isset($item['badge']))
                            <div class="mt-3">
                                <x-badge variant="{{ $item['badge']['variant'] ?? 'default' }}" size="sm">
                                    {{ $item['badge']['text'] }}
                                </x-badge>
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        @if(isset($item['actions']))
                            <div class="mt-3 flex space-x-2">
                                @foreach($item['actions'] as $action)
                                    <x-button 
                                        variant="{{ $action['variant'] ?? 'outline' }}" 
                                        size="sm"
                                        href="{{ $action['url'] ?? null }}"
                                        onclick="{{ $action['onclick'] ?? null }}"
                                    >
                                        {{ $action['text'] }}
                                    </x-button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @if(empty($items))
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p>No timeline items available</p>
        </div>
    @endif
</div>
