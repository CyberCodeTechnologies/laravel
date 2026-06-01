@extends('layouts.app')

@section('title', 'Collector Dashboard - Panchi Gallery')
@section('meta-description', 'Manage your art collection, track purchases, and discover new artworks in your Panchi Gallery collector dashboard.')

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    {{ auth()->user()->name }}'s Collection
                </h1>
                <p class="text-gray-300">
                    Track your artworks, manage purchases, and explore the marketplace
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('public.artworks.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition">
                    Browse Artworks
                </a>
                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 border border-white text-white rounded-lg hover:bg-white hover:text-gray-900 transition">
                    Marketplace
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $stats['total_artworks'] ?? 0 }}</div>
                <div class="text-gray-300">Artworks Owned</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $stats['total_purchases'] ?? 0 }}</div>
                <div class="text-gray-300">Total Purchases</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $stats['wishlist_count'] ?? 0 }}</div>
                <div class="text-gray-300">Wishlist Items</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $stats['following_count'] ?? 0 }}</div>
                <div class="text-gray-300">Artists Following</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Purchases -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-serif font-bold">Recent Purchases</h2>
                        <a href="{{ route('collector.purchases') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    @if(count($recentPurchases ?? []) > 0)
                        <div class="space-y-4">
                            @foreach($recentPurchases as $purchase)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <img src="{{ $purchase->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                                     alt="{{ $purchase->artwork->title }}" 
                                     class="w-16 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $purchase->artwork->title }}</h3>
                                    <p class="text-sm text-gray-600">by {{ $purchase->artwork->artist->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $purchase->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">${{ number_format($purchase->amount, 2) }}</p>
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No recent purchases. Start collecting today!</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Owned Artworks -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-serif font-bold">My Collection</h2>
                        <a href="{{ route('collector.artworks') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    @if(count($collection ?? []) > 0)
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($collection->take(4) as $artwork)
                            <a href="{{ route('public.artworks.show', $artwork) }}" class="group">
                                <img src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                                     alt="{{ $artwork->title }}" 
                                     class="w-full h-24 object-cover rounded-lg group-hover:opacity-75 transition">
                                <p class="text-xs mt-1 truncate">{{ $artwork->title }}</p>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No artworks in your collection yet.</p>
                    @endif
                </div>

                <!-- Wishlist -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-serif font-bold">Wishlist</h2>
                        <a href="{{ route('collector.wishlist') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    @if(count($wishlist ?? []) > 0)
                        <div class="space-y-3">
                            @foreach($wishlist->take(3) as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                                     alt="{{ $item->title }}" 
                                     class="w-12 h-12 object-cover rounded">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $item->title }}</p>
                                    <p class="text-xs text-gray-500">${{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Your wishlist is empty.</p>
                    @endif
                </div>

                <!-- Resale Listings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-serif font-bold">My Resales</h2>
                        <a href="{{ route('collector.resales') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    @if(count($resaleListings ?? []) > 0)
                        <div class="space-y-3">
                            @foreach($resaleListings->take(3) as $resale)
                            <div class="flex items-center justify-between">
                                <span class="text-sm truncate">{{ $resale->artwork->title }}</span>
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $resale->status === 'listed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($resale->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-3">No active resale listings.</p>
                        <a href="{{ route('collector.artworks') }}" class="text-sm text-blue-600 hover:underline">List an artwork for resale</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
