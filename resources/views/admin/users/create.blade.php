@extends('admin.layouts.app')

@section('title', 'Create User - Admin - Panchi Gallery')
@section('meta-description', 'Create a new user in Panchi Gallery admin panel')

@section('header', 'Create User')

@section('admin_content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">Create New User</h1>
                    <p class="text-sm text-blue-100">Add a new user to the platform</p>
                </div>
                <a href="{{ route('admin.users') }}" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-8">
                <!-- Profile Image Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images mr-2 text-blue-600"></i>
                        Profile Image
                    </h2>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0" id="avatar-preview-container">
                            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                <span class="text-gray-500 font-medium text-xl">?</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, GIF, WebP. Max 2MB.</p>
                            @error('avatar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Basic Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        Contact Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   value="{{ old('state') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('state')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('postal_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <input type="text" 
                                   id="country" 
                                   name="country" 
                                   value="{{ old('country') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Role & Status Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                        Role & Status
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                            <select id="role" 
                                    name="role" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="artist" {{ old('role') == 'artist' ? 'selected' : '' }}>Artist</option>
                                <option value="collector" {{ old('role') == 'collector' ? 'selected' : '' }}>Collector</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_approved" 
                                       value="1"
                                       {{ old('is_approved') ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Auto-approve user</span>
                            </label>
                        </div>
                    </div>

                    <!-- Role Descriptions -->
                    <div class="bg-gray-50 rounded-lg p-4 mt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Role Permissions</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <i class="fas fa-user-shield text-purple-600 mt-0.5 mr-2"></i>
                                <div>
                                    <strong>Admin:</strong> Full access to all platform features and settings
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-palette text-blue-600 mt-0.5 mr-2"></i>
                                <div>
                                    <strong>Artist:</strong> Can upload artworks, manage portfolio, track sales
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-shopping-bag text-green-600 mt-0.5 mr-2"></i>
                                <div>
                                    <strong>Collector:</strong> Can purchase artworks, manage collection, resell items
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Artist Specific Section -->
                <div id="artist-section" class="hidden border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Artist Information
                    </h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                                <input type="text" 
                                       id="specialization" 
                                       name="specialization" 
                                       value="{{ old('specialization') }}"
                                       placeholder="e.g., Oil Painting, Sculpture, Digital Art"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('specialization')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="years_active" class="block text-sm font-medium text-gray-700 mb-2">Years Active</label>
                                <input type="number" 
                                       id="years_active" 
                                       name="years_active" 
                                       value="{{ old('years_active') }}"
                                       min="0"
                                       max="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('years_active')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Studio Location</label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}"
                                       placeholder="e.g., Yangon, Myanmar"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center pt-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox"
                                           name="participate_in_orders"
                                           value="1"
                                           {{ old('participate_in_orders') ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Participate in Custom Orders</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Artist Bio</label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      maxlength="1000"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1"><span id="bio-char-count">0</span>/1000 characters</p>
                            @error('bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="artist_statement" class="block text-sm font-medium text-gray-700 mb-2">Artist Statement</label>
                            <textarea id="artist_statement"
                                      name="artist_statement"
                                      rows="4"
                                      maxlength="2000"
                                      placeholder="Describe your artistic vision and philosophy"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('artist_statement') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1"><span id="statement-char-count">0</span>/2000 characters</p>
                            @error('artist_statement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Security
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-4">
                    <a href="{{ route('admin.users') }}" 
                       class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Users
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Create User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role change handler
    const roleSelect = document.getElementById('role');
    const artistSection = document.getElementById('artist-section');

    if (roleSelect && artistSection) {
        roleSelect.addEventListener('change', function() {
            if (this.value === 'artist') {
                artistSection.classList.remove('hidden');
            } else {
                artistSection.classList.add('hidden');
            }
        });
    }

    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreviewContainer = document.getElementById('avatar-preview-container');

    if (avatarInput && avatarPreviewContainer) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreviewContainer.innerHTML = '<img class="h-24 w-24 rounded-full object-cover border-2 border-gray-200" src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Character counters
    const bioTextarea = document.getElementById('bio');
    const bioCharCount = document.getElementById('bio-char-count');
    const statementTextarea = document.getElementById('artist_statement');
    const statementCharCount = document.getElementById('statement-char-count');

    if (bioTextarea && bioCharCount) {
        bioTextarea.addEventListener('input', function() {
            bioCharCount.textContent = this.value.length;
        });
    }

    if (statementTextarea && statementCharCount) {
        statementTextarea.addEventListener('input', function() {
            statementCharCount.textContent = this.value.length;
        });
    }
});
</script>
@endsection
