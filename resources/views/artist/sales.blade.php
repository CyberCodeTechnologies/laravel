@extends('layouts.app')

@section('title', __('messages.artist_dashboard.sales_certificates') . ' - ' . __('messages.artist_dashboard.title') . ' - Panchi Gallery')
@section('meta-description', __('messages.artist_dashboard.sales_subtitle'))

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    {{ __('messages.artist_dashboard.sales_title', ['name' => auth()->user()->name]) }}
                </h1>
                <p class="text-gray-300">
                    {{ __('messages.artist_dashboard.sales_subtitle') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <x-button variant="secondary" onclick="window.location.href='{{ route('artist.earnings') }}'">
                    {{ __('messages.artist_dashboard.view_earnings') }}
                </x-button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ $totalSales ?? 0 }}</div>
                <div class="text-gray-300">{{ __('messages.artist_profile.total_sales') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">${{ number_format($totalRevenue ?? 0) }}</div>
                <div class="text-gray-300">{{ __('messages.artist_dashboard.total_revenue') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">${{ number_format($monthlyRevenue ?? 0) }}</div>
                <div class="text-gray-300">{{ __('messages.artist_dashboard.this_month') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">{{ auth()->user()->artworks()->where('status', 'sold')->count() }}</div>
                <div class="text-gray-300">{{ __('messages.artist_dashboard.sold_artworks') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <a href="{{ route('dashboard') }}" id="artworks-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.my_artworks') }}
                </a>
                <a href="{{ route('artist.analytics') }}" id="analytics-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.analytics') }}
                </a>
                <a href="{{ route('artist.orders') }}" id="orders-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.orders') }}
                </a>
                <button id="sales-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    {{ __('messages.artist_dashboard.sales_certificates') }}
                </button>
                <a href="{{ route('artist.profile') }}" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    {{ __('messages.artist_dashboard.artist_profile') }}
                </a>
            </nav>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Sales Table -->
        <div id="sales-content" class="tab-content">
            <div class="mb-8">
                <h2 class="font-serif text-2xl font-bold text-gray-900">{{ __('messages.artist_dashboard.sales_history') }}</h2>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @if(isset($sales) && count($sales) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.artist_dashboard.artwork') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.artist_dashboard.buyer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.artist_dashboard.date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.artist_dashboard.price') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.artist_dashboard.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sales as $sale)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $sale->artwork->image_url ?? asset('images/placeholder-artwork.jpg') }}" alt="" class="w-10 h-10 rounded object-cover">
                                            <span class="font-medium text-gray-900">{{ $sale->artwork->title ?? 'Artwork' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $sale->buyer->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">${{ number_format($sale->amount ?? $sale->price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $sale->status === 'refunded' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($sales, 'links'))
                    <div class="p-4 border-t border-gray-100">
                        {{ $sales->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No sales yet</h3>
                    <p class="text-gray-500">Your sales will appear here once collectors purchase your artworks.</p>
                </div>
            @endif
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8">
                <a href="{{ route('dashboard') }}" class="text-black hover:text-gray-700 font-medium">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function switchTab(tabName) {
    console.log('Switched to tab:', tabName);
}
</script>
@endpush
@endsection
