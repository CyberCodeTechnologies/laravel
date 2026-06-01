@extends('admin.layouts.app')

@section('title', 'Revenue Statistics - Admin')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">Platform Revenue</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Platform Fees</p>
            <p class="text-2xl font-bold">${{ number_format($stats['total_fees'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Pending Fees</p>
            <p class="text-2xl font-bold text-yellow-600">${{ number_format($stats['pending_fees'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Paid Fees</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($stats['paid_fees'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-blue-600">${{ number_format($stats['monthly_fees'], 2) }}</p>
        </div>
    </div>

    <!-- Fee Percentage Setting -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Platform Fee Settings</h2>
        <form action="{{ route('admin.revenue.fee-percentage') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Fee Percentage</label>
                <div class="flex items-center">
                    <input type="number" name="percentage" value="30" min="0" max="100" step="0.01" class="w-32 px-3 py-2 border rounded-lg">
                    <span class="ml-2">%</span>
                </div>
            </div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Update
            </button>
        </form>
    </div>

    <!-- Monthly Revenue Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <h2 class="text-lg font-semibold p-6 border-b">Monthly Revenue {{ now()->year }}</h2>
        
        @if($monthlyRevenue->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Month</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-600">Platform Fees</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($monthlyRevenue as $revenue)
                        <tr>
                            <td class="px-6 py-4">{{ date('F', mktime(0, 0, 0, $revenue->month, 1)) }}</td>
                            <td class="px-6 py-4 text-right font-medium">${{ number_format($revenue->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No revenue data available for this year.</p>
            </div>
        @endif
    </div>
</div>
@endsection
