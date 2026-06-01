@extends('layouts.artist')

@section('title', __('messages.commissions') . ' - Artist Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">{{ __('messages.commissions') ?? 'Commissions' }}</h1>
        <a href="{{ route('artist.earnings') }}" class="text-gray-600 hover:text-black">{{ __('messages.back_to_earnings') ?? 'Back to Earnings' }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($commissions->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.date') ?? 'Date' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.order') ?? 'Order' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.sale_amount') ?? 'Sale Amount' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.platform_fee') ?? 'Platform Fee' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.your_earnings') ?? 'Your Earnings' }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">{{ __('messages.status') ?? 'Status' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($commissions as $commission)
                        <tr>
                            <td class="px-6 py-4">{{ $commission->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                @if($commission->order_id)
                                    <a href="{{ route('orders.show', $commission->order_id) }}" class="text-blue-600 hover:underline">
                                        #{{ str_pad($commission->order_id, 6, '0', STR_PAD_LEFT) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">${{ number_format($commission->sale_amount, 2) }}</td>
                            <td class="px-6 py-4 text-red-600">-${{ number_format($commission->platform_fee, 2) }}</td>
                            <td class="px-6 py-4 font-semibold text-green-600">${{ number_format($commission->artist_earnings, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = $commission->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t">
                {{ $commissions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('messages.no_commissions_yet') ?? 'No commissions yet' }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
