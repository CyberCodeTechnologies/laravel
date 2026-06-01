<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the artworks with internationalization.
     */
    public function index(Request $request)
    {
        $query = Artwork::query();
        
        // Apply filters
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('medium')) {
            $query->byMedium($request->medium);
        }
        
        if ($request->filled('artist')) {
            $query->byArtist($request->artist);
        }
        
        if ($request->filled('size')) {
            $query->bySize($request->size);
        }
        
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('artist', function ($artistQuery) use ($request) {
                      $artistQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        // Apply ordering
        $sort = $request->get('sort', 'newest');
        
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $artworks = $query->approved()->with(['artist', 'category'])->paginate(30);
        
        // Get filter options with internationalization
        $categories = Category::withCount('artworks')->get();
        $artists = \App\Models\User::where('role', 'artist')->where('is_approved', true)
            ->withCount('artworks')->orderBy('name')->get();
        
        $mediums = [
            'oil' => __('messages.oil_painting'),
            'acrylic' => __('messages.acrylic_painting'),
            'watercolor' => __('messages.watercolor'),
            'digital' => __('messages.digital_art'),
            'mixed_media' => __('messages.mixed_media'),
            'sculpture' => __('messages.sculpture'),
            'photography' => __('messages.photography'),
            'other' => __('messages.other') ?? 'Other',
        ];
        
        $sizes = [
            'small' => __('messages.small_size'),
            'medium' => __('messages.medium_size'),
            'large' => __('messages.large_size'),
            'extra_large' => __('messages.extra_large_size'),
        ];
        
        $sortOptions = [
            'featured' => __('messages.sort_featured'),
            'price_low' => __('messages.sort_price_low_high'),
            'price_high' => __('messages.sort_price_high_low'),
            'newest' => __('messages.sort_newest'),
            'popular' => __('messages.sort_popular'),
        ];
        
        // Get counts for stats
        $artists_count = $artists->count();
        $categories_count = $categories->count();
        
        return view('artworks.index', compact(
            'artworks',
            'categories',
            'artists',
            'mediums',
            'sizes',
            'sortOptions',
            'artists_count',
            'categories_count'
        ));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('artworks.create', compact('categories'));
    }

    /**
     * Store a newly created artwork in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'in:oil,acrylic,watercolor,digital,photography,sculpture,mixed_media,other'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max per image
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Validate file type and size
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
                }
                if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                    return back()->with('error', 'File size too large. Maximum size is 5MB.');
                }
                $path = $image->store('artworks', 'public');
                $images[] = $path;
            }
        }

        $artwork = Artwork::create([
            'artist_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'medium' => $request->medium,
            'dimensions' => $request->dimensions,
            'price' => $request->price,
            'year' => $request->year,
            'images' => $images,
            'status' => auth()->user()->isAdmin() ? 'approved' : 'pending',
        ]);

        return redirect()->route('artworks.show', $artwork->slug)
            ->with('success', 'Artwork submitted successfully. It will be reviewed and approved within 24-48 hours.');
    }

    /**
     * Display the specified artwork.
     */
    public function show(Artwork $artwork)
    {
        // Increment view count
        $artwork->incrementViews();

        // Get related artworks
        $relatedArtworks = Artwork::where('category_id', $artwork->category_id)
            ->where('id', '!=', $artwork->id)
            ->approved()
            ->take(6)
            ->get();

        // Generate QR code URL for artwork page
        $artworkUrl = route('artworks.show', $artwork);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=2&data=' . urlencode($artworkUrl);

        return view('artworks.show', compact('artwork', 'relatedArtworks', 'qrCodeUrl'));
    }

    /**
     * Return artwork data as JSON for API requests.
     */
    public function apiShow(Artwork $artwork)
    {
        // Increment view count
        $artwork->incrementViews();
        
        // Load relationships
        $artwork->load(['artist', 'category']);
        
        // Format images for frontend
        $images = [];
        if ($artwork->images) {
            foreach ($artwork->images as $image) {
                $images[] = asset('storage/' . $image);
            }
        }
        
        return response()->json([
            'id' => $artwork->id,
            'title' => $artwork->title,
            'description' => $artwork->description,
            'medium' => $artwork->medium,
            'size' => $artwork->dimensions,
            'year' => $artwork->year,
            'price' => $artwork->price,
            'status' => $artwork->status,
            'artist' => $artwork->artist ? $artwork->artist->name : 'Unknown Artist',
            'category' => $artwork->category ? $artwork->category->name : null,
            'image' => $images[0] ?? asset('images/placeholder-artwork.jpg'),
            'images' => $images,
            'views' => $artwork->views_count,
            'likes_count' => $artwork->likes_count,
            'created_at' => $artwork->created_at->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for editing the specified artwork.
     */
    public function edit(Artwork $artwork)
    {
        $this->authorize('update', $artwork);
        
        $categories = Category::orderBy('name')->get();
        return view('artworks.edit', compact('artwork', 'categories'));
    }

    /**
     * Update the specified artwork in storage.
     */
    public function update(Request $request, Artwork $artwork)
    {
        $this->authorize('update', $artwork);
        
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'in:oil,acrylic,watercolor,digital,photography,sculpture,mixed_media,other'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'images' => ['nullable', 'array', 'min:1', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $data = $request->except('images');
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($artwork->images) {
                foreach ($artwork->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            // Upload new images
            $images = [];
            foreach ($request->file('images') as $image) {
                // Validate file type and size
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
                }
                if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                    return back()->with('error', 'File size too large. Maximum size is 5MB.');
                }
                $path = $image->store('artworks', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        // Update slug if title changed
        if ($request->title !== $artwork->title) {
            $data['slug'] = Str::slug($request->title) . '-' . uniqid();
        }
        
        // Reset status to pending if artwork was previously approved
        if ($artwork->status === 'approved' && !auth()->user()->isAdmin()) {
            $data['status'] = 'pending';
        }
        
        $artwork->update($data);

        return redirect()->route('artworks.show', $artwork->slug)
            ->with('success', 'Artwork updated successfully.');
    }

    /**
     * Remove the specified artwork from storage.
     */
    public function destroy(Artwork $artwork)
    {
        $this->authorize('delete', $artwork);
        
        // Delete images
        if ($artwork->images) {
            foreach ($artwork->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $artwork->delete();

        return redirect()->route('artist.artworks')
            ->with('success', 'Artwork deleted successfully.');
    }

    /**
     * Display artist's artworks.
     */
    public function myArtworks()
    {
        $artworks = auth()->user()->artworks()
            ->with('category')
            ->latest()
            ->paginate(12);
        
        return view('artworks.my-artworks', compact('artworks'));
    }

    /**
     * Toggle like status for an artwork.
     */
    public function toggleLike(Artwork $artwork)
    {
        $user = auth()->user();
        
        if ($user->likedArtworks()->where('artwork_id', $artwork->id)->exists()) {
            $user->likedArtworks()->detach($artwork->id);
            $liked = false;
        } else {
            $user->likedArtworks()->attach($artwork->id);
            $liked = true;
        }
        
        $artwork->updateLikesCount();
        
        return response()->json([
            'liked' => $liked,
            'likes_count' => $artwork->fresh()->likes_count,
        ]);
    }
}
