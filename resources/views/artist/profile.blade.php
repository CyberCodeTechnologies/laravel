@extends('layouts.app')

@section('title', __('messages.artist_profile.title') . ' - Panchi Gallery')
@section('meta-description', __('messages.artist_profile.meta_description'))

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    {{ __('messages.artist_profile.studio_title', ['name' => auth()->user()->name]) }}
                </h1>
                <p class="text-gray-300">
                    {{ __('messages.artist_profile.studio_subtitle') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('public.artists.show', auth()->user()->slug ?? auth()->user()->id) }}" target="_blank" class="text-gray-300 hover:text-white">
                    {{ __('messages.artist_profile.view_public_profile') }}
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->artworks()->count() }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.artworks') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->followers()->count() }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.followers') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->sales()->where('status', 'pending')->count() ?? 0 }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.pending_orders') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">${{ number_format($stats['total_sales'] ?? 0) }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.total_sales') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <a href="{{ route('dashboard') }}" id="artworks-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_profile.my_artworks') }}
                </a>
                <a href="{{ route('artist.analytics') }}" id="analytics-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_profile.analytics') }}
                </a>
                <a href="{{ route('artist.orders') }}" id="orders-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_profile.orders') }}
                </a>
                <a href="{{ route('artist.sales') }}" id="certificates-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_profile.sales_certificates') }}
                </a>
                <button id="profile-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    {{ __('messages.artist_profile.artist_profile_nav') }}
                </button>
            </nav>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h2 class="font-serif text-2xl font-bold text-gray-900">{{ __('messages.artist_profile.edit_profile') }}</h2>
                <p class="text-gray-600 mt-2">{{ __('messages.artist_profile.edit_profile_subtitle') }}</p>
            </div>

            <form action="{{ route('artist.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-8">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-8">
                <!-- Profile Photos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.profile_photos') }}</h3>
                        
                        <!-- Profile Photo -->
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="w-24 h-24 object-cover">
                                @else
                                    <i class="fas fa-user text-4xl text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.profile_photo') }}</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800">
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.profile_photo_help') }}</p>
                            </div>
                        </div>

                        <!-- Cover Photo -->
                        <div class="flex items-center gap-6">
                            <div class="w-32 h-20 rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->cover_image)
                                    <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" alt="" class="w-32 h-20 object-cover">
                                @else
                                    <i class="fas fa-image text-2xl text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.cover_photo') }}</label>
                                <input type="file" id="cover_image" name="cover_image" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800">
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.cover_photo_help') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.basic_information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.first_name') }}</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.first_name_placeholder') }}">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.last_name') }}</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.last_name_placeholder') }}">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.email_address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.email_placeholder') }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.phone_number') }}</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.phone_placeholder') }}">
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.location') }}</label>
                            <input type="text" id="location" name="location" value="{{ old('location', auth()->user()->location) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.location_placeholder') }}">
                        </div>
                        <div>
                            <label for="years_active" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.years_active') }}</label>
                            <input type="number" id="years_active" name="years_active" value="{{ old('years_active', auth()->user()->years_active) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.years_active_placeholder') }}" min="0" max="100">
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.professional_information') }}</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.specialization') }}</label>
                            <input type="text" id="specialization" name="specialization" value="{{ old('specialization', auth()->user()->specialization) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.specialization_placeholder') }}">
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.artist_bio') }}</label>
                            <textarea id="bio" name="bio" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.bio_placeholder') }}">{{ old('bio', auth()->user()->bio) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.bio_help') }}</p>
                        </div>

                        <div>
                            <label for="artist_statement" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.artist_statement') }}</label>
                            <textarea id="artist_statement" name="artist_statement" rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.artist_statement_placeholder') }}">{{ old('artist_statement', auth()->user()->artist_statement) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.artist_statement_help') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education & Achievements -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.education_achievements') }}</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="education" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.education') }}</label>
                            <textarea id="education" name="education" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.education_placeholder') }}">{{ old('education', auth()->user()->education) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.education_help') }}</p>
                        </div>

                        <div>
                            <label for="exhibitions" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.exhibitions') }}</label>
                            <textarea id="exhibitions" name="exhibitions" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.exhibitions_placeholder') }}">{{ old('exhibitions', auth()->user()->exhibitions) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.exhibitions_help') }}</p>
                        </div>

                        <div>
                            <label for="awards" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.awards_recognition') }}</label>
                            <textarea id="awards" name="awards" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.awards_placeholder') }}">{{ old('awards', auth()->user()->awards) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.awards_help') }}</p>
                        </div>

                        <div>
                            <label for="press" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.press_media') }}</label>
                            <textarea id="press" name="press" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                      placeholder="{{ __('messages.artist_profile.press_placeholder') }}">{{ old('press', auth()->user()->press) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.artist_profile.press_help') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Online Presence -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.online_presence') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.website') }}</label>
                            <input type="url" id="website" name="website" value="{{ old('website', auth()->user()->website) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.website_placeholder') }}">
                        </div>
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.instagram') }}</label>
                            <input type="text" id="instagram" name="instagram" value="{{ old('instagram', auth()->user()->instagram) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.instagram_placeholder') }}">
                        </div>
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.artist_profile.facebook') }}</label>
                            <input type="text" id="facebook" name="facebook" value="{{ old('facebook', auth()->user()->facebook) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="{{ __('messages.artist_profile.facebook_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.artist_profile.settings') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="participate_in_orders" name="participate_in_orders" value="1"
                                   {{ auth()->user()->participate_in_orders ? 'checked' : '' }}
                                   class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                            <label for="participate_in_orders" class="ml-2 block text-sm text-gray-700">
                                {{ __('messages.artist_profile.participate_in_orders') }}
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">{{ __('messages.artist_profile.participate_in_orders_help') }}</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                        {{ __('messages.artist_profile.save_all_changes') }}
                    </button>
                </div>
            </div>
            </form>

            <!-- Back to Dashboard -->
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="text-black hover:text-gray-700 font-medium">
                    {{ __('messages.artist_profile.back_to_dashboard') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
