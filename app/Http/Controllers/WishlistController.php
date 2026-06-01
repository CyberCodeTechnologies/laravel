<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlistItems = Auth::user()->wishlistItems()->with('artwork.artist')->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add an artwork to wishlist.
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        $request->validate([
            'artwork_id' => 'required|integer|exists:artworks,id',
        ]);

        $artwork = Artwork::findOrFail($request->artwork_id);

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('artwork_id', $artwork->id)
            ->first();

        if ($existingWishlist) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This artwork is already in your wishlist.',
                ], 200);
            }
            return back()->with('info', 'This artwork is already in your wishlist.');
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'artwork_id' => $artwork->id,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artwork added to wishlist successfully!',
                'wishlist_count' => Auth::user()->wishlistItems()->count(),
            ]);
        }

        return back()->with('success', 'Artwork added to wishlist successfully!');
    }

    /**
     * Remove an artwork from wishlist.
     */
    public function remove(Wishlist $wishlist)
    {
        if (!Auth::check() || $wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Artwork removed from wishlist.');
    }

    /**
     * Toggle artwork in wishlist (add if not present, remove if present).
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        $request->validate([
            'artwork_id' => 'required|integer|exists:artworks,id',
        ]);

        $artwork = Artwork::findOrFail($request->artwork_id);
        
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('artwork_id', $artwork->id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $message = 'Artwork removed from wishlist';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'artwork_id' => $artwork->id,
            ]);
            $message = 'Artwork added to wishlist';
            $inWishlist = true;
        }

        $wishlistCount = Auth::user()->wishlistItems()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $inWishlist,
            'wishlist_count' => $wishlistCount,
        ]);
    }
}
