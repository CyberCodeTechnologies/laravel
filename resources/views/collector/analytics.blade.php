@extends('layouts.app')

@section('title', 'Collection Analytics - Panchi Gallery')
@section('meta-description', 'View insights and analytics about your art collection.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Collection Analytics</h1>
        <p class="text-gray-300">Insights about your art collection</p>
    </div>
</section>

<!-- Analytics Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Total Artworks</p>
                <p class="text-2xl font-bold">{{ $stats['total_artworks'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Collection Value</p>
                <p class="text-2xl font-bold">${{ number_format($stats['total_value'] ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Avg. Purchase Price</p>
                <p class="text-2xl font-bold">${{ number_format($stats['avg_purchase_price'] ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-1">Categories</p>
                <p class="text-2xl font-bold">{{ count($collectionByCategory ?? []) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Collection by Category -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-6">Collection by Category</h2>
                @if(count($collectionByCategory ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($collectionByCategory as $category => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">{{ $category }}</span>
                            <div class="flex items-center gap-4">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gray-900 h-2 rounded-full" style="width: {{ ($count / array_sum($collectionByCategory)) * 100 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No category data available</p>
                @endif
            </div>

            <!-- Monthly Purchases -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-6">Purchase Activity</h2>
                @if(count($monthlyPurchases ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($monthlyPurchases as $month => $data)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-700">{{ $month }}</span>
                            <div class="text-right">
                                <p class="font-semibold">${{ number_format($data['amount'] ?? 0, 0) }}</p>
                                <p class="text-sm text-gray-500">{{ $data['count'] ?? 0 }} artwork{{ ($data['count'] ?? 0) > 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No purchase history available</p>
                @endif
            </div>
        </div>

        <!-- Top Valued Artworks -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
            <h2 class="text-lg font-serif font-bold mb-6">Most Valuable Artworks</h2>
            @if(count($topValuedArtworks ?? []) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($topValuedArtworks as $artwork)
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $artwork->image_url ?? asset('images/placeholder-artwork.jpg') }}" 
                             alt="{{ $artwork->title }}" 
                             class="w-20 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold truncate">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-600">by {{ $artwork->artist->name ?? 'Unknown' }}</p>
                            <p class="text-sm font-medium mt-1">${{ number_format($artwork->current_value ?? $artwork->purchase_price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No artworks in your collection yet</p>
            @endif
        </div>
    </div>
</section>
@endsection
