@extends('layouts.app')

@section('title', __('messages.blog_title'))
@section('meta-description', __('messages.blog_meta_description'))
@section('meta-keywords', 'art blog, art news, artist interviews, gallery blog, Myanmar art, art collecting tips')
@section('meta-image', asset('images/og-default.jpg'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                {{ __('messages.blog_hero_title') }}
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                {{ __('messages.blog_hero_description') }}
            </p>
        </div>
    </div>
</section>

<!-- Featured Posts -->
@if($featuredBlogs && $featuredBlogs->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.featured_posts') }}</h2>
            <div class="w-20 h-1 bg-red-600"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredBlogs as $blog)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <a href="{{ route('blog.show', $blog->slug) }}">
                    <div class="relative h-48">
                        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                        @if($blog->category)
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                                {{ $blog->category }}
                            </span>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-red-600 transition-colors">
                            {{ $blog->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $blog->excerpt }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div class="flex items-center">
                            @if($blog->author)
                            <img src="{{ $blog->author->avatar_url }}" alt="{{ $blog->author->name }}" class="w-6 h-6 rounded-full mr-2">
                            <span>{{ $blog->author->name }}</span>
                            @endif
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $blog->reading_time }} min read</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Filters -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center gap-4">
            <form method="GET" action="{{ route('blog.index') }}" class="flex-1 min-w-0">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_blog') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
            
            <div class="flex gap-2">
                <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-lg {{ !request('category') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.all') }}
                </a>
                @foreach($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category]) }}" class="px-4 py-2 rounded-lg {{ request('category') === $category ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $category }}
                </a>
                @endforeach
            </div>
            
            <select onchange="window.location.href=this.value" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="{{ route('blog.index', ['sort' => 'latest']) }}" {{ request('sort') === 'latest' ? 'selected' : '' }}>{{ __('messages.latest') }}</option>
                <option value="{{ route('blog.index', ['sort' => 'oldest']) }}" {{ request('sort') === 'oldest' ? 'selected' : '' }}>{{ __('messages.oldest') }}</option>
                <option value="{{ route('blog.index', ['sort' => 'popular']) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('messages.popular') }}</option>
            </select>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <a href="{{ route('blog.show', $blog->slug) }}">
                        <div class="relative h-48">
                            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                            @if($blog->category)
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $blog->category }}
                                </span>
                            </div>
                            @endif
                            @if($blog->is_featured)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ __('messages.featured') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </a>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-red-600 transition-colors">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $blog->excerpt }}</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                @if($blog->author)
                                <img src="{{ $blog->author->avatar_url }}" alt="{{ $blog->author->name }}" class="w-6 h-6 rounded-full mr-2">
                                <span>{{ $blog->author->name }}</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $blog->reading_time }} min</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                {{ $blog->published_at?->format('M d, Y') }}
                            </div>
                            <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                                {{ __('messages.read_more') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $blogs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.no_blog_posts') }}</h3>
                <p class="text-gray-600">{{ __('messages.no_blog_posts_description') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection
