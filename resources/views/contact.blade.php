@extends('layouts.app')

@section('title', $artist ? __('messages.send_message_to', ['name' => $artist->name]) . ' - Panchi Gallery' : cms_content('contact_title', __('messages.contact_us')) . ' - Panchi Gallery')
@section('meta-description', $artist ? __('messages.contact_artist_meta', ['name' => $artist->name]) : __('messages.contact_meta_description'))
@section('meta-keywords', 'contact Panchi Gallery, art gallery contact, Myanmar art support, artist commission, buy art inquiry, art consultation')
@section('meta-image', asset('images/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Contact Panchi Gallery",
    "url": "{{ route('contact') }}",
    "description": "Get in touch with Panchi Gallery for art inquiries, artist commissions, and customer support.",
    "mainEntity": {
        "@@type": "Organization",
        "name": "Panchi Gallery",
        "url": "{{ url('/') }}",
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "customer service",
            "email": "contact@panchigallery.com"
        }
    }
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="bg-black text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-light mb-6">
                @if($artist)
                    {{ __('messages.send_message_to', ['name' => $artist->name]) }}
                @else
                    {{ cms_content('get_in_touch', __('messages.get_in_touch')) }}
                @endif
            </h1>
            <p class="text-xl font-light opacity-90">
                @if($artist)
                    {{ __('messages.send_message_to', ['name' => $artist->name]) }}
                @else
                    {{ cms_content('were_here_to_help', __('messages.were_here_to_help')) }}
                @endif
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-light mb-8">
                        @if($artist)
                            {{ __('messages.send_message_to', ['name' => $artist->name]) }}
                        @else
                            {{ __('messages.send_us_message') }}
                        @endif
                    </h2>
                    
                    @if($artist)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $artist->avatar_url }}" alt="{{ $artist->name }}" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $artist->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $artist->specialization ?? 'Visual Artist' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf
                        @if($artist)
                            <input type="hidden" name="artist_id" value="{{ $artist->id }}">
                        @endif
                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <ul class="list-disc list-inside text-red-600 space-y-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-green-600">{{ session('success') }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.your_name') }} *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.your_email') }} *</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.subject') }} *</label>
                            <select id="subject" 
                                    name="subject" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black">
                                <option value="">{{ __('messages.select_topic') }}</option>
                                @if($artist)
                                    <option value="inquiry">{{ __('messages.artwork_inquiry') }}</option>
                                    <option value="commission">{{ __('messages.commission_request') }}</option>
                                    <option value="exhibition">{{ __('messages.exhibition_opportunity') }}</option>
                                    <option value="collaboration">{{ __('messages.collaboration') }}</option>
                                @else
                                    <option value="general">{{ __('messages.general_inquiry') }}</option>
                                    <option value="artist">{{ __('messages.artist_support') }}</option>
                                    <option value="collector">{{ __('messages.collector_support') }}</option>
                                    <option value="purchase">{{ __('messages.purchase_question') }}</option>
                                    <option value="technical">{{ __('messages.technical_issue') }}</option>
                                    <option value="partnership">{{ __('messages.partnership_opportunity') }}</option>
                                    <option value="press">{{ __('messages.press_inquiry') }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.message') }} *</label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="6" 
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-black"
                                      placeholder="{{ $artist ? 'Hi ' . $artist->name . ', I\'m interested in your work...' : 'How can we help you?' }}">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                            @if($artist)
                                {{ __('messages.send_message_to', ['name' => $artist->name]) }}
                            @else
                                {{ __('messages.send_message') }}
                            @endif
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-light mb-8">{{ __('messages.contact_information') }}</h2>
                    
                    <div class="space-y-8">
                        <!-- Email -->
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-lg mb-2">{{ __('messages.email') }}</h3>
                                <p class="text-gray-600">{{ $contactInfo['email'] }}</p>
                                <p class="text-sm text-gray-500">{{ __('messages.response_24h') }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13 2.257a1 1 0 001.21.502l4.493 1.498a1 1 0 00.684-.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-lg mb-2">{{ __('messages.phone') }}</h3>
                                <p class="text-gray-600">{{ $contactInfo['phone'] }}</p>
                                <p class="text-sm text-gray-500">{{ __('messages.business_hours_short') }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-lg mb-2">{{ __('messages.office') }}</h3>
                                <p class="text-gray-600">{!! nl2br(e($contactInfo['address'])) !!}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-lg mb-4">{{ __('messages.business_hours') }}</h3>
                        <div class="space-y-2 text-gray-600">
                            <p>{{ $contactInfo['business_hours'] }}</p>
                        </div>
                    </div>
                </div>            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-light mb-8">{{ __('messages.faq_title') }}</h2>
            <p class="text-gray-600 mb-8">{{ __('messages.contact_faq_subtitle') }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('faq') }}#general" class="block p-6 bg-white border border-gray-200 rounded-lg hover:border-black transition-colors">
                    <h3 class="font-medium mb-2">{{ __('messages.general_questions') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('messages.contact_faq_general_desc') }}</p>
                </a>
                
                <a href="{{ route('faq') }}#artists" class="block p-6 bg-white border border-gray-200 rounded-lg hover:border-black transition-colors">
                    <h3 class="font-medium mb-2">{{ __('messages.for_artists') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('messages.contact_faq_artists_desc') }}</p>
                </a>
                
                <a href="{{ route('faq') }}#collectors" class="block p-6 bg-white border border-gray-200 rounded-lg hover:border-black transition-colors">
                    <h3 class="font-medium mb-2">{{ __('messages.for_collectors') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('messages.contact_faq_collectors_desc') }}</p>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
