 @extends('admin.layouts.app')

@section('title', 'Edit Artwork - Admin - Panchi Gallery')
@section('header', 'Edit Artwork')

@section('admin_content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.artworks.update', ['id' => $artwork->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Artist Selection -->
                <div>
                    <label for="artist_id" class="block text-sm font-medium text-gray-700 mb-2">Artist <span class="text-red-500">*</span></label>
                    <select id="artist_id" 
                            name="artist_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Artist</option>
                        @foreach($artists ?? [] as $artist)
                        <option value="{{ $artist->id }}" {{ old('artist_id', $artwork->artist_id) == $artist->id ? 'selected' : '' }}>
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
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $artwork->title) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $artwork->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select id="category_id" 
                            name="category_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $artwork->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               step="0.01" 
                               min="0"
                               required
                               value="{{ old('price', $artwork->price) }}"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dimensions -->
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions</label>
                    <input type="text" 
                           id="dimensions" 
                           name="dimensions" 
                           value="{{ old('dimensions', $artwork->dimensions) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., 24 x 36 inches">
                    @error('dimensions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Medium -->
                <div>
                    <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">Medium</label>
                    <select id="medium" name="medium" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a medium</option>
                        <option value="oil" {{ old('medium', $artwork->medium) == 'oil' ? 'selected' : '' }}>Oil</option>
                        <option value="acrylic" {{ old('medium', $artwork->medium) == 'acrylic' ? 'selected' : '' }}>Acrylic</option>
                        <option value="watercolor" {{ old('medium', $artwork->medium) == 'watercolor' ? 'selected' : '' }}>Watercolor</option>
                        <option value="digital" {{ old('medium', $artwork->medium) == 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="photography" {{ old('medium', $artwork->medium) == 'photography' ? 'selected' : '' }}>Photography</option>
                        <option value="sculpture" {{ old('medium', $artwork->medium) == 'sculpture' ? 'selected' : '' }}>Sculpture</option>
                        <option value="mixed_media" {{ old('medium', $artwork->medium) == 'mixed_media' ? 'selected' : '' }}>Mixed Media</option>
                        <option value="other" {{ old('medium', $artwork->medium) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('medium')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <input type="number" 
                           id="year" 
                           name="year" 
                           min="1900" 
                           max="{{ date('Y') }}"
                           value="{{ old('year', $artwork->year) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <select id="currency" 
                            name="currency" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="USD" {{ old('currency', $artwork->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="MMK" {{ old('currency', $artwork->currency) === 'MMK' ? 'selected' : '' }}>MMK</option>
                    </select>
                    @error('currency')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           min="0"
                           value="{{ old('stock', $artwork->stock ?? 1) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Set to 0 or leave empty for unlimited (digital artworks)</p>
                    @error('stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" 
                           id="weight" 
                           name="weight" 
                           min="0" 
                           step="0.1"
                           value="{{ old('weight', $artwork->weight) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., 2.5">
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Digital -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="is_digital" 
                               name="is_digital" 
                               value="1"
                               {{ old('is_digital', $artwork->is_digital) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Digital Artwork (no shipping required)</span>
                    </label>
                </div>

                <!-- Is Featured -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured', $artwork->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Artwork (displayed prominently)</span>
                    </label>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" {{ $artwork->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $artwork->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $artwork->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="sold" {{ $artwork->status === 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="resale" {{ $artwork->status === 'resale' ? 'selected' : '' }}>Resale</option>
                    </select>
                </div>

                <!-- Stats -->
                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Artwork Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artwork->views ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Views</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artwork->likes_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Likes</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ \App\Models\Wishlist::where('artwork_id', $artwork->id)->count() }}</p>
                            <p class="text-sm text-gray-600">Wishlists</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artwork->approved_at ? $artwork->approved_at->format('M j, Y') : 'N/A' }}</p>
                            <p class="text-sm text-gray-600">Approved</p>
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    @if($artwork->images && is_array($artwork->images) && count($artwork->images) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            @foreach($artwork->images as $index => $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="{{ $artwork->title }} - Image {{ $index + 1 }}" 
                                         class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center">
                                        <span class="text-white text-xs">Image {{ $index + 1 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No images uploaded</p>
                    @endif
                </div>

                <!-- New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add/Replace Images</label>
                    <input type="file" 
                           id="images" 
                           name="images[]" 
                           multiple
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Upload 1-5 images. Max 5MB each. Allowed formats: JPG, PNG, GIF, WebP. Leave blank to keep current images.</p>
                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('admin.artworks') }}"
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
