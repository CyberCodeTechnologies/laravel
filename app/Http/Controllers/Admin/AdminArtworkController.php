<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Category;
use App\Repositories\Interfaces\ArtworkRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArtworkController extends Controller
{
    protected ArtworkRepositoryInterface $artworkRepository;

    public function __construct(ArtworkRepositoryInterface $artworkRepository)
    {
        $this->artworkRepository = $artworkRepository;
    }

    /**
     * Display all artworks.
     */
    public function artworks(Request $request)
    {
        $query = Artwork::withoutGlobalScopes();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by artist
        if ($request->filled('artist')) {
            $query->where('artist_id', $request->artist);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $artworks = $query->with(['artist', 'category'])->latest()->paginate(20);

        $categories = Category::all();

        return view('admin.artworks.index', compact('artworks', 'categories'));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function createArtwork()
    {
        $categories = Category::all();
        $artists = \App\Models\User::where('role', 'artist')->where('is_approved', true)->get();

        return view('admin.artworks.create', compact('categories', 'artists'));
    }

    /**
     * Store a newly created artwork.
     */
    public function storeArtwork(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'medium' => 'required|string',
            'dimensions' => 'nullable|string',
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('artworks', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = 'approved';

        Artwork::create($validated);

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork created successfully.');
    }

    /**
     * Display pending artworks.
     */
    public function pendingArtworks()
    {
        $artworks = $this->artworkRepository->getPending()->paginate(20);

        return view('admin.artworks.pending', compact('artworks'));
    }

    /**
     * Approve artwork.
     */
    public function approveArtwork(Artwork $artwork)
    {
        $this->artworkRepository->toggleApproval($artwork->id);

        return redirect()->back()
            ->with('success', 'Artwork approved successfully.');
    }

    /**
     * Reject artwork.
     */
    public function rejectArtwork(Artwork $artwork)
    {
        $artwork->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Artwork rejected successfully.');
    }

    /**
     * Show the form for editing artwork.
     */
    public function editArtwork(Artwork $artwork)
    {
        $categories = Category::all();
        $artists = \App\Models\User::where('role', 'artist')->where('is_approved', true)->get();

        return view('admin.artworks.edit', compact('artwork', 'categories', 'artists'));
    }

    /**
     * Show artwork details.
     */
    public function showArtwork(Artwork $artwork)
    {
        $artwork->load(['artist', 'category', 'transactions']);

        return view('admin.artworks.show', compact('artwork'));
    }

    /**
     * Update artwork.
     */
    public function updateArtwork(Request $request, Artwork $artwork)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'medium' => 'required|string',
            'dimensions' => 'nullable|string',
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }
            $validated['image'] = $request->file('image')->store('artworks', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        $artwork->update($validated);

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork updated successfully.');
    }

    /**
     * Delete artwork.
     */
    public function deleteArtwork(Artwork $artwork)
    {
        if ($artwork->image) {
            Storage::disk('public')->delete($artwork->image);
        }

        $artwork->delete();

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork deleted successfully.');
    }
}
