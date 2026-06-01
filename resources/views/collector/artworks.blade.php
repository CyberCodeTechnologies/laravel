@extends('layouts.app')

@section('title', 'My Collection - Panchi Gallery')
@section('meta-description', 'View and manage your art collection. Track ownership, certificates, and resale value.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl font-bold mb-2">My Collection</h1>
                <p class="text-gray-300">Manage your artworks and ownership certificates</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-gray-300">{{ $ownerships->total() ?? 0 }} artworks owned</span>
            </div>
        </div>
    </div>
</section>

<!-- Collection Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($ownerships->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ownerships as $ownership)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="relative">
                        <img src="{{ $ownership->artwork->image_url ?? asset('images/placeholder-artwork.jpg') }}" 
                             alt="{{ $ownership->artwork->title }}" 
                             class="w-full h-64 object-cover">
                        @if($ownership->artwork->is_resale_available)
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-full">
                            Listed for Resale
                        </span>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="font-serif text-xl font-bold mb-1">{{ $ownership->artwork->title }}</h3>
                        <p class="text-gray-600 mb-4">by {{ $ownership->artwork->artist->name ?? 'Unknown' }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span>Acquired:</span>
                                <span>{{ $ownership->acquired_at?->format('M d, Y') ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Purchase Price:</span>
                                <span>${{ number_format($ownership->purchase_price ?? 0, 2) }}</span>
                            </div>
                            @if($ownership->certificate)
                            <div class="flex justify-between">
                                <span>Certificate:</span>
                                <span class="text-green-600">Verified</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('public.artworks.show', $ownership->artwork) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                View Artwork
                            </a>
                            @if($ownership->certificate)
                            <a href="{{ route('collector.ownership.certificate', $ownership) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                Certificate
                            </a>
                            @endif
                            @if(!$ownership->artwork->is_resale_available)
                            <a href="{{ route('collector.resales.create', $ownership->artwork) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                                Resell
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $ownerships->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No artworks in your collection yet</h3>
                <p class="text-gray-500 mb-6">Start building your collection by browsing our curated artworks</p>
                <a href="{{ route('public.artworks.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
