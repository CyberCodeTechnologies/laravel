@extends('layouts.app')

@section('title', 'My Dashboard - Panchi Gallery')
@section('meta-description', 'Manage your art collection, wishlist, and purchase history in your Panchi Gallery dashboard.')

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-gray-300">
                    Manage your art collection and discover new pieces
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <x-button variant="secondary" href="{{ route('marketplace.index') }}">
                    Browse Artworks
                </x-button>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->artworks_count ?? 0 }}</div>
                <div class="text-gray-300">Artworks Owned</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->wishlist_count ?? 0 }}</div>
                <div class="text-gray-300">Wishlist Items</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">${{ number_format(auth()->user()->total_spent ?? 0) }}</div>
                <div class="text-gray-300">Total Spent</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->following_count ?? 0 }}</div>
                <div class="text-gray-300">Artists Following</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <button onclick="switchTab('collection')" id="collection-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    {{ __('messages.collector_dashboard.my_collection', ['default' => 'My Collection']) }}
                </button>
                <button onclick="switchTab('wishlist')" id="wishlist-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.collector_dashboard.wishlist', ['default' => 'Wishlist']) }}
                </button>
                <button onclick="switchTab('purchases')" id="purchases-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.collector_dashboard.purchase_history', ['default' => 'Purchase History']) }}
                </button>
                <button onclick="switchTab('resale')" id="resale-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.collector_dashboard.resale_management', ['default' => 'Resale Management']) }}
                </button>
                <button onclick="switchTab('profile')" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.collector_dashboard.profile_settings', ['default' => 'Profile Settings']) }}
                </button>
            </nav>
        </div>
        
        <!-- My Collection Tab -->
        <div id="collection-content" class="tab-content">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">{{ __('messages.collector_dashboard.my_collection_title', ['default' => 'My Art Collection']) }}</h2>
                <div class="flex space-x-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        <option>{{ __('messages.collector_dashboard.sort_newest', ['default' => 'Sort by: Newest']) }}</option>
                        <option>{{ __('messages.collector_dashboard.sort_price', ['default' => 'Sort by: Price']) }}</option>
                        <option>{{ __('messages.collector_dashboard.sort_artist', ['default' => 'Sort by: Artist']) }}</option>
                    </select>
                </div>
            </div>
            
            @if($ownedArtworks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($ownedArtworks as $artwork)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury group">
                            <div class="relative image-hover-zoom h-48">
                                                <img src="{{ $artwork->primary_image }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('public.artworks.show', $artwork) }}" class="p-2 bg-white rounded-full hover:bg-gray-100 transition-colors" title="View Details">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('collector.resales.create', $artwork) }}" class="p-2 bg-white rounded-full hover:bg-gray-100 transition-colors" title="List for Sale">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1">{{ $artwork->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $artwork->artist->name ?? 'Unknown Artist' }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Owned</span>
                                    <span class="font-semibold text-black">${{ number_format($artwork->price) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No artworks in your collection</h3>
                    <p class="text-gray-600 mb-6">Start building your collection by browsing our marketplace</p>
                    <x-button variant="primary" href="{{ route('marketplace.index') }}">
                        Browse Artworks
                    </x-button>
                </div>
            @endif
        </div>
        
        <!-- Wishlist Tab -->
        <div id="wishlist-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">My Wishlist</h2>
                <div class="flex space-x-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        <option>Sort by: Recently Added</option>
                        <option>Sort by: Price</option>
                        <option>Sort by: Artist</option>
                    </select>
                </div>
            </div>
            
            @if($likedArtworks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($likedArtworks as $artwork)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden card-luxury group">
                            <div class="relative image-hover-zoom h-48">
                                                <img src="{{ $artwork->primary_image }}" alt="{{ $artwork->title }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <button onclick="removeFromWishlist({{ $artwork->id }})" class="p-2 bg-white/90 rounded-full hover:bg-white transition-colors">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-serif text-lg font-semibold text-gray-900 mb-1">{{ $artwork->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $artwork->artist->name ?? 'Unknown Artist' }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-black">${{ number_format($artwork->price) }}</span>
                                    <a href="{{ route('public.artworks.show', $artwork) }}" class="btn-luxury px-4 py-2 rounded-lg text-sm font-medium inline-block">
                                        Buy Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.collector_dashboard.empty_wishlist_title', ['default' => 'Your wishlist is empty']) }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('messages.collector_dashboard.empty_wishlist_desc', ['default' => 'Save artworks you love to your wishlist']) }}</p>
                    <x-button variant="primary" href="{{ route('marketplace.index') }}">
                        {{ __('messages.collector_dashboard.browse_artworks', ['default' => 'Browse Artworks']) }}
                    </x-button>
                </div>
            @endif
        </div>
        
        <!-- Purchase History Tab -->
        <div id="purchases-content" class="tab-content hidden">
            <div class="mb-8">
                <h2 class="font-serif text-2xl font-bold text-gray-900">{{ __('messages.collector_dashboard.purchase_history', ['default' => 'Purchase History']) }}</h2>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.artwork', ['default' => 'Artwork']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.artist', ['default' => 'Artist']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.price', ['default' => 'Price']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.date', ['default' => 'Date']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.status', ['default' => 'Status']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.collector_dashboard.actions', ['default' => 'Actions']) }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($recentPurchases->count() > 0)
                                @foreach($recentPurchases as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img src="{{ $transaction->artwork->primary_image }}" 
                                                     alt="{{ $transaction->artwork->title }}" 
                                                     class="w-10 h-10 rounded-lg object-cover mr-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->artwork->title }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->artwork->artist->name ?? __('messages.unknown_artist', ['default' => 'Unknown Artist']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($transaction->amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->completed_at ? $transaction->completed_at->format('Y-m-d') : $transaction->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-badge variant="verified" size="sm">{{ ucfirst($transaction->status) }}</x-badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('collector.ownership.certificate', $transaction->artwork->currentOwnership) }}" class="text-black hover:text-gray-700">{{ __('messages.collector_dashboard.view_certificate', ['default' => 'View Certificate']) }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        {{ __('messages.collector_dashboard.no_purchase_history', ['default' => 'No purchase history available.']) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Resale Management Tab -->
        <div id="resale-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">Resale Management</h2>
                <x-button variant="primary" onclick="openResaleModal()">
                    List Artwork for Sale
                </x-button>
            </div>
            
            @if($resaleListings->count() > 0)
                <div class="space-y-6">
                    @foreach($resaleListings as $resale)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 card-luxury">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-1">
                                    <img src="{{ $resale->artwork->primary_image }}" 
                                         alt="{{ $resale->artwork->title }}" 
                                         class="w-full h-40 object-cover rounded-lg">
                                </div>
                                <div class="md:col-span-3">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-serif text-xl font-semibold text-gray-900 mb-1">{{ $resale->artwork->title }}</h3>
                                            <p class="text-gray-600 mb-2">by {{ $resale->artwork->artist->name ?? 'Unknown Artist' }}</p>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span>Purchased: ${{ number_format($resale->artwork->price) }}</span>
                                                <span>→</span>
                                                <span class="font-semibold text-green-600">Listing: ${{ number_format($resale->asking_price) }}</span>
                                                @if($resale->artwork->price > 0)
                                                <span class="text-green-600">(+{{ round((($resale->asking_price - $resale->artwork->price) / $resale->artwork->price) * 100) }}%)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <x-badge variant="verified" size="sm">{{ ucfirst($resale->status) }}</x-badge>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Views</span>
                                            <div class="font-semibold text-gray-900">{{ $resale->artwork->views_count ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Offers</span>
                                            <div class="font-semibold text-gray-900">{{ $resale->offers_count ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Days Listed</span>
                                            <div class="font-semibold text-gray-900">{{ $resale->created_at ? $resale->created_at->diffInDays(now()) : 0 }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('collector.resales.edit', $resale) }}" class="border-2 border-black text-black px-4 py-2 rounded-lg text-sm font-medium hover:bg-black hover:text-white transition-colors inline-block">
                                            Edit Listing
                                        </a>
                                        <x-button variant="outline" size="sm">
                                            View Offers
                                        </x-button>
                                        <form action="{{ route('collector.resales.destroy', $resale) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-2 border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors">
                                                Remove Listing
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No resale listings</h3>
                    <p class="text-gray-600 mb-6">List artworks from your collection for resale</p>
                    <x-button variant="primary" onclick="openResaleModal()">
                        Create First Listing
                    </x-button>
                </div>
            @endif
        </div>
        
        <!-- Profile Settings Tab -->
        <div id="profile-content" class="tab-content hidden">
            <div class="max-w-3xl">
                <h2 class="font-serif text-2xl font-bold text-gray-900 mb-8">Profile Settings</h2>
                
                <form class="space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" value="{{ auth()->user()->first_name ?? 'John' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" value="{{ auth()->user()->last_name ?? 'Doe' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" value="{{ auth()->user()->phone ?? '+95 123 456 789' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                <input type="text" value="{{ auth()->user()->address ?? '123 Main St' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" value="{{ auth()->user()->city ?? 'Yangon' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" value="{{ auth()->user()->postal_code ?? '11181' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                                    <option>Myanmar</option>
                                    <option>United States</option>
                                    <option>United Kingdom</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preferences -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Preferences</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Email Notifications</p>
                                    <p class="text-sm text-gray-600">Receive updates about new artworks and artists</p>
                                </div>
                                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full bg-black transition-colors">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Newsletter</p>
                                    <p class="text-sm text-gray-600">Weekly newsletter with featured artworks</p>
                                </div>
                                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 transition-colors">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <x-button variant="outline" type="button">
                            Cancel
                        </x-button>
                        <x-button variant="primary" type="submit">
                            Save Changes
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Resale Modal -->
<x-modal id="resale-modal" size="lg" title="List Artwork for Resale">
    <form action="{{ route('collector.resales.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Artwork</label>
            <select name="artwork_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black" required>
                <option value="">Choose from your collection...</option>
                @foreach($ownedArtworks as $artwork)
                    <option value="{{ $artwork->id }}">{{ $artwork->title }} - {{ $artwork->artist->name ?? 'Unknown Artist' }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Listing Price</label>
                <input type="number" name="asking_price" placeholder="Enter price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Offer</label>
                <input type="number" name="minimum_price" placeholder="Enter minimum price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Describe the artwork's condition and why you're selling..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"></textarea>
        </div>
        
        <div class="flex justify-end space-x-4">
            <x-button variant="outline" type="button" onclick="closeModal('resale-modal')">
                Cancel
            </x-button>
            <x-button variant="primary" type="submit">
                Create Listing
            </x-button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('nav button').forEach(tab => {
        tab.classList.remove('border-black', 'text-black');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Activate selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-black', 'text-black');
}

function removeFromWishlist(artworkId) {
    if (confirm('Are you sure you want to remove this artwork from your wishlist?')) {
        // Remove from wishlist logic
        console.log('Remove from wishlist:', artworkId);
        location.reload();
    }
}

function openResaleModal() {
    openModal('resale-modal');
}
</script>
@endpush
