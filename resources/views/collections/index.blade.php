@extends('layouts.app')

@section('title', __('messages.collections_title'))
@section('meta-description', __('messages.collections_meta_description'))
@section('meta-keywords', 'art collections, curated art, Myanmar art collections, themed artwork, art series, Panchi Gallery collections, buy art collections')
@section('meta-image', asset('images/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "{{ __('messages.collections_title') }} - Panchi Gallery",
    "url": "{{ route('collections') }}",
    "description": "{{ __('messages.collections_meta_description') }}"
}
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-elegant text-sm font-medium text-white/60 mb-4 tracking-widest uppercase">
            {{ __('messages.curated_collections') }}
        </p>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
            {{ __('messages.explore_collections') }}
        </h1>
        <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
            {{ __('messages.collections_description') }}
        </p>
    </div>
</section>

<!-- Collections Grid -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $categories = \App\Models\Category::withCount('artworks')->where('is_active', true)->orderBy('artworks_count', 'desc')->get();
        @endphp

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    @php
                        $categoryKey = 'category_' . Str::slug($category->name, '_');
                        $categoryDescKey = $categoryKey . '_desc';
                    @endphp
                    <a href="{{ route('public.artworks.index', ['category' => $category->id]) }}" class="group block">
                        <div class="relative overflow-hidden rounded-2xl aspect-[4/3] mb-4">
                            @if($category->image)
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/placeholder-category.jpg') }}" alt="{{ __("messages.$categoryKey") }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-6">
                                <h3 class="font-serif text-2xl font-bold mb-2">{{ __("messages.$categoryKey") }}</h3>
                                <p class="text-white/80 text-sm mb-4">{{ $category->artworks_count }} {{ __('messages.items') }}</p>
                                <span class="inline-flex items-center text-sm font-medium border-b border-white/50 pb-1 group-hover:border-white transition-colors">
                                    {{ __('messages.view_collection') }}
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @if($category->description)
                            <p class="text-gray-600 text-sm line-clamp-2">{{ __("messages.$categoryDescKey") }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.no_collections') }}</h3>
                <p class="text-gray-600">{{ __('messages.no_collections_description') }}</p>
            </div>
        @endif
    </div>
</section>

<!-- Featured Artworks Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-4">
                {{ __('messages.featured_artworks') }}
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.featured_artworks_description') }}
            </p>
        </div>

        @php
            $featuredArtworks = \App\Models\Artwork::with('artist')->available()->where('is_featured', true)->latest()->take(8)->get();
        @endphp

        @if($featuredArtworks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredArtworks as $artwork)
                    @include('partials.artwork-card', ['artwork' => $artwork])
                @endforeach
            </div>

            <div class="text-center mt-12">
                <x-button variant="primary" size="lg" href="{{ route('public.artworks.index') }}">
                    {{ __('messages.browse_all_artworks') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </x-button>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('messages.no_featured_artworks') }}</p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-black text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-serif text-3xl font-bold mb-4">
            {{ __('messages.start_your_collection') }}
        </h2>
        <p class="text-gray-300 mb-8 text-lg">
            {{ __('messages.collection_cta_description') }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <x-button variant="secondary" size="lg" href="{{ route('public.artworks.index') }}">
                {{ __('messages.explore_artworks') }}
            </x-button>
            <x-button variant="outline" size="lg" href="{{ route('about') }}" class="border-white text-white hover:bg-white hover:text-black">
                {{ __('messages.learn_more') }}
            </x-button>
        </div>
    </div>
</section>
@endsection
