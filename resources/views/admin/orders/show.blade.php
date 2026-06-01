@extends('admin.layouts.app')

@section('title', 'Order Details - #' . $order->order_number)

@section('header', 'Order Details: #' . $order->order_number)

@section('admin_content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back and Actions -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-gray-600 hover:text-black">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
            <div class="flex space-x-3">
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-edit mr-2"></i> Edit Order
                </a>
                @if($order->canBeCancelled())
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i> Cancel Order
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Order Info & Items -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Order Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Order Items</h3>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-4 {{ !$loop->last ? 'border-b' : '' }}">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->artwork && $item->artwork->images)
                                            <img src="{{ asset('storage/' . $item->artwork->images[0]) }}" alt="{{ $item->artwork->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-gray-900">{{ $item->artwork->title ?? 'Deleted Artwork' }}</h4>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × {{ $order->currency }} {{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">{{ $order->currency }} {{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Totals -->
                        <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-gray-900 font-medium">{{ $order->currency }} {{ number_format($order->subtotal_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-gray-900 font-medium">{{ $order->currency }} {{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold pt-3 border-t">
                                <span class="text-gray-900">Total</span>
                                <span class="text-black">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Proofs -->
                @if($order->paymentProofs->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Payment Verification</h3>
                    </div>
                    <div class="p-6">
                        @foreach($order->paymentProofs as $proof)
                        <div class="bg-gray-50 rounded-lg p-6 mb-4 last:mb-0">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="md:w-1/3">
                                    <a href="{{ asset('storage/' . $proof->screenshot) }}" target="_blank" class="block group relative">
                                        <img src="{{ asset('storage/' . $proof->screenshot) }}" alt="Payment Proof" class="rounded-lg shadow-sm w-full">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-2xl"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="md:w-2/3 space-y-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Status</p>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $proof->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $proof->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $proof->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($proof->status) }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Date</p>
                                            <p class="text-sm font-medium">{{ $proof->created_at->format('M j, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Method</p>
                                        <p class="text-sm font-medium">{{ $proof->paymentMethod->name ?? 'N/A' }}</p>
                                    </div>
                                    @if($proof->transaction_reference)
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Reference</p>
                                        <p class="text-sm font-mono font-medium">{{ $proof->transaction_reference }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($proof->status === 'pending')
                                    <div class="pt-4 flex space-x-3">
                                        <form action="{{ route('admin.payments.approve', $proof->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                                                Approve Payment
                                            </button>
                                        </form>
                                        <button onclick="document.getElementById('reject-form-{{ $proof->id }}').classList.toggle('hidden')" class="flex-1 bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition font-semibold">
                                            Reject
                                        </button>
                                    </div>
                                    <form id="reject-form-{{ $proof->id }}" action="{{ route('admin.payments.reject', $proof->id) }}" method="POST" class="mt-4 hidden space-y-3 border-t pt-4">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                                            <textarea name="admin_notes" required class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="e.g., Image is unclear, amount doesn't match..."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                            Confirm Rejection
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Customer & Shipping Info -->
            <div class="space-y-8">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Customer</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($order->first_name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->email }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Phone</p>
                            <p class="text-sm">{{ $order->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Shipping Address</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm leading-relaxed text-gray-700">
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                            {{ $order->country }}
                        </p>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Method</p>
                            <p class="text-sm font-medium">{{ ucfirst($order->shipping_method ?? 'Standard') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->order_notes)
                <div class="bg-yellow-50 rounded-xl border border-yellow-100 overflow-hidden">
                    <div class="px-6 py-4 bg-yellow-100 bg-opacity-50 border-b border-yellow-100">
                        <h3 class="font-bold text-yellow-900">Order Notes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-yellow-800">{{ $order->order_notes }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Gift Info -->
                @if($order->is_gift)
                <div class="bg-pink-50 rounded-xl border border-pink-100 overflow-hidden">
                    <div class="px-6 py-4 bg-pink-100 bg-opacity-50 border-b border-pink-100">
                        <h3 class="font-bold text-pink-900"><i class="fas fa-gift mr-2"></i>Gift Order</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($order->gift_message)
                        <div>
                            <p class="text-xs text-pink-700 uppercase tracking-wider font-semibold mb-1">Message</p>
                            <p class="text-sm italic text-pink-900">"{{ $order->gift_message }}"</p>
                        </div>
                        @endif
                        <div class="flex items-center text-sm text-pink-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $order->gift_receipt ? 'Include gift receipt' : 'No gift receipt' }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
