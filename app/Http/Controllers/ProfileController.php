<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        
        // Get stats based on user role
        $stats = $this->getUserStats($user);
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);
        
        return view('profile.show', compact('user', 'stats', 'recentActivity'));
    }
    
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
        
        // Add artist-specific fields
        if ($user->isArtist()) {
            $rules['artist_statement'] = ['nullable', 'string', 'max:2000'];
            $rules['specialization'] = ['nullable', 'string', 'max:255'];
            $rules['education'] = ['nullable', 'string', 'max:2000'];
            $rules['exhibitions'] = ['nullable', 'string', 'max:2000'];
            $rules['awards'] = ['nullable', 'string', 'max:2000'];
        }
        
        $validated = $request->validate($rules);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $user->update($validated);
        
        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Update the user's avatar via AJAX.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
        
        $user = auth()->user();
        
        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        
        return response()->json([
            'success' => true,
            'avatar_url' => $user->avatar_url,
        ]);
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        return redirect()->route('profile')
            ->with('success', 'Password updated successfully.');
    }
    
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        $user = auth()->user();
        
        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Logout and delete user
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        $user->delete();
        
        return redirect()->route('home')
            ->with('success', 'Your account has been deleted.');
    }
    
    /**
     * Get user statistics based on role.
     */
    private function getUserStats($user)
    {
        $stats = [];
        
        if ($user->isArtist()) {
            $stats = [
                'artworks_created' => $user->artworks()->count(),
                'artworks_approved' => $user->artworks()->approved()->count(),
                'artworks_sold' => $user->artworks()->sold()->count(),
                'total_sales' => $user->sales()->completed()->sum('seller_earnings'),
                'total_views' => $user->artworks()->sum('views_count'),
                'followers_count' => $user->followers()->count(),
            ];
        } elseif ($user->isCollector()) {
            $stats = [
                'artworks_owned' => $user->ownerships()->where('is_current_owner', true)->count(),
                'total_purchases' => $user->purchases()->completed()->count(),
                'total_spent' => $user->purchases()->completed()->sum('amount'),
                'wishlist_count' => $user->likes()->count(),
                'following_count' => $user->following()->count(),
                'resales_active' => $user->resales()->listed()->count(),
            ];
        }
        
        return $stats;
    }
    
    /**
     * Get recent activity for the user.
     */
    private function getRecentActivity($user)
    {
        $activity = [];
        
        if ($user->isArtist()) {
            // Recent artworks
            $recentArtworks = $user->artworks()->latest()->take(4)->get();
            
            // Recent sales
            $recentSales = $user->sales()->completed()->with('buyer', 'artwork')->latest()->take(4)->get();
            
            $activity = [
                'artworks' => $recentArtworks,
                'sales' => $recentSales,
            ];
        } elseif ($user->isCollector()) {
            // Recent purchases
            $recentPurchases = $user->purchases()->completed()->with('artwork.artist')->latest()->take(4)->get();
            
            // Recent collection additions
            $recentCollection = $user->ownerships()
                ->where('is_current_owner', true)
                ->with('artwork.artist')
                ->latest()
                ->take(4)
                ->get();
            
            $activity = [
                'purchases' => $recentPurchases,
                'collection' => $recentCollection,
            ];
        }
        
        return $activity;
    }
}
