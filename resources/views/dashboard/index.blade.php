@extends('layouts.app')

@section('title', __('messages.dashboard') . ' - Panchi Gallery')
@section('meta-description', __('messages.dashboard_meta_description'))

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ __('messages.welcome_back', ['name' => auth()->user()->name]) }}</h1>
                <p class="text-indigo-100">{{ __('messages.manage_collection_discover') }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-indigo-100">{{ __('messages.member_since') }}</div>
                <div class="text-lg font-semibold">{{ auth()->user()->created_at->format('F Y') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Artworks Created (for artists) -->
            @if(auth()->user()->isArtist())
                <div class="bg-indigo-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <i class="fas fa-palette text-indigo-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-indigo-600 font-medium">{{ __('messages.artworks_created') }}</p>
                            <p class="text-2xl font-bold text-indigo-900">{{ auth()->user()->artworks->count() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Artworks Owned -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-image text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">{{ __('messages.artworks_owned') }}</p>
                        <p class="text-2xl font-bold text-green-900">{{ auth()->user()->currentArtworks->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Purchases -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-shopping-bag text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">{{ __('messages.total_purchases') }}</p>
                        <p class="text-2xl font-bold text-purple-900">{{ auth()->user()->purchases->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Wishlist Items -->
            <div class="bg-pink-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-pink-100 rounded-full">
                        <i class="fas fa-heart text-pink-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-pink-600 font-medium">{{ __('messages.wishlist_items') }}</p>
                        <p class="text-2xl font-bold text-pink-900">{{ auth()->user()->likedArtworks->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.recent_activity') }}</h2>
                    <div class="space-y-4">
                        <!-- Activity Items -->
                        @if(auth()->user()->purchases->count() > 0)
                            @foreach(auth()->user()->purchases()->take(5)->get() as $purchase)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="p-2 bg-green-100 rounded-full">
                                        <i class="fas fa-shopping-cart text-green-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ __('messages.purchased_artwork', ['title' => $purchase->artwork->title]) }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $purchase->created_at->format('M d, Y') }} • {{ $purchase->formatted_amount }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">{{ __('messages.no_recent_activity') }}</p>
                                <a href="{{ route('public.artworks.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm mt-2 inline-block">
                                    {{ __('messages.start_exploring_artworks') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.quick_actions') }}</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @if(auth()->user()->isArtist())
                            <a href="{{ route('artist.artworks.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-plus-circle text-indigo-600 text-xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('messages.add_artwork') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('messages.list_new_artwork') }}</p>
                                </div>
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-user-edit text-indigo-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('messages.edit_profile') }}</p>
                                <p class="text-sm text-gray-500">{{ __('messages.update_information') }}</p>
                            </div>
                        </a>

                        <a href="{{ route('wishlist') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-heart text-indigo-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('messages.wishlist') }}</p>
                                <p class="text-sm text-gray-500">{{ __('messages.view_saved_items') }}</p>
                            </div>
                        </a>

                        <a href="{{ route('transactions.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-receipt text-indigo-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('messages.transactions') }}</p>
                                <p class="text-sm text-gray-500">{{ __('messages.view_history') }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recommended Artworks -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.recommended_for_you') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="group cursor-pointer">
                                <div class="relative overflow-hidden rounded-lg mb-3">
                                    <div class="w-full h-40 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500 text-2xl"></i>
                                    </div>
                                </div>
                                <h4 class="font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">Artwork Title {{ $i }}</h4>
                                <p class="text-sm text-gray-600">Artist Name</p>
                                <p class="font-semibold text-gray-900">$850</p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-20 h-20 rounded-full mx-auto mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ auth()->user()->role === 'artist' ? __('messages.artist') : __('messages.collector') }}</p>
                        
                        @if(!auth()->user()->is_approved && !auth()->user()->isAdmin())
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded-lg text-sm">
                                <i class="fas fa-clock mr-2"></i>
                                {{ __('messages.profile_pending_approval') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('messages.profile_completion') }}</span>
                            <span class="font-medium text-gray-900">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.notifications') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-start p-3 bg-blue-50 rounded-lg">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('messages.welcome_panchi') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.complete_profile_get_started') }}</p>
                            </div>
                        </div>
                        
                        @if(auth()->user()->isArtist())
                            <div class="flex items-start p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 mt-0.5 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('messages.artist_status_verified') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.can_now_sell_artworks') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-indigo-600 text-white rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.need_help') }}</h3>
                    <p class="text-sm text-indigo-100 mb-4">{{ __('messages.support_team_assist') }}</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center text-white font-medium hover:text-indigo-100">
                        <i class="fas fa-headset mr-2"></i>
                        {{ __('messages.contact_support') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
