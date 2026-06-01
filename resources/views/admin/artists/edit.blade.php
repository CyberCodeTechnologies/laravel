@extends('admin.layouts.app')

@section('title', 'Edit Artist - Admin - Panchi Gallery')
@section('header', 'Edit Artist')

@section('admin_content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Profile Image -->
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($artist->avatar)
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('storage/' . $artist->avatar) }}" alt="{{ $artist->name }}">
                            @else
                                <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium text-xl">{{ substr($artist->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Allowed formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB.</p>
                            @error('avatar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $artist->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $artist->email) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $artist->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Participation -->
                <div class="flex items-center space-x-3">
                    <input type="checkbox" 
                           id="participate_in_orders" 
                           name="participate_in_orders" 
                           value="1"
                           {{ old('participate_in_orders', $artist->participate_in_orders ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="participate_in_orders" class="text-sm font-medium text-gray-700">
                        {{ __('messages.participate_in_custom_orders') }}
                    </label>
                    <span class="text-xs text-gray-500">{{ __('messages.artist_will_appear_in_order_form') }}</span>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Studio Location</label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $artist->location) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Yangon, Myanmar">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Years Active -->
                <div>
                    <label for="years_active" class="block text-sm font-medium text-gray-700 mb-2">Years Active</label>
                    <input type="number" 
                           id="years_active" 
                           name="years_active" 
                           value="{{ old('years_active', $artist->years_active) }}"
                           min="0" max="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('years_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Specialization -->
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                    <input type="text" 
                           id="specialization" 
                           name="specialization" 
                           value="{{ old('specialization', $artist->specialization) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Oil Painting, Sculpture, Digital Art">
                    @error('specialization')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artist Statement -->
                <div>
                    <label for="artist_statement" class="block text-sm font-medium text-gray-700 mb-2">Artist Statement</label>
                    <textarea id="artist_statement"
                              name="artist_statement"
                              rows="4"
                              maxlength="2000"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('artist_statement', $artist->artist_statement) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1"><span id="statement-char-count">{{ strlen($artist->artist_statement ?? '') }}</span>/2000 characters</p>
                    @error('artist_statement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                    <input type="url" 
                           id="website" 
                           name="website" 
                           value="{{ old('website', $artist->website) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://example.com">
                    @error('website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Media -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                        <input type="text" 
                               id="instagram" 
                               name="instagram" 
                               value="{{ old('instagram', $artist->instagram) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="@username">
                    </div>
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                        <input type="text" 
                               id="facebook" 
                               name="facebook" 
                               value="{{ old('facebook', $artist->facebook) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="username">
                    </div>
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                        <input type="text" 
                               id="twitter" 
                               name="twitter" 
                               value="{{ old('twitter', $artist->twitter) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="@username">
                    </div>
                </div>

                <!-- Cover Image -->
                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($artist->cover_image)
                                <img class="h-24 w-32 rounded-lg object-cover" src="{{ asset('storage/' . $artist->cover_image) }}" alt="{{ $artist->name }} cover">
                            @else
                                <div class="h-24 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 font-medium text-xs">No Cover</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   id="cover_image" 
                                   name="cover_image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, GIF, WebP. Max 2MB.</p>
                            @error('cover_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Verification Status -->
                <div class="flex items-center space-x-3">
                    <input type="checkbox" 
                           id="is_verified" 
                           name="is_verified" 
                           value="1"
                           {{ old('is_verified', $artist->is_verified) ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_verified" class="text-sm font-medium text-gray-700">
                        Verified Artist
                    </label>
                    <span class="text-xs text-gray-500">Badge displayed on artist profile</span>
                </div>

                <!-- Stats -->
                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Artist Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artist->artworks()->count() }}</p>
                            <p class="text-sm text-gray-600">Artworks</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artist->followers()->count() }}</p>
                            <p class="text-sm text-gray-600">Followers</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ $artist->sales()->where('status', 'completed')->count() }}</p>
                            <p class="text-sm text-gray-600">Sales</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold">{{ \App\Models\Like::where('artist_id', $artist->id)->count() }}</p>
                            <p class="text-sm text-gray-600">Total Likes</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('admin.artists') }}" 
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
