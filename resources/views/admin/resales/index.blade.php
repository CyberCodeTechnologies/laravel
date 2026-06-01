@extends('admin.layouts.app')

@section('title', 'Resales Management - Panchi Gallery')
@section('meta-description', 'Manage artwork resales and secondary market in Panchi Gallery')

@section('header', 'Resales')

@section('admin_content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Resales</h1>
                    <p class="text-sm text-gray-500">Secondary market artwork resales</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.marketplace') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-store mr-2"></i>Marketplace
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" placeholder="Search resales..." value="{{ request('search') }}" 
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="listed" {{ request('status') == 'listed' ? 'selected' : '' }}>Listed</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Resales List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Artwork
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Owner
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Listed Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($resales as $resale)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($resale->artwork->image)
                                            <img src="{{ asset('storage/' . $resale->artwork->image) }}" alt="{{ $resale->artwork->title }}" 
                                                 class="h-10 w-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $resale->artwork->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $resale->artwork->artist->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $resale->owner->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $resale->owner->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($resale->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @switch($resale->status)
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('listed')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('sold')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('cancelled')
                                                bg-red-100 text-red-800
                                                @break
                                        @endswitch
                                    ">
                                        {{ ucfirst($resale->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $resale->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($resale->status === 'pending')
                                        <form method="POST" action="{{ route('admin.marketplace.approve', $resale) }}" class="inline mr-2">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.marketplace.reject', $resale) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Reject this resale?')">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No resales found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($resales->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    {{ $resales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
