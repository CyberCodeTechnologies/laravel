@extends('layouts.app')

@section('title', 'Track Shipment')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-serif font-bold text-center mb-8">Track Your Shipment</h1>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form action="{{ route('shipments.track') }}" method="GET" class="flex gap-4">
                <input type="text" name="tracking_number" value="{{ request('tracking_number') }}" placeholder="Enter tracking number..." class="flex-1 px-4 py-3 border rounded-lg" required>
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800">Track</button>
            </form>
        </div>

        @if(isset($shipment))
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Tracking Number</p>
                        <p class="text-xl font-bold">{{ $shipment->tracking_number }}</p>
                    </div>
                    @php
                        $statusClass = match($shipment->status) {
                            'delivered' => 'bg-green-100 text-green-800',
                            'in_transit' => 'bg-blue-100 text-blue-800',
                            'out_for_delivery' => 'bg-purple-100 text-purple-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Carrier</p>
                        <p class="font-medium">{{ $shipment->carrier }}</p>
                    </div>
                    @if($shipment->service)
                        <div>
                            <p class="text-sm text-gray-500">Service</p>
                            <p class="font-medium">{{ $shipment->service }}</p>
                        </div>
                    @endif
                </div>

                @if($shipment->estimated_delivery)
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-600 font-medium">Estimated Delivery</p>
                        <p class="text-lg font-bold text-blue-800">{{ $shipment->estimated_delivery->format('F d, Y') }}</p>
                    </div>
                @endif

                @if($shipment->delivered_at)
                    <div class="bg-green-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-green-600 font-medium">Delivered On</p>
                        <p class="text-lg font-bold text-green-800">{{ $shipment->delivered_at->format('F d, Y') }}</p>
                    </div>
                @endif

                @if($shipment->getTrackingUrl())
                    <a href="{{ $shipment->getTrackingUrl() }}" target="_blank" class="block w-full text-center bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800">
                        Track on {{ $shipment->carrier }} Website
                    </a>
                @endif
            </div>

            <!-- Tracking History -->
            @if($shipment->tracking_history && count($shipment->tracking_history) > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Tracking History</h2>
                    
                    <div class="space-y-4">
                        @foreach(array_reverse($shipment->tracking_history) as $event)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                                <div>
                                    <p class="font-medium">{{ $event['event'] }}</p>
                                    @if(isset($event['location']))
                                        <p class="text-sm text-gray-500">{{ $event['location'] }}</p>
                                    @endif
                                    @if(isset($event['description']))
                                        <p class="text-sm text-gray-600">{{ $event['description'] }}</p>
                                    @endif
                                    <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @elseif(request('tracking_number'))
            <div class="bg-red-50 rounded-lg p-6 text-center">
                <p class="text-red-600">No shipment found with tracking number: {{ request('tracking_number') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
