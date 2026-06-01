@extends('layouts.app')

@section('title', __('messages.cookie_policy'))
@section('meta-description', 'Panchi Gallery cookie policy. Learn how we use cookies to enhance your browsing experience and improve our art gallery services.')
@section('meta-keywords', 'cookie policy, cookies, Panchi Gallery cookies, privacy, website cookies, Myanmar art gallery cookies')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('messages.cookie_policy') }}</h1>
            
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.what_are_cookies') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.cookies_description') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.how_we_use_cookies') }}</h2>
                <ul class="list-disc list-inside text-gray-600 mb-4">
                    <li>{{ __('messages.cookies_remember_preferences') }}</li>
                    <li>{{ __('messages.cookies_keep_logged_in') }}</li>
                    <li>{{ __('messages.cookies_analyze_traffic') }}</li>
                    <li>{{ __('messages.cookies_personalize') }}</li>
                </ul>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.types_of_cookies') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.cookies_types_description') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.managing_cookies') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.managing_cookies_description') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.contact_us') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.cookie_contact_text') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
