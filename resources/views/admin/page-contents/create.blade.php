@extends('admin.layouts.app')

@section('title', 'Create Page Content - Admin - Panchi Gallery')
@section('header', 'Create Page Content')

@section('admin_content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.page-contents.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Page -->
                <div>
                    <label for="page" class="block text-sm font-medium text-gray-700 mb-2">Page <span class="text-red-500">*</span></label>
                    <select id="page" name="page" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Page</option>
                        <option value="home" {{ old('page') == 'home' ? 'selected' : '' }}>Home</option>
                        <option value="about" {{ old('page') == 'about' ? 'selected' : '' }}>About</option>
                        <option value="contact" {{ old('page') == 'contact' ? 'selected' : '' }}>Contact</option>
                        <option value="faq" {{ old('page') == 'faq' ? 'selected' : '' }}>FAQ</option>
                        <option value="privacy" {{ old('page') == 'privacy' ? 'selected' : '' }}>Privacy Policy</option>
                        <option value="terms" {{ old('page') == 'terms' ? 'selected' : '' }}>Terms of Service</option>
                    </select>
                    @error('page')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                    <input type="text" id="section" name="section" value="{{ old('section') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., hero, features, mission">
                    @error('section')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Key -->
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 mb-2">Content Key <span class="text-red-500">*</span></label>
                    <input type="text" id="key" name="key" value="{{ old('key') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., home_hero_title">
                    <p class="text-sm text-gray-500 mt-1">Unique identifier for this content piece</p>
                    @error('key')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- English Content -->
                <div>
                    <label for="content_en" class="block text-sm font-medium text-gray-700 mb-2">English Content <span class="text-red-500">*</span></label>
                    <textarea id="content_en" name="content_en" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter English content">{{ old('content_en') }}</textarea>
                    @error('content_en')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Myanmar Content -->
                <div>
                    <label for="content_my" class="block text-sm font-medium text-gray-700 mb-2">Myanmar Content</label>
                    <textarea id="content_my" name="content_my" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter Myanmar content (optional)">{{ old('content_my') }}</textarea>
                    @error('content_my')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Content Type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="text" {{ old('type', 'text') == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="html" {{ old('type') == 'html' ? 'selected' : '' }}>HTML</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image URL</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active -->
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1">Inactive content won't be displayed on the site</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('admin.page-contents.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Content
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
