@extends('layouts.app')

@section('title', 'Page Not Found - Panchi Gallery')
@section('meta-description', 'The page you are looking for could not be found. Browse our collection of authentic Myanmar artworks.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center py-20 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- 404 Animation -->
        <div class="mb-8">
            <h1 class="font-serif text-9xl font-bold text-gray-900 mb-4">404</h1>
            <div class="w-32 h-1 bg-black mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="font-serif text-3xl md:text-4xl font-semibold text-gray-900 mb-4">
            {{ __('messages.page_not_found') ?? 'Page Not Found' }}
        </h2>
        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            {{ __('messages.page_not_found_description') ?? 'The artwork or page you are looking for has been moved, removed, or never existed.' }}
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
            <a href="{{ route('home') }}" class="px-8 py-4 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                {{ __('messages.go_home') ?? 'Go Home' }}
            </a>
            <a href="{{ route('public.artworks.index') }}" class="px-8 py-4 border-2 border-black text-black rounded-xl font-semibold hover:bg-black hover:text-white transition-all duration-300">
                {{ __('messages.browse_artworks') ?? 'Browse Artworks' }}
            </a>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
            <h3 class="font-serif text-xl font-semibold text-gray-900 mb-6">
                {{ __('messages.explore_more') ?? 'Explore More' }}
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('public.artworks.index') }}" class="text-gray-600 hover:text-black transition-colors duration-300">
                    {{ __('messages.artworks') ?? 'Artworks' }}
                </a>
                <a href="{{ route('public.artists.index') }}" class="text-gray-600 hover:text-black transition-colors duration-300">
                    {{ __('messages.artists') ?? 'Artists' }}
                </a>
                <a href="{{ route('marketplace.index') }}" class="text-gray-600 hover:text-black transition-colors duration-300">
                    {{ __('messages.marketplace') ?? 'Marketplace' }}
                </a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-black transition-colors duration-300">
                    {{ __('messages.contact') ?? 'Contact' }}
                </a>
            </div>
        </div>
        
        <!-- Help Text -->
        <p class="text-gray-500 mt-8 text-sm">
            {{ __('messages.need_help') ?? 'Need help? ' }} <a href="{{ route('contact') }}" class="text-black hover:underline">{{ __('messages.contact_support') ?? 'Contact our support team' }}</a>
        </p>
    </div>
</div>
@endsection
