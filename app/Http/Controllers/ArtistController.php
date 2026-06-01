<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Transaction;
use App\Models\Ownership;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    /**
     * Display public artists listing with internationalization.
     */
    public function index()
    {
        // Show all approved artists for marketplace experience
        $artists = User::where('role', 'artist')
            ->where('is_approved', true)
            ->withCount(['artworks' => function ($query) {
                $query->approved();
            }])
            ->withCount('followers')
            ->orderBy('years_active', 'desc')
            ->get();

        // Get featured artist for spotlight (most experienced with approved artworks)
        $featuredArtist = User::where('role', 'artist')
            ->where('is_approved', true)
            ->whereHas('artworks', function ($query) {
                $query->approved();
            })
            ->withCount(['artworks' => function ($query) {
                $query->approved();
            }])
            ->withCount('followers')
            ->orderBy('years_active', 'desc')
            ->first();

        // Get artist specializations for filters
        $specializations = [
            'painting' => __('messages.painting'),
            'sculpture' => __('messages.sculpture'),
            'photography' => __('messages.photography'),
            'digital_art' => __('messages.digital_art'),
        ];

        return view('artists.index', compact('artists', 'featuredArtist', 'specializations'));
    }

    /**
     * Display artist dashboard.
     */
    public function dashboard()
    {
        $artist = auth()->user();
        
        $stats = [
            'total_artworks' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->count(),
            'approved_artworks' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->approved()->count(),
            'pending_artworks' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->where('status', 'pending')->count(),
            'sold_artworks' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->where('status', 'sold')->count(),
            'total_sales' => $artist->sales()->completed()->sum('seller_earnings'),
            'monthly_sales' => $artist->sales()->completed()
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->sum('seller_earnings'),
            'total_views' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->sum('views_count'),
            'total_likes' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->withCount('likes')->get()->sum('likes_count'),
            'followers' => $artist->followers()->count(),
        ];
        
        // Get all artworks for the dashboard
        $artworks = $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->latest()->get();
        
        $recentArtworks = $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->latest()->take(5)->get();
        $recentSales = $artist->sales()->completed()->with('buyer', 'artwork')->latest()->take(5)->get();
        
        return view('artist.dashboard', compact('stats', 'artworks', 'recentArtworks', 'recentSales'));
    }

    /**
     * Display artist's artworks.
     */
    public function artworks(Request $request)
    {
        $artist = auth()->user();
        $query = $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->with('category');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $artworks = $query->latest()->paginate(12);
        
        return view('artist.artworks', compact('artworks'));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function createArtwork()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('artist.artworks-create', compact('categories'));
    }

    /**
     * Store a newly created artwork.
     */
    public function storeArtwork(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'string', 'in:oil,acrylic,watercolor,digital,photography,sculpture,mixed_media,traditional'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'stock' => ['nullable', 'integer', 'min:1', 'max:999'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'use_ai_generation' => ['nullable', 'boolean'],
        ]);
        
        $artist = auth()->user();
        $category = \App\Models\Category::find($request->category_id);
        
        // Handle image uploads
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
        
        // AI Generation if requested
        $title = $request->title;
        $description = $request->description;
        
        if ($request->has('use_ai_generation') && $request->use_ai_generation) {
            try {
                $aiService = app(\App\Services\AIDescriptionGeneratorService::class);
                $aiContent = $aiService->generate([
                    'title' => $request->title,
                    'medium' => $request->medium,
                    'dimensions' => $request->dimensions,
                    'category' => $category->name ?? null,
                ]);
                
                $title = $aiContent['title'] ?? $request->title;
                $description = $aiContent['description'] ?? $request->description;
                
                // Store AI generated content in session for social media use
                session([
                    'ai_generated_content' => [
                        'facebook_post' => $aiContent['facebook_post'] ?? '',
                        'instagram_caption' => $aiContent['instagram_caption'] ?? '',
                        'seo_keywords' => $aiContent['seo_keywords'] ?? [],
                    ]
                ]);
                
            } catch (\Exception $e) {
                // If AI fails, use manual input
                \Log::error('AI Generation Failed: ' . $e->getMessage());
            }
        }
        
        // Ensure we have title and description
        if (empty($title)) {
            $title = 'Untitled Artwork';
        }
        if (empty($description)) {
            $description = 'A beautiful artwork created in ' . $request->medium . '.';
        }
        
        $artwork = $artist->artworks()->create([
            'title' => $title,
            'description' => $description,
            'category_id' => $request->category_id,
            'medium' => $request->medium,
            'dimensions' => $request->dimensions,
            'price' => $request->price,
            'year' => $request->year,
            'stock' => $request->stock ?? 1,
            'weight' => $request->weight ?? 0,
            'is_digital' => $request->has('is_digital'),
            'images' => $images,
            'status' => 'pending',
            'currency' => 'USD',
        ]);
        
        return redirect()->route('artist.artworks')
            ->with('success', __('messages.artwork_create.success', ['default' => 'Artwork submitted for approval. It will be reviewed within 24-48 hours.']));
    }

    /**
     * Show the form for editing an artwork.
     */
    public function editArtwork(Artwork $artwork)
    {
        $this->authorize('update', $artwork);
        
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('artist.artworks-edit', compact('artwork', 'categories'));
    }

    /**
     * Update an artwork.
     */
    public function updateArtwork(Request $request, Artwork $artwork)
    {
        $this->authorize('update', $artwork);
        
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'string', 'in:oil,acrylic,watercolor,digital,photography,sculpture,mixed_media,traditional'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'images' => ['nullable', 'array', 'max:5'],
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
        
        // Reset to pending if artwork was previously approved
        if ($artwork->status === 'approved') {
            $data['status'] = 'pending';
        }
        
        $artwork->update($data);
        
        return redirect()->route('artist.artworks')
            ->with('success', 'Artwork updated successfully.');
    }

    /**
     * Delete an artwork.
     */
    public function deleteArtwork(Artwork $artwork)
    {
        $this->authorize('delete', $artwork);
        
        // Check if artwork has sales
        if ($artwork->transactions()->exists()) {
            return back()->with('error', 'Cannot delete artwork that has been sold.');
        }
        
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
     * Display artist's sales.
     */
    public function sales(Request $request)
    {
        $artist = auth()->user();
        $query = $artist->sales()->with(['buyer', 'artwork']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('completed_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('completed_at', '<=', $request->date_to);
        }
        
        $sales = $query->latest()->paginate(15);
        
        return view('artist.sales', compact('sales'));
    }

    /**
     * Display artist's profile.
     */
    public function profile()
    {
        $artist = auth()->user();
        return view('artist.profile', compact('artist'));
    }

    /**
     * Update artist's profile.
     */
    public function updateProfile(Request $request)
    {
        $artist = auth()->user();
        
        $request->validate([
            // Basic Information
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $artist->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'years_active' => ['nullable', 'integer', 'min:0', 'max:100'],
            
            // Professional Information
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'artist_statement' => ['nullable', 'string', 'max:5000'],
            
            // Education & Achievements
            'education' => ['nullable', 'string'],
            'exhibitions' => ['nullable', 'string'],
            'awards' => ['nullable', 'string'],
            'press' => ['nullable', 'string'],
            
            // Online Presence
            'website' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:255'],
            
            // Photos
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            
            // Settings
            'participate_in_orders' => ['nullable', 'boolean'],
        ]);
        
        $data = $request->except(['avatar', 'cover_image']);
        
        // Handle boolean fields
        $data['participate_in_orders'] = $request->has('participate_in_orders') ? true : false;
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($artist->avatar) {
                Storage::disk('public')->delete($artist->avatar);
            }
            
            $avatar = $request->file('avatar');
            $data['avatar'] = $avatar->store('avatars', 'public');
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($artist->cover_image) {
                Storage::disk('public')->delete($artist->cover_image);
            }
            
            $coverImage = $request->file('cover_image');
            $data['cover_image'] = $coverImage->store('covers', 'public');
        }
        
        // Update the artist profile
        $artist->update($data);
        
        return back()->with('success', __('messages.artist_profile.profile_updated_success'));
    }

    /**
     * Display artist's followers.
     */
    public function followers()
    {
        $artist = auth()->user();
        $followers = $artist->followers()->with('follower')->latest()->paginate(15);
        
        return view('artist.followers', compact('followers'));
    }

    /**
     * Display artist analytics.
     */
    public function analytics()
    {
        $artist = auth()->user();
        
        // Monthly sales for the past 12 months
        $monthlySales = $artist->sales()->completed()
            ->selectRaw('MONTH(completed_at) as month, YEAR(completed_at) as year, SUM(seller_earnings) as total')
            ->where('completed_at', '>=', now()->subYear())
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Artwork views by category
        $viewsByCategory = $artist->artworks()
            ->join('categories', 'artworks.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(artworks.views_count) as total_views')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_views', 'desc')
            ->get();
        
        // Top performing artworks
        $topArtworks = $artist->artworks()
            ->withCount(['transactions', 'likes'])
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        // Artworks for the table (like My Artworks tab)
        $artworks = $artist->artworks()->latest()->take(5)->get();

        return view('artist.analytics', compact('monthlySales', 'viewsByCategory', 'topArtworks', 'artworks'));
    }

    /**
     * Display public artist profile by ID (for backward compatibility).
     */
    public function show(User $artist)
    {
        $artist->load(['artworks' => function ($query) {
            $query->approved()->latest()->take(12);
        }]);

        // Load additional artist data
        $artist->loadCount(['artworks as artworks_count' => function ($query) {
            $query->approved();
        }]);
        $artist->loadCount('followers');

        // Generate QR code URL for artist profile using external API
        $profileUrl = route('artists.show', $artist->slug ?? $artist->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=2&data=' . urlencode($profileUrl);

        return view('artists.show', compact('artist', 'qrCodeUrl'));
    }

    /**
     * Display public artist profile by slug.
     */
    public function showBySlug(string $slug)
    {
        // Try to find by slug first, then fall back to ID for backward compatibility
        $artist = User::where('slug', $slug)->first();

        if (!$artist && is_numeric($slug)) {
            $artist = User::find($slug);
        }

        if (!$artist || $artist->role !== 'artist') {
            abort(404);
        }

        // Load initial 12 approved artworks (both available and sold)
        $artist->load(['artworks' => function ($query) {
            $query->whereIn('status', ['approved', 'sold'])->latest()->take(12);
        }]);

        // Load additional artist data
        $artist->loadCount(['artworks as artworks_count' => function ($query) {
            $query->whereIn('status', ['approved', 'sold']);
        }]);
        $artist->loadCount(['artworks as available_count' => function ($query) {
            $query->where('status', 'approved');
        }]);
        $artist->loadCount(['artworks as sold_count' => function ($query) {
            $query->where('status', 'sold');
        }]);
        $artist->loadCount('followers');

        // Generate QR code URL for artist profile using external API
        $profileUrl = route('artists.show', $artist->slug ?? $artist->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=2&data=' . urlencode($profileUrl);

        return view('artists.show', compact('artist', 'qrCodeUrl'));
    }

    /**
     * Follow an artist.
     */
    public function follow($id)
    {
        $artist = User::findOrFail($id);
        
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to follow artists.',
                'redirect' => route('login')
            ], 401);
        }
        
        if (auth()->id() === $artist->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 400);
        }

        $follower = auth()->user();

        if ($follower->isFollowing($artist)) {
            $follower->unfollow($artist);
            $message = 'Artist unfollowed successfully.';
            $following = false;
        } else {
            $follower->follow($artist);
            $message = 'Artist followed successfully.';
            $following = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'following' => $following
        ]);
    }

    /**
     * Get artist statistics API.
     */
    public function statistics()
    {
        $artist = auth()->user();
        
        $stats = [
            'artworks' => [
                'total' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->count(),
                'approved' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->approved()->count(),
                'pending' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->where('status', 'pending')->count(),
                'sold' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->where('status', 'sold')->count(),
            ],
            'sales' => [
                'total' => $artist->sales()->completed()->sum('seller_earnings'),
                'count' => $artist->sales()->completed()->count(),
                'monthly' => $artist->sales()->completed()
                    ->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year)
                    ->sum('seller_earnings'),
            ],
            'engagement' => [
                'views' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->sum('views_count'),
                'likes' => $artist->artworks()->withoutGlobalScope(\App\Models\Scopes\ApprovedScope::class)->withCount('likes')->get()->sum('likes_count'),
                'followers' => $artist->followers()->count(),
            ],
        ];
        
        return response()->json($stats);
    }

    /**
     * Get paginated artworks for an artist (AJAX endpoint for Load More).
     */
    public function getArtworks(Request $request, $slug)
    {
        $artist = User::where('slug', $slug)->first();

        if (!$artist && is_numeric($slug)) {
            $artist = User::find($slug);
        }

        if (!$artist || $artist->role !== 'artist') {
            return response()->json(['error' => 'Artist not found'], 404);
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);
        $filter = $request->input('filter', 'all');

        $query = $artist->artworks()
            ->whereIn('status', ['approved', 'sold'])
            ->with('category');

        // Apply filters
        if ($filter === 'available') {
            $query->where('status', 'approved');
        } elseif ($filter === 'sold') {
            $query->where('status', 'sold');
        }

        // Apply sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $artworks = $query->paginate($perPage, ['*'], 'page', $page);

        // Render artwork cards HTML
        $html = '';
        foreach ($artworks as $artwork) {
            $html .= view('components.artwork-card', ['artwork' => $artwork])->render();
        }

        return response()->json([
            'html' => $html,
            'current_page' => $artworks->currentPage(),
            'last_page' => $artworks->lastPage(),
            'total' => $artworks->total(),
            'has_more' => $artworks->hasMorePages(),
        ]);
    }
}
