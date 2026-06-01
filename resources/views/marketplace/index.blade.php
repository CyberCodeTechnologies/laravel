@extends('layouts.app')

@section('title', __('messages.marketplace_title'))
@section('meta-description', __('messages.marketplace_meta_description'))
@section('meta-keywords', 'art resale marketplace, buy pre-owned art, sell artwork, secondary art market, authenticated art, art investment, Panchi Gallery marketplace, Myanmar art resale')
@section('meta-image', asset('images/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Art Marketplace - Panchi Gallery",
    "url": "{{ route('marketplace.index') }}",
    "description": "Buy and sell authenticated pre-owned artwork on the Panchi Gallery marketplace. Verified ownership history and certificates of authenticity."
}
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in-up">
            <p class="text-elegant text-sm font-medium text-white/60 mb-4 tracking-widest uppercase">{{ __('messages.secondary_market') }}</p>
            <h1 class="font-serif text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
                {{ __('messages.art_marketplace') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                {{ __('messages.marketplace_hero_description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <x-button variant="secondary" size="lg" href="{{ route('artist.artworks.create') }}" class="btn-elegant">
                    {{ __('messages.sell_your_art') }}
                </x-button>
                <x-button variant="outline" size="lg" class="border-white text-white hover:bg-white hover:text-black transition-all duration-300">
                    {{ __('messages.browse_collection') }}
                </x-button>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">{{ __('messages.verified_100') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('messages.verified_artworks_text') }}</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">{{ __('messages.secure_transactions') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('messages.secure_transactions_text') }}</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">{{ __('messages.ownership_history_title') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('messages.ownership_history_text') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar Filters -->
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-serif text-lg font-semibold text-gray-900">{{ __('messages.filters') }}</h2>
                        <button onclick="clearFilters()" class="text-sm text-gray-600 hover:text-black transition-colors font-medium">
                            {{ __('messages.clear_all') }}
                        </button>
                    </div>

                    <form id="filter-form" onsubmit="applyFilters(event)">
                        <!-- Verified Only -->
                        <div class="mb-6">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="verified" value="1" checked class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">{{ __('messages.verified_artworks_only') }}</span>
                            </label>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm">{{ __('messages.price_range') }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="min_price" placeholder="{{ __('messages.min_price_placeholder') }}" class="input-elegant w-full px-4 py-2.5 rounded-lg text-sm">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="max_price" placeholder="{{ __('messages.max_price_placeholder') }}" class="input-elegant w-full px-4 py-2.5 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm">{{ __('messages.category') }}</h3>
                            <div class="space-y-3">
                                @foreach([__('messages.painting'), __('messages.photography'), __('messages.sculpture'), __('messages.digital_art')] as $category)
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="categories[]" value="{{ $category }}" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                        <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">{{ $category }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Seller Type -->
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3 text-sm">{{ __('messages.seller_type') }}</h3>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="artist" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">{{ __('messages.original_artist') }}</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="gallery" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">{{ __('messages.gallery') }}</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="seller_type[]" value="collector" class="rounded border-gray-300 text-black focus:ring-black w-4 h-4">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">{{ __('messages.collector_seller') }}</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </aside>
            
            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <p class="text-gray-600">
                            {{ __('messages.showing_results_of', ['count' => $resales->count(), 'total' => $resales->total()]) }}
                        </p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Sort -->
                        <select name="sort" class="input-elegant px-4 py-2.5 rounded-lg text-sm" onchange="applyFilters()">
                            <option value="featured">{{ __('messages.sort_by_featured') }}</option>
                            <option value="price-low">{{ __('messages.sort_price_low_high') }}</option>
                            <option value="price-high">{{ __('messages.sort_price_high_low') }}</option>
                            <option value="newest">{{ __('messages.sort_newest_first') }}</option>
                            <option value="ending-soon">{{ __('messages.sort_ending_soon') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Marketplace Grid -->
                @if($resales->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($resales as $resale)
                        <div class="card-elegant rounded-2xl overflow-hidden group">
                            <!-- Image Container -->
                            <div class="relative image-hover-zoom h-72">
                                <a href="{{ route('marketplace.show', $resale->artwork->slug) }}" class="block w-full h-full">
                                    <img src="{{ $resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" alt="{{ $resale->artwork->title }}" class="w-full h-full object-cover">
                                </a>

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-4">
                                    <div class="flex space-x-3">
                                        <button onclick="event.preventDefault(); quickView({{ $resale->artwork->id }})" class="p-3 bg-white/95 backdrop-blur-sm rounded-full hover:bg-white transition-colors shadow-lg" title="Quick View">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="event.preventDefault(); toggleWishlist({{ $resale->artwork->id }})" class="p-3 bg-white/95 backdrop-blur-sm rounded-full hover:bg-white transition-colors shadow-lg" title="Add to Wishlist">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="absolute top-3 left-3 space-y-2">
                                    @if($resale->is_verified)
                                        <x-badge variant="verified" size="sm">{{ __('messages.verified_badge') }}</x-badge>
                                    @endif
                                    @if($resale->minimum_price && $resale->asking_price > $resale->minimum_price)
                                        <x-badge variant="trending" size="sm">{{ __('messages.negotiable') }}</x-badge>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <!-- Title and Artist -->
                                <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1 group-hover:text-black transition-colors">
                                    <a href="{{ route('marketplace.show', $resale->artwork->slug) }}" class="hover:underline">
                                        {{ $resale->artwork->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mb-3">{{ __('messages.by_artist') }} {{ $resale->artwork->artist ? $resale->artwork->artist->first_name . ' ' . $resale->artwork->artist->last_name : __('messages.unknown_artist') }}</p>

                                <!-- Seller Info -->
                                <div class="flex items-center text-xs text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $resale->owner ? $resale->owner->first_name . ' ' . $resale->owner->last_name : __('messages.unknown_seller') }}
                                </div>

                                <!-- Price and Stats -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        @if($resale->minimum_price)
                                            <div class="flex flex-col">
                                                <span class="text-xl font-bold text-black">
                                                    {{ session('currency', 'USD') === 'MMK' ? number_format($resale->asking_price * 2100) . ' MMK' : '$' . number_format($resale->asking_price) }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ __('messages.min_price_label') }} {{ session('currency', 'USD') === 'MMK' ? number_format($resale->minimum_price * 2100) . ' MMK' : '$' . number_format($resale->minimum_price) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xl font-bold text-black">
                                                {{ session('currency', 'USD') === 'MMK' ? number_format($resale->asking_price * 2100) . ' MMK' : '$' . number_format($resale->asking_price) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Activity Stats -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                                    <span>{{ $resale->artwork->views_count ?? 0 }} {{ __('messages.views_count') }}</span>
                                    <span>{{ __('messages.listed_time') }} {{ $resale->created_at ? $resale->created_at->diffForHumans() : __('messages.recently') }}</span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <a href="{{ route('marketplace.show', $resale->artwork->slug) }}" class="btn-luxury w-full text-center px-4 py-2.5 rounded-lg text-sm font-medium text-white">
                                        {{ __('messages.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($resales->hasPages())
                <div class="mt-12">
                    {{ $resales->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-16">
                    <p class="text-gray-500 text-lg">{{ __('messages.no_resale_listings') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.simple_process') }}</p>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                {{ __('messages.how_it_works_title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.how_it_works_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['step' => '1', 'title' => __('messages.step_1_title'), 'description' => __('messages.step_1_description')],
                ['step' => '2', 'title' => __('messages.step_2_title'), 'description' => __('messages.step_2_description')],
                ['step' => '3', 'title' => __('messages.step_3_title'), 'description' => __('messages.step_3_description')],
                ['step' => '4', 'title' => __('messages.step_4_title'), 'description' => __('messages.step_4_description')]
            ] as $item)
                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-16 h-16">
                        <div class="absolute inset-0 bg-black rounded-full transform group-hover:scale-110 transition-transform duration-300"></div>
                        <div class="relative w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            {{ $item['step'] }}
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-black transition-colors">{{ $item['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $item['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function applyFilters(event) {
    if (event) {
        event.preventDefault();
    }

    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Add form data
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }

    // Add sort parameter
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        params.append('sort', sortSelect.value);
    }

    // Build URL with parameters
    const queryString = params.toString();
    const newUrl = queryString ? `{{ route('marketplace.index') }}?${queryString}` : '{{ route('marketplace.index') }}';

    // Show loading state
    const container = document.querySelector('.grid');
    if (container) {
        container.style.opacity = '0.5';
    }

    // Navigate to new URL
    window.location.href = newUrl;
}

function clearFilters() {
    // Reset all form inputs
    document.getElementById('filter-form').reset();

    // Reset sort select
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.selectedIndex = 0;
    }

    // Navigate to base URL
    window.location.href = '{{ route('marketplace.index') }}';
}
</script>
@endpush
