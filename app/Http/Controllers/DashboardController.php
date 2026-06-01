<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isArtist()) {
            return $this->artistDashboard();
        } elseif ($user->isCollector()) {
            return $this->collectorDashboard();
        }
        
        return view('dashboard.default');
    }
    
    /**
     * Display the admin dashboard.
     */
    private function adminDashboard(): View
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'pending_artists' => \App\Models\User::where('role', 'artist')->where('is_approved', false)->count(),
            'total_artworks' => \App\Models\Artwork::count(),
            'pending_artworks' => \App\Models\Artwork::where('status', 'pending')->count(),
            'pending_resales' => \App\Models\Resale::where('status', 'pending')->count(),
            'total_transactions' => \App\Models\Transaction::count(),
            'total_sales' => \App\Models\Transaction::where('status', 'completed')->sum('amount'),
        ];
        
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentArtworks = \App\Models\Artwork::latest()->take(5)->get();
        $recentTransactions = \App\Models\Transaction::latest()->take(5)->get();
        
        return view('dashboard.admin', compact('stats', 'recentUsers', 'recentArtworks', 'recentTransactions'));
    }
    
    /**
     * Display the artist dashboard.
     */
    private function artistDashboard(): View
    {
        $user = auth()->user();
        
        $stats = [
            'total_artworks' => $user->artworks()->count(),
            'approved_artworks' => $user->artworks()->where('status', 'approved')->count(),
            'sold_artworks' => $user->artworks()->where('status', 'sold')->count(),
            'total_sales' => $user->sales()->where('status', 'completed')->sum('seller_earnings'),
            'total_views' => $user->artworks()->sum('views_count'),
            'total_likes' => $user->artworks()->sum('likes_count'),
        ];
        
        $artworks = $user->artworks()->latest()->take(5)->get();
        $recentSales = $user->sales()->where('status', 'completed')->latest()->take(5)->get();

        return view('dashboard.artist', compact('stats', 'artworks', 'recentSales'));
    }
    
    /**
     * Display the collector dashboard.
     */
    private function collectorDashboard(): View
    {
        $user = auth()->user();
        
        $stats = [
            'total_purchases' => $user->purchases()->where('status', 'completed')->count(),
            'total_spent' => $user->purchases()->where('status', 'completed')->sum('amount'),
            'owned_artworks' => $user->currentArtworks()->count(),
            'listed_resales' => $user->resales()->where('status', 'listed')->count(),
            'total_likes' => $user->likes()->count(),
            'following' => $user->following()->count(),
        ];
        
        $recentPurchases = $user->purchases()->where('status', 'completed')->latest()->take(5)->get();
        $ownedArtworks = $user->currentArtworks()->latest()->take(5)->get();
        $likedArtworks = $user->likedArtworks()->latest()->take(5)->get();
        $resaleListings = $user->resales()->latest()->take(5)->get();
        
        return view('dashboard.collector', compact('stats', 'recentPurchases', 'ownedArtworks', 'likedArtworks', 'resaleListings'));
    }
    
    /**
     * Display the pending approval page.
     */
    public function pendingApproval(): View
    {
        return view('auth.pending-approval');
    }
}
