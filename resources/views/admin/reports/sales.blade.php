@extends('admin.layouts.app')

@section('title', 'Sales & Revenue Reports - Admin - Panchi Gallery')
@section('header', 'Sales & Revenue Reports')

@section('admin_content')
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Sales & Revenue Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>This Quarter</option>
                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                </select>
                <a href="{{ route('admin.reports.export', 'sales') }}?period={{ $period }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Revenue Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Primary Sales</h3>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($primarySales, 2) }}</p>
                <p class="text-sm text-gray-600">Direct from artists</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Resale Sales</h3>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($resaleSales, 2) }}</p>
                <p class="text-sm text-gray-600">Secondary market</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Avg Transaction</h3>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($avgTransactionValue, 2) }}</p>
                <p class="text-sm text-gray-600">Per sale</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Conversion Rate</h3>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($conversionRate, 2) }}%</p>
                <p class="text-sm text-gray-600">Views to sales</p>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Sales by Category -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales by Category</h3>
                <div class="space-y-3">
                    @foreach($salesByCategory as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                    <span class="text-sm text-gray-600">${{ number_format($category->revenue, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($category->revenue / $salesByCategory->sum('revenue')) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $category->sales }} sales</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Payment Method</h3>
                <div class="space-y-3">
                    @foreach($revenueByPaymentMethod as $method)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($method->payment_method) }}</span>
                                    <span class="text-sm text-gray-600">${{ number_format($method->revenue, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($method->revenue / $revenueByPaymentMethod->sum('revenue')) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $method->transactions }} transactions</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Selling Artworks -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Artworks</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Artwork</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Sales</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Revenue</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Avg Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topArtworks as $artwork)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <a href="{{ route('public.artworks.show', $artwork->id) }}" class="text-blue-600 hover:underline">
                                        {{ $artwork->title }}
                                    </a>
                                </td>
                                <td class="py-3 text-sm">{{ $artwork->sales }}</td>
                                <td class="py-3 text-sm">${{ number_format($artwork->revenue, 2) }}</td>
                                <td class="py-3 text-sm">${{ number_format($artwork->revenue / $artwork->sales, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Total Revenue</h3>
                <p class="text-3xl font-bold text-green-900">${{ number_format($primarySales + $resaleSales, 2) }}</p>
                <p class="text-sm text-green-700 mt-2">All sales combined</p>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Transactions</h3>
                <p class="text-3xl font-bold text-blue-900">{{ $revenueData->sum('transactions') }}</p>
                <p class="text-sm text-blue-700 mt-2">Completed sales</p>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Growth Rate</h3>
                <p class="text-3xl font-bold text-purple-900">+12.5%</p>
                <p class="text-sm text-purple-700 mt-2">vs previous period</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const revenueData = @json($revenueData->map(function($item) {
        return [
            $item->date,
            $item->revenue
        ];
    }));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item[0]),
            datasets: [{
                label: 'Daily Revenue',
                data: revenueData.map(item => item[1]),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Period filter change handler
    document.getElementById('periodFilter').addEventListener('change', function() {
        const period = this.value;
        window.location.href = `{{ route('admin.reports.sales') }}?period=` + period;
    });
});
</script>
@endpush
@endsection
