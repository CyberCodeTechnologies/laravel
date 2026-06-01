@extends('layouts.app')

@section('title', 'Certificate of Authenticity - Panchi Gallery')
@section('meta-description', 'View and download your certificate of authenticity.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Certificate Container -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-4 border-gray-900">
        <!-- Certificate Header -->
        <div class="bg-gray-900 text-white p-8 text-center">
            <h1 class="font-serif text-4xl font-bold mb-2">Certificate of Authenticity</h1>
            <p class="text-gray-300">Panchi Gallery Art Marketplace</p>
        </div>

        <!-- Certificate Content -->
        <div class="p-8">
            <!-- Certificate ID -->
            <div class="text-center mb-8">
                <p class="text-sm text-gray-500 mb-1">Certificate Number</p>
                <p class="text-xl font-mono font-bold">{{ $ownership->certificate->certificate_code ?? 'PG-' . str_pad($ownership->id, 8, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- Artwork Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <img src="{{ $ownership->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                         alt="{{ $ownership->artwork->title }}" 
                         class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>
                <div class="flex flex-col justify-center">
                    <h2 class="font-serif text-2xl font-bold mb-4">{{ $ownership->artwork->title }}</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Artist:</span>
                            <span class="font-medium">{{ $ownership->artwork->artist->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Medium:</span>
                            <span class="font-medium">{{ $ownership->artwork->medium ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Dimensions:</span>
                            <span class="font-medium">{{ $ownership->artwork->dimensions ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Year:</span>
                            <span class="font-medium">{{ $ownership->artwork->year ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Current Owner:</span>
                            <span class="font-medium">{{ $ownership->owner->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Acquired Date:</span>
                            <span class="font-medium">{{ $ownership->acquired_at?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Section -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Verification Status</p>
                        <p class="text-lg font-semibold text-green-600">Verified & Authentic</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Issued Date</p>
                        <p class="font-medium">{{ $ownership->certificate->issue_date?->format('M d, Y') ?? $ownership->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="flex items-center justify-center gap-6 mb-8">
                <div class="text-center">
                    @if($ownership->certificate && $ownership->certificate->qr_code)
                        <img src="{{ $ownership->certificate->qr_code }}" alt="QR Code" class="w-32 h-32 rounded-lg">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                            <span class="text-xs text-gray-500">QR Code</span>
                        </div>
                    @endif
                    <p class="text-xs text-gray-500">Scan to verify</p>
                </div>
                <div class="text-sm text-gray-600 max-w-xs">
                    <p>This certificate verifies the authenticity of the artwork. Scan the QR code or visit our verification page to confirm.</p>
                </div>
            </div>

            <!-- Digital Signature -->
            <div class="border-t pt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Digitally Signed by</p>
                        <p class="font-semibold">Panchi Gallery</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Verification URL</p>
                        <p class="text-sm font-mono text-blue-600">{{ route('verify.certificate', $ownership->certificate->certificate_code ?? '') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4 mt-8">
        <a href="{{ route('collector.artworks') }}" 
           class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Back to Collection
        </a>
        <button onclick="window.print()" 
                class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
            Print Certificate
        </button>
        <a href="#" 
           class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Download PDF
        </a>
    </div>
</div>
@endsection
