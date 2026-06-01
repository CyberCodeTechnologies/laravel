@extends('layouts.app')

@section('title', 'Shipment Details - Order #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-serif font-bold">Shipment Details</h1>
        <a href="{{ route('orders.show', $order) }}" class="text-gray-600 hover:text-black">Back to Order</a>
    </div>

    @if($shipment)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tracking Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Tracking Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Carrier</p>
                            <p class="font-medium">{{ $shipment->carrier }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tracking Number</p>
                            <p class="font-medium">{{ $shipment->tracking_number }}</p>
                        </div>
                        @if($shipment->service)
                            <div>
                                <p class="text-sm text-gray-500">Service</p>
                                <p class="font-medium">{{ $shipment->service }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @php
                                $statusClass = match($shipment->status) {
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'in_transit' => 'bg-blue-100 text-blue-800',
                                    'out_for_delivery' => 'bg-purple-100 text-purple-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </div>
                    </div>

                    @if($shipment->estimated_delivery)
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-500">Estimated Delivery</p>
                            <p class="font-medium">{{ $shipment->estimated_delivery->format('F d, Y') }}</p>
                        </div>
                    @endif

                    @if($shipment->shipped_at)
                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-500">Shipped On</p>
                            <p class="font-medium">{{ $shipment->shipped_at->format('F d, Y') }}</p>
                        </div>
                    @endif

                    @if($shipment->delivered_at)
                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-500">Delivered On</p>
                            <p class="font-medium text-green-600">{{ $shipment->delivered_at->format('F d, Y') }}</p>
                        </div>
                    @endif

                    @if($shipment->getTrackingUrl())
                        <div class="mt-6">
                            <a href="{{ $shipment->getTrackingUrl() }}" target="_blank" class="inline-block bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                                Track on {{ $shipment->carrier }} Website
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tracking History -->
                @if($shipment->tracking_history && count($shipment->tracking_history) > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Tracking History</h2>
                        
                        <div class="space-y-4">
                            @foreach($shipment->tracking_history as $event)
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
            </div>

            <!-- Shipping Address -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                    @if(is_array($shipment->shipping_address))
                        <p>{{ $shipment->shipping_address['address'] ?? '' }}</p>
                        <p>{{ $shipment->shipping_address['city'] ?? '' }}, {{ $shipment->shipping_address['state'] ?? '' }} {{ $shipment->shipping_address['postal_code'] ?? '' }}</p>
                        <p>{{ $shipment->shipping_address['country'] ?? '' }}</p>
                    @else
                        <p>{{ $shipment->shipping_address ?? $order->address }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
                        <p>{{ $order->country }}</p>
                    @endif
                </div>

                @if($shipment->notes)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Notes</h2>
                        <p>{{ $shipment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 mb-4">No shipment has been created for this order yet.</p>
            
            @auth
                @if(Auth::user()->role === 'artist' || Auth::user()->role === 'admin')
                    <form action="{{ route('shipments.create', $order) }}" method="POST" class="max-w-md mx-auto text-left">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                            <select name="carrier" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="DHL">DHL</option>
                                <option value="FedEx">FedEx</option>
                                <option value="UPS">UPS</option>
                                <option value="USPS">USPS</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service (Optional)</label>
                            <input type="text" name="service" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery</label>
                            <input type="date" name="estimated_delivery" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800">
                            Create Shipment
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    @endif
</div>
@endsection
