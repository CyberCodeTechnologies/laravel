@extends('layouts.app')

@section('title', $artwork->title . ' - Panchi Gallery')
@section('meta-description', 'View ' . $artwork->title . ' by ' . ($artwork->artist ? $artwork->artist->name : 'Unknown Artist') . ' - Original artwork available for purchase.')
@section('meta-keywords', $artwork->title . ', ' . ($artwork->artist ? $artwork->artist->name : '') . ', Myanmar art, artwork for sale, ' . ($artwork->category ? $artwork->category->name : '') . ', ' . ($artwork->style ?? 'contemporary art') . ', original painting')
@section('meta-image', $artwork->primary_image ?? asset('images/og-default.jpg'))

@section('schema')
<x-schema-markup type="product" :data="$artwork" />
<x-schema-markup type="artwork" :data="$artwork" />
        },
        {
            "@@type": "PropertyValue",
            "name": "Style",
            "value": "{{ $artwork->style ?? 'Contemporary' }}"
        }
    ]
}
</script>
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <nav class="text-sm text-gray-600" aria-label="Breadcrumb">
        <ol itemscope itemtype="https://schema.org/BreadcrumbList" class="flex items-center space-x-2">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="link-elegant" itemprop="item">
                    <span itemprop="name">{{ __('messages.home') }}</span>
                </a>
                <meta itemprop="position" content="1">
            </li>
            <li class="text-gray-400">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('public.artworks.index') }}" class="link-elegant" itemprop="item">
                    <span itemprop="name">{{ __('messages.artworks') }}</span>
                </a>
                <meta itemprop="position" content="2">
            </li>
            <li class="text-gray-400">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-black font-medium" itemprop="name">{{ $artwork->title }}</span>
                <meta itemprop="position" content="3">
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Image Gallery -->
        <div>
            <div class="mb-6 relative overflow-hidden rounded-2xl" id="imageZoomContainer">
                <img id="mainImage"
                     src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}"
                     alt="{{ $artwork->title }}"
                     class="w-full rounded-2xl cursor-zoom-in hover:opacity-95 transition-opacity"
                     onmousemove="zoomImage(event)"
                     onmouseleave="resetZoom()">
                <div id="zoomLens" class="absolute w-32 h-32 border-2 border-black pointer-events-none hidden bg-white bg-opacity-30"></div>
            </div>

            @if(count($artwork->image_urls) > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($artwork->image_urls as $index => $image)
                        @if($index > 0)
                            <img src="{{ $image }}"
                                 alt="{{ $artwork->title }} - View {{ $index + 1 }}"
                                 class="w-full h-24 object-cover rounded-xl cursor-pointer border-2 border-transparent hover:border-black transition-all duration-300"
                                 onclick="changeMainImage('{{ $image }}')"
                                 loading="lazy">
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <!-- Availability Badge -->
            @if($artwork->isAvailable())
                <span class="inline-block bg-green-50 text-green-700 text-xs px-4 py-2 rounded-full mb-4 font-medium border border-green-200">
                    {{ __('messages.in_stock') }}
                </span>
            @elseif($artwork->isSold())
                <span class="inline-block bg-red-50 text-red-700 text-xs px-4 py-2 rounded-full mb-4 font-medium border border-red-200">
                    {{ __('messages.sold') }}
                </span>
            @else
                <span class="inline-block bg-yellow-50 text-yellow-700 text-xs px-4 py-2 rounded-full mb-4 font-medium border border-yellow-200">
                    {{ ucfirst($artwork->status) }}
                </span>
            @endif

            <h1 class="font-serif text-4xl md:text-5xl font-bold mb-4">{{ $artwork->title }}</h1>

            <!-- Artist -->
            @if($artwork->artist)
                <p class="text-lg text-gray-600 mb-6">
                    {{ __('messages.by_artist') }} <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}" class="link-elegant font-medium">
                        {{ $artwork->artist->name }}
                    </a>
                </p>
            @endif

            <!-- Price -->
            <p class="text-3xl font-bold text-black mb-8">
                {{ $currencyService->format($currencyService->convertToCurrent($artwork->price, $artwork->currency ?? 'USD')) }}
            </p>

            <!-- Description -->
            <p class="text-gray-600 mb-8 leading-relaxed">{{ $artwork->description ?? __('messages.no_description') }}</p>

            <!--Specifications -->
            <div class="border-t border-b border-gray-200 py-6 mb-8">
                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.medium') }}</p>
                        <p class="font-medium text-gray-900">{{ $artwork->medium ? (is_array($artwork->medium) ? implode(', ', $artwork->medium) : $artwork->medium) : __('messages.oil_on_canvas') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.dimensions') }}</p>
                        <p class="font-medium text-gray-900">{{ $artwork->dimensions ?? __('messages.na') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.year') }}</p>
                        <p class="font-medium text-gray-900">{{ $artwork->year ?? __('messages.na') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.condition') }}</p>
                        <p class="font-medium text-gray-900">{{ $artwork->condition ?? __('messages.excellent') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.style') }}</p>
                        <p class="font-medium text-gray-900">{{ $artwork->style ?? __('messages.contemporary') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">{{ __('messages.certificate') }}</p>
                        <p class="font-medium text-gray-900">
                            @if($artwork->is_verified)
                                <span class="text-green-600 flex items-center gap-1">
                                    <i class="fas fa-certificate"></i>
                                    {{ __('messages.included') }}
                                </span>
                            @else
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-check-circle text-gray-400"></i>
                                    {{ __('messages.included') }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quantity Selector -->
            @if($artwork->isAvailable())
                <div class="mb-8">
                    <label for="quantity" class="block text-sm font-medium mb-3 text-gray-700">{{ __('messages.quantity') }}</label>
                    <div class="flex items-center border border-gray-300 rounded-xl w-max">
                        <button onclick="changeQuantity(-1)" class="px-5 py-3 hover:bg-gray-100 transition-colors text-lg font-medium" aria-label="Decrease quantity">−</button>
                        <input type="number" id="quantity" value="1" min="1" max="10" readonly
                               class="w-20 text-center border-x border-gray-300 focus:outline-none text-lg font-medium">
                        <button onclick="changeQuantity(1)" class="px-5 py-3 hover:bg-gray-100 transition-colors text-lg font-medium" aria-label="Increase quantity">+</button>
                    </div>
                    @if(!$artwork->is_digital)
                        <p class="text-sm text-gray-600 mt-2">
                            @if($artwork->isInStock())
                                <span class="text-green-600">{{ $artwork->getAvailableStock() }} {{ __('messages.items_available') }}</span>
                            @else
                                <span class="text-red-600">{{ __('messages.out_of_stock') }}</span>
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="text-blue-600">{{ __('messages.digital_artwork_unlimited') }}</span>
                        </p>
                    @endif
                </div>

                @php
                    $inWishlist = auth()->check() && auth()->user()->wishlistItems()->where('artwork_id', $artwork->id)->exists();
                @endphp

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <button onclick="addToCart({{ $artwork->id }}, this)"
                        class="flex-1 btn-luxury text-white px-8 py-4 rounded-xl font-medium text-lg hover:shadow-lg transition-all duration-300"
                        aria-label="Add to cart">
                    <i class="fas fa-shopping-cart mr-2"></i>{{ __('messages.add_to_cart') }}
                </button>
                <button onclick="buyNow({{ $artwork->id }})"
                        class="flex-1 btn-elegant text-black border border-gray-300 px-8 py-4 rounded-xl font-medium text-lg hover:border-black hover:shadow-lg transition-all duration-300"
                        aria-label="Buy now">
                    <i class="fas fa-bolt mr-2"></i>{{ __('messages.buy_now') }}
                </button>
                <button onclick="toggleWishlist({{ $artwork->id }})"
                        id="wishlistBtn"
                        data-artwork-id="{{ $artwork->id }}"
                        class="px-5 py-4 border border-gray-300 rounded-xl hover:border-black transition-all duration-300 {{ $inWishlist ? 'text-red-600 border-red-300 bg-red-50' : 'text-gray-700' }}"
                        aria-label="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart w-6 h-6 flex items-center justify-center"></i>
                </button>
            </div>
            @else
                <button disabled
                        class="w-full bg-gray-200 text-gray-500 px-8 py-4 rounded-xl cursor-not-allowed font-medium mb-8">
                    {{ __('messages.sold_out') }}
                </button>
            @endif

            <!-- QR Code Section -->
            <div class="mb-8 p-8 bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl text-center shadow-lg">
                <h3 class="font-semibold text-white mb-6 flex items-center justify-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('messages.scan_to_visit') }}
                </h3>
                <div class="flex justify-center mb-4">
                    <div class="bg-white p-4 rounded-xl shadow-inner">
                        <img src="{{ $qrCodeUrl }}" 
                             alt="{{ __('messages.artwork_qr_code') }}" 
                             class="rounded-lg w-60 h-60">
                    </div>
                </div>
                <p class="text-sm text-gray-400">{{ __('messages.scan_to_view_artwork') }}</p>
            </div>

            <!-- Social Sharing -->
            <div class="border-t border-gray-200 pt-8">
                <p class="text-sm font-medium mb-4 text-gray-700">{{ __('messages.share_artwork') }}</p>
                <x-social-share 
                    :url="route('artworks.show', $artwork->slug)"
                    :title="$artwork->title"
                    :description="$artwork->description"
                    :image="$artwork->images[0] ?? null"
                />
            </div>
        </div>
    </div>

            <!-- Artist Profile Section -->
    @if($artwork->artist)
        <div class="mt-20 border-t border-gray-200 pt-16" id="artist-profile">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.about_the_artist') }}</p>
            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-8">{{ __('messages.meet_the_creator') }}</h2>
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full transform scale-105"></div>
                    <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}" class="block relative group">
                        <img src="{{ $artwork->artist->avatar_url }}"
                             alt="{{ $artwork->artist->name }}"
                             class="relative w-40 h-40 rounded-full object-cover ring-4 ring-white shadow-xl group-hover:scale-105 transition-transform duration-300">
                    </a>
                </div>
                <div class="flex-1">
                    <h3 class="font-serif text-2xl font-semibold text-gray-900 mb-3">
                        <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}" class="hover:text-black transition-colors">
                            {{ $artwork->artist->name }}
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $artwork->artist->bio ?? __('messages.no_bio') }}</p>
                    <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}"
                       class="inline-flex items-center btn-elegant px-6 py-3 rounded-lg font-medium">
                        {{ __('messages.view_all_artworks') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Related Artworks -->
    @if(isset($relatedArtworks) && $relatedArtworks->count() > 0)
        <div class="mt-20 border-t border-gray-200 pt-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.you_may_also_like') }}</p>
            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-8">{{ __('messages.related_artworks') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedArtworks as $related)
                    <div class="group">
                        <a href="{{ route('public.artworks.show', $related->slug) }}">
                            <div class="relative overflow-hidden rounded-2xl mb-4">
                                <img src="{{ $related->primary_image ?? asset('images/placeholder-artwork.jpg') }}"
                                     alt="{{ $related->title }}"
                                     class="w-full aspect-square object-cover rounded-2xl group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h3 class="font-medium text-gray-900 group-hover:text-black transition-colors mb-1">{{ $related->title }}</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $currencyService->format($currencyService->convertToCurrent($related->price, $related->currency ?? 'USD')) }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recently Viewed Artworks -->
    @if(session()->has('recently_viewed') && count(session('recently_viewed')) > 1)
        <div class="mt-20 border-t border-gray-200 pt-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.your_history') }}</p>
            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-8">{{ __('messages.recently_viewed') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $recentlyViewedIds = array_slice(array_reverse(session('recently_viewed')), 0, 4);
                    $recentlyViewed = \App\Models\Artwork::whereIn('id', $recentlyViewedIds)
                        ->where('id', '!=', $artwork->id)
                        ->approved()
                        ->get();
                @endphp
                @foreach($recentlyViewed as $recent)
                    <div class="group">
                        <a href="{{ route('public.artworks.show', $recent->slug) }}">
                            <div class="relative overflow-hidden rounded-2xl mb-4">
                                <img src="{{ $recent->primary_image ?? asset('images/placeholder-artwork.jpg') }}"
                                     alt="{{ $recent->title }}"
                                     class="w-full aspect-square object-cover rounded-2xl group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h3 class="font-medium text-gray-900 group-hover:text-black transition-colors mb-1">{{ $recent->title }}</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $currencyService->format($currencyService->convertToCurrent($recent->price, $recent->currency ?? 'USD')) }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Store recently viewed artwork
document.addEventListener('DOMContentLoaded', function() {
    const artworkId = {{ $artwork->id }};
    let recentlyViewed = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');

    // Remove current artwork if already in list
    recentlyViewed = recentlyViewed.filter(id => id !== artworkId);

    // Add current artwork to beginning
    recentlyViewed.unshift(artworkId);

    // Keep only last 10 artworks
    if (recentlyViewed.length > 10) {
        recentlyViewed = recentlyViewed.slice(0, 10);
    }

    localStorage.setItem('recentlyViewed', JSON.stringify(recentlyViewed));
});

/**
 * Change quantity by delta
 * @param {number} delta - Change amount
 */
function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 10;
    let val = parseInt(input.value) + delta;
    if (val < min) val = min;
    if (val > max) val = max;
    input.value = val;
}

/**
 * Change the main product image
 * @param {string} src - Image source URL
 */
function changeMainImage(src) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) mainImage.src = src;
}

/**
 * Share artwork on Facebook
 */
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    showToast('{{ __('messages.opening_facebook') }}', 'info');
}

/**
 * Share artwork on Twitter
 */
function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    showToast('{{ __('messages.opening_twitter') }}', 'info');
}

/**
 * Share artwork on Pinterest
 */
function shareOnPinterest() {
    const url = encodeURIComponent(window.location.href);
    const description = encodeURIComponent(document.title);
    window.open(`https://pinterest.com/pin/create/button/?url=${url}&description=${description}`, '_blank');
    showToast('{{ __('messages.opening_pinterest') }}', 'info');
}

/**
 * Copy artwork URL to clipboard
 */
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('{{ __('messages.link_copied') }}', 'success');
    }).catch(err => {
        console.error('Failed to copy link:', err);
        showToast('{{ __('messages.failed_copy_link') }}', 'error');
    });
}

/**
 * Update cart badge count in header
 */
function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.getElementById('cart-badge');
            if (cartBadge) {
                const count = data.count || 0;
                cartBadge.textContent = count;
                if (count > 0) {
                    cartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

/**
 * Update wishlist badge count in header
 */
function updateWishlistCount() {
    const wishlistBadge = document.getElementById('wishlist-badge');
    if (wishlistBadge) {
        const currentCount = parseInt(wishlistBadge.textContent) || 0;
        wishlistBadge.textContent = currentCount + 1;
        wishlistBadge.classList.remove('hidden');
    }
}

/**
 * Zoom image on mouse hover
 * @param {MouseEvent} event - Mouse event
 */
function zoomImage(event) {
    const container = document.getElementById('imageZoomContainer');
    const image = document.getElementById('mainImage');
    const lens = document.getElementById('zoomLens');

    const rect = container.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const lensWidth = lens.offsetWidth;
    const lensHeight = lens.offsetHeight;

    lens.style.display = 'block';
    lens.style.left = (x - lensWidth / 2) + 'px';
    lens.style.top = (y - lensHeight / 2) + 'px';

    const zoomLevel = 2;
    image.style.transformOrigin = `${(x / rect.width) * 100}% ${(y / rect.height) * 100}%`;
    image.style.transform = `scale(${zoomLevel})`;
}

/**
 * Reset image zoom on mouse leave
 */
function resetZoom() {
    const image = document.getElementById('mainImage');
    const lens = document.getElementById('zoomLens');

    lens.style.display = 'none';
    image.style.transform = 'scale(1)';
}

</script>
@endpush
@endsection
