@extends('admin.layouts.app')

@section('title', 'Collections Management - Panchi Gallery')
@section('meta-description', 'Manage curated collections in Panchi Gallery')

@section('header', 'Collections')

@section('admin_content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Collections</h1>
                    <p class="text-sm text-gray-500">Curated artwork collections</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.collections.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Create Collection
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($collections as $collection)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if ($collection->featured_image)
                        <img src="{{ asset('storage/' . $collection->featured_image) }}" alt="{{ $collection->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-images text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $collection->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($collection->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-image mr-1"></i>{{ $collection->artworks->count() }} artworks</span>
                            <span><i class="fas fa-user mr-1"></i>{{ $collection->curator->name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $collection->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $collection->is_featured ? 'Featured' : 'Standard' }}
                            </span>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.collections.edit', $collection) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.collections.delete', $collection) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this collection?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No collections found</h3>
                    <p class="text-gray-500">Get started by creating your first collection.</p>
                    <a href="{{ route('admin.collections.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Create Collection
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if ($collections->hasPages())
            <div class="mt-8">
                {{ $collections->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
