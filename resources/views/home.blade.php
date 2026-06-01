@extends('layouts.app')

@section('title', cms_content('home_title', __('messages.home_title')))
@section('meta-description', cms_content('home_meta_description', __('messages.home_meta_description')))
@section('meta-keywords', 'Panchi Gallery, Myanmar art gallery, buy art online, original artwork, contemporary art, traditional art, art marketplace, verified art certificates')
@section('meta-image', asset('images/og-default.jpg'))

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Hero Slider -->
    <div class="absolute inset-0">
        @if($heroArtworks->count() > 0)
            @foreach($heroArtworks as $index => $artwork)
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
                    <img src="{{ $artwork->primary_image }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover">
                </div>
            @endforeach
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
            <img src="{{ asset('images/placeholder-artwork.jpg') }}" alt="Featured Artwork" class="w-full h-full object-cover">
        @endif
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Hero Text -->
                <div class="max-w-3xl animate-fade-in-up">
                <div class="inline-flex items-center bg-red-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6 animate-pulse-soft">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    {{ __('messages.limited_time_offer') }}
                </div>
                <p class="text-elegant text-sm md:text-base font-medium text-white/80 mb-4 tracking-widest uppercase">
                    {{ cms_content('hero_premium_marketplace', __('messages.hero_premium_marketplace')) }}
                </p>
                <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold text-white mb-6 leading-tight">
                    {!! nl2br(e(cms_content('hero_title', __('messages.hero_title')))) !!}
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl leading-relaxed">
                    {{ cms_content('hero_description', __('messages.hero_description')) }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.artworks.index') }}" class="btn-luxury px-8 py-4 text-white font-medium rounded-lg text-lg text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        {{ cms_content('shop_now', __('messages.shop_now')) }}
                    </a>
                    <a href="#flash-sale" class="px-8 py-4 bg-red-600/90 backdrop-blur-sm border-2 border-red-500 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 text-lg text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ cms_content('flash_sale', __('messages.flash_sale')) }}
                    </a>
                </div>
                <div class="mt-8 flex flex-wrap items-center gap-4 text-white/80 text-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        {{ __('messages.verified_artists_badge') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        {{ __('messages.authentic_certificates') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        {{ __('messages.secure_payments') }}
                    </div>
                </div>
            </div>

            <!-- Right: Featured Artwork of the Week -->
            @if($featuredOfWeek)
                <div class="hidden lg:block animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20 shadow-2xl hover:bg-white/15 transition-all duration-500 group">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ __('messages.featured_of_week') }}
                            </span>
                        </div>
                        <div class="relative overflow-hidden rounded-2xl mb-4">
                            <img src="{{ $featuredOfWeek->primary_image }}"
                                 alt="{{ $featuredOfWeek->title }}"
                                 class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full animate-pulse">
                                {{ __('messages.hot') }}
                            </div>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-white mb-2">{{ $featuredOfWeek->title }}</h3>
                        <p class="text-white/70 text-sm mb-3">by {{ $featuredOfWeek->artist->name ?? __('messages.unknown_artist') }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-white">${{ number_format($featuredOfWeek->price) }}</span>
                                <span class="text-white/50 text-sm line-through ml-2">${{ number_format($featuredOfWeek->price * 1.15) }}</span>
                            </div>
                            <a href="{{ route('public.artworks.show', $featuredOfWeek) }}" class="bg-white text-black px-5 py-2.5 rounded-xl font-medium hover:bg-gray-100 transition-colors flex items-center gap-2 group-hover:shadow-lg">
                                {{ __('messages.view_details') }}
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Slider Indicators -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
        @if($heroArtworks->count() > 0)
            @foreach($heroArtworks as $index => $artwork)
                <button
                    class="h-1 rounded-full bg-white/40 hover:bg-white transition-all duration-300 {{ $index === 0 ? 'w-12 bg-white' : 'w-8' }}"
                    onclick="goToSlide({{ $index }})"
                    data-indicator="{{ $index }}">
                </button>
            @endforeach
        @endif
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 right-8 z-20 animate-bounce">
        <a href="#featured" class="text-white/60 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>

<!-- Flash Sale Banner -->
<section id="flash-sale" class="py-16 bg-gradient-to-r from-red-600 to-red-700 relative overflow-hidden">
    <!-- Animated background pulse -->
    <div class="absolute inset-0 bg-red-500/20 animate-pulse"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <div class="flex flex-wrap items-center gap-2 mb-3 justify-center md:justify-start">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold animate-pulse">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2v20M2 12h20"/>
                        </svg>
                        {{ __('messages.limited_time_offer_badge') }}
                    </div>
                    <div class="inline-flex items-center bg-black/30 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('messages.only_pieces_left') }}
                    </div>
                </div>
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-white mb-2">
                    {{ __('messages.flash_sale_title') }}
                </h2>
                <p class="text-white/90 text-lg">{{ __('messages.flash_sale_description') }}</p>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                <div class="text-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 md:px-4 py-3 border border-white/30">
                        <span id="hours" class="text-2xl md:text-3xl font-bold text-white block tabular-nums">23</span>
                        <span class="text-white/80 text-xs uppercase">{{ __('messages.hours') }}</span>
                    </div>
                </div>
                <span class="text-white text-2xl md:text-3xl font-bold animate-pulse">:</span>
                <div class="text-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 md:px-4 py-3 border border-white/30">
                        <span id="minutes" class="text-2xl md:text-3xl font-bold text-white block tabular-nums">59</span>
                        <span class="text-white/80 text-xs uppercase">{{ __('messages.minutes') }}</span>
                    </div>
                </div>
                <span class="text-white text-2xl md:text-3xl font-bold animate-pulse">:</span>
                <div class="text-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 md:px-4 py-3 border border-white/30 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-400/20 animate-ping opacity-0" id="seconds-ping"></div>
                        <span id="seconds" class="text-2xl md:text-3xl font-bold text-white block tabular-nums">59</span>
                        <span class="text-white/80 text-xs uppercase">{{ __('messages.seconds') }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('public.artworks.index') }}?sort=discount" class="px-8 py-4 bg-white text-red-600 font-semibold rounded-lg hover:bg-gray-100 transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                {{ __('messages.shop_flash_sale') }}
            </a>
        </div>
        <!-- Scarcity progress bar -->
        <div class="mt-8 max-w-md mx-auto md:mx-0">
            <div class="flex justify-between text-white/80 text-sm mb-2">
                <span>{{ __('messages.selling_fast') }}</span>
                <span>{{ __('messages.percent_sold') }}</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white h-2 rounded-full transition-all duration-1000" style="width: 85%"></div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="py-8 bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center justify-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ __('messages.authentic_100') }}</p>
                    <p class="text-sm text-gray-500">{{ __('messages.verified_artworks') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ __('messages.secure_payment') }}</p>
                    <p class="text-sm text-gray-500">{{ __('messages.encrypted_transactions') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ __('messages.free_shipping') }}</p>
                    <p class="text-sm text-gray-500">{{ __('messages.on_orders_over') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ __('messages.day_returns') }}</p>
                    <p class="text-sm text-gray-500">{{ __('messages.hassle_free_policy') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Artworks -->
<section id="featured" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-up">
            <div class="inline-flex items-center bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.66 11.2C17.43 10.9 17.15 10.64 16.89 10.38C16.22 9.78 15.46 9.35 14.82 8.72C13.33 7.26 13 4.85 13.95 3C13 3.23 12.17 3.75 11.46 4.32C8.87 6.4 7.85 10.07 9.07 13.22C9.11 13.32 9.15 13.42 9.15 13.55C9.15 13.77 9 13.97 8.8 14.05C8.57 14.15 8.33 14.09 8.14 13.93C8.08 13.88 8.04 13.83 8 13.76C6.87 12.33 6.69 10.28 7.45 8.64C5.78 10 4.87 12.3 5 14.47C5.06 14.97 5.12 15.47 5.29 15.97C5.43 16.57 5.7 17.17 6 17.7C7.08 19.43 8.95 20.67 10.96 20.92C13.1 21.19 15.39 20.8 17.03 19.32C18.86 17.66 19.5 15 18.56 12.72L18.43 12.46C18.22 12 17.66 11.2 17.66 11.2Z"/>
                </svg>
                {{ __('messages.best_sellers') }}
            </div>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                {{ __('messages.featured_artworks_title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.featured_artworks_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @if($featuredArtworks->count() > 0)
                @foreach($featuredArtworks as $artwork)
                    <div class="group cursor-pointer card-elegant rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="relative overflow-hidden">
                            <img src="{{ $artwork->primary_image }}"
                                 alt="{{ $artwork->title }}"
                                 class="w-full h-64 md:h-72 object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                                 loading="lazy">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Wishlist Button -->
                            <button onclick="event.preventDefault(); addToWishlist({{ $artwork->id }})" class="absolute top-4 right-4 w-10 h-10 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 shadow-lg hover:bg-red-50 hover:scale-110">
                                <svg class="w-5 h-5 text-gray-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>

                            <!-- Quick View Button -->
                            <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                <a href="{{ route('public.artworks.show', $artwork) }}" class="w-full bg-white text-black py-3 rounded-xl text-sm font-semibold text-center block hover:bg-gray-100 transition-colors shadow-lg flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ __('messages.quick_view') }}
                                </a>
                            </div>
                        </div>
                        <div class="p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">{{ __('messages.in_stock') }}</span>
                                <span class="text-xs text-gray-400">{{ $artwork->views_count ?? 0 }} {{ __('messages.views') }}</span>
                            </div>
                            <h3 class="font-serif text-lg md:text-xl font-semibold text-gray-900 mb-1 group-hover:text-black transition-colors line-clamp-1">{{ $artwork->title }}</h3>
                            @if($artwork->artist)
                                <a href="{{ route('public.artists.show', $artwork->artist->slug ?? $artwork->artist->id) }}" class="text-gray-600 text-sm hover:text-black transition-colors mb-3 inline-block">{{ $artwork->artist->name }}</a>
                            @else
                                <span class="text-gray-600 text-sm mb-3 inline-block">{{ __('messages.unknown_artist') }}</span>
                            @endif
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <span class="text-xl md:text-2xl font-bold text-black">${{ number_format($artwork->price) }}</span>
                                    <span class="text-xs md:text-sm text-gray-400 line-through ml-2">${{ number_format($artwork->price * 1.2) }}</span>
                                </div>
                                <a href="{{ route('public.artworks.show', $artwork) }}" class="btn-luxury px-3 md:px-4 py-2 text-white text-sm font-medium rounded-lg hover:shadow-lg transition-shadow">
                                    {{ __('messages.buy_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center text-gray-500 py-12">
                    {{ __('messages.no_featured_artworks') }}
                </div>
            @endif
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('public.artworks.index') }}" class="inline-flex items-center px-8 py-4 border-2 border-black text-black font-medium rounded-lg hover:bg-black hover:text-white transition-all duration-300">
                {{ __('messages.view_all_artworks') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- New Arrivals - Netflix Style Horizontal Scroll -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="inline-flex items-center bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold mb-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('messages.just_arrived') }}
                </div>
                <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900">
                    {{ __('messages.new_arrivals_title') }}
                </h2>
            </div>
            <div class="flex gap-2">
                <button onclick="scrollNewArrivals('left')" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="scrollNewArrivals('right')" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="newArrivalsContainer" class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4" style="scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none;">
            @if($trendingArtworks->count() > 0)
                @foreach($trendingArtworks as $artwork)
                    <div class="group cursor-pointer flex-shrink-0 w-64 md:w-72">
                        <div class="relative rounded-xl overflow-hidden mb-3 shadow-md group-hover:shadow-xl transition-all duration-300">
                            <img src="{{ $artwork->primary_image }}"
                                 alt="{{ $artwork->title }}"
                                 class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-500">

                            <!-- Animated NEW Badge -->
                            <div class="absolute top-3 left-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg animate-pulse">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('messages.new_badge') }}
                                </span>
                            </div>

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <a href="{{ route('public.artworks.show', $artwork) }}" class="w-full bg-white text-black py-2 rounded-lg text-sm font-medium text-center block hover:bg-gray-100 transition-colors">
                                        {{ __('messages.quick_view') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h4 class="font-medium text-gray-900 group-hover:text-black transition-colors mb-1 truncate">{{ $artwork->title }}</h4>
                        <p class="text-sm text-gray-600 mb-1">{{ $artwork->artist->name ?? __('messages.unknown_artist') }}</p>
                        <p class="font-semibold text-black">${{ number_format($artwork->price) }}</p>
                    </div>
                @endforeach
            @else
                <div class="text-center text-gray-500 py-12 flex-shrink-0 w-full">
                    {{ __('messages.no_new_arrivals') }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Featured Artists -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.creative_minds') }}</p>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                {{ __('messages.featured_artists_title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.featured_artists_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if($featuredArtists->count() > 0)
                @foreach($featuredArtists as $artist)
                    <div class="text-center group cursor-pointer bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-all duration-300 hover:shadow-lg">
                        <div class="relative mb-6 mx-auto w-32 h-32">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full transform group-hover:scale-110 transition-transform duration-500"></div>
                            <img src="{{ $artist->avatar_url }}" alt="{{ $artist->name }}" class="relative w-32 h-32 rounded-full object-cover ring-4 ring-white shadow-xl group-hover:shadow-2xl transition-all duration-300">
                            <div class="absolute inset-0 rounded-full bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                            @if($artist->is_verified)
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center ring-2 ring-white">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-serif text-xl font-semibold text-gray-900 mb-2 group-hover:text-black transition-colors">{{ $artist->name }}</h3>

                        <!-- Location & Style Tags -->
                        <div class="flex flex-wrap items-center justify-center gap-2 mb-3">
                            @if($artist->location)
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $artist->location }}
                                </span>
                            @endif
                            @if($artist->specialization)
                                <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $artist->specialization }}
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-600 mb-4 px-2 text-sm leading-relaxed line-clamp-2">{{ $artist->bio ?? __('messages.artist_bio_default') }}</p>

                        <div class="flex items-center justify-center space-x-3 mb-4 text-sm">
                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full">{{ $artist->artworks_count ?? 0 }} {{ __('messages.artworks') }}</span>
                            @if($artist->years_active)
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full">{{ $artist->years_active }}+ {{ __('messages.years') }}</span>
                            @endif
                        </div>

                        <a href="{{ route('public.artists.show', $artist->slug ?? $artist->id) }}" class="inline-flex items-center px-6 py-2.5 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition-colors group-hover:shadow-lg">
                            {{ __('messages.view_portfolio') }}
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-3 text-center text-gray-500 py-12">
                    {{ __('messages.no_featured_artists') }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-flex items-center bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                {{ __('messages.loved_by_collectors') }}
            </div>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                {{ __('messages.customer_reviews_title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.customer_reviews_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1: USA -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed">"{{ __('messages.testimonial_1_text') }}"</p>
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Kim&background=random&color=fff&size=128" alt="Sarah Kim" class="w-12 h-12 rounded-full mr-4 object-cover ring-2 ring-gray-100">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ __('messages.testimonial_1_author') }}</p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <span class="text-lg">🇺🇸</span> {{ __('messages.testimonial_1_role') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2: UK -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed">"{{ __('messages.testimonial_2_text') }}"</p>
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name=James+Miller&background=random&color=fff&size=128" alt="James Miller" class="w-12 h-12 rounded-full mr-4 object-cover ring-2 ring-gray-100">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ __('messages.testimonial_2_author') }}</p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <span class="text-lg">🇬🇧</span> {{ __('messages.testimonial_2_role') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3: Singapore -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed">"{{ __('messages.testimonial_3_text') }}"</p>
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name=Anna+Tan&background=random&color=fff&size=128" alt="Anna Tan" class="h-12 w-12 rounded-full mr-4 object-cover ring-2 ring-gray-100">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ __('messages.testimonial_3_author') }}</p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <span class="text-lg">🇸🇬</span> {{ __('messages.testimonial_3_role') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Curated Collections -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-elegant text-sm font-medium text-gray-500 mb-3 tracking-widest uppercase">{{ __('messages.themed_collections') }}</p>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                {{ __('messages.curated_collections') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.explore_carefully_selected_collections') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <div class="group cursor-pointer rounded-2xl overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/placeholder-category.jpg') }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                 loading="lazy">

                            <!-- Dark Overlay with Animation -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>

                            <!-- Content Overlay -->
                            <div class="absolute inset-0 flex flex-col justify-end p-6">
                                <div class="transform group-hover:translate-y-0 transition-transform duration-500">
                                    <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full mb-3 transform -translate-y-2 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
                                        {{ $category->artworks_count ?? 0 }} {{ __('messages.artworks_count') }}
                                    </span>
                                    <h3 class="font-serif text-2xl md:text-3xl font-bold text-white mb-2 transform group-hover:scale-105 transition-transform duration-500 origin-left">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-white/80 text-sm line-clamp-2 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-150">
                                        {{ $category->description ?? 'Explore this curated collection of authentic artworks' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Explore Button -->
                            <div class="absolute top-4 right-4 transform translate-x-10 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-500">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('categories.show', $category) }}" class="block p-6 bg-white hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">{{ __('messages.explore_collection') }}</span>
                                <span class="text-black font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                                    {{ __('messages.view_all') }}
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center text-gray-500 py-12">
                    {{ __('messages.no_collections_available') }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-24 bg-black text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fade-in-up">
            <div class="inline-flex items-center bg-red-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6 animate-pulse-soft">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                {{ __('messages.get_10_off_first_order') }}
            </div>
            <p class="text-elegant text-sm font-medium text-white/60 mb-3 tracking-widest uppercase">{{ __('messages.stay_connected') }}</p>
            <h2 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                {{ __('messages.stay_inspired') }}
            </h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ __('messages.stay_inspired_description') }}
            </p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="{{ __('messages.enter_your_email') }}"
                    class="flex-1 px-6 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-white focus:bg-white/15 transition-all duration-300"
                    required
                >
                <button type="submit" class="btn-luxury px-8 py-4 rounded-xl font-semibold">
                    {{ __('messages.subscribe') }}
                </button>
            </form>
            <p class="text-sm text-gray-400 mt-6">
                {{ __('messages.join_art_enthusiasts') }}
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Hero Slider
let currentSlide = 0;
const slides = document.querySelectorAll('[data-slide]');
const indicators = document.querySelectorAll('[data-indicator]');

function goToSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('opacity-100', i === index);
        slide.classList.toggle('opacity-0', i !== index);
    });
    
    indicators.forEach((indicator, i) => {
        if (i === index) {
            indicator.classList.add('w-8', 'bg-white');
            indicator.classList.remove('w-2', 'bg-white/60');
        } else {
            indicator.classList.remove('w-8', 'bg-white');
            indicator.classList.add('w-2', 'bg-white/60');
        }
    });
    
    currentSlide = index;
}

// Auto-advance slider
setInterval(() => {
    const nextSlide = (currentSlide + 1) % slides.length;
    goToSlide(nextSlide);
}, 5000);

// Countdown Timer for Flash Sale
function startCountdown() {
    // Get stored end time or set new one
    let endTime = localStorage.getItem('flashSaleEndTime');
    if (!endTime) {
        endTime = new Date().getTime() + (24 * 60 * 60 * 1000);
        localStorage.setItem('flashSaleEndTime', endTime);
    }
    endTime = parseInt(endTime);

    let lastSeconds = -1;

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            // Reset countdown
            endTime = new Date().getTime() + (24 * 60 * 60 * 1000);
            localStorage.setItem('flashSaleEndTime', endTime);
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        const secondsPing = document.getElementById('seconds-ping');

        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
        if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');

        // Trigger ping animation on seconds change
        if (seconds !== lastSeconds && secondsPing) {
            secondsPing.classList.remove('animate-ping');
            void secondsPing.offsetWidth; // Force reflow
            secondsPing.classList.add('animate-ping');
            lastSeconds = seconds;
        }
    }

    updateTimer();
    setInterval(updateTimer, 1000);
}

// Start countdown when page loads
startCountdown();

// Netflix-style horizontal scroll for New Arrivals
function scrollNewArrivals(direction) {
    const container = document.getElementById('newArrivalsContainer');
    if (container) {
        const scrollAmount = 300; // Width of one card + gap
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});
</script>
@endpush
