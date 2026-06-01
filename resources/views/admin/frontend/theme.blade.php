@extends('admin.layouts.app')

@section('title', 'Theme & Appearance - Panchi Gallery')

@section('header', 'Theme & Appearance')

@section('admin_content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Customize Theme</h1>
            <p class="text-gray-600 mt-1">Control colors, fonts, and visual appearance</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
            Back to Dashboard
        </a>
    </div>

    <form action="{{ route('admin.frontend.theme.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Color Settings -->
        <div class="card-luxury rounded-xl p-6">
            <h3 class="font-semibold text-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                Brand Colors
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Primary Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="theme[primary_color]" 
                               value="{{ $themeSettings['primary_color']->value ?? $defaultColors['primary_color'] }}"
                               class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                        <input type="text" name="theme[primary_color]_text" 
                               value="{{ $themeSettings['primary_color']->value ?? $defaultColors['primary_color'] }}"
                               class="flex-1 border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                               placeholder="#000000">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Main brand color for buttons, links, highlights</p>
                </div>

                <!-- Secondary Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="theme[secondary_color]" 
                               value="{{ $themeSettings['secondary_color']->value ?? $defaultColors['secondary_color'] }}"
                               class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                        <input type="text" name="theme[secondary_color]_text" 
                               value="{{ $themeSettings['secondary_color']->value ?? $defaultColors['secondary_color'] }}"
                               class="flex-1 border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                               placeholder="#ffffff">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Background color for cards, sections</p>
                </div>

                <!-- Accent Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Accent/Gold Color</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="theme[accent_color]" 
                               value="{{ $themeSettings['accent_color']->value ?? $defaultColors['accent_color'] }}"
                               class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                        <input type="text" name="theme[accent_color]_text" 
                               value="{{ $themeSettings['accent_color']->value ?? $defaultColors['accent_color'] }}"
                               class="flex-1 border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                               placeholder="#d4af37">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Luxury accent for premium elements</p>
                </div>

                <!-- Text Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="theme[text_color]" 
                               value="{{ $themeSettings['text_color']->value ?? $defaultColors['text_color'] }}"
                               class="w-12 h-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                        <input type="text" name="theme[text_color]_text" 
                               value="{{ $themeSettings['text_color']->value ?? $defaultColors['text_color'] }}"
                               class="flex-1 border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                               placeholder="#171717">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Main body text color</p>
                </div>
            </div>
        </div>

        <!-- Typography -->
        <div class="card-luxury rounded-xl p-6">
            <h3 class="font-semibold text-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
                Typography
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Font Family -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Font Family</label>
                    <select name="theme[font_family]" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        <option value="inter" {{ ($themeSettings['font_family']->value ?? 'inter') == 'inter' ? 'selected' : '' }}>Inter (Modern Sans)</option>
                        <option value="playfair" {{ ($themeSettings['font_family']->value ?? '') == 'playfair' ? 'selected' : '' }}>Playfair Display (Elegant Serif)</option>
                        <option value="roboto" {{ ($themeSettings['font_family']->value ?? '') == 'roboto' ? 'selected' : '' }}>Roboto (Clean Sans)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Primary font for entire website</p>
                </div>

                <!-- Border Radius -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Border Radius</label>
                    <select name="theme[border_radius]" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        <option value="none" {{ ($themeSettings['border_radius']->value ?? '') == 'none' ? 'selected' : '' }}>None (Sharp corners)</option>
                        <option value="sm" {{ ($themeSettings['border_radius']->value ?? '') == 'sm' ? 'selected' : '' }}>Small (Subtle)</option>
                        <option value="md" {{ ($themeSettings['border_radius']->value ?? 'md') == 'md' ? 'selected' : '' }}>Medium (Balanced)</option>
                        <option value="lg" {{ ($themeSettings['border_radius']->value ?? '') == 'lg' ? 'selected' : '' }}>Large (Rounded)</option>
                        <option value="xl" {{ ($themeSettings['border_radius']->value ?? '') == 'xl' ? 'selected' : '' }}>Extra Large (Very rounded)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Roundness of buttons, cards, inputs</p>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="card-luxury rounded-xl p-6">
            <h3 class="font-semibold text-lg mb-4">Live Preview</h3>
            <div class="p-6 rounded-xl border-2 border-gray-200" id="theme-preview">
                <div class="space-y-4">
                    <h4 class="text-2xl font-bold">Sample Heading</h4>
                    <p class="text-base">This is how your text will appear with the selected theme settings.</p>
                    <button class="px-6 py-3 rounded-lg text-white font-medium">Sample Button</button>
                    <div class="p-4 rounded-lg bg-opacity-10">Sample Card Content</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-6 py-3 rounded-lg">
                Cancel
            </a>
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">
                Save Theme Changes
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Live preview update
    function updatePreview() {
        const primary = document.querySelector('input[name="theme[primary_color]"]').value;
        const secondary = document.querySelector('input[name="theme[secondary_color]"]').value;
        const accent = document.querySelector('input[name="theme[accent_color]"]').value;
        const text = document.querySelector('input[name="theme[text_color]"]').value;
        
        const preview = document.getElementById('theme-preview');
        preview.style.color = text;
        preview.style.backgroundColor = secondary + '20'; // 20 = hex opacity
        
        const button = preview.querySelector('button');
        button.style.backgroundColor = primary;
        button.style.color = '#ffffff';
        
        const card = preview.querySelector('div > div:last-child');
        card.style.backgroundColor = accent + '15';
        card.style.borderLeft = `4px solid ${accent}`;
    }

    // Add event listeners
    document.querySelectorAll('input[type="color"]').forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    // Initial preview
    updatePreview();
</script>
@endpush
