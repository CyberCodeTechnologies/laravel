@extends('layouts.app')

@section('title', 'Ownership History - ' . $artwork->title . ' - Panchi Gallery')
@section('meta-description', 'View the complete provenance and ownership history of this artwork.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-sm mb-4">
            <a href="{{ route('public.artworks.show', $artwork) }}" class="text-gray-400 hover:text-white">{{ $artwork->title }}</a>
            <span class="text-gray-600">/</span>
            <span class="text-gray-300">Ownership History</span>
        </div>
        <h1 class="font-serif text-3xl font-bold mb-2">Provenance & Ownership History</h1>
        <p class="text-gray-300">Complete chain of ownership for this artwork</p>
    </div>
</section>

<!-- History Timeline -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Artwork Info -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex gap-4">
                <img src="{{ $artwork->image_url ?? asset('images/placeholder-artwork.jpg') }}" 
                     alt="{{ $artwork->title }}" 
                     class="w-24 h-24 object-cover rounded-lg">
                <div>
                    <h2 class="font-serif text-xl font-bold">{{ $artwork->title }}</h2>
                    <p class="text-gray-600">by {{ $artwork->artist->name ?? 'Unknown' }}</p>
                    <p class="text-gray-500 text-sm mt-1">{{ $artwork->medium }} | {{ $artwork->dimensions }}</p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h3 class="text-lg font-semibold mb-6">Ownership Timeline</h3>
            
            @if($ownerships->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                    <div class="space-y-8">
                        @foreach($ownerships as $index => $ownership)
                        <div class="relative flex gap-6">
                            <!-- Timeline Dot -->
                            <div class="relative z-10">
                                <div class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-gray-900' : 'bg-white border-2 border-gray-900' }} flex items-center justify-center">
                                    @if($index === 0)
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $ownerships->count() - $index }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 pb-8">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs rounded-full 
                                            {{ $index === 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $index === 0 ? 'Current Owner' : 'Previous Owner' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $ownership->acquired_at?->format('M d, Y') ?? 'Date unknown' }}
                                        </span>
                                    </div>
                                    
                                    <p class="font-semibold">{{ $ownership->owner->name ?? 'Unknown Owner' }}</p>
                                    
                                    @if($ownership->purchase_price)
                                    <p class="text-sm text-gray-600 mt-1">
                                        Purchase Price: ${{ number_format($ownership->purchase_price, 2) }}
                                    </p>
                                    @endif

                                    @if($ownership->transaction && $ownership->transaction->type === 'resale')
                                    <div class="mt-2 inline-flex items-center text-xs text-blue-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Secondary Market Purchase
                                    </div>
                                    @endif

                                    @if($ownership->certificate)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <a href="{{ route('verify.certificate', $ownership->certificate->certificate_code) }}" 
                                           class="text-sm text-blue-600 hover:underline">
                                            View Certificate: {{ $ownership->certificate->certificate_code }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Original Creation -->
                <div class="relative flex gap-6 mt-4">
                    <div class="relative z-10">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs rounded-full bg-purple-100 text-purple-800 mb-2">
                                Origin
                            </span>
                            <p class="font-semibold">Created by {{ $artwork->artist->name ?? 'Unknown Artist' }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $artwork->year ? 'Year: ' . $artwork->year : 'Creation date unknown' }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No ownership records available for this artwork.</p>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8">
            <a href="{{ route('public.artworks.show', $artwork) }}" 
               class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-white transition">
                Back to Artwork
            </a>
            @if($artwork->certificate)
            <a href="{{ route('verify.certificate', $artwork->certificate->certificate_code) }}" 
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                Verify Certificate
            </a>
            @endif
        </div>
    </div>
</section>
@endsection
