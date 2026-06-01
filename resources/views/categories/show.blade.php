@extends('layouts.app')

@section('title', $category->name . ' - Panchi Gallery')
@section('meta-description', 'Browse ' . $category->name . ' artworks at Panchi Gallery. Discover beautiful ' . $category->name . ' pieces from talented Myanmar artists.')
@section('meta-keywords', $category->name . ' art, Myanmar ' . $category->name . ', ' . $category->name . ' for sale, original ' . $category->name . ', buy ' . $category->name . ' online, Panchi Gallery')
@section('meta-image', asset('images/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "{{ $category->name }}",
    "url": "{{ route('categories.show', $category->slug) }}",
    "description": "Browse {{ $category->name }} artworks at Panchi Gallery. Discover beautiful {{ $category->name }} pieces from talented Myanmar artists."
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700" itemprop="item">
                            <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1">
                    </li>
                    <li>
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700" itemprop="item">
                            <span itemprop="name">Categories</span>
                        </a>
                        <meta itemprop="position" content="2">
                    </li>
                    <li>
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="text-gray-900 font-medium" itemprop="name">{{ $category->name }}</span>
                        <meta itemprop="position" content="3">
                    </li>
                </ol>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            <p class="text-gray-600 mt-2">
                {{ $category->description ?? 'Explore our collection of ' . $category->name . ' artworks' }}
            </p>
        </div>

        <!-- Filters -->
        <div class="mb-8 bg-white p-4 rounded-lg border">
            <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Medium</label>
                    <select name="medium" class="border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black">
                        <option value="">All Mediums</option>
                        <option value="Oil Painting">Oil Painting</option>
                        <option value="Acrylic">Acrylic</option>
                        <option value="Watercolor">Watercolor</option>
                        <option value="Digital">Digital</option>
                        <option value="Photography">Photography</option>
                        <option value="Sculpture">Sculpture</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" placeholder="Min" 
                               class="border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black"
                               value="{{ request('min_price') }}">
                        <input type="number" name="max_price" placeholder="Max"
                               class="border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black"
                               value="{{ request('max_price') }}">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black">
                        <option value="created_at">Newest First</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="title">Title A-Z</option>
                        <option value="views">Most Viewed</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Artworks Grid -->
        @if(isset($artworks) && $artworks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($artworks as $artwork)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                        <!-- Image -->
                        <a href="{{ route('public.artworks.show', $artwork->slug) }}" class="block aspect-square bg-gray-100">
                            @if($artwork->primary_image)
                                <img src="{{ $artwork->primary_image }}" 
                                     alt="{{ $artwork->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1">
                                <a href="{{ route('public.artworks.show', $artwork->slug) }}" class="hover:text-black transition-colors">
                                    {{ $artwork->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $artwork->artist->name ?? 'Unknown Artist' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">
                                    {{ $artwork->formatted_price }}
                                </span>
                                @if($artwork->is_verified)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            {{ $artworks->links() }}
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 21a9 9 0 110-18 9 9 0 010 18z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No artworks found</h3>
                <p class="text-gray-600 mb-6">No artworks match your current filters.</p>
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
