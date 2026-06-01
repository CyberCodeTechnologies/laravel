@extends('admin.layouts.app')

@section('title', 'Edit FAQ - Admin')
@section('meta-description', 'Edit FAQ for Panchi Gallery')

@section('header', 'Edit FAQ')

@section('admin_content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('admin.support') }}" class="hover:text-gray-900">Support</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('admin.support.faq') }}" class="hover:text-gray-900">FAQ</a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-900">Edit</span>
            </li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-900">Edit FAQ</h2>
            <p class="text-sm text-gray-600 mt-1">Update the frequently asked question details.</p>
        </div>

        <form action="{{ route('admin.support.faq.update', $faq) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-gray-400">(Optional)</span>
                </label>
                <input 
                    type="text" 
                    id="category" 
                    name="category" 
                    value="{{ old('category', $faq->category) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., General, Artists, Collectors, Payments"
                >
                <p class="mt-1 text-sm text-gray-500">Group related FAQs together by category</p>
            </div>

            <!-- English Content -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-globe-americas mr-2 text-blue-600"></i>English Content
                </h3>
                
                <!-- Question English -->
                <div class="mb-6">
                    <label for="question_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="question_en" 
                        name="question_en" 
                        value="{{ old('question_en', $faq->question_en) }}"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the FAQ question in English"
                    >
                </div>

                <!-- Answer English -->
                <div class="mb-6">
                    <label for="answer_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Answer <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="answer_en" 
                        name="answer_en" 
                        rows="5" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the detailed answer in English"
                    >{{ old('answer_en', $faq->answer_en) }}</textarea>
                </div>
            </div>

            <!-- Myanmar Content -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-globe-asia mr-2 text-green-600"></i>Myanmar Content
                </h3>
                
                <!-- Question Myanmar -->
                <div class="mb-6">
                    <label for="question_my" class="block text-sm font-medium text-gray-700 mb-2">
                        မေးခွန်း <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input 
                        type="text" 
                        id="question_my" 
                        name="question_my" 
                        value="{{ old('question_my', $faq->question_my) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the FAQ question in Myanmar"
                    >
                </div>

                <!-- Answer Myanmar -->
                <div class="mb-6">
                    <label for="answer_my" class="block text-sm font-medium text-gray-700 mb-2">
                        အဖြေ <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea 
                        id="answer_my" 
                        name="answer_my" 
                        rows="5" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter the detailed answer in Myanmar"
                    >{{ old('answer_my', $faq->answer_my) }}</textarea>
                </div>
            </div>

            <!-- Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-gray-600"></i>Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input 
                            type="number" 
                            id="order" 
                            name="order" 
                            value="{{ old('order', $faq->order) }}"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    </div>

                    <!-- Published Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Published Status
                        </label>
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_published" 
                                name="is_published" 
                                value="1"
                                {{ old('is_published', $faq->is_published) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                Published (visible on public FAQ page)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Info -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">FAQ Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">ID:</span>
                        <span class="ml-2 text-gray-900">#{{ $faq->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="ml-2 text-gray-900">{{ $faq->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Updated:</span>
                        <span class="ml-2 text-gray-900">{{ $faq->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="ml-2">
                            @if($faq->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.support.faq') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <form action="{{ route('admin.support.faq.delete', $faq) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Update FAQ
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
