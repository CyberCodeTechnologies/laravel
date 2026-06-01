@extends('admin.layouts.app')

@section('title', 'Create Exhibition')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Create Exhibition</h1>
    <p class="text-gray-600">Add a new exhibition</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.exhibitions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_featured" id="is_featured" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured Exhibition</label>
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_published" id="is_published" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_published" class="ml-2 text-sm text-gray-700">Published</label>
                </div>
            </div>
        </div>
        
        <!-- Location -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Location</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                    <input type="text" name="venue" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('venue')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Images -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Images</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Recommended size: 1920x1080px. Max size: 10MB.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">You can upload up to 10 images. Max size: 10MB each.</p>
                </div>
            </div>
        </div>
        
        <!-- Artworks -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Artworks</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($artworks as $artwork)
                <div class="relative">
                    <input type="checkbox" name="artworks[]" value="{{ $artwork->id }}" id="artwork_{{ $artwork->id }}" class="sr-only peer">
                    <label for="artwork_{{ $artwork->id }}" class="cursor-pointer">
                        <img src="{{ $artwork->primary_image }}" alt="{{ $artwork->title }}" class="w-full aspect-square object-cover rounded-lg border-2 border-transparent peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-500 transition-all">
                    </label>
                    <p class="mt-1 text-xs text-gray-600 truncate">{{ $artwork->title }}</p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Artists -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Artists</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($artists as $artist)
                <div class="relative">
                    <input type="checkbox" name="artists[]" value="{{ $artist->id }}" id="artist_{{ $artist->id }}" class="sr-only peer">
                    <label for="artist_{{ $artist->id }}" class="cursor-pointer">
                        <img src="{{ $artist->avatar_url }}" alt="{{ $artist->name }}" class="w-full aspect-square object-cover rounded-full border-2 border-transparent peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-500 transition-all">
                    </label>
                    <p class="mt-1 text-xs text-gray-600 text-center truncate">{{ $artist->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- SEO -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">SEO</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('meta_keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.exhibitions.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                Create Exhibition
            </button>
        </div>
    </form>
</div>
@endsection
