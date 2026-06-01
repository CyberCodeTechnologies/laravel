@extends('admin.layouts.app')

@section('title', 'Edit Collection - Admin - Panchi Gallery')
@section('header', 'Edit Collection')

@section('admin_content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Collection Title <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $collection->title) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $collection->description) }}</textarea>
                </div>

                <!-- Current Image -->
                @if($collection->featured_image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                    <img src="{{ asset('storage/' . $collection->featured_image) }}" 
                         alt="{{ $collection->title }}" 
                         class="w-48 h-32 object-cover rounded-lg">
                </div>
                @endif

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Replace Featured Image</label>
                    <input type="file" 
                           id="featured_image" 
                           name="featured_image" 
                           accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Max size: 2MB. Leave blank to keep current image.</p>
                </div>

                <!-- Curator -->
                <div>
                    <label for="curator_id" class="block text-sm font-medium text-gray-700 mb-2">Curator</label>
                    <select id="curator_id" 
                            name="curator_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">No Curator</option>
                        @foreach(\App\Models\User::where('role', 'artist')->orWhere('role', 'admin')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('curator_id', $collection->curator_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured', $collection->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Featured Collection</span>
                    </label>
                </div>

                <!-- Active -->
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $collection->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>

                <!-- Artwork Count -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Artworks in this collection: <span class="font-semibold">{{ $collection->artworks()->count() }}</span></p>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('admin.collections') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
