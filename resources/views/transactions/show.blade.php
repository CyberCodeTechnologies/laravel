@extends('layouts.app')

@section('title', 'Transaction Details - Panchi Gallery')
@section('meta-description', 'View detailed information about your transaction.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-sm mb-4">
            <a href="{{ route('transactions.index') }}" class="text-gray-400 hover:text-white">Transactions</a>
            <span class="text-gray-600">/</span>
            <span class="text-gray-300">{{ $transaction->transaction_id ?? $transaction->id }}</span>
        </div>
        <h1 class="font-serif text-3xl font-bold">Transaction Details</h1>
    </div>
</section>

<!-- Transaction Details -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Status Banner -->
        <div class="mb-8 p-4 rounded-lg 
            {{ $transaction->status === 'completed' ? 'bg-green-50 border border-green-200' : '' }}
            {{ $transaction->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : '' }}
            {{ $transaction->status === 'refunded' ? 'bg-red-50 border border-red-200' : '' }}
            {{ $transaction->status === 'processing' ? 'bg-blue-50 border border-blue-200' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium mb-1">Transaction Status</p>
                    <p class="text-lg font-semibold 
                        {{ $transaction->status === 'completed' ? 'text-green-800' : '' }}
                        {{ $transaction->status === 'pending' ? 'text-yellow-800' : '' }}
                        {{ $transaction->status === 'refunded' ? 'text-red-800' : '' }}
                        {{ $transaction->status === 'processing' ? 'text-blue-800' : '' }}">
                        {{ ucfirst($transaction->status) }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Transaction Date</p>
                    <p class="font-medium">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Artwork Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-4">Artwork</h2>
                @if($transaction->artwork)
                <div class="flex gap-4">
                    <img src="{{ $transaction->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                         alt="{{ $transaction->artwork->title }}" 
                         loading="lazy"
                         class="w-24 h-24 object-cover rounded-lg">
                    <div>
                        <h3 class="font-semibold">{{ $transaction->artwork->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">by {{ $transaction->artwork->artist->name ?? 'Unknown' }}</p>
                        <a href="{{ route('public.artworks.show', $transaction->artwork) }}" 
                           class="text-sm text-blue-600 hover:underline">
                            View Artwork
                        </a>
                    </div>
                </div>
                @else
                <p class="text-gray-500">Artwork information not available</p>
                @endif
            </div>

            <!-- Transaction Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-serif font-bold mb-4">Transaction Summary</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction ID</span>
                        <span class="font-mono">{{ $transaction->transaction_id ?? $transaction->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type</span>
                        <span class="capitalize">{{ $transaction->type ?? 'purchase' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount</span>
                        <span class="font-semibold">${{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    @if($transaction->platform_fee)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Platform Fee</span>
                        <span>${{ number_format($transaction->platform_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($transaction->shipping_cost)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span>${{ number_format($transaction->shipping_cost, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between font-semibold text-base">
                            <span>Total</span>
                            <span>${{ number_format($transaction->total_amount ?? $transaction->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parties Involved -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
            <h2 class="text-lg font-serif font-bold mb-4">Parties</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Buyer</p>
                    <p class="font-medium">{{ $transaction->buyer->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->buyer->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Seller</p>
                    <p class="font-medium">{{ $transaction->seller->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->seller->email ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8">
            <a href="{{ route('transactions.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Back to Transactions
            </a>
            @if($transaction->certificate)
            <a href="{{ route('collector.ownership.certificate', $transaction->certificate) }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                View Certificate
            </a>
            @endif
        </div>
    </div>
</section>
@endsection
