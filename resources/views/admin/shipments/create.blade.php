@extends('admin.layouts.app')

@section('title', 'Create Shipment - Order #' . $order->order_number)

@section('header', 'Create Shipment: Order #' . $order->order_number)

@section('admin_content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <div class="mb-8">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center text-gray-600 hover:text-black">
                <i class="fas fa-arrow-left mr-2"></i> Back to Order Details
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Shipment Information</h3>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden field to mark as shipped -->
                    <input type="hidden" name="status" value="shipped">

                    <div class="space-y-6">
                        <!-- Shipping Method -->
                        <div>
                            <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-1">Shipping Carrier / Method</label>
                            <input type="text" name="shipping_method" id="shipping_method" value="{{ old('shipping_method', $order->shipping_method ?? 'Standard Shipping') }}" 
                                   required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g., DHL, FedEx, Royal Mail">
                        </div>

                        <!-- Tracking Number -->
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                            <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number') }}" 
                                   required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Enter the tracking ID provided by the carrier">
                        </div>

                        <!-- Shipping Address (Read-only for reference) -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Delivery Address</h4>
                            <p class="text-sm text-gray-700">
                                {{ $order->first_name }} {{ $order->last_name }}<br>
                                {{ $order->address }}<br>
                                {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                                {{ $order->country }}
                            </p>
                        </div>

                        <!-- Admin Notes -->
                        <div>
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Shipping Notes (Optional)</label>
                            <textarea name="admin_notes" id="admin_notes" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Any internal notes about the shipment...">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Submitting this form will update the order status to <strong>Shipped</strong> and notify the customer.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t flex justify-end space-x-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition font-semibold">
                            Confirm Shipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
