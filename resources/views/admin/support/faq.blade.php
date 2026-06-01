@extends('admin.layouts.app')

@section('title', 'FAQ Management - Admin')
@section('meta-description', 'Manage frequently asked questions on Panchi Gallery')

@section('header', 'FAQ Management')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total FAQs -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total FAQs</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $faqs->count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">Help articles</p>
                    </div>
                </div>
            </div>

            <!-- Published -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Published</p>
                        <p class="text-2xl font-bold text-green-900">{{ $faqs->where('is_published', true)->count() }}</p>
                        <p class="text-xs text-green-700 mt-1">Live on site</p>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-folder text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Categories</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $categories->count() }}</p>
                        <p class="text-xs text-purple-700 mt-1">Topic groups</p>
                    </div>
                </div>
            </div>

            <!-- Drafts -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-edit text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Drafts</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $faqs->where('is_published', false)->count() }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Unpublished</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Management -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Actions -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">FAQ Articles</h2>
                <p class="text-gray-600 mt-1">Manage frequently asked questions and organize them by category.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('faq') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i>View Public FAQ
                </a>
                <a href="{{ route('admin.support.faq.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add New FAQ
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                        <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                        <select id="categoryFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex-1 max-w-md">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Search questions or answers..."
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ List -->
        <div class="bg-white rounded-lg shadow-sm border">
            @if($faqs->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($faqs as $faq)
                        <div class="p-6 hover:bg-gray-50 transition faq-item" 
                             data-status="{{ $faq->is_published ? 'published' : 'draft' }}" 
                             data-category="{{ $faq->category ?? 'uncategorized' }}"
                             data-search="{{ strtolower($faq->question_en . ' ' . $faq->answer_en . ' ' . ($faq->question_my ?? '') . ' ' . ($faq->answer_my ?? '')) }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $faq->question_en }}</h3>
                                        @if($faq->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @endif
                                        @if($faq->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $faq->category }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit(strip_tags($faq->answer_en), 150) }}</p>
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <span><i class="fas fa-sort-numeric-down mr-1"></i>Order: {{ $faq->order }}</span>
                                        <span><i class="fas fa-calendar mr-1"></i>Updated: {{ $faq->updated_at->format('M j, Y') }}</span>
                                        @if($faq->question_my)
                                            <span><i class="fas fa-globe-asia mr-1"></i>Myanmar Available</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('admin.support.faq.edit', $faq) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.support.faq.delete', $faq) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-question-circle text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No FAQs Found</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first FAQ article.</p>
                    <a href="{{ route('admin.support.faq.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Create First FAQ
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const faqItems = document.querySelectorAll('.faq-item');

    function filterFaqs() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;

        faqItems.forEach(item => {
            const searchMatch = searchTerm === '' || item.dataset.search.includes(searchTerm);
            const statusMatch = statusValue === '' || item.dataset.status === statusValue;
            const categoryMatch = categoryValue === '' || item.dataset.category.toLowerCase() === categoryValue.toLowerCase();

            if (searchMatch && statusMatch && categoryMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterFaqs);
    statusFilter.addEventListener('change', filterFaqs);
    categoryFilter.addEventListener('change', filterFaqs);
});
</script>
@endsection
