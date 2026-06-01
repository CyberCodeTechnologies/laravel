@extends('admin.layouts.app')

@section('title', 'Create Artwork - Admin')
@section('meta-description', 'Create new artwork for an artist')

@section('header', 'Create New Artwork')

@section('admin_content')
<!-- Header Section -->
<section class="py-6 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Artwork</h1>
                <p class="text-gray-600 mt-1">Add artwork on behalf of an artist.</p>
            </div>
            <a href="{{ route('admin.artworks') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Artworks
            </a>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Artist Selection -->
                    <div>
                        <label for="artist_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Artist <span class="text-red-500">*</span>
                        </label>
                        <select id="artist_id" name="artist_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select an artist</option>
                            @foreach($artists ?? [] as $artist)
                                <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                                    {{ $artist->name }} ({{ $artist->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('artist_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Artwork Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a category</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medium & Dimensions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">
                                Medium <span class="text-red-500">*</span>
                            </label>
                            <select id="medium" name="medium" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a medium</option>
                                <option value="oil" {{ old('medium') == 'oil' ? 'selected' : '' }}>Oil</option>
                                <option value="acrylic" {{ old('medium') == 'acrylic' ? 'selected' : '' }}>Acrylic</option>
                                <option value="watercolor" {{ old('medium') == 'watercolor' ? 'selected' : '' }}>Watercolor</option>
                                <option value="digital" {{ old('medium') == 'digital' ? 'selected' : '' }}>Digital</option>
                                <option value="photography" {{ old('medium') == 'photography' ? 'selected' : '' }}>Photography</option>
                                <option value="sculpture" {{ old('medium') == 'sculpture' ? 'selected' : '' }}>Sculpture</option>
                                <option value="mixed_media" {{ old('medium') == 'mixed_media' ? 'selected' : '' }}>Mixed Media</option>
                                <option value="traditional" {{ old('medium') == 'traditional' ? 'selected' : '' }}>Traditional</option>
                            </select>
                            @error('medium')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                                Dimensions <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}" 
                                   placeholder="e.g., 24 x 36 inches" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('dimensions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price & Year -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price (USD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" 
                                   required min="0" step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                Year Created
                            </label>
                            <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" 
                                   min="1900" max="{{ date('Y') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Select "Approved" to publish immediately.</p>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Artwork Images <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Upload 1-5 images. Max 5MB each. Allowed formats: JPG, PNG, GIF, WebP</p>
                        @error('images')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.artworks') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i>Create Artwork
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
