@extends('layouts.artist')

@section('title', __('messages.payouts') . ' - Artist Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">{{ __('messages.payouts') ?? 'Payouts' }}</h1>
        <a href="{{ route('artist.earnings') }}" class="text-gray-600 hover:text-black">{{ __('messages.back_to_earnings') ?? 'Back to Earnings' }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($payouts->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.date') ?? 'Date' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.amount') ?? 'Amount' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.method') ?? 'Method' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.commissions') ?? 'Commissions' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.status') ?? 'Status' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.reference') ?? 'Reference' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payouts as $payout)
                        <tr>
                            <td class="px-6 py-4">{{ $payout->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-bold">${{ number_format($payout->amount, 2) }}</td>
                            <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $payout->method)) }}</td>
                            <td class="px-6 py-4">{{ $payout->commission_count }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match($payout->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $payout->payment_reference ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t">
                {{ $payouts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('messages.no_payouts_yet') ?? 'No payouts yet' }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
