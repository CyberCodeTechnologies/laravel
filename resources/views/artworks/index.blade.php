@extends('layouts.app')

@section('title', __('messages.artworks') . ' - Panchi Gallery')
@section('meta-description', __('messages.artworks_meta_description'))
@section('meta-keywords', 'Myanmar art, artwork for sale, original paintings, contemporary art, traditional art, art gallery, buy art online, Panchi Gallery')

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "{{ __('messages.artworks') }}",
    "description": "{{ __('messages.artworks_meta_description') }}",
    "url": "{{ route('public.artworks.index') }}",
    "numberOfItems": {{ $artworks->count() ?? 0 }},
    "itemListElement": [
        @foreach($artworks->take(20) as $index => $artwork)
        {
            "@@type": "ListItem",
            "position": {{ $index + 1 }},
            "item": {
                "@@type": "Product",
                "name": "{{ $artwork->title }}",
                "url": "{{ route('public.artworks.show', $artwork->slug) }}",
                "image": "{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}",
                "offers": {
                    "@@type": "Offer",
                    "price": "{{ $artwork->price }}",
                    "priceCurrency": "{{ $artwork->currency ?? 'USD' }}",
                    "availability": "{{ $artwork->isAvailable() ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut' }}"
                }
            }
        }@if($index < $artworks->count() - 1 && $index < 19),@endif
        @endforeach
    ]
}
</script>
@endsection

@section('content')
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.animate-float { animation: float 6s ease-in-out infinite; }
.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 8s ease infinite;
}
</style>

<!-- Hero Section -->
<section class="min-h-[70vh] bg-gradient-to-br from-gray-50 via-white to-gray-100 py-20 relative overflow-hidden animate-gradient">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-gray-400/30 rounded-full blur-3xl animate-float"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-gray-300/20 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-gray-400/20 rounded-full blur-3xl animate-float" style="animation-delay: -4s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-gray-400/10 to-gray-300/10 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.05]" style="background-image: linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center space-x-2 bg-black/5 backdrop-blur-md px-6 py-2 rounded-full mb-8 border border-black/10">
                <span class="w-2 h-2 bg-black rounded-full animate-pulse"></span>
                <span class="text-black/80 text-sm font-medium">{{ $artworks->count() ?? 0 }} {{ __('messages.unique_artworks') }}</span>
            </div>
            
            <h1 class="font-serif text-6xl md:text-7xl lg:text-8xl font-bold text-gray-900 mb-6 tracking-tight leading-tight">
                {!! __('messages.discover_exceptional_art') !!}
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-12 leading-relaxed">
                {{ __('messages.artworks_description') }}
            </p>
            
            <!-- Search Bar with Glassmorphism -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="artworkSearchHero" 
                            placeholder="{{ __('messages.search_artworks_placeholder') }}"
                            onkeyup="syncSearchAndApply(this.value)"
                            class="w-full px-8 py-5 pl-14 rounded-full bg-white border border-gray-200 text-gray-900 placeholder-gray-400 text-lg focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 shadow-lg"
                        >
                        <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                            <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-medium text-gray-400 bg-gray-100 rounded border border-gray-200">⌘K</kbd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-8 mt-12">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-gray-900 mb-1">{{ $artworks->count() ?? 0 }}+</div>
                    <div class="text-gray-600 text-sm font-medium">{{ __('messages.artworks') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-gray-900 mb-1">{{ $artists_count ?? 50 }}+</div>
                    <div class="text-gray-600 text-sm font-medium">{{ __('messages.artists') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-gray-900 mb-1">{{ $categories_count ?? 12 }}+</div>
                    <div class="text-gray-600 text-sm font-medium">{{ __('messages.category') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters & Gallery -->
<section class="py-16 bg-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle at 1px 1px, black 1px, transparent 0); background-size: 40px 40px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
            <div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ __('messages.browse_collection') }}
                </h2>
                <p class="text-gray-600 text-lg">{{ __('messages.browse_collection_subtitle') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <form id="sortForm" method="GET" class="flex items-center space-x-4">
                    @foreach(request()->except(['sort', 'page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <div class="relative">
                        <select name="sort" onchange="document.getElementById('sortForm').submit()" class="appearance-none px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 cursor-pointer pr-10 shadow-sm">
                            @foreach($sortOptions as $key => $option)
                                <option value="{{ $key }}" {{ request('sort') == $key ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </form>
                <div class="flex bg-gray-100 rounded-lg border border-gray-200">
                    <button onclick="setViewMode('grid')" class="view-btn px-4 py-2 bg-white rounded-lg text-sm font-medium text-gray-900 shadow-sm" data-view="grid">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16 a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button onclick="setViewMode('list')" class="view-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors" data-view="list">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="main-content-area flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <aside class="filter-sidebar lg:w-72 flex-shrink-0">
                <form id="filterForm" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-lg p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-semibold text-lg text-gray-900">{{ __('messages.filter') }}</h2>
                        <button type="button" onclick="clearFilters()" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">{{ __('messages.clear_all') }}</button>
                    </div>
                    
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-800 mb-2">{{ __('messages.search') }}</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ __('messages.search_artworks') }}"
                                onkeyup="debouncedSearch()"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 text-gray-900 placeholder-gray-400"
                            >
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('messages.category') }}</h3>
                        <div class="space-y-2">
                            @foreach ($categories as $category)
                            <label class="flex items-center group cursor-pointer">
                                <input type="checkbox" class="rounded border-gray-300 bg-white text-black focus:ring-black focus:ring-offset-0" name="category[]" value="{{ $category->id }}" {{ is_array(request('category')) && in_array($category->id, request('category')) ? 'checked' : (request('category') == $category->id ? 'checked' : '') }} onchange="applyFilters()">
                                <span class="ml-2 text-sm text-gray-800 group-hover:text-gray-900 transition-colors">{{ $category->name }}</span>
                                <span class="ml-auto text-xs text-gray-400">({{ $category->artworks_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('messages.price_range') }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('messages.min') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 text-gray-900 placeholder-gray-400">
                                <span class="text-gray-500">-</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('messages.max') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 text-gray-900 placeholder-gray-400">
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setPriceRange(0, 500)" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 transition-colors border border-gray-200 {{ request('min_price') == 0 && request('max_price') == 500 ? 'bg-black text-white border-black' : '' }}">
                                    {{ __('messages.under_price', ['price' => '$500']) }}
                                </button>
                                <button type="button" onclick="setPriceRange(500, 1000)" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 transition-colors border border-gray-200 {{ request('min_price') == 500 && request('max_price') == 1000 ? 'bg-black text-white border-black' : '' }}">
                                    $500 - $1,000
                                </button>
                                <button type="button" onclick="setPriceRange(1000, 2000)" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 transition-colors border border-gray-200 {{ request('min_price') == 1000 && request('max_price') == 2000 ? 'bg-black text-white border-black' : '' }}">
                                    $1,000 - $2,000
                                </button>
                                <button type="button" onclick="setPriceRange(2000, 5000)" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 transition-colors border border-gray-200 {{ request('min_price') == 2000 && request('max_price') == 5000 ? 'bg-black text-white border-black' : '' }}">
                                    $2,000 - $5,000
                                </button>
                                <button type="button" onclick="setPriceRange(5000, null)" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 transition-colors border border-gray-200 {{ request('min_price') == 5000 && !request('max_price') ? 'bg-black text-white border-black' : '' }}">
                                    Over $5,000
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medium -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('messages.medium') }}</h3>
                        <div class="space-y-2">
                            @foreach ($mediums as $key => $medium)
                            <label class="flex items-center group cursor-pointer">
                                <input type="checkbox" class="rounded border-gray-300 bg-white text-black focus:ring-black focus:ring-offset-0" name="medium[]" value="{{ $key }}" {{ is_array(request('medium')) && in_array($key, request('medium')) ? 'checked' : (request('medium') == $key ? 'checked' : '') }} onchange="applyFilters()">
                                <span class="ml-2 text-sm text-gray-800 group-hover:text-gray-900 transition-colors">{{ $medium }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Artist -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('messages.artist') }}</h3>
                        <select name="artist" onchange="applyFilters()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all duration-300 text-gray-900">
                            <option value="">{{ __('messages.all_artists') }}</option>
                            @foreach ($artists as $artist)
                            <option value="{{ $artist->id }}" {{ request('artist') == $artist->id ? 'selected' : '' }}>{{ $artist->name }} ({{ $artist->artworks_count }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Size -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('messages.size') }}</h3>
                        <div class="space-y-2">
                            @foreach ($sizes as $key => $size)
                            <label class="flex items-center group cursor-pointer">
                                <input type="checkbox" class="rounded border-gray-300 bg-white text-black focus:ring-black focus:ring-offset-0" name="size[]" value="{{ $key }}" {{ is_array(request('size')) && in_array($key, request('size')) ? 'checked' : (request('size') == $key ? 'checked' : '') }} onchange="applyFilters()">
                                <span class="ml-2 text-sm text-gray-800 group-hover:text-gray-900 transition-colors">{{ $size }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-black text-white font-medium rounded-xl hover:bg-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                        {{ __('messages.apply_filters') }}
                    </button>
                </form>
            </aside>
            
            <!-- Gallery Grid -->
            <div class="flex-1">
                <!-- Active Filters -->
                <div class="mb-6 flex flex-wrap items-center gap-3" id="activeFilters">
                    <span class="text-sm text-gray-600">{{ __('messages.active_filters') }}</span>
                    @if(request('category'))
                        @php $requestedCategories = is_array(request('category')) ? request('category') : [request('category')]; @endphp
                        @foreach($requestedCategories as $catId)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-100 border border-gray-200 text-gray-900">
                                {{ $categories->find($catId)->name ?? $catId }}
                                <button onclick="removeFilterValue('category', '{{ $catId }}')" class="ml-2 hover:text-gray-600 transition-colors" aria-label="Remove filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                    @endif
                    @if(request('medium'))
                        @php $requestedMediums = is_array(request('medium')) ? request('medium') : [request('medium')]; @endphp
                        @foreach($requestedMediums as $medKey)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-100 border border-gray-200 text-gray-900">
                                {{ $mediums[$medKey] ?? $medKey }}
                                <button onclick="removeFilterValue('medium', '{{ $medKey }}')" class="ml-2 hover:text-gray-600 transition-colors" aria-label="Remove filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                    @endif
                    @if(request('artist'))
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-100 border border-gray-200 text-gray-900">
                            {{ $artists->find(request('artist'))->name ?? request('artist') }}
                            <button onclick="removeFilter('artist')" class="ml-2 hover:text-gray-600 transition-colors" aria-label="Remove filter">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if(request('size'))
                        @php $requestedSizes = is_array(request('size')) ? request('size') : [request('size')]; @endphp
                        @foreach($requestedSizes as $sizeKey)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-100 border border-gray-200 text-gray-900">
                                {{ $sizes[$sizeKey] ?? $sizeKey }}
                                <button onclick="removeFilterValue('size', '{{ $sizeKey }}')" class="ml-2 hover:text-gray-600 transition-colors" aria-label="Remove filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                    @endif
                    @if(request('search'))
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-100 border border-gray-200 text-gray-900">
                            "{{ request('search') }}"
                            <button onclick="removeFilter('search')" class="ml-2 hover:text-gray-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    <span class="text-sm text-gray-500">{{ $artworks->count() ?? 0 }} results</span>
                </div>
                
                <!-- Artworks Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="artworksGrid">
                    @if($artworks->count() > 0)
                        @foreach($artworks as $artwork)
                            <div class="artwork-card-wrapper opacity-0 transform translate-y-8 transition-all duration-700 ease-out">
                                <x-artwork-card :artwork="$artwork" :show-artist="true" :show-price="true" :show-wishlist="true" :compact="false" />
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-16">
                            <div class="bg-white rounded-2xl p-12 border border-gray-200 shadow-lg">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-600 text-lg">{{ __('messages.no_artworks_found') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Pagination -->
                @if($artworks->hasPages())
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-600">
                        {{ __('messages.showing_results', ['first' => $artworks->firstItem(), 'last' => $artworks->lastItem(), 'total' => $artworks->total()]) }}
                    </p>
                    <div class="flex items-center space-x-2">
                        {{ $artworks->links('pagination::tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// View mode management
let currentViewMode = localStorage.getItem('artworksViewMode') || 'grid';

// Animate artwork cards on page load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.artwork-card-wrapper');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.remove('opacity-0', 'translate-y-8');
        }, index * 100);
    });
    
    // Set initial view mode
    setViewMode(currentViewMode, false);
});

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search functionality with debouncing
const debouncedSearch = debounce(function() {
    applyFilters();
}, 500);

// Sync hero search with sidebar search and apply
function syncSearchAndApply(value) {
    const sidebarSearch = document.querySelector('input[name="search"]');
    if (sidebarSearch) {
        sidebarSearch.value = value;
    }
    debouncedSearch();
}

// Apply filters
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Collect all form data
    for (let [key, value] of formData.entries()) {
        if (value) {
            // Check if it's an array field
            if (key.endsWith('[]')) {
                params.append(key, value);
            } else {
                params.set(key, value);
            }
        }
    }
    
    // Preserve sort parameter
    const sortParam = new URLSearchParams(window.location.search).get('sort');
    if (sortParam) {
        params.set('sort', sortParam);
    }
    
    // Navigate to filtered URL
    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

// Clear all filters
function clearFilters() {
    window.location.href = window.location.pathname;
}

// Remove specific filter
function removeFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    url.searchParams.delete(filterName + '[]');
    window.location.href = url.toString();
}

// Remove specific value from an array filter
function removeFilterValue(filterName, value) {
    const url = new URL(window.location);
    const params = url.searchParams;
    const arrayName = filterName + '[]';
    
    const values = params.getAll(arrayName);
    params.delete(arrayName);
    
    values.forEach(v => {
        if (v !== value) {
            params.append(arrayName, v);
        }
    });
    
    window.location.href = url.toString();
}

// Set price range
function setPriceRange(min, max) {
    const form = document.getElementById('filterForm');
    form.querySelector('input[name="min_price"]').value = min !== null ? min : '';
    form.querySelector('input[name="max_price"]').value = max !== null ? max : '';
    applyFilters();
}

// Set view mode
function setViewMode(mode, save = true) {
    currentViewMode = mode;
    const grid = document.getElementById('artworksGrid');
    const viewButtons = document.querySelectorAll('.view-btn');
    
    if (save) {
        localStorage.setItem('artworksViewMode', mode);
    }
    
    // Update button states
    viewButtons.forEach(btn => {
        if (btn.dataset.view === mode) {
            btn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            btn.classList.remove('text-gray-600');
        } else {
            btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
            btn.classList.add('text-gray-600');
        }
    });
    
    // Update grid layout
    if (mode === 'list') {
        grid.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
        grid.classList.add('grid-cols-1');
        
        // Convert cards to list view
        const cards = grid.querySelectorAll('.artwork-card-wrapper');
        cards.forEach(card => {
            card.classList.add('lg:flex', 'gap-6');
            const artworkCard = card.querySelector('.artwork-card');
            if (artworkCard) {
                artworkCard.classList.add('lg:flex-row', 'lg:max-w-none');
                const imageContainer = artworkCard.querySelector('.relative');
                if (imageContainer) {
                    imageContainer.classList.add('lg:w-1/3', 'lg:h-48');
                }
                const content = artworkCard.querySelector('.p-4');
                if (content) {
                    content.classList.add('lg:flex-1', 'lg:py-6');
                }
            }
        });
    } else {
        grid.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
        grid.classList.remove('grid-cols-1');
        
        // Convert cards back to grid view
        const cards = grid.querySelectorAll('.artwork-card-wrapper');
        cards.forEach(card => {
            card.classList.remove('lg:flex', 'gap-6');
            const artworkCard = card.querySelector('.artwork-card');
            if (artworkCard) {
                artworkCard.classList.remove('lg:flex-row', 'lg:max-w-none');
                const imageContainer = artworkCard.querySelector('.relative');
                if (imageContainer) {
                    imageContainer.classList.remove('lg:w-1/3', 'lg:h-48');
                }
                const content = artworkCard.querySelector('.p-4');
                if (content) {
                    content.classList.remove('lg:flex-1', 'lg:py-6');
                }
            }
        });
    }
}

// Price range quick filters
function applyPriceRange(min, max) {
    const minInput = document.querySelector('input[name="min_price"]');
    const maxInput = document.querySelector('input[name="max_price"]');
    
    if (minInput) minInput.value = min;
    if (maxInput) maxInput.value = max;
    
    applyFilters();
}

// Handle price range button clicks
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flex-wrap button').forEach(button => {
        if (button.textContent.includes('$')) {
            button.addEventListener('click', function() {
                const text = this.textContent.trim();
                
                // Parse price range
                if (text.includes('Under')) {
                    const price = text.replace(/[^0-9]/g, '');
                    applyPriceRange(0, price - 1);
                } else if (text.includes('over')) {
                    const price = text.replace(/[^0-9]/g, '');
                    applyPriceRange(price + 1, 999999);
                } else if (text.includes('-')) {
                    const [min, max] = text.split('-').map(p => p.replace(/[^0-9]/g, ''));
                    applyPriceRange(min, max);
                }
            });
        }
    });
});

// Keyboard shortcut for search (Cmd+K / Ctrl+K)
document.addEventListener('keydown', function(e) {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        document.querySelector('input[name="search"]').focus();
    }
});

// Enhanced search functionality
document.querySelector('input[name="search"]')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.artwork-card-wrapper');
    
    // Only do client-side filtering if we have results on page
    if (cards.length > 0) {
        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const artist = card.querySelector('.artist-name')?.textContent.toLowerCase() || '';
            const medium = card.querySelector('.text-sm.text-gray-500')?.textContent.toLowerCase() || '';
            
            if (title.includes(searchTerm) || artist.includes(searchTerm) || medium.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
});

// AJAX wishlist toggle (if user is logged in)
function toggleWishlist(artworkId) {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        showToast('Please login to add items to wishlist', 'warning');
        return;
    }
    
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ artwork_id: artworkId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateWishlistButton(artworkId, data.in_wishlist);
            updateWishlistCount(data.wishlist_count);
        } else {
            showToast(data.message || 'Error updating wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating wishlist', 'error');
    });
}

// Update wishlist button state
function updateWishlistButton(artworkId, inWishlist) {
    const buttons = document.querySelectorAll(`[onclick*="toggleWishlist(${artworkId})"]`);
    buttons.forEach(button => {
        const svg = button.querySelector('svg');
        if (inWishlist) {
            svg.classList.add('text-red-600');
            svg.classList.remove('text-gray-700');
        } else {
            svg.classList.add('text-gray-700');
            svg.classList.remove('text-red-600');
        }
    });
}

// Update wishlist count
function updateWishlistCount(count) {
    const badge = document.getElementById('wishlist-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) {
        // Create container if it doesn't exist
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    toast.className = `${bgColors[type] || bgColors.success} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full');
    });
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Quick view modal functionality
function showArtworkModal(artworkId) {
    fetch(`/api/artworks/${artworkId}`)
        .then(response => response.json())
        .then(artwork => {
            // Create modal if it doesn't exist
            let modal = document.getElementById('artworkModal');
            if (!modal) {
                modal = createArtworkModal();
                document.body.appendChild(modal);
            }
            
            // Populate modal with artwork data
            populateArtworkModal(modal, artwork);
            
            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching artwork details:', error);
            showToast('Error loading artwork details', 'error');
        });
}

function createArtworkModal() {
    const modal = document.createElement('div');
    modal.id = 'artworkModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900" id="modalTitle"></h3>
                    <button onclick="closeArtworkModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div id="modalImage"></div>
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>
    `;
    return modal;
}

function populateArtworkModal(modal, artwork) {
    document.getElementById('modalTitle').textContent = artwork.title;
    document.getElementById('modalImage').innerHTML = `
        <img src="${artwork.image}" alt="${artwork.title}" class="w-full h-auto rounded-lg">
    `;
    document.getElementById('modalContent').innerHTML = `
        <div class="space-y-4">
            <p><strong>Artist:</strong> ${artwork.artist}</p>
            <p><strong>Medium:</strong> ${artwork.medium}</p>
            <p><strong>Size:</strong> ${artwork.size || 'N/A'}</p>
            <p><strong>Year:</strong> ${artwork.year || 'N/A'}</p>
            <p><strong>Price:</strong> $${artwork.price}</p>
            <p>${artwork.description}</p>
            <div class="flex space-x-4">
                <button onclick="buyNow(${artwork.id})" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                    Buy Now
                </button>
                <button onclick="toggleWishlist(${artwork.id})" class="border border-gray-300 px-6 py-2 rounded-lg hover:bg-gray-50">
                    Add to Wishlist
                </button>
            </div>
        </div>
    `;
}

function closeArtworkModal() {
    const modal = document.getElementById('artworkModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeArtworkModal();
    }
});

// Close modal on background click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('artworkModal');
    if (modal && e.target === modal) {
        closeArtworkModal();
    }
});
</script>
@endpush
