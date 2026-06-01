@extends('layouts.app')

@section('title', 'Transactions - Panchi Gallery')
@section('meta-description', 'View all your transactions and payment history.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Transactions</h1>
        <p class="text-gray-300">View all your transaction history</p>
    </div>
</section>

<!-- Transactions List -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($transactions->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Transaction ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Artwork</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                    {{ $transaction->transaction_id ?? $transaction->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $transaction->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                                             alt="{{ $transaction->artwork->title ?? 'N/A' }}" 
                                             loading="lazy"
                                             class="w-10 h-10 object-cover rounded">
                                        <span class="font-medium text-sm">{{ $transaction->artwork->title ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="capitalize">{{ $transaction->type ?? 'purchase' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs rounded-full 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $transaction->status === 'refunded' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $transaction->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $transaction->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('transactions.show', $transaction->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    {{ $transactions->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No transactions yet</h3>
                <p class="text-gray-500 mb-6">Your transaction history will appear here</p>
                <a href="{{ route('public.artworks.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    Browse Artworks
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
