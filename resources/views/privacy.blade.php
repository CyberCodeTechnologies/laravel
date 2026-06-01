@extends('layouts.app')

@section('title', __('messages.privacy_title') . ' - Panchi Gallery')
@section('meta-description', __('messages.privacy_meta_description'))
@section('meta-keywords', 'privacy policy, data protection, Panchi Gallery privacy, personal information, Myanmar art gallery privacy, GDPR')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="bg-black text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-light mb-6">{{ __('messages.privacy_policy') }}</h1>
            <p class="text-xl font-light opacity-90">{{ __('messages.privacy_subtitle') }}</p>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-8">{{ __('messages.last_updated') }}: {{ date('F j, Y') }}</p>

                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_intro_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_intro_p1') }}
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_intro_p2') }}
                    </p>
                </div>

                <!-- Information We Collect -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_info_collect_title') }}</h2>
                    
                    <h3 class="text-xl font-medium mb-4">{{ __('messages.privacy_personal_info') }}</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_personal_info_desc') }}
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        @foreach(__('messages.privacy_personal_info_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <h3 class="text-xl font-medium mb-4">{{ __('messages.privacy_artwork_info') }}</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_artwork_info_desc') }}
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        @foreach(__('messages.privacy_artwork_info_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <h3 class="text-xl font-medium mb-4">{{ __('messages.privacy_technical_info') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_technical_info_desc') }}
                    </p>
                </div>

                <!-- How We Use Your Information -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_use_info_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">{{ __('messages.privacy_use_info_desc') }}</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        @foreach(__('messages.privacy_use_info_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Information Sharing -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_sharing_title') }}</h2>
                    
                    <h3 class="text-xl font-medium mb-4">{{ __('messages.privacy_no_sell') }}</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ __('messages.privacy_no_sell_desc') }}
                    </p>

                    <h3 class="text-xl font-medium mb-4">{{ __('messages.privacy_when_share') }}</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                        @foreach(__('messages.privacy_when_share_list') as $item)
                            <li>{!! $item !!}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Data Security -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_security_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_security_desc') }}
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        @foreach(__('messages.privacy_security_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Your Rights -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_rights_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">{{ __('messages.privacy_rights_desc') }}</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        @foreach(__('messages.privacy_rights_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Cookies and Tracking -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_cookies_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_cookies_p1') }}
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_cookies_p2') }}
                    </p>
                </div>

                <!-- International Data Transfers -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_transfers_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_transfers_desc') }}
                    </p>
                </div>

                <!-- Children's Privacy -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_children_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_children_desc') }}
                    </p>
                </div>

                <!-- Changes to This Policy -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_changes_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('messages.privacy_changes_desc') }}
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-3xl font-light mb-6">{{ __('messages.privacy_contact_title') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ __('messages.privacy_contact_desc') }}
                    </p>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-600">
                            <strong>{{ __('messages.privacy_email_label') }}:</strong> privacy@panchigallery.com<br>
                            <strong>{{ __('messages.privacy_address_label') }}:</strong> [Your Business Address]<br>
                            <strong>{{ __('messages.privacy_phone_label') }}:</strong> [Your Phone Number]
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
