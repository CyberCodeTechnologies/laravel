@extends('layouts.app')

@section('title', 'Resale Marketplace - Panchi Gallery')
@section('meta-description', 'Buy and sell pre-owned artworks in the Panchi Gallery resale marketplace. Find authenticated pieces from verified collectors.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">
                Resale Marketplace
            </h1>
            <p class="text-xl text-purple-100 max-w-3xl mx-auto">
                Discover pre-owned artworks from verified collectors. Buy and sell with confidence on our secure platform.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('resales.create') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>
                    List Your Artwork
                </a>
                <a href="#browse" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors">
                    Browse Listings
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold text-purple-600 mb-2">250+</div>
                <div class="text-gray-600">Active Listings</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-indigo-600 mb-2">180+</div>
                <div class="text-gray-600">Verified Sellers</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-green-600 mb-2">$2.5M</div>
                <div class="text-gray-600">Total Sales</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-orange-600 mb-2">98%</div>
                <div class="text-gray-600">Seller Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Browse Resales -->
<section id="browse" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
                    
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search resales..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="space-y-2">
                            <input type="range" 
                                   name="price_range" 
                                   min="0" 
                                   max="10000" 
                                   value="{{ request('max_price', 10000) }}"
                                   class="w-full"
                                   id="price-slider">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>$0</span>
                                <span id="price-value">${{ number_format(request('max_price', 10000)) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="paintings" class="mr-2">
                                <span class="text-sm">Paintings</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="sculptures" class="mr-2">
                                <span class="text-sm">Sculptures</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="digital-art" class="mr-2">
                                <span class="text-sm">Digital Art</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="photography" class="mr-2">
                                <span class="text-sm">Photography</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Condition -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                        <select name="condition" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">All Conditions</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                    
                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        Apply Filters
                    </button>
                    
                    <a href="{{ route('resales.index') }}" class="block w-full text-center mt-2 text-gray-600 hover:text-gray-800">
                        Clear Filters
                    </a>
                </div>
            </aside>
            
            <!-- Resale Listings -->
            <main class="lg:w-3/4">
                <!-- Results Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            @if(isset($resales) && $resales->count() > 0)
                                {{ $resales->count() }} Resale Listings
                            @else
                                Browse Resale Listings
                            @endif
                        </h2>
                        @if(request()->hasAny(['search', 'categories', 'condition', 'max_price']))
                            <p class="text-gray-600 mt-1">Active filters applied</p>
                        @endif
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2">
                        <button class="p-2 rounded-lg bg-purple-100 text-purple-600">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="p-2 rounded-lg text-gray-400 hover:text-gray-600">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Resale Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if(isset($resales) && $resales->count() > 0)
                        @foreach($resales as $resale)
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                <div class="relative group">
                                    <a href="{{ route('resales.show', $resale->id) }}">
                                        <img src="{{ $resale->primary_image }}" 
                                             alt="{{ $resale->artwork->title }}" 
                                             loading="lazy"
                                             class="w-full h-64 object-cover">
                                        
                                        <!-- Overlay Actions -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <div class="flex space-x-3">
                                                <button class="bg-white text-gray-900 p-3 rounded-full hover:bg-gray-100 transition-colors">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="bg-white text-gray-900 p-3 rounded-full hover:bg-gray-100 transition-colors">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        @if($resale->isVerified())
                                            <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Verified
                                            </div>
                                        @endif
                                        
                                        <!-- Price Badge -->
                                        <div class="absolute bottom-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-medium">
                                            {{ $resale->formatted_asking_price }}
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="p-6">
                                    <a href="{{ route('resales.show', $resale->id) }}">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-purple-600 transition-colors">
                                            {{ $resale->artwork->title }}
                                        </h3>
                                    </a>
                                    <p class="text-gray-600 mb-3">
                                        by
                                        @if($resale->artwork->artist)
                                            <a href="{{ route('public.artists.show', $resale->artwork->artist->slug ?? $resale->artwork->artist->id) }}" class="hover:text-purple-600">
                                                {{ $resale->artwork->artist->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">{{ __('messages.unknown_artist') }}</span>
                                        @endif
                                    </p>
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-purple-600">{{ $resale->formatted_asking_price }}</span>
                                            @if($resale->minimum_price)
                                                <span class="text-sm text-gray-500 block">Min: {{ $resale->formatted_minimum_price }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500">Listed</span>
                                            <div class="text-xs text-gray-600">{{ $resale->listed_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ $resale->owner->avatar_url }}" 
                                                 alt="{{ $resale->owner->name }}" 
                                                 class="w-6 h-6 rounded-full">
                                            <span class="text-sm text-gray-600">{{ $resale->owner->name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1 text-yellow-500">
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star-half-alt text-sm"></i>
                                            <span class="text-xs text-gray-600 ml-1">4.5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Placeholder resale listings -->
                        @for($i = 1; $i <= 6; $i++)
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <div class="relative group">
                                    <div class="w-full h-64 bg-gradient-to-br from-purple-300 to-indigo-400 flex items-center justify-center">
                                        <i class="fas fa-image text-white text-4xl"></i>
                                    </div>
                                    <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verified
                                    </div>
                                    <div class="absolute bottom-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        $2,500
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Premium Artwork {{ $i }}</h3>
                                    <p class="text-gray-600 mb-3">by Artist Name</p>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-purple-600">$2,500</span>
                                            <span class="text-sm text-gray-500 block">Min: $2,000</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500">Listed</span>
                                            <div class="text-xs text-gray-600">2 days ago</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                                            <span class="text-sm text-gray-600">Seller Name</span>
                                        </div>
                                        <div class="flex items-center space-x-1 text-yellow-500">
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star-half-alt text-sm"></i>
                                            <span class="text-xs text-gray-600 ml-1">4.5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
                
                <!-- Pagination -->
                @if(isset($resales) && $resales->hasPages())
                    <div class="mt-12">
                        {{ $resales->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How Resale Works</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Buy and sell pre-owned artworks safely and securely
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-list text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">List Your Artwork</h3>
                <p class="text-gray-600">
                    Create a listing with photos, description, and your asking price. We'll verify ownership before publishing.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-handshake text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Connect with Buyers</h3>
                <p class="text-gray-600">
                    Interested buyers can make offers or purchase directly. All communications happen through our secure platform.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Transaction</h3>
                <p class="text-gray-600">
                    We handle payment processing and ensure safe delivery. Funds are released only after successful delivery.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-serif font-bold mb-6">
            Ready to Sell Your Artwork?
        </h2>
        <p class="text-xl mb-8">
            Join our trusted marketplace and reach thousands of art enthusiasts worldwide
        </p>
        <a href="{{ route('resales.create') }}" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
            Start Selling Today
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Price slider
    const priceSlider = document.getElementById('price-slider');
    const priceValue = document.getElementById('price-value');
    
    priceSlider.addEventListener('input', function() {
        priceValue.textContent = '$' + number_format(this.value);
    });
    
    // Format number function
    function number_format(num) {
        return new Intl.NumberFormat().format(num);
    }
    
    // Auto-submit filters on change
    document.querySelectorAll('select, input[type="checkbox"]').forEach(element => {
        element.addEventListener('change', function() {
            if (this.type !== 'checkbox' || document.querySelectorAll('input[type="checkbox"]:checked').length > 0) {
                this.form.submit();
            }
        });
    });
    
    // Search on enter key
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
</script>
@endpush
