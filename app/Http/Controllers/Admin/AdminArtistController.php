<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class AdminArtistController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display all artists.
     */
    public function artists(Request $request)
    {
        $query = User::where('role', 'artist');

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $artists = $query->withCount('artworks')->latest()->paginate(20);

        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Display pending artists.
     */
    public function pendingArtists()
    {
        $artists = $this->userRepository->getPending()
            ->where('role', 'artist')
            ->paginate(20);

        return view('admin.artists.pending', compact('artists'));
    }

    /**
     * Approve artist.
     */
    public function approveArtist(User $artist)
    {
        $this->userRepository->toggleApproval($artist->id);

        return redirect()->back()
            ->with('success', 'Artist approved successfully.');
    }

    /**
     * Reject artist.
     */
    public function rejectArtist(User $artist)
    {
        $artist->update(['is_approved' => false]);

        return redirect()->back()
            ->with('success', 'Artist rejected successfully.');
    }

    /**
     * Show the form for editing artist.
     */
    public function editArtist(User $artist)
    {
        $artist->load('artworks');

        return view('admin.artists.edit', compact('artist'));
    }

    /**
     * Update artist.
     */
    public function updateArtist(Request $request, User $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $artist->id,
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'social_links' => 'nullable|array',
        ]);

        $artist->update($validated);

        return redirect()->route('admin.artists')
            ->with('success', 'Artist updated successfully.');
    }

    /**
     * Delete artist.
     */
    public function deleteArtist(User $artist)
    {
        $artist->delete();

        return redirect()->route('admin.artists')
            ->with('success', 'Artist deleted successfully.');
    }
}
