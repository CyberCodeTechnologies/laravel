@extends('admin.layouts.app')

@section('title', 'Manage Shipments - Admin')
@section('meta-description', 'Manage and track shipments on Panchi Gallery platform')

@section('header', 'Manage Shipments')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Shipments -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Shipments</p>
                        <p class="text-2xl font-bold text-blue-900">{{ App\Models\Shipment::count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">
                            @php
                                $newShipments = App\Models\Shipment::where('created_at', '>=', now()->subDays(7))->count();
                            @endphp
                            +{{ $newShipments }} this week
                        </p>
                    </div>
                </div>
            </div>

            <!-- In Transit -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-truck text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">In Transit</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ App\Models\Shipment::whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])->count() }}</p>
                        <p class="text-xs text-yellow-700 mt-1">On the way</p>
                    </div>
                </div>
            </div>

            <!-- Delivered -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Delivered</p>
                        <p class="text-2xl font-bold text-green-900">{{ App\Models\Shipment::where('status', 'delivered')->count() }}</p>
                        <p class="text-xs text-green-700 mt-1">Successfully delivered</p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-purple-900">{{ App\Models\Shipment::where('status', 'pending')->count() }}</p>
                        <p class="text-xs text-purple-700 mt-1">Awaiting pickup</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by tracking number..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="label_created" {{ request('status') == 'label_created' ? 'selected' : '' }}>Label Created</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="exception" {{ request('status') == 'exception' ? 'selected' : '' }}>Exception</option>
                    </select>
                </div>
                <div>
                    <select name="carrier" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Carriers</option>
                        <option value="DHL" {{ request('carrier') == 'DHL' ? 'selected' : '' }}>DHL</option>
                        <option value="FedEx" {{ request('carrier') == 'FedEx' ? 'selected' : '' }}>FedEx</option>
                        <option value="UPS" {{ request('carrier') == 'UPS' ? 'selected' : '' }}>UPS</option>
                        <option value="Local" {{ request('carrier') == 'Local' ? 'selected' : '' }}>Local Courier</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.shipments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
        </div>
    </div>
</section>

<!-- Shipments Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Est. Delivery</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipped</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $shipment->tracking_number ?? 'Pending' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $shipment->order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $shipment->order->first_name }} {{ $shipment->order->last_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $shipment->carrier }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $shipment->service ?? 'Standard' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $shipment->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $shipment->status === 'label_created' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $shipment->status === 'picked_up' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $shipment->status === 'in_transit' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $shipment->status === 'out_for_delivery' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $shipment->status === 'exception' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $shipment->estimated_delivery ? $shipment->estimated_delivery->format('M j, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $shipment->shipped_at ? $shipment->shipped_at->format('M j, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($shipment->label_url)
                                            <a href="{{ $shipment->label_url }}" target="_blank" class="text-green-600 hover:text-green-900" title="Download Label">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-shipping-fast text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No shipments found</p>
                                        <p class="text-sm">Get started by adjusting your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($shipments->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
