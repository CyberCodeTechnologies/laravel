@extends('layouts.app')

@section('title', __('messages.terms_title'))
@section('meta-description', __('messages.terms_meta_description') ?? 'Panchi Gallery terms of service. Read our terms and conditions for buying, selling, and collecting authentic Myanmar artwork on our platform.')
@section('meta-keywords', 'terms of service, terms and conditions, art gallery terms, buy art terms, sell art terms, Panchi Gallery legal')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('messages.terms_of_service') }}</h1>
            
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_acceptance_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_acceptance_text') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_license_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_license_text') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_sales_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_sales_text') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_artist_responsibilities_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_artist_responsibilities_text') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_privacy_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_privacy_text') }}
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">{{ __('messages.terms_contact_title') }}</h2>
                <p class="text-gray-600 mb-4">
                    {{ __('messages.terms_contact_text') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
