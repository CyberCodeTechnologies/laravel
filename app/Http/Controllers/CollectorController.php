<?php

namespace App\Http\Controllers;

use App\Models\Ownership;
use App\Models\Transaction;
use App\Models\Resale;
use App\Models\Like;
use Illuminate\Http\Request;

class CollectorController extends Controller
{
    /**
     * Display collector dashboard.
     */
    public function dashboard()
    {
        $collector = auth()->user();
        
        $stats = [
            'total_artworks' => $collector->ownerships()->where('is_current_owner', true)->count(),
            'total_purchases' => $collector->purchases()->completed()->count(),
            'total_spent' => $collector->purchases()->completed()->sum('amount'),
            'pending_purchases' => $collector->purchases()->pending()->count(),
            'active_resales' => $collector->resales()->listed()->count(),
            'pending_resales' => $collector->resales()->pending()->count(),
            'total_likes' => $collector->likes()->count(),
            'following_artists' => $collector->following()->count(),
        ];
        
        // Collection data
        $collection = $collector->ownerships()
            ->where('is_current_owner', true)
            ->with('artwork.artist')
            ->latest()
            ->get();
        
        // Wishlist data
        $wishlist = $collector->likes()
            ->with('artwork.artist')
            ->latest()
            ->get();
        
        // Purchase history
        $purchases = $collector->purchases()
            ->completed()
            ->with(['artwork.artist', 'seller'])
            ->latest()
            ->get();
        
        // Resale listings
        $resaleListings = $collector->resales()
            ->with('artwork.artist')
            ->latest()
            ->get();
        
        return view('collector.dashboard', compact(
            'stats',
            'collection',
            'wishlist',
            'purchases',
            'resaleListings'
        ));
    }

    /**
     * Display collector's artworks collection.
     */
    public function artworks(Request $request)
    {
        $collector = auth()->user();
        $query = $collector->ownerships()->where('is_current_owner', true)->with(['artwork.artist', 'artwork.category']);
        
        if ($request->filled('search')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }
        
        if ($request->filled('medium')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('medium', $request->medium);
            });
        }
        
        $ownerships = $query->latest()->paginate(12);
        
        return view('collector.artworks', compact('ownerships'));
    }

    /**
     * Display collector's purchase history.
     */
    public function purchases(Request $request)
    {
        $collector = auth()->user();
        $query = $collector->purchases()->with(['seller', 'artwork.artist', 'artwork.category']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('collector.purchases', compact('transactions'));
    }

    /**
     * Display collector's resale listings.
     */
    public function resales(Request $request)
    {
        $collector = auth()->user();
        $query = $collector->resales()->with(['artwork.artist', 'artwork.category']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $resales = $query->latest()->paginate(12);
        
        return view('collector.resales', compact('resales'));
    }

    /**
     * Show form for creating a new resale listing.
     */
    public function createResale(\App\Models\Artwork $artwork)
    {
        $this->authorize('resale', $artwork);
        
        // Verify ownership
        if (!$artwork->current_owner || $artwork->current_owner->id !== auth()->id()) {
            abort(403, 'You are not the current owner of this artwork.');
        }
        
        // Check if artwork is available for resale
        if (!$artwork->isAvailableForResale()) {
            return back()->with('error', 'This artwork is not available for resale.');
        }
        
        return view('collector.resales-create', compact('artwork'));
    }

    /**
     * Store a new resale listing.
     */
    public function storeResale(Request $request, \App\Models\Artwork $artwork)
    {
        $this->authorize('resale', $artwork);
        
        $request->validate([
            'asking_price' => ['required', 'numeric', 'min:0'],
            'minimum_price' => ['nullable', 'numeric', 'min:0', 'lt:asking_price'],
            'description' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
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
                $path = $image->store('resales', 'public');
                $images[] = $path;
            }
        }
        
        // Create resale listing
        $resale = Resale::create([
            'artwork_id' => $artwork->id,
            'owner_id' => auth()->id(),
            'asking_price' => $request->asking_price,
            'minimum_price' => $request->minimum_price,
            'description' => $request->description,
            'images' => $images,
            'status' => 'pending',
            'is_verified' => false,
        ]);
        
        return redirect()->route('collector.resales')
            ->with('success', 'Resale listing submitted for approval. It will be reviewed within 24-48 hours.');
    }

    /**
     * Display collector's wishlist.
     */
    public function wishlist(Request $request)
    {
        $collector = auth()->user();
        $query = $collector->likes()->with(['artwork.artist', 'artwork.category']);
        
        if ($request->filled('search')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }
        
        $likes = $query->latest()->paginate(12);
        
        return view('collector.wishlist', compact('likes'));
    }

    /**
     * Display collector's followed artists.
     */
    public function following()
    {
        $collector = auth()->user();
        $following = $collector->following()->with('following')->latest()->paginate(15);
        
        return view('collector.following', compact('following'));
    }

    /**
     * Display collector's profile.
     */
    public function profile()
    {
        $collector = auth()->user();
        return view('collector.profile', compact('collector'));
    }

    /**
     * Update collector's profile.
     */
    public function updateProfile(Request $request)
    {
        $collector = auth()->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $collector->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        $data = $request->except('avatar');
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($collector->avatar) {
                \Storage::disk('public')->delete($collector->avatar);
            }
            
            // Validate avatar file
            $avatar = $request->file('avatar');
            if (!in_array($avatar->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($avatar->getSize() > 2 * 1024 * 1024) { // 2MB limit for avatar
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            $data['avatar'] = $avatar->store('avatars', 'public');
        }
        
        $collector->update($data);
        
        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Transfer artwork ownership.
     */
    public function transferOwnership(Request $request, \App\Models\Artwork $artwork)
    {
        $this->authorize('transfer', $artwork);
        
        $request->validate([
            'recipient_email' => ['required', 'email', 'exists:users,email'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $recipient = \App\Models\User::where('email', $request->recipient_email)->first();
        
        if ($recipient->id === auth()->id()) {
            return back()->with('error', 'Cannot transfer artwork to yourself.');
        }
        
        // Get current ownership
        $currentOwnership = $artwork->ownerships()
            ->where('owner_id', auth()->id())
            ->where('is_current_owner', true)
            ->first();
        
        if (!$currentOwnership) {
            return back()->with('error', 'You are not the current owner of this artwork.');
        }
        
        // Transfer ownership
        $newOwnership = $currentOwnership->transferTo(
            $recipient,
            null, // Free transfer
            'transfer'
        );
        
        return redirect()->route('collector.artworks')
            ->with('success', 'Artwork transferred successfully to ' . $recipient->name);
    }

    /**
     * Download ownership certificate.
     */
    public function downloadCertificate(Ownership $ownership)
    {
        $this->authorize('view', $ownership);
        
        // Generate PDF certificate
        $pdf = \PDF::loadView('ownership.certificate', compact('ownership'));
        
        return $pdf->download("ownership-certificate-{$ownership->id}.pdf");
    }

    /**
     * Display collector analytics.
     */
    public function analytics()
    {
        $collector = auth()->user();
        
        // Monthly purchases for past 12 months
        $monthlyPurchases = $collector->purchases()->completed()
            ->selectRaw('MONTH(completed_at) as month, YEAR(completed_at) as year, SUM(amount) as total')
            ->where('completed_at', '>=', now()->subYear())
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Collection by category
        $collectionByCategory = $collector->ownerships()
            ->where('is_current_owner', true)
            ->join('artworks', 'ownerships.artwork_id', '=', 'artworks.id')
            ->join('categories', 'artworks.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();
        
        // Top valued artworks
        $topValuedArtworks = $collector->ownerships()
            ->where('is_current_owner', true)
            ->with(['artwork.artist'])
            ->orderBy('purchase_price', 'desc')
            ->take(10)
            ->get();
        
        return view('collector.analytics', compact('monthlyPurchases', 'collectionByCategory', 'topValuedArtworks'));
    }

    /**
     * Toggle artwork like.
     */
    public function toggleLike(\App\Models\Artwork $artwork)
    {
        $collector = auth()->user();
        
        $like = $collector->likes()->where('artwork_id', $artwork->id)->first();
        
        if ($like) {
            $like->delete();
            $message = 'Artwork removed from wishlist.';
            $liked = false;
        } else {
            $collector->likes()->create(['artwork_id' => $artwork->id]);
            $message = 'Artwork added to wishlist.';
            $liked = true;
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'liked' => $liked,
            'likes_count' => $artwork->likes()->count(),
        ]);
    }

    /**
     * Get collector statistics API.
     */
    public function statistics()
    {
        $collector = auth()->user();
        
        $stats = [
            'collection' => [
                'total_artworks' => $collector->ownerships()->where('is_current_owner', true)->count(),
                'total_value' => $collector->ownerships()->where('is_current_owner', true)->sum('purchase_price'),
            ],
            'purchases' => [
                'total_spent' => $collector->purchases()->completed()->sum('amount'),
                'count' => $collector->purchases()->completed()->count(),
                'monthly' => $collector->purchases()->completed()
                    ->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year)
                    ->sum('amount'),
            ],
            'resales' => [
                'active_listings' => $collector->resales()->listed()->count(),
                'pending_listings' => $collector->resales()->pending()->count(),
                'sold_count' => $collector->resales()->where('status', 'sold')->count(),
            ],
            'engagement' => [
                'wishlist_count' => $collector->likes()->count(),
                'following_count' => $collector->following()->count(),
            ],
        ];
        
        return response()->json($stats);
    }
}
