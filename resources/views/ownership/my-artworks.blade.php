@extends('layouts.app')

@section('title', 'My Artworks - Panchi Gallery')
@section('meta-description', 'View and manage your owned artworks.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">My Artworks</h1>
        <p class="text-gray-300">View and manage your collection</p>
    </div>
</section>

<!-- Artworks Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($ownerships->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ownerships as $ownership)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ $ownership->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                         alt="{{ $ownership->artwork->title }}" 
                         class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="font-serif text-lg font-bold mb-1">{{ $ownership->artwork->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">by {{ $ownership->artwork->artist->name ?? 'Unknown' }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span>Acquired:</span>
                                <span>{{ $ownership->acquired_at?->format('M d, Y') ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Certificate:</span>
                                <span class="text-green-600">{{ $ownership->certificate ? 'Verified' : 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('public.artworks.show', $ownership->artwork) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                                View
                            </a>
                            @if($ownership->certificate)
                            <a href="{{ route('ownership.certificate', $ownership) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm">
                                Certificate
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
                <p class="text-gray-500 mb-4">No artworks in your collection yet.</p>
                <a href="{{ route('public.artworks.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
