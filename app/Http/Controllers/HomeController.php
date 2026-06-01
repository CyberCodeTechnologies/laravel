<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured artworks from database
        $featuredArtworks = Artwork::approved()
            ->with('artist')
            ->latest()
            ->take(4)
            ->get();

        // Get featured artwork of the week
        $featuredOfWeek = $featuredArtworks->first();

        // Get featured artists from database
        $featuredArtists = User::where('role', 'artist')
            ->withCount(['artworks' => function ($query) {
                $query->approved();
            }])
            ->orderBy('artworks_count', 'desc')
            ->take(3)
            ->get();

        // Get trending artworks (most viewed)
        $trendingArtworks = Artwork::approved()
            ->with('artist')
            ->orderBy('views_count', 'desc')
            ->take(4)
            ->get();

        // Get hero artworks for slider
        $heroArtworks = Artwork::approved()
            ->with('artist')
            ->latest()
            ->take(3)
            ->get();

        // Get categories for collections
        $categories = Category::withCount('artworks')
            ->orderBy('artworks_count', 'desc')
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredArtworks',
            'featuredOfWeek',
            'featuredArtists',
            'trendingArtworks',
            'heroArtworks',
            'categories'
        ));
    }

    /**
     * Display the about page with dynamic stats.
     */
    public function about()
    {
        // Count verified artists (artists with is_verified = true)
        $verifiedArtistsCount = User::where('role', 'artist')
            ->where('is_verified', true)
            ->count();

        // Count total artworks listed (approved artworks)
        $artworksListedCount = Artwork::approved()->count();

        // Count happy collectors (users who have made purchases)
        $happyCollectorsCount = User::whereHas('purchases', function ($query) {
            $query->where('status', 'completed');
        })->count();

        // Calculate total art sales (sum of completed transactions in USD)
        $totalArtSales = Transaction::where('status', 'completed')
            ->sum('price_usd');

        // Format the stats for display
        $stats = [
            'verified_artists' => $this->formatStatNumber($verifiedArtistsCount) . '+',
            'artworks_listed' => $this->formatStatNumber($artworksListedCount) . '+',
            'happy_collectors' => $this->formatStatNumber($happyCollectorsCount) . '+',
            'art_sales' => '$' . $this->formatCurrency($totalArtSales) . '+'
        ];

        return view('about', compact('stats'));
    }

    /**
     * Format a number for stats display with K/M suffixes.
     */
    private function formatStatNumber($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 0) . 'K';
        } else {
            return number_format($number);
        }
    }

    /**
     * Format currency for stats display with K/M suffixes.
     */
    private function formatCurrency($amount)
    {
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, 0) . 'K';
        } else {
            return number_format($amount, 0);
        }
    }
}
