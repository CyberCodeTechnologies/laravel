@extends('layouts.app')

@section('title', __('messages.artwork_create.title', ['default' => 'Create New Artwork']) . ' - Panchi Gallery')
@section('meta-description', __('messages.artwork_create.meta_description', ['default' => 'Upload your artwork to Panchi Gallery. Share your art with collectors worldwide and get verified certificates of authenticity.']))

@section('content')
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    {{ __('messages.artwork_create.title', ['default' => 'Create New Artwork']) }}
                </h1>
                <p class="text-gray-300">
                    {{ __('messages.artwork_create.subtitle', ['default' => 'Share your masterpiece with collectors worldwide']) }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <x-button variant="secondary" href="{{ route('artist.artworks') }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('messages.artwork_create.back_to_artworks', ['default' => 'Back to Artworks']) }}
                </x-button>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium">1</span>
                    <span class="ml-2 text-sm font-medium text-gray-900">{{ __('messages.artwork_create.step_details', ['default' => 'Details']) }}</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">2</span>
                    <span class="ml-2 text-sm font-medium text-gray-500">{{ __('messages.artwork_create.step_images', ['default' => 'Images']) }}</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">3</span>
                    <span class="ml-2 text-sm font-medium text-gray-500">{{ __('messages.artwork_create.step_review', ['default' => 'Review']) }}</span>
                </div>
            </div>
        </div>

        <!-- Error Summary -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">{{ __('messages.artwork_create.errors_title', ['default' => 'Please fix the following errors:']) }}</p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('artist.artworks.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf

            <!-- Section: Basic Information -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">1</span>
                    {{ __('messages.artwork_create.basic_info', ['default' => 'Basic Information']) }}
                </h2>

                <div class="space-y-6">
                    <!-- AI Generation Toggle -->
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <input type="checkbox" id="use_ai_generation" name="use_ai_generation" value="1"
                                   class="mt-1 h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <div class="ml-3">
                                <label for="use_ai_generation" class="block text-sm font-medium text-gray-900">
                                    🤖 Use AI to Generate Title & Description
                                </label>
                                <p class="text-xs text-gray-600 mt-1">
                                    Let AI create a compelling title, description, SEO keywords, and social media posts for your artwork based on the medium, dimensions, and category.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.title_label', ['default' => 'Artwork Title']) }} <span class="text-gray-400">(Optional with AI)</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" maxlength="255"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="{{ __('messages.artwork_create.title_placeholder', ['default' => 'Enter a compelling title for your artwork']) }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.category_label', ['default' => 'Category']) }} <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition bg-white">
                            <option value="">{{ __('messages.artwork_create.select_category', ['default' => 'Select a category']) }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.description_label', ['default' => 'Description']) }} <span class="text-gray-400">(Optional with AI)</span>
                        </label>
                        <textarea id="description" name="description" rows="5" maxlength="2000"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition resize-none"
                                  placeholder="{{ __('messages.artwork_create.description_placeholder', ['default' => 'Describe your artwork, inspiration, techniques used, and any story behind it...']) }}">{{ old('description') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('description')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p></p>
                            @enderror
                            <p class="text-sm text-gray-500"><span id="desc-count">0</span> / 2000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Artwork Details -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">2</span>
                    {{ __('messages.artwork_create.artwork_details', ['default' => 'Artwork Details']) }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Medium -->
                    <div>
                        <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.medium_label', ['default' => 'Medium']) }} <span class="text-red-500">*</span>
                        </label>
                        <select id="medium" name="medium" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition bg-white">
                            <option value="">{{ __('messages.artwork_create.select_medium', ['default' => 'Select medium']) }}</option>
                            <option value="oil" {{ old('medium') == 'oil' ? 'selected' : '' }}>{{ __('messages.mediums.oil', ['default' => 'Oil']) }}</option>
                            <option value="acrylic" {{ old('medium') == 'acrylic' ? 'selected' : '' }}>{{ __('messages.mediums.acrylic', ['default' => 'Acrylic']) }}</option>
                            <option value="watercolor" {{ old('medium') == 'watercolor' ? 'selected' : '' }}>{{ __('messages.mediums.watercolor', ['default' => 'Watercolor']) }}</option>
                            <option value="digital" {{ old('medium') == 'digital' ? 'selected' : '' }}>{{ __('messages.mediums.digital', ['default' => 'Digital']) }}</option>
                            <option value="photography" {{ old('medium') == 'photography' ? 'selected' : '' }}>{{ __('messages.mediums.photography', ['default' => 'Photography']) }}</option>
                            <option value="sculpture" {{ old('medium') == 'sculpture' ? 'selected' : '' }}>{{ __('messages.mediums.sculpture', ['default' => 'Sculpture']) }}</option>
                            <option value="mixed_media" {{ old('medium') == 'mixed_media' ? 'selected' : '' }}>{{ __('messages.mediums.mixed_media', ['default' => 'Mixed Media']) }}</option>
                            <option value="traditional" {{ old('medium') == 'traditional' ? 'selected' : '' }}>{{ __('messages.mediums.traditional', ['default' => 'Traditional']) }}</option>
                        </select>
                        @error('medium')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dimensions -->
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.dimensions_label', ['default' => 'Dimensions']) }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}" required maxlength="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="{{ __('messages.artwork_create.dimensions_placeholder', ['default' => 'e.g., 24 x 36 inches']) }}">
                        @error('dimensions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.year_label', ['default' => 'Year Created']) }}
                        </label>
                        <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="{{ date('Y') }}">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.price_label', ['default' => 'Price (USD)']) }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500">$</span>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                    <!-- Stock/Quantity -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.stock_label', ['default' => 'Stock Quantity']) }}
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 1) }}" min="1" max="999"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="1">
                        <p class="mt-1 text-xs text-gray-500">{{ __('messages.artwork_create.stock_help', ['default' => 'Number of identical pieces available']) }}</p>
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.artwork_create.weight_label', ['default' => 'Weight (kg)']) }}
                        </label>
                        <input type="number" id="weight" name="weight" value="{{ old('weight') }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition"
                               placeholder="0.00">
                        <p class="mt-1 text-xs text-gray-500">{{ __('messages.artwork_create.weight_help', ['default' => 'For shipping cost calculation']) }}</p>
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Digital -->
                    <div class="flex items-end">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="is_digital" name="is_digital" value="1" {{ old('is_digital') ? 'checked' : '' }}
                                   class="w-5 h-5 text-black border-gray-300 rounded focus:ring-black">
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ __('messages.artwork_create.is_digital_label', ['default' => 'Digital Artwork']) }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section: Images -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">3</span>
                    {{ __('messages.artwork_create.images_section', ['default' => 'Artwork Images']) }}
                    <span class="text-red-500 ml-1">*</span>
                </h2>

                <!-- Image Upload Area -->
                <div class="relative">
                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-black transition-colors cursor-pointer bg-gray-50">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-700 mb-2">{{ __('messages.artwork_create.dropzone_title', ['default' => 'Drag & drop your images here']) }}</p>
                        <p class="text-sm text-gray-500 mb-4">{{ __('messages.artwork_create.dropzone_subtitle', ['default' => 'or click to browse from your device']) }}</p>
                        <p class="text-xs text-gray-400">{{ __('messages.artwork_create.image_requirements', ['default' => 'PNG, JPG, JPEG, GIF, WebP up to 5MB each. Minimum 1 image, maximum 5 images.']) }}</p>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required
                               class="hidden" onchange="previewImages(this)">
                        <button type="button" onclick="document.getElementById('images').click()"
                                class="mt-4 px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                            {{ __('messages.artwork_create.select_files', ['default' => 'Select Files']) }}
                        </button>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="image-previews" class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 hidden">
                    </div>

                    @error('images')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section: Review & Submit -->
            <div class="p-8 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium mr-3">4</span>
                    {{ __('messages.artwork_create.review_submit', ['default' => 'Review & Submit']) }}
                </h2>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">{{ __('messages.artwork_create.review_notice_title', ['default' => 'Important:']) }}</p>
                            <p class="text-sm text-yellow-700 mt-1">{{ __('messages.artwork_create.review_notice_text', ['default' => 'Your artwork will be reviewed by our team before being published. This process typically takes 24-48 hours. Ensure all information is accurate and images are high quality.']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4">
                    <x-button variant="outline" href="{{ route('artist.artworks') }}">
                        {{ __('messages.artwork_create.cancel', ['default' => 'Cancel']) }}
                    </x-button>
                    <x-button variant="primary" type="submit" id="submit-btn">
                        <svg id="submit-spinner" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="submit-text">{{ __('messages.artwork_create.submit', ['default' => 'Submit for Approval']) }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Character counter for description
const descInput = document.getElementById('description');
const descCount = document.getElementById('desc-count');

descInput.addEventListener('input', function() {
    descCount.textContent = this.value.length;
});

// Initialize count
descCount.textContent = descInput.value.length;

// Image preview functionality
function previewImages(input) {
    const previewContainer = document.getElementById('image-previews');
    const uploadArea = document.getElementById('upload-area');
    
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewContainer.classList.remove('hidden');
        uploadArea.classList.add('border-black', 'bg-gray-100');
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all"></div>
                    <span class="absolute top-2 left-2 bg-black text-white text-xs px-2 py-1 rounded font-medium">
                        ${index === 0 ? '{{ __("messages.artwork_create.primary", ["default" => "Primary"]) }}' : index + 1}
                    </span>
                    <span class="absolute bottom-2 right-2 bg-white text-gray-800 text-xs px-2 py-1 rounded">
                        ${(file.size / 1024 / 1024).toFixed(2)} MB
                    </span>
                `;
                previewContainer.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        });
    } else {
        previewContainer.classList.add('hidden');
        uploadArea.classList.remove('border-black', 'bg-gray-100');
    }
}

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('images');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-black', 'bg-gray-100');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-black', 'bg-gray-100');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-black', 'bg-gray-100');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        previewImages(fileInput);
    }
});

// Form submission loading state
const form = document.querySelector('form');
const submitBtn = document.getElementById('submit-btn');
const submitSpinner = document.getElementById('submit-spinner');
const submitText = document.getElementById('submit-text');

form.addEventListener('submit', function() {
    submitBtn.disabled = true;
    submitSpinner.classList.remove('hidden');
    submitText.textContent = '{{ __("messages.artwork_create.submitting", ["default" => "Submitting..."]) }}';
});
</script>
@endpush
