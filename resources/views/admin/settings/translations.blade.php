@extends('admin.layouts.app')

@section('title', 'Translation Editor - Admin')
@section('meta-description', 'Edit and manage translations on Panchi Gallery')

@section('header', 'Translation Editor')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Current Language -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-globe text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Current Language</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $language === 'en' ? 'English' : 'Myanmar' }}</p>
                        <p class="text-xs text-blue-700 mt-1">Editing language</p>
                    </div>
                </div>
            </div>

            <!-- Translation Files -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-file-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Translation Files</p>
                        <p class="text-2xl font-bold text-green-900">{{ count($translations) }}</p>
                        <p class="text-xs text-green-700 mt-1">Available files</p>
                    </div>
                </div>
            </div>

            <!-- Translation Keys -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-key text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Translation Keys</p>
                        <p class="text-2xl font-bold text-purple-900">{{ count($currentTranslations) }}</p>
                        <p class="text-xs text-purple-700 mt-1">In current file</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Translation Editor Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar - Language & File Selection -->
            <div class="space-y-6">
                <!-- Language Selection -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Select Language</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.settings.translations', ['lang' => 'en', 'file' => $file]) }}" 
                           class="flex items-center p-3 rounded-lg transition {{ $language === 'en' ? 'bg-blue-50 border-2 border-blue-500' : 'bg-gray-50 hover:bg-gray-100' }}">
                            <span class="text-2xl mr-3">🇬🇧</span>
                            <span class="text-sm font-medium text-gray-900">English</span>
                        </a>
                        <a href="{{ route('admin.settings.translations', ['lang' => 'my', 'file' => $file]) }}" 
                           class="flex items-center p-3 rounded-lg transition {{ $language === 'my' ? 'bg-blue-50 border-2 border-blue-500' : 'bg-gray-50 hover:bg-gray-100' }}">
                            <span class="text-2xl mr-3">🇲🇲</span>
                            <span class="text-sm font-medium text-gray-900">Myanmar (Burmese)</span>
                        </a>
                    </div>
                </div>

                <!-- File Selection -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Select File</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($translations as $filename => $content)
                            <a href="{{ route('admin.settings.translations', ['lang' => $language, 'file' => $filename]) }}" 
                               class="flex items-center p-3 rounded-lg transition {{ $file === $filename ? 'bg-blue-50 border-2 border-blue-500' : 'bg-gray-50 hover:bg-gray-100' }}">
                                <i class="fas fa-file-alt text-gray-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $filename }}.php</span>
                                <span class="ml-auto text-xs text-gray-500">{{ count($content) }} keys</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Editor Area -->
            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('admin.settings.translations.update') }}" class="bg-white rounded-lg shadow-md">
                    @csrf
                    <input type="hidden" name="language" value="{{ $language }}">
                    <input type="hidden" name="file" value="{{ $file }}">
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-edit mr-2"></i>Editing: {{ $file }}.php ({{ $language === 'en' ? 'English' : 'Myanmar' }})
                            </h2>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>Save Translations
                            </button>
                        </div>

                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            @foreach($currentTranslations as $key => $value)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $key }}</code>
                                    </label>
                                    @if(is_array($value))
                                        <div class="space-y-2 ml-4 border-l-2 border-gray-200 pl-4">
                                            @foreach($value as $subKey => $subValue)
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">{{ $key }}.{{ $subKey }}</label>
                                                    <input type="text" 
                                                           name="translations[{{ $key }}][{{ $subKey }}]" 
                                                           value="{{ is_string($subValue) ? $subValue : '' }}"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <textarea name="translations[{{ $key }}]" 
                                                  rows="{{ is_string($value) && strlen($value) > 100 ? 3 : 1 }}"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">{{ is_string($value) ? $value : '' }}</textarea>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
