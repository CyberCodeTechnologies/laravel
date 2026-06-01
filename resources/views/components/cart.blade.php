@props([
    'cart' => null,
    'showCheckout' => true,
])

@if($cart && $cart->item_count > 0)
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Shopping Cart') }}</h2>
            <span class="text-sm text-gray-500">{{ __(':count items', ['count' => $cart->item_count]) }}</span>
        </div>
        
        <!-- Cart Items -->
        <div class="space-y-4 mb-6">
            @foreach($cart->items as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <!-- Artwork Image -->
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                        @if($item->artwork->primary_image)
                            <img src="{{ asset($item->artwork->primary_image) }}" 
                                 alt="{{ $item->artwork->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0 2.828 2.828 2 2.828 2.828L16 16m-4-4.586a2 2 0 00-2.828-2.828-2.828-2.828L8 8m4 4.586a2 2 0 012.828 0 2.828 2.828 2.828L16 16z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Artwork Details -->
                    <div class="flex-1">
                        <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1">
                            {{ $item->artwork_title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            {{ __('messages.quantity') }}: {{ $item->quantity }}
                        </p>
                        
                        <p class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $item->formatted_price }}
                        </p>
                        
                        <p class="text-sm text-gray-600">
                            {{ __('messages.subtotal') }}: {{ $item->formatted_subtotal }}
                        </p>
                    </div>
                    
                    <!-- Remove Button -->
                    <div class="flex items-center">
                        <button onclick="removeFromCart({{ $item->id }})" 
                                class="text-red-600 hover:text-red-800 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 5.14A2 2 0 00-2.828-2.828L10.172 10.172a2 2 0 00-2.828-2.828L5.828 19.172a2 2 0 002.828 2.828l-12.14 12.14a2 2 0 002.828 2.828L19 7z"></path>
                            </svg>
                            {{ __('messages.remove_item') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Cart Summary -->
        <div class="border-t border-gray-200 pt-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold text-gray-900">{{ __('messages.total') }}</span>
                <span class="text-lg font-bold text-black">{{ $cart->formatted_total }}</span>
            </div>
            
            @if($showCheckout)
                <!-- Checkout Button -->
                <div class="flex justify-center">
                    <a href="{{ route('checkout.index') }}" 
                       class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition-colors text-center font-medium">
                        {{ __('messages.proceed_to_checkout') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2h-2a2 2 0 00-2-2v-2a2 2 0 00-2h14a2 2 0 002h14a2 2 0 002v2a2 2 0 002h-2a2 2 0 00-2-2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Your cart is empty') }}</h3>
        <p class="text-gray-600 mb-6">{{ __('Add some beautiful artworks to get started') }}</p>
        <a href="{{ route('public.artworks.index') }}" 
           class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition-colors inline-block">
            {{ __('Browse Artworks') }}
        </a>
    </div>
@endif
