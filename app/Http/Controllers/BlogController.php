<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of all published blog posts.
     */
    public function index(Request $request)
    {
        $query = Blog::published()->with('author');
        
        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        match($sort) {
            'latest' => $query->orderBy('published_at', 'desc'),
            'oldest' => $query->orderBy('published_at', 'asc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            default => $query->orderBy('published_at', 'desc'),
        };
        
        $blogs = $query->paginate(12);
        
        // Get featured posts
        $featuredBlogs = Blog::published()->featured()->latest()->take(3)->get();
        
        // Get all categories
        $categories = Blog::published()->whereNotNull('category')->distinct()->pluck('category');
        
        return view('blog.index', compact('blogs', 'featuredBlogs', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug)
    {
        $blog = Blog::published()
            ->where('slug', $slug)
            ->with('author')
            ->firstOrFail();

        // Increment view count
        $blog->incrementViews();
        
        // Get related posts
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest()
            ->take(4)
            ->get();
        
        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
    
    /**
     * Display admin blog listing.
     */
    public function adminIndex(Request $request)
    {
        $query = Blog::with('author');
        
        // Filter by status
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }
        
        $blogs = $query->latest()->paginate(20);
        
        return view('admin.blogs.index', compact('blogs'));
    }
    
    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $authors = User::where('role', 'admin')->orWhere('role', 'artist')->get();
        $categories = Blog::whereNotNull('category')->distinct()->pluck('category');
        
        return view('admin.blogs.create', compact('authors', 'categories'));
    }
    
    /**
     * Store a newly created blog post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date', 'after:now'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
        
        $data = $request->except(['featured_image', 'tags']);
        $data['is_published'] = $request->has('is_published');
        $data['is_featured'] = $request->has('is_featured');
        $data['published_at'] = $data['is_published'] ? ($request->published_at ?? now()) : null;
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image');
            $data['featured_image'] = $featuredImage->store('blogs', 'public');
        }
        
        // Handle tags
        if ($request->has('tags')) {
            $data['tags'] = array_filter($request->tags);
        }
        
        $blog = Blog::create($data);
        
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }
    
    /**
     * Show the form for editing a blog post.
     */
    public function edit(Blog $blog)
    {
        $blog->load('author');
        $authors = User::where('role', 'admin')->orWhere('role', 'artist')->get();
        $categories = Blog::whereNotNull('category')->distinct()->pluck('category');
        
        return view('admin.blogs.edit', compact('blog', 'authors', 'categories'));
    }
    
    /**
     * Update a blog post.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
        
        $data = $request->except(['featured_image', 'tags']);
        $data['is_published'] = $request->has('is_published');
        $data['is_featured'] = $request->has('is_featured');
        
        // Handle published_at
        if ($data['is_published'] && !$blog->published_at) {
            $data['published_at'] = $request->published_at ?? now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        } elseif ($request->filled('published_at')) {
            $data['published_at'] = $request->published_at;
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $featuredImage = $request->file('featured_image');
            $data['featured_image'] = $featuredImage->store('blogs', 'public');
        }
        
        // Handle tags
        if ($request->has('tags')) {
            $data['tags'] = array_filter($request->tags);
        } else {
            $data['tags'] = [];
        }
        
        $blog->update($data);
        
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }
    
    /**
     * Delete a blog post.
     */
    public function destroy(Blog $blog)
    {
        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        
        $blog->delete();
        
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
