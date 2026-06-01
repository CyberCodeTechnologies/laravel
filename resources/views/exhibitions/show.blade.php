@extends('layouts.app')

@section('title', $exhibition->title)
@section('meta-description', $exhibition->meta_description ?? Str::limit(strip_tags($exhibition->description), 160))
@section('meta-keywords', $exhibition->meta_keywords ?? 'art exhibition, gallery exhibition, ' . $exhibition->title)
@section('meta-image', $exhibition->first_image_url)

@section('content')
<!-- Hero Section -->
<section class="relative h-96">
    <img src="{{ $exhibition->first_image_url }}" alt="{{ $exhibition->title }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $exhibition->status_color }}-100 text-{{ $exhibition->status_color }}-800">
                    {{ ucfirst($exhibition->status) }}
                </span>
                @if($exhibition->is_featured)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    {{ __('messages.featured') }}
                </span>
                @endif
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">{{ $exhibition->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-white/80">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $exhibition->date_range }}</span>
                </div>
                @if($exhibition->venue)
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $exhibition->full_location }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Exhibition Details -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Description -->
                @if($exhibition->description)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.about_exhibition') }}</h2>
                    <div class="prose prose-lg max-w-none text-gray-600">
                        {!! nl2br(e($exhibition->description)) !!}
                    </div>
                </div>
                @endif
                
                <!-- Gallery Images -->
                @if($exhibition->images && count($exhibition->images) > 1)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.exhibition_gallery') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($exhibition->image_urls as $imageUrl)
                        <div class="aspect-square rounded-lg overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $exhibition->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Artworks in Exhibition -->
                @if($exhibition->artworks && $exhibition->artworks->count() > 0)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.artworks_in_exhibition') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($exhibition->artworks as $artwork)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('public.artworks.show', $artwork->slug ?? $artwork->id) }}">
                                <div class="aspect-square">
                                    <img src="{{ $artwork->primary_image }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover">
                                </div>
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $artwork->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $artwork->artist?->name ?? __('messages.unknown_artist') }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-red-600 font-bold">{{ format_price($artwork->price, $artwork->currency) }}</span>
                                    <a href="{{ route('public.artworks.show', $artwork->slug ?? $artwork->id) }}" class="text-sm text-gray-600 hover:text-red-600">
                                        {{ __('messages.view') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.exhibition_info') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.date') }}</p>
                                <p class="font-medium text-gray-900">{{ $exhibition->date_range }}</p>
                            </div>
                        </div>
                        @if($exhibition->venue)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.venue') }}</p>
                                <p class="font-medium text-gray-900">{{ $exhibition->venue }}</p>
                            </div>
                        </div>
                        @endif
                        @if($exhibition->address)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.address') }}</p>
                                <p class="font-medium text-gray-900">{{ $exhibition->address }}</p>
                            </div>
                        </div>
                        @endif
                        @if($exhibition->artworks_count > 0)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.artworks_count') }}</p>
                                <p class="font-medium text-gray-900">{{ $exhibition->artworks_count }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Participating Artists -->
                @if($exhibition->artists && $exhibition->artists->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.participating_artists') }}</h3>
                    <div class="space-y-4">
                        @foreach($exhibition->artists as $artist)
                        <a href="{{ route('public.artists.show', $artist->slug ?? $artist->id) }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <img src="{{ $artist->avatar_url }}" alt="{{ $artist->name }}" class="w-12 h-12 rounded-full object-cover mr-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $artist->name }}</p>
                                <p class="text-sm text-gray-500">{{ $artist->specialization ?? __('messages.artist') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Share -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('messages.share_exhibition') }}</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('exhibitions.show', $exhibition->slug)) }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('exhibitions.show', $exhibition->slug)) }}&text={{ urlencode($exhibition->title) }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-500 text-white hover:bg-sky-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="mailto:?subject={{ urlencode($exhibition->title) }}&body={{ urlencode(route('exhibitions.show', $exhibition->slug)) }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-600 text-white hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Exhibitions -->
@if($relatedExhibitions && $relatedExhibitions->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('messages.related_exhibitions') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedExhibitions as $related)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="relative h-48">
                    <img src="{{ $related->first_image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $related->status_color }}-100 text-{{ $related->status_color }}-800">
                            {{ ucfirst($related->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $related->title }}</h3>
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $related->date_range }}</span>
                    </div>
                    <a href="{{ route('exhibitions.show', $related->slug) }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                        {{ __('messages.view_exhibition') }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
