@extends('layouts.app')

@section('title', 'My Artworks - Panchi Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900">My Artworks</h1>
                <p class="text-gray-600 mt-2">Manage your portfolio</p>
            </div>
            <a href="{{ route('artist.artworks.create') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> Add New
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($artworks) && count($artworks) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($artworks as $artwork)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="relative">
                            <img src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" alt="{{ $artwork->title }}" class="w-full h-56 object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $artwork->status === 'approved' ? 'green' : ($artwork->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $artwork->status === 'approved' ? 'green' : ($artwork->status === 'pending' ? 'yellow' : 'gray') }}-800">
                                    {{ ucfirst($artwork->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $artwork->title }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $artwork->category->name ?? 'Uncategorized' }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">${{ number_format($artwork->price, 2) }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('artist.artworks.edit', $artwork) }}" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('artist.artworks.delete', $artwork) }}" method="POST" class="inline" onsubmit="return confirm('Delete this artwork?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-palette text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No artworks yet</h3>
                <p class="text-gray-500 mb-6">Start building your portfolio</p>
                <a href="{{ route('artist.artworks.create') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    Add New Artwork
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
