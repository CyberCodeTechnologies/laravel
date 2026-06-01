@props([
    'artwork' => null,
    'showArtist' => true,
    'showPrice' => true,
    'showWishlist' => true,
    'compact' => false
])

@if(!$artwork)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury">
        <div class="skeleton h-64"></div>
        <div class="p-4">
            <div class="skeleton h-4 mb-2"></div>
            <div class="skeleton h-3 w-3/4"></div>
        </div>
    </div>
@else
    <div class="artwork-card group bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury {{ $compact ? 'compact' : '' }}" 
         data-status="{{ (is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'approved')) }}"
         data-price="{{ is_object($artwork) ? $artwork->price : ($artwork['price'] ?? 0) }}"
         data-views="{{ is_object($artwork) ? $artwork->views_count : ($artwork['views_count'] ?? 0) }}"
         data-date="{{ is_object($artwork) ? $artwork->created_at->timestamp : (strtotime($artwork['created_at'] ?? 'now')) }}">
        <!-- Image Container -->
        <div class="relative image-hover-zoom {{ $compact ? 'h-48' : 'h-64' }}">
            <a href="{{ route('public.artworks.show', is_object($artwork) ? ($artwork->slug ?? $artwork->id) : ($artwork['slug'] ?? $artwork['id'] ?? '#')) }}" class="block w-full h-full">
                <img 
                    src="{{ is_object($artwork) ? ($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')) : ($artwork['image'] ?? asset('images/placeholder-artwork.jpg')) }}" 
                    alt="{{ is_object($artwork) ? $artwork->title : ($artwork['title'] ?? __('messages.untitled')) }}"
                    class="w-full h-full object-cover"
                    loading="lazy"
                >
            </a>
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex space-x-2">
                    @if($showWishlist)
                        <button 
                            onclick="toggleWishlist({{ is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 'null') }})"
                            class="p-2 bg-white rounded-full hover:bg-gray-100 transition-colors"
                            title="{{ auth()->check() && auth()->user()->wishlistItems()->where('artwork_id', is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0))->exists() ? __('messages.remove_from_wishlist') : __('messages.add_to_wishlist') }}"
                            data-artwork-id="{{ is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0) }}"
                        >
                            <svg class="w-5 h-5 {{ auth()->check() && auth()->user()->wishlistItems()->where('artwork_id', is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0))->exists() ? 'text-red-600' : 'text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Status Badges -->
            @if((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'sold')
                <div class="absolute top-2 left-2">
                    <x-badge variant="sold" size="sm">{{ __('messages.sold') }}</x-badge>
                </div>
            @elseif((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'new')
                <div class="absolute top-2 left-2">
                    <x-badge variant="new" size="sm">{{ __('messages.new_badge') }}</x-badge>
                </div>
            @endif
            
            @if(isset($artwork->is_verified) && $artwork->is_verified)
                <div class="absolute top-2 right-2">
                    <x-badge variant="verified" size="sm">{{ __('messages.verified') }}</x-badge>
                </div>
            @endif
        </div>
        
        <!-- Content -->
        <div class="p-4">
            <!-- Title -->
            <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1 group-hover:text-black transition-colors">
                <a href="{{ route('public.artworks.show', is_object($artwork) ? ($artwork->slug ?? $artwork->id) : ($artwork['slug'] ?? $artwork['id'] ?? '#')) }}" class="hover:underline">
                    {{ is_object($artwork) ? $artwork->title : ($artwork['title'] ?? __('messages.untitled')) }}
                </a>
            </h3>
            
            <!-- Artist -->
            @if($showArtist && isset($artwork->artist) && $artwork->artist)
                <p class="text-sm text-gray-600 mb-2">
                    @if(is_object($artwork))
                        <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}" class="hover:text-black transition-colors">
                            {{ $artwork->artist->name }}
                        </a>
                    @else
                        <span class="text-gray-600">{{ $artwork['artist'] ?? __('messages.unknown_artist') }}</span>
                    @endif
                </p>
            @endif
            
            <!-- Meta Info -->
            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                <span>{{ is_object($artwork) ? ($artwork->medium ?? 'Mixed Media') : ($artwork['medium'] ?? 'Mixed Media') }}</span>
                <span>{{ is_object($artwork) ? ($artwork->year ?? date('Y')) : ($artwork['year'] ?? date('Y')) }}</span>
            </div>
            
            <!-- Price -->
            @if($showPrice)
                <div class="flex items-center justify-between">
                    <div>
                        @if(is_object($artwork) ? $artwork->price : ($artwork['price'] ?? null))
                            <span class="text-xl font-bold text-black">
                                {{ \App\Helpers\CurrencyHelper::formatArtworkPrice($artwork) }}
                            </span>
                        @else
                            <span class="text-xl font-bold text-black">{{ __('messages.price_on_request') }}</span>
                        @endif
                    </div>
                    
                    @if((is_object($artwork) ? $artwork->status : ($artwork['status'] ?? 'available')) === 'available')
                        <button type="button"
                                onclick="buyNow({{ is_object($artwork) ? $artwork->id : ($artwork['id'] ?? 0) }})"
                                class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                            {{ __('messages.buy_now') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
