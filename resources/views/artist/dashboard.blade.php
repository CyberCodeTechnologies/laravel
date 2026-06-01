@extends('layouts.app')

@section('title', 'Artist Dashboard - Panchi Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Artist Dashboard</h1>
            <p class="text-gray-600 mt-2">Manage your artworks, track sales, and connect with collectors.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Total Artworks</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_artworks'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Total Sales</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_sales'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Revenue</div>
                <div class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-500 mb-1">Followers</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['followers_count'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('artist.artworks.create') }}" class="bg-black text-white rounded-lg p-6 hover:bg-gray-800 transition">
                <i class="fas fa-plus-circle text-2xl mb-3"></i>
                <h3 class="font-semibold">Add New Artwork</h3>
                <p class="text-sm text-gray-300 mt-1">Upload and list a new artwork</p>
            </a>
            <a href="{{ route('artist.sales') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:border-gray-400 transition">
                <i class="fas fa-chart-line text-2xl mb-3 text-gray-600"></i>
                <h3 class="font-semibold text-gray-900">View Sales</h3>
                <p class="text-sm text-gray-500 mt-1">Track your sales history</p>
            </a>
            <a href="{{ route('artist.profile') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:border-gray-400 transition">
                <i class="fas fa-user-edit text-2xl mb-3 text-gray-600"></i>
                <h3 class="font-semibold text-gray-900">Edit Profile</h3>
                <p class="text-sm text-gray-500 mt-1">Update your artist profile</p>
            </a>
        </div>

        <!-- Recent Artworks -->
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Recent Artworks</h2>
                <a href="{{ route('artist.artworks') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="p-6">
                @if(isset($recentArtworks) && count($recentArtworks) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recentArtworks as $artwork)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" alt="{{ $artwork->title }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $artwork->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $artwork->status }}</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="font-bold text-gray-900">${{ number_format($artwork->price, 2) }}</span>
                                        <a href="{{ route('artist.artworks.edit', $artwork) }}" class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No artworks yet. <a href="{{ route('artist.artworks.create') }}" class="text-blue-600 hover:underline">Create your first artwork</a></p>
                @endif
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Recent Sales</h2>
                <a href="{{ route('artist.sales') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="p-6">
                @if(isset($recentSales) && count($recentSales) > 0)
                    <div class="space-y-4">
                        @foreach($recentSales as $sale)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $sale->artwork->image_url ?? asset('images/placeholder-artwork.jpg') }}" alt="" class="w-12 h-12 rounded object-cover">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $sale->artwork->title ?? 'Artwork' }}</h4>
                                        <p class="text-sm text-gray-500">{{ $sale->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-green-600">+${{ number_format($sale->amount ?? $sale->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No sales yet. Keep creating amazing art!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
