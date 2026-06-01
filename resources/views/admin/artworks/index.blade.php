@extends('admin.layouts.app')

@section('title', 'Manage Artworks - Admin')
@section('meta-description', 'Manage and approve artworks on Panchi Gallery platform')

@section('header', 'Manage Artworks')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Artworks -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-image text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Artworks</p>
                        <p class="text-2xl font-bold text-blue-900">{{ App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">
                            @php
                                $newArtworks = App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('created_at', '>=', now()->subDays(7))->count();
                            @endphp
                            +{{ $newArtworks }} this week
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approved Artworks -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Approved</p>
                        <p class="text-2xl font-bold text-green-900">{{ App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'approved')->count() }}</p>
                        <p class="text-xs text-green-700 mt-1">Live on platform</p>
                    </div>
                </div>
            </div>

            <!-- Pending Artworks -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'pending')->count() }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting approval</p>
                    </div>
                </div>
            </div>

            <!-- Sold Artworks -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Sold</p>
                        <p class="text-2xl font-bold text-purple-900">{{ App\Models\Artwork::withoutGlobalScope(App\Models\Scopes\ApprovedScope::class)->where('status', 'sold')->count() }}</p>
                        <p class="text-xs text-purple-700 mt-1">Successfully sold</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search artworks..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
                <div>
                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.artworks') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <div class="flex gap-3">
                <a href="{{ route('admin.artworks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add Artwork
                </a>
                <a href="{{ route('admin.artworks.pending') }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-clock mr-2"></i>Pending Artworks
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Artworks Grid -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($artworks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($artworks as $artwork)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Artwork Image -->
                        <div class="relative h-48 bg-gray-200">
                            <img src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                                 alt="{{ $artwork->title }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $artwork->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $artwork->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $artwork->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $artwork->status === 'sold' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ ucfirst($artwork->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Artwork Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">by {{ $artwork->artist?->name ?? 'Unknown Artist' }}</p>
                            <p class="text-sm text-gray-500 mb-3">{{ $artwork->category?->name ?? 'Uncategorized' }}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-lg font-bold text-gray-900">${{ number_format($artwork->price, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ $artwork->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('public.artworks.show', $artwork) }}" 
                                   class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition text-center">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('admin.artworks.edit', $artwork->id) }}" 
                                   class="flex-1 bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if($artwork->status === 'pending')
                                    <form method="POST" action="{{ route('admin.artworks.approve', $artwork->id) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-image text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No artworks found</h3>
                <p class="text-gray-500">No artworks match your current filters.</p>
            </div>
        @endif
        
        <!-- Pagination -->
        @if($artworks->hasPages())
            <div class="mt-8">
                {{ $artworks->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
