@extends('admin.layouts.app')

@section('title', 'Manage Wishlists - Admin')
@section('meta-description', 'Manage user wishlists on Panchi Gallery platform')

@section('header', 'Manage Wishlists')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Wishlists -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-heart text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Wishlists</p>
                        <p class="text-2xl font-bold text-blue-900">{{ App\Models\Wishlist::count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">
                            @php
                                $uniqueUsers = App\Models\Wishlist::distinct('user_id')->count('user_id');
                            @endphp
                            {{ $uniqueUsers }} users
                        </p>
                    </div>
                </div>
            </div>

            <!-- Most Wishlisted Artworks -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Top Artwork</p>
                        @php
                            $topArtwork = App\Models\Wishlist::select('artwork_id')
                                ->selectRaw('COUNT(*) as count')
                                ->groupBy('artwork_id')
                                ->orderByDesc('count')
                                ->first();
                        @endphp
                        @if($topArtwork)
                            <p class="text-2xl font-bold text-purple-900">{{ $topArtwork->count }} saves</p>
                            <p class="text-xs text-purple-700 mt-1">Most wishlisted</p>
                        @else
                            <p class="text-2xl font-bold text-purple-900">0</p>
                            <p class="text-xs text-purple-700 mt-1">No data</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">This Week</p>
                        <p class="text-2xl font-bold text-green-900">
                            @php
                                $thisWeek = App\Models\Wishlist::where('created_at', '>=', now()->startOfWeek())->count();
                            @endphp
                            {{ $thisWeek }}
                        </p>
                        <p class="text-xs text-green-700 mt-1">New wishlists</p>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-calendar text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">This Month</p>
                        <p class="text-2xl font-bold text-yellow-900">
                            @php
                                $thisMonth = App\Models\Wishlist::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                            @endphp
                            {{ $thisMonth }}
                        </p>
                        <p class="text-xs text-yellow-700 mt-1">New wishlists</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by user or artwork..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select name="date_range" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.wishlists.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-times mr-2"></i>Clear
            </a>
        </form>
    </div>
</section>

<!-- Wishlists Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artwork</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added On</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($wishlists as $wishlist)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center text-blue-700 font-bold overflow-hidden">
                                            @if($wishlist->user->avatar)
                                                <img src="{{ asset('storage/' . $wishlist->user->avatar) }}" class="h-full w-full object-cover">
                                            @else
                                                {{ substr($wishlist->user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $wishlist->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $wishlist->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($wishlist->artwork && $wishlist->artwork->images && is_array($wishlist->artwork->images) && count($wishlist->artwork->images) > 0)
                                            <img src="{{ asset('storage/' . $wishlist->artwork->images[0]) }}" class="h-12 w-12 rounded object-cover mr-3" alt="{{ $wishlist->artwork->title }}">
                                        @else
                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $wishlist->artwork?->title ?? 'Deleted Artwork' }}</div>
                                            <div class="text-sm text-gray-500">{{ $wishlist->artwork?->category?->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $wishlist->artwork?->artist?->name ?? 'Unknown' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($wishlist->artwork)
                                            ${{ number_format($wishlist->artwork->price, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $wishlist->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if($wishlist->artwork)
                                            <a href="{{ route('public.artworks.show', $wishlist->artwork->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="View Artwork">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.users.show', $wishlist->user->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View User">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.wishlists.delete', $wishlist->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Remove" onclick="return confirm('Remove this wishlist item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-heart text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No wishlists found</p>
                                        <p class="text-sm">Wishlists are created when users save artworks.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($wishlists->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $wishlists->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
