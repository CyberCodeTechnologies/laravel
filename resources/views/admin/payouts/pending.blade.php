@extends('admin.layouts.app')

@section('title', 'Pending Payouts - Admin')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">Pending Payouts</h1>
        <a href="{{ route('admin.payouts.index') }}" class="text-gray-600 hover:text-black">All Payouts</a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Pending Amount</p>
            <p class="text-2xl font-bold">${{ number_format($stats['total_pending_amount'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Pending Requests</p>
            <p class="text-2xl font-bold">{{ $stats['total_pending_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Processing</p>
            <p class="text-2xl font-bold">{{ $stats['total_processing_count'] }}</p>
        </div>
    </div>

    @if($payouts->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Artist</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Method</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Commissions</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Requested</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payouts as $payout)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        {{ substr($payout->artist->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $payout->artist->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $payout->artist->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold">${{ number_format($payout->amount, 2) }}</td>
                            <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $payout->method)) }}</td>
                            <td class="px-6 py-4">{{ $payout->commission_count }} commissions</td>
                            <td class="px-6 py-4">{{ $payout->requested_at?->format('M d, Y') ?? $payout->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.payouts.show', $payout) }}" class="text-blue-600 hover:underline mr-3">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $payouts->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">No pending payouts</p>
        </div>
    @endif
</div>
@endsection
