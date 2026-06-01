@extends('layouts.app')

@section('title', 'Order Tracking - ' . $order->formatted_order_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order Tracking</h1>
                    <p class="text-gray-600 mt-1">Order #{{ $order->formatted_order_number }}</p>
                </div>
                <a href="{{ route('orders.show', $order) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Back to Order Details
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Timeline -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-8">Order Progress</h2>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                
                @php
                    $timeline = [
                        'pending_artist_approval' => [
                            'title' => 'Order Submitted',
                            'description' => 'Your order has been submitted and is waiting for artist approval.',
                            'icon' => 'document-text',
                            'time' => $order->created_at
                        ],
                        'artist_accepted' => [
                            'title' => 'Artist Accepted',
                            'description' => 'The artist has accepted your order and will begin work soon.',
                            'icon' => 'check-circle',
                            'time' => $order->artist_accepted_at
                        ],
                        'in_progress' => [
                            'title' => 'Artwork in Progress',
                            'description' => 'The artist is currently working on your custom artwork.',
                            'icon' => 'paint-brush',
                            'time' => null
                        ],
                        'ready_for_review' => [
                            'title' => 'Ready for Review',
                            'description' => 'The artwork is complete and ready for your review.',
                            'icon' => 'eye',
                            'time' => $order->completed_at
                        ],
                        'customer_approved' => [
                            'title' => 'Customer Approved',
                            'description' => 'You have approved the artwork. Preparing for shipment.',
                            'icon' => 'thumbs-up',
                            'time' => null
                        ],
                        'shipped' => [
                            'title' => 'Artwork Shipped',
                            'description' => 'Your artwork has been shipped and is on its way.',
                            'icon' => 'truck',
                            'time' => $order->shipped_at
                        ],
                        'delivered' => [
                            'title' => 'Delivered',
                            'description' => 'Your custom artwork has been delivered successfully.',
                            'icon' => 'flag',
                            'time' => $order->delivered_at
                        ]
                    ];
                    
                    $currentStatusIndex = array_search($order->custom_status, array_keys($timeline));
                @endphp
                
                @foreach($timeline as $status => $data)
                    @php
                        $statusIndex = array_search($status, array_keys($timeline));
                        $isCompleted = $statusIndex < $currentStatusIndex;
                        $isCurrent = $statusIndex == $currentStatusIndex;
                        $isPending = $statusIndex > $currentStatusIndex;
                    @endphp
                    
                    <div class="relative flex items-start mb-8 last:mb-0">
                        <!-- Timeline Dot -->
                        <div class="flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center z-10
                            {{ $isCompleted ? 'bg-green-500 text-white' : 
                               ($isCurrent ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($data['icon'] == 'document-text')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                @elseif($data['icon'] == 'check-circle')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @elseif($data['icon'] == 'paint-brush')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                @elseif($data['icon'] == 'eye')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                @elseif($data['icon'] == 'thumbs-up')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                @elseif($data['icon'] == 'truck')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                @elseif($data['icon'] == 'flag')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m-2-4v4m9-14l2 2m0 0l2-2m-2 2l-2-2m2 2v2.5A2.5 2.5 0 0114.5 12h-1a2.5 2.5 0 00-2.5 2.5V16a2.5 2.5 0 002.5 2.5h1a2.5 2.5 0 002.5-2.5V9.5z"/>
                                @endif
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="ml-6 flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $data['title'] }}</h3>
                                @if($data['time'])
                                    <span class="text-sm text-gray-500">
                                        {{ $data['time']->format('M j, Y g:i A') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 {{ $isPending ? 'text-gray-400' : '' }}">
                                {{ $data['description'] }}
                            </p>
                            
                            @if($isCurrent && $order->custom_status === 'pending_artist_approval')
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Status:</strong> Waiting for artist to review and accept your order.
                                    </p>
                                </div>
                            @endif
                            
                            @if($isCurrent && $order->custom_status === 'in_progress')
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>Status:</strong> The artist is currently working on your artwork.
                                    </p>
                                </div>
                            @endif
                            
                            @if($isCurrent && $order->custom_status === 'ready_for_review')
                                <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                    <p class="text-sm text-purple-800">
                                        <strong>Status:</strong> The artwork is ready for your review!
                                    </p>
                                    <div class="mt-2">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                            Review Artwork
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">Artwork Details</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Title</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->title }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Artist</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->artist->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Size</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->size }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Medium</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->medium }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Style</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->style }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Budget</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->proposed_price, 2) }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">Contact Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Customer</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->user->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Order Date</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Current Status</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $order->custom_status_color }}-100 text-{{ $order->custom_status_color }}-800">
                                    {{ $order->custom_status_label }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            @if($order->customer_notes || $order->artist_notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-medium text-gray-900 mb-4">Notes & Communication</h3>
                    
                    @if($order->customer_notes)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Customer Notes</h4>
                            <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                {{ $order->customer_notes }}
                            </div>
                        </div>
                    @endif
                    
                    @if($order->artist_notes)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Artist Notes</h4>
                            <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                                {{ $order->artist_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
