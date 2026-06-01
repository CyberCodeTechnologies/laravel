@extends('admin.layouts.app')

@section('title', 'Payout Details - Admin')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">Payout Details</h1>
        <div class="flex gap-4">
            <a href="{{ route('admin.payouts.pending') }}" class="text-gray-600 hover:text-black">Back to Pending</a>
            <a href="{{ route('admin.payouts.index') }}" class="text-gray-600 hover:text-black">All Payouts</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payout Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Payout Information</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="text-2xl font-bold">${{ number_format($payout->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @php
                            $statusClass = match($payout->status) {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ ucfirst($payout->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Method</p>
                        <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $payout->method)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Commissions Included</p>
                        <p class="font-medium">{{ $payout->commission_count }}</p>
                    </div>
                </div>

                @if($payout->payment_reference)
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-500">Payment Reference</p>
                        <p class="font-medium">{{ $payout->payment_reference }}</p>
                    </div>
                @endif

                @if($payout->failure_reason)
                    <div class="border-t pt-4 mt-4">
                        <p class="text-sm text-gray-500">Failure Reason</p>
                        <p class="text-red-600">{{ $payout->failure_reason }}</p>
                    </div>
                @endif

                @if($payout->notes)
                    <div class="border-t pt-4 mt-4">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p>{{ $payout->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Associated Commissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Associated Commissions</h2>
                
                @if($commissions->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Sale Amount</th>
                                <th class="text-left py-2">Fee</th>
                                <th class="text-left py-2">Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $commission)
                                <tr class="border-b">
                                    <td class="py-3">{{ $commission->created_at->format('M d, Y') }}</td>
                                    <td class="py-3">${{ number_format($commission->sale_amount, 2) }}</td>
                                    <td class="py-3 text-red-600">-${{ number_format($commission->platform_fee, 2) }}</td>
                                    <td class="py-3 font-semibold text-green-600">${{ number_format($commission->artist_earnings, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No commissions associated with this payout.</p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-6">
            <!-- Artist Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Artist</h2>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                        {{ substr($payout->artist->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium">{{ $payout->artist->name }}</p>
                        <p class="text-sm text-gray-500">{{ $payout->artist->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Process Actions -->
            @if($payout->isPending())
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Process Payout</h2>
                    
                    <form action="{{ route('admin.payouts.process', $payout) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference</label>
                            <input type="text" name="payment_reference" class="w-full px-3 py-2 border rounded-lg" placeholder="e.g., TRX123456">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" class="w-full px-3 py-2 border rounded-lg" rows="2"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                            Mark as Completed
                        </button>
                    </form>

                    <form action="{{ route('admin.payouts.reject', $payout) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                            <textarea name="reason" class="w-full px-3 py-2 border rounded-lg" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                            Reject Payout
                        </button>
                    </form>
                </div>
            @endif

            @if($payout->processor)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Processed By</h2>
                    <p class="font-medium">{{ $payout->processor->name }}</p>
                    <p class="text-sm text-gray-500">{{ $payout->processed_at?->format('M d, Y H:i') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
