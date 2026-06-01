@extends('layouts.app')

@section('title', __('messages.artist_dashboard.title') . ' - Panchi Gallery')
@section('meta-description', __('messages.artist_dashboard.meta_description'))

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    {{ __('messages.artist_dashboard.studio_title', ['name' => auth()->user()->name]) }}
                </h1>
                <p class="text-gray-300">
                    {{ __('messages.artist_dashboard.studio_subtitle') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <x-button variant="secondary" href="{{ route('artist.artworks.create') }}">
                    {{ __('messages.artist_dashboard.upload_new_artwork') }}
                </x-button>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $stats['total_artworks'] ?? 0 }}</div>
                <div class="text-gray-300">{{ __('messages.artist_dashboard.total_artworks') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->followers()->count() ?? 0 }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.followers') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">${{ number_format($stats['total_sales'] ?? 0) }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.total_sales') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->sales()->where('status', 'pending')->count() ?? 0 }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.pending_orders') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <button id="artworks-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    {{ __('messages.artist_dashboard.my_artworks') }}
                </button>
                <a href="{{ route('artist.analytics') }}" id="analytics-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.analytics') }}
                </a>
                <a href="{{ route('artist.orders') }}" id="orders-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.orders') }}
                </a>
                <a href="{{ route('artist.sales') }}" id="certificates-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.sales_certificates') }}
                </a>
                <a href="{{ route('artist.profile') }}" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.artist_profile') }}
                </a>
            </nav>
        </div>
        
        <!-- My Artworks Tab -->
        <div id="artworks-content" class="tab-content">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">{{ __('messages.artist_dashboard.my_artworks_title') }}</h2>
                <div class="flex space-x-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        <option>{{ __('messages.artist_dashboard.all_status') }}</option>
                        <option>{{ __('messages.artist_dashboard.published') }}</option>
                        <option>{{ __('messages.artist_dashboard.pending_review') }}</option>
                        <option>{{ __('messages.artist_dashboard.sold') }}</option>
                        <option>{{ __('messages.artist_dashboard.draft') }}</option>
                    </select>
                    <x-button variant="outline" href="{{ route('artist.artworks.create') }}">
                        {{ __('messages.artist_dashboard.add_new') }}
                    </x-button>
                </div>
            </div>
            
            
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.artwork') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.price') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.views') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.sales') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.created', ['default' => 'Created']) }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.artist_dashboard.actions', ['default' => 'Actions']) }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($artworks->count() > 0)
                                @foreach($artworks as $artwork)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img src="{{ $artwork->primary_image }}" 
                                                     alt="{{ $artwork->title }}" 
                                                     class="w-12 h-12 rounded-lg object-cover mr-3">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $artwork->title }}</div>
                                                <div class="text-sm text-gray-500">ID: #{{ str_pad($artwork->id, 6, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($artwork->price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($artwork->status)
                                            @case('approved')
                                                <x-badge variant="verified" size="sm">{{ __('messages.status.published', ['default' => 'Published']) }}</x-badge>
                                            @break
                                            @case('pending')
                                                <x-badge variant="default" size="sm">{{ __('messages.status.pending_review', ['default' => 'Pending Review']) }}</x-badge>
                                            @break
                                            @case('sold')
                                                <x-badge variant="luxury" size="sm">{{ __('messages.status.sold', ['default' => 'Sold']) }}</x-badge>
                                            @break
                                            @default
                                                <x-badge variant="default" size="sm">{{ ucfirst($artwork->status) }}</x-badge>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $artwork->views_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $artwork->transactions()->count() ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $artwork->created_at ? $artwork->created_at->format('M j, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('artist.artworks.edit', $artwork) }}" class="text-black hover:text-gray-700">{{ __('messages.artist_dashboard.edit', ['default' => 'Edit']) }}</a>
                                            <form action="{{ route('artist.artworks.delete', $artwork) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700">{{ __('messages.artist_dashboard.delete', ['default' => 'Delete']) }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        {{ __('messages.artist_dashboard.no_artworks_yet', ['default' => 'No artworks yet. Upload your first artwork to get started.']) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Analytics Tab -->
        <div id="analytics-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">Sales Analytics</h2>
                <a href="{{ route('artist.analytics') }}" class="text-black hover:text-gray-700 font-medium">
                    View Full Analytics &rarr;
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Total Revenue</h4>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_sales'] ?? 0) }}</div>
                    <div class="text-sm text-green-600">{{ __('messages.total_earnings') ?? 'Total Earnings' }}</div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Total Views</h4>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_views'] ?? 0) }}</div>
                    <div class="text-sm text-blue-600">{{ __('messages.artwork_views') ?? 'Artwork Views' }}</div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Total Likes</h4>
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_likes'] ?? 0) }}</div>
                    <div class="text-sm text-purple-600">{{ __('messages.total_likes') ?? 'Total Likes' }}</div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Sold Artworks</h4>
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['sold_artworks'] ?? 0 }}</div>
                    <div class="text-sm text-orange-600">{{ __('messages.sold_artworks') ?? 'Sold Artworks' }}</div>
                </div>
            </div>

            <!-- Top Performing Artworks -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900">Top Performing Artworks</h3>
                    <a href="{{ route('artist.analytics') }}" class="text-sm text-black hover:text-gray-700">View All</a>
                </div>
                <div class="space-y-4">
                    @if(isset($artworks) && $artworks->count() > 0)
                        @foreach($artworks->sortByDesc('views_count')->take(3) as $artwork)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <img src="{{ $artwork->primary_image }}"
                                         alt="{{ $artwork->title }}"
                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $artwork->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $artwork->views_count ?? 0 }} views</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">${{ number_format($artwork->price) }}</div>
                                    <div class="text-sm text-gray-500">{{ $artwork->transactions()->count() }} sales</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">{{ __('messages.no_artworks_yet') ?? 'No artworks uploaded yet' }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Orders Tab -->
        <div id="orders-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">Recent Orders</h2>
                <a href="{{ route('artist.orders') }}" class="text-black hover:text-gray-700 font-medium">
                    View All Orders &rarr;
                </a>
            </div>

            <div class="space-y-6">
                @php
                    $recentOrders = auth()->user()->sales()->latest()->take(3)->get();
                @endphp

                @if($recentOrders->count() > 0)
                    @foreach($recentOrders as $order)
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Order #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M j, Y') }}</p>
                                </div>
                                @switch($order->status)
                                    @case('pending')
                                        <x-badge variant="new" size="sm">Pending</x-badge>
                                    @break
                                    @case('processing')
                                    @case('completed')
                                        <x-badge variant="verified" size="sm">Completed</x-badge>
                                    @break
                                    @default
                                        <x-badge variant="default" size="sm">{{ ucfirst($order->status) }}</x-badge>
                                @endswitch
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Artwork</p>
                                    <p class="font-medium text-gray-900">{{ $order->artwork->title ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Buyer</p>
                                    <p class="font-medium text-gray-900">{{ $order->buyer->name ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Amount</p>
                                    <p class="font-medium text-gray-900">${{ number_format($order->amount ?? 0) }}</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-sm font-medium text-gray-900">No orders yet</h3>
                        <p class="text-sm text-gray-500 mt-1">Your orders will appear here once collectors purchase your artworks.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Certificates Tab -->
        <div id="certificates-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">Certificates & Sales</h2>
                <a href="{{ route('artist.sales') }}" class="text-black hover:text-gray-700 font-medium">
                    View All Sales & Certificates &rarr;
                </a>
            </div>

            <!-- Recent Sales -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Recent Sales</h3>
                @if(isset($recentSales) && $recentSales->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentSales->take(3) as $sale)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <img src="{{ $sale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}"
                                         alt="{{ $sale->artwork->title ?? 'Artwork' }}"
                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $sale->artwork->title ?? 'Artwork' }}</div>
                                        <div class="text-sm text-gray-500">{{ $sale->created_at->format('M j, Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">${{ number_format($sale->amount ?? 0, 2) }}</div>
                                    <x-badge variant="verified" size="sm">Completed</x-badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No sales yet. Your sales and certificates will appear here.</p>
                @endif
            </div>

            <!-- Certificate Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Certificate Information</h3>
                <p class="text-gray-600 mb-4">
                    Certificates of authenticity are automatically generated when your artworks are sold.
                    Each certificate includes a unique verification code that collectors can use to verify ownership.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('artist.sales') }}" class="text-black hover:text-gray-700 font-medium">
                        View Sales History &rarr;
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Artist Profile Tab -->
        <div id="profile-content" class="tab-content hidden">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="font-serif text-2xl font-bold text-gray-900">Artist Profile</h2>
                <a href="{{ route('artist.profile') }}" class="text-black hover:text-gray-700 font-medium">
                    Edit Full Profile &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Overview -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Profile Overview</h3>
                    <div class="flex items-center space-x-4 mb-6">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ auth()->user()->name }}</h4>
                            <p class="text-sm text-gray-500">{{ auth()->user()->specialization ?? 'Artist' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Location:</span>
                            <p class="text-gray-900">{{ auth()->user()->location ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Member Since:</span>
                            <p class="text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_artworks'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Artworks</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ auth()->user()->followers()->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Followers</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['sold_artworks'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Sold</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_sales'] ?? 0) }}</div>
                            <div class="text-sm text-gray-500">Revenue</div>
                        </div>
                    </div>
                </div>

                <!-- Bio Preview -->
                <div class="md:col-span-2 bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Bio</h3>
                    <p class="text-gray-600">
                        {{ auth()->user()->bio ?? 'No bio added yet. Click "Edit Full Profile" to add your artist bio.' }}
                    </p>
                </div>

                <!-- Profile Actions -->
                <div class="md:col-span-2 bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Profile Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('artist.profile') }}" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                            Edit Profile
                        </a>
                        <a href="{{ route('public.artists.show', auth()->user()->slug ?? auth()->user()->id) }}" target="_blank" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            View Public Profile
                        </a>
                        <a href="{{ route('artist.earnings') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            View Earnings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upload Artwork Modal -->
<x-modal id="upload-modal" size="lg" title="Upload New Artwork">
    <form action="{{ route('artist.artworks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="artwork-title" class="block text-sm font-medium text-gray-700 mb-2">Artwork Title <span class="text-red-500">*</span></label>
            <input type="text" id="artwork-title" name="title" value="{{ old('title') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                   placeholder="Enter artwork title">
        </div>

        <div>
            <label for="artwork-category" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
            <select id="artwork-category" name="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">Select a category</option>
                @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="artwork-description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
            <textarea id="artwork-description" name="description" rows="3" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                      placeholder="Describe your artwork...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="artwork-medium" class="block text-sm font-medium text-gray-700 mb-2">Medium <span class="text-red-500">*</span></label>
                <select id="artwork-medium" name="medium" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="">Select medium</option>
                    <option value="oil" {{ old('medium') == 'oil' ? 'selected' : '' }}>Oil</option>
                    <option value="acrylic" {{ old('medium') == 'acrylic' ? 'selected' : '' }}>Acrylic</option>
                    <option value="watercolor" {{ old('medium') == 'watercolor' ? 'selected' : '' }}>Watercolor</option>
                    <option value="digital" {{ old('medium') == 'digital' ? 'selected' : '' }}>Digital</option>
                    <option value="photography" {{ old('medium') == 'photography' ? 'selected' : '' }}>Photography</option>
                    <option value="sculpture" {{ old('medium') == 'sculpture' ? 'selected' : '' }}>Sculpture</option>
                    <option value="mixed_media" {{ old('medium') == 'mixed_media' ? 'selected' : '' }}>Mixed Media</option>
                    <option value="traditional" {{ old('medium') == 'traditional' ? 'selected' : '' }}>Traditional</option>
                </select>
            </div>
            <div>
                <label for="artwork-dimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions <span class="text-red-500">*</span></label>
                <input type="text" id="artwork-dimensions" name="dimensions" value="{{ old('dimensions') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                       placeholder="e.g., 24 x 36 inches">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="artwork-price" class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>
                <input type="number" id="artwork-price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                       placeholder="0.00">
            </div>
            <div>
                <label for="artwork-year" class="block text-sm font-medium text-gray-700 mb-2">Year Created</label>
                <input type="number" id="artwork-year" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                       placeholder="{{ date('Y') }}">
            </div>
        </div>

        <div>
            <label for="artwork-images" class="block text-sm font-medium text-gray-700 mb-2">Images <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                <p class="text-xs text-gray-500 mb-4">PNG, JPG, GIF up to 5MB each (max 5 images)</p>
                <input type="file" id="artwork-images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="hidden" onchange="previewArtworkImages(this)">
                <button type="button" onclick="document.getElementById('artwork-images').click()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Choose Files
                </button>
            </div>
            <div id="artwork-images-preview" class="mt-4 grid grid-cols-5 gap-2 hidden">
            </div>
            <p class="text-xs text-gray-500 mt-1">At least 1 image required. First image will be the primary image.</p>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
            <x-button variant="outline" type="button" onclick="closeModal('upload-modal')">
                Cancel
            </x-button>
            <x-button variant="primary" type="submit">
                Upload Artwork
            </x-button>
        </div>
    </form>
</x-modal>

<!-- Avatar Upload Modal -->
<x-modal id="avatar-modal" size="md" title="Update Profile Picture">
    <form action="{{ route('profile.update-avatar') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select New Photo</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar-input" onchange="previewAvatar(this)">
                <button type="button" onclick="document.getElementById('avatar-input').click()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Choose File
                </button>
            </div>
            <div id="avatar-preview" class="mt-4 hidden">
                <img id="avatar-preview-img" src="" alt="Preview" class="w-32 h-32 rounded-full object-cover mx-auto">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <x-button variant="outline" type="button" onclick="closeModal('avatar-modal')">
                Cancel
            </x-button>
            <x-button variant="primary" type="submit">
                Update Photo
            </x-button>
        </div>
    </form>
</x-modal>

<!-- Cover Image Upload Modal -->
<x-modal id="cover-modal" size="md" title="Update Cover Image">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select New Cover Image</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Recommended 1200x400 pixels.</p>
                <input type="file" name="cover_image" accept="image/*" class="hidden" id="cover-input" onchange="previewCover(this)">
                <button type="button" onclick="document.getElementById('cover-input').click()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Choose File
                </button>
            </div>
            <div id="cover-preview" class="mt-4 hidden">
                <img id="cover-preview-img" src="" alt="Preview" class="w-full h-32 rounded-lg object-cover">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <x-button variant="outline" type="button" onclick="closeModal('cover-modal')">
                Cancel
            </x-button>
            <x-button variant="primary" type="submit">
                Update Cover
            </x-button>
        </div>
    </form>
</x-modal>

@endsection

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('nav button').forEach(tab => {
        tab.classList.remove('border-black', 'text-black');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Activate selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-black', 'text-black');
}

function openUploadModal() {
    openModal('upload-modal');
}

function generateCertificate() {
    // Generate certificate logic
    console.log('Generate new certificate');
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview-img').src = e.target.result;
            document.getElementById('avatar-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview-img').src = e.target.result;
            document.getElementById('cover-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewArtworkImages(input) {
    const previewContainer = document.getElementById('artwork-images-preview');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
        previewContainer.classList.remove('hidden');

        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover rounded-lg">
                    <span class="absolute top-1 left-1 bg-black text-white text-xs px-2 py-1 rounded">${index + 1}</span>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        previewContainer.classList.add('hidden');
    }
}
</script>
@endpush
