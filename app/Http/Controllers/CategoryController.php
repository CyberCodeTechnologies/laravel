<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount(['artworks' => function ($query) {
                $query->approved();
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category, Request $request)
    {
        $query = $category->activeArtworks()->approved();
        
        // Apply filters
        if ($request->filled('medium')) {
            $query->byMedium($request->medium);
        }
        
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply ordering
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'likes':
                $query->orderBy('likes_count', 'desc');
                break;
            default:
                $query->orderBy($sort, $order);
        }
        
        $artworks = $query->paginate(30);
        
        return view('categories.show', compact('category', 'artworks'));
    }

    /**
     * Show the form for creating a new category (admin only).
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage (admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Validate file type and size
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) { // 2MB limit for category image
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            $data['image'] = $image->store('categories', 'public');
        }
        
        Category::create($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category (admin only).
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage (admin only).
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $data = $request->except('image');
        
        // Update slug if name changed
        if ($request->name !== $category->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            // Validate file type and size
            $image = $request->file('image');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) { // 2MB limit for category image
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            $data['image'] = $image->store('categories', 'public');
        }
        
        $category->update($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage (admin only).
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        // Check if category has artworks
        if ($category->artworks()->exists()) {
            return back()->with('error', 'Cannot delete category that contains artworks.');
        }
        
        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Category deleted successfully.');
    }
}
