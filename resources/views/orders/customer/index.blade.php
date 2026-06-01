@extends('layouts.app')

@section('title', 'My Custom Orders - Customer Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Custom Orders</h1>
                    <p class="text-gray-600 mt-1">Track your custom artwork commissions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('custom-orders.create') }}" 
                       class="px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        New Order Request
                    </a>
                    <div class="text-sm text-gray-500">
                        Total Orders: {{ $orders->total() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('orders.customer.index') }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('status') ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    All Orders
                </a>
                <a href="{{ route('orders.customer.index', ['status' => 'pending_artist_approval']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ request('status') == 'pending_artist_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Pending Approval
                </a>
                <a href="{{ route('orders.customer.index', ['status' => 'artist_accepted']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ request('status') == 'artist_accepted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Artist Accepted
                </a>
                <a href="{{ route('orders.customer.index', ['status' => 'in_progress']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ request('status') == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    In Progress
                </a>
                <a href="{{ route('orders.customer.index', ['status' => 'ready_for_review']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ request('status') == 'ready_for_review' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Ready for Review
                </a>
                <a href="{{ route('orders.customer.index', ['status' => 'delivered']) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg {{ request('status') == 'delivered' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Delivered
                </a>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="text-sm font-medium text-gray-500">
                                            Order #{{ $order->formatted_order_number }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $order->custom_status_color }}-100 text-{{ $order->custom_status_color }}-800">
                                            {{ $order->custom_status_label }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $order->created_at->format('M j, Y') }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $order->title }}
                                    </h3>
                                    <p class="text-gray-600 line-clamp-2">
                                        {{ $order->description }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">
                                        ${{ number_format($order->proposed_price, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Budget
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <div class="text-sm text-gray-500">Artist</div>
                                    <div class="font-medium text-gray-900">{{ $order->artist->name }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Size</div>
                                    <div class="font-medium text-gray-900">{{ $order->size }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Medium</div>
                                    <div class="font-medium text-gray-900">{{ $order->medium }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Style</div>
                                    <div class="font-medium text-gray-900">{{ $order->style }}</div>
                                </div>
                            </div>

                            <!-- Progress Timeline -->
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-2">Progress</div>
                                <div class="flex items-center space-x-2">
                                    @php
                                        $statuses = [
                                            'pending_artist_approval' => ['icon' => 'clock', 'label' => 'Pending'],
                                            'artist_accepted' => ['icon' => 'check-circle', 'label' => 'Accepted'],
                                            'in_progress' => ['icon' => 'play-circle', 'label' => 'In Progress'],
                                            'ready_for_review' => ['icon' => 'eye', 'label' => 'Review'],
                                            'customer_approved' => ['icon' => 'thumbs-up', 'label' => 'Approved'],
                                            'shipped' => ['icon' => 'truck', 'label' => 'Shipped'],
                                            'delivered' => ['icon' => 'flag', 'label' => 'Delivered']
                                        ];
                                        
                                        $currentStatusIndex = array_search($order->custom_status, array_keys($statuses));
                                    @endphp
                                    
                                    @foreach($statuses as $status => $data)
                                        @php
                                            $statusIndex = array_search($status, array_keys($statuses));
                                            $isCompleted = $statusIndex < $currentStatusIndex;
                                            $isCurrent = $statusIndex == $currentStatusIndex;
                                        @endphp
                                        
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ 
                                                $isCompleted ? 'bg-green-500 text-white' : 
                                                ($isCurrent ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') 
                                            }}">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    @if($data['icon'] == 'clock')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    @elseif($data['icon'] == 'check-circle')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    @elseif($data['icon'] == 'play-circle')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                    @elseif($data['icon'] == 'eye')
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    @elseif($data['icon'] == 'thumbs-up')
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                    @elseif($data['icon'] == 'truck')
                                                        <path d="M8 16a2 2 0 100-4 2 2 0 000 4zM12 16a2 2 0 100-4 2 2 0 000 4zM5.051 7.5a1.5 1.5 0 10-2.9-.5L1 7.5v6l1.051 3.5a1.5 1.5 0 102.9.5L4 13.5v-6l1.051-3.5z"/>
                                                        <path fill-rule="evenodd" d="M1 7.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM5.051 7.5a1.5 1.5 0 10-2.9-.5L1 7.5v6l1.051 3.5a1.5 1.5 0 102.9.5L4 13.5v-6l1.051-3.5z" clip-rule="evenodd"/>
                                                    @elseif($data['icon'] == 'flag')
                                                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                                    @endif
                                                </svg>
                                            </div>
                                            @if(!$loop->last)
                                                <div class="w-8 h-1 {{ $isCompleted ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Artist Notes -->
                            @if($order->artist_notes)
                                <div class="mb-4">
                                    <div class="text-sm text-gray-500 mb-1">Artist Notes</div>
                                    <div class="text-sm text-gray-700 bg-blue-50 p-3 rounded-lg">
                                        {{ $order->artist_notes }}
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        View Details
                                    </a>
                                    <a href="{{ route('orders.track', $order) }}" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        Track Progress
                                    </a>
                                </div>

                                <!-- Status-specific Actions -->
                                <div class="flex space-x-3">
                                    @if($order->canCustomerApprove())
                                        <form action="{{ route('orders.customer-approve', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                                Approve Artwork
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $order->id }})" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                            Request Changes
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No custom orders</h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ request('status') ? 'No orders found with this status.' : 'You haven\'t placed any custom orders yet.' }}
                </p>
                <div class="mt-6">
                    <a href="{{ route('custom-orders.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        Create Your First Order
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Artwork Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Changes</h3>
            <form id="rejectForm" action="" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="reject_order_id">
                <div class="mb-4">
                    <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        What changes would you like? *
                    </label>
                    <textarea name="customer_notes" id="customer_notes" rows="4" required
                              placeholder="Please describe the changes you'd like the artist to make..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        Request Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(orderId) {
    document.getElementById('reject_order_id').value = orderId;
    document.getElementById('rejectForm').action = `/orders/${orderId}/customer-reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target == modal) {
        closeRejectModal();
    }
}
</script>
@endsection
