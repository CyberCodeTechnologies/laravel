<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogManagementController extends ApiController
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::withTrashed()->with('author:id,name,email');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->filled('author_id')) {
            $query->where('user_id', $request->author_id);
        }

        $blogs = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($blogs);
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
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

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog/featured', 'public');
        }

        // Set published_at if publishing now
        if ($request->is_published && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog = Blog::create($data);

        return $this->success($blog, 'Blog post created successfully', 201);
    }

    /**
     * Display the specified blog post.
     */
    public function show(int $id): JsonResponse
    {
        $blog = Blog::withTrashed()->with('author:id,name,email')->find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        return $this->success($blog);
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $blog = Blog::withTrashed()->find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['sometimes', 'required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
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

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        // Update slug if title changed
        if ($request->has('title') && $request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog/featured', 'public');
        }

        // Set published_at if publishing now
        if ($request->has('is_published') && $request->is_published && !$blog->is_published && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return $this->success($blog, 'Blog post updated successfully');
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy(int $id): JsonResponse
    {
        $blog = Blog::withTrashed()->find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->forceDelete();

        return $this->success(null, 'Blog post deleted successfully');
    }

    /**
     * Restore a soft-deleted blog post.
     */
    public function restore(int $id): JsonResponse
    {
        $blog = Blog::withTrashed()->find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        if (!$blog->trashed()) {
            return $this->error('Blog post is not deleted', 400);
        }

        $blog->restore();

        return $this->success($blog, 'Blog post restored successfully');
    }

    /**
     * Update blog status (publish/unpublish).
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        $validator = Validator::make($request->all(), [
            'is_published' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = ['is_published' => $request->is_published];
        
        if ($request->is_published && !$blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return $this->success($blog, 'Blog status updated successfully');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(int $id): JsonResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->notFound('Blog post not found');
        }

        $blog->update(['is_featured' => !$blog->is_featured]);

        return $this->success($blog, 'Featured status toggled successfully');
    }
}
