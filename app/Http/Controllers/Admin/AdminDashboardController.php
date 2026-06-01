<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Resale;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        // Time periods
        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $thisYearStart = $now->copy()->startOfYear();

        // Primary Statistics
        $stats = [
            'total_users' => User::count(),
            'pending_artists' => User::where('role', 'artist')->where('is_approved', false)->count(),
            'total_artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->count(),
            'pending_artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'pending')->count(),
            'pending_resales' => Resale::where('status', 'pending')->count(),
            'total_transactions' => Transaction::count(),
            'total_sales' => Transaction::where('status', 'completed')->sum('amount'),
            'monthly_sales' => Transaction::where('status', 'completed')
                ->whereMonth('completed_at', $now->month)
                ->whereYear('completed_at', $now->year)
                ->sum('amount'),
            'last_month_sales' => Transaction::where('status', 'completed')
                ->whereBetween('completed_at', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount'),
            'yearly_sales' => Transaction::where('status', 'completed')
                ->whereYear('completed_at', $now->year)
                ->sum('amount'),
        ];

        // Secondary Statistics
        $secondaryStats = [
            'total_artists' => User::where('role', 'artist')->where('is_approved', true)->count(),
            'total_collectors' => User::where('role', 'collector')->count(),
            'approved_artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'approved')->count(),
            'sold_artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'sold')->count(),
            'active_resales' => Resale::where('status', 'listed')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'average_order_value' => Transaction::where('status', 'completed')->avg('amount') ?? 0,
        ];

        // Engagement Metrics
        $engagementStats = [
            'total_likes' => \App\Models\Like::count(),
            'total_wishlists' => \App\Models\Wishlist::count(),
            'active_carts' => \App\Models\Cart::whereHas('items')->count(),
            'total_views' => Artwork::withoutGlobalScope(ApprovedScope::class)->sum('views_count') ?? 0,
            'new_users_this_month' => User::where('created_at', '>=', $thisMonthStart)->count(),
        ];

        // Revenue Analytics
        $revenueThisMonth = Transaction::where('status', 'completed')
            ->whereBetween('completed_at', [$thisMonthStart, $now])
            ->sum('amount');
        $revenueLastMonth = Transaction::where('status', 'completed')
            ->whereBetween('completed_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');
        $revenueGrowth = $revenueLastMonth > 0 
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 
            : 0;

        // Top Categories
        $topCategories = Category::withCount('artworks')
            ->orderBy('artworks_count', 'desc')
            ->take(5)
            ->get();

        // Top Artists by Sales
        $topArtists = User::where('role', 'artist')
            ->where('is_approved', true)
            ->withCount(['artworks' => function($query) {
                $query->withoutGlobalScope(ApprovedScope::class);
            }])
            ->with(['sales' => function($query) use ($thisYearStart) {
                $query->where('status', 'completed')
                    ->whereYear('completed_at', $thisYearStart->year);
            }])
            ->get()
            ->sortByDesc(function($artist) {
                return $artist->sales->sum('seller_earnings');
            })
            ->take(5);

        // Recent Activity
        $recentUsers = User::latest()->take(5)->get();
        $recentArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)->with('artist')->latest()->take(5)->get();
        $recentTransactions = Transaction::with(['buyer', 'seller', 'artwork'])->latest()->take(5)->get();
        $recentResales = Resale::with(['artwork', 'owner'])->latest()->take(5)->get();

        // Pending Items Requiring Attention
        $pendingItems = [
            'artists' => User::where('role', 'artist')->where('is_approved', false)->latest()->take(3)->get(),
            'artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'pending')->with('artist')->latest()->take(3)->get(),
            'resales' => Resale::where('status', 'pending')->with(['artwork', 'owner'])->latest()->take(3)->get(),
            'payment_proofs' => \App\Models\PaymentProof::where('status', 'pending')->with('order')->latest()->take(3)->get(),
        ];

        // Sales Data for Charts (Last 6 months)
        $salesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $salesChartData[] = [
                'month' => $month->format('M Y'),
                'sales' => Transaction::where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->sum('amount'),
                'transactions' => Transaction::where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->count(),
            ];
        }

        // System Health
        $systemHealth = [
            'disk_usage' => $this->getDiskUsage(),
            'cache_status' => Cache::getStore() instanceof \Illuminate\Cache\FileStore ? 'File' : 'Database',
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'secondaryStats',
            'engagementStats',
            'revenueThisMonth',
            'revenueLastMonth',
            'revenueGrowth',
            'topCategories',
            'topArtists',
            'recentUsers',
            'recentArtworks',
            'recentTransactions',
            'recentResales',
            'pendingItems',
            'salesChartData',
            'systemHealth'
        ));
    }

    /**
     * Get disk usage percentage
     */
    private function getDiskUsage()
    {
        $free = disk_free_space(storage_path());
        $total = disk_total_space(storage_path());
        if ($total > 0) {
            return [
                'free' => $this->formatBytes($free),
                'total' => $this->formatBytes($total),
                'percentage' => round((($total - $free) / $total) * 100, 2),
            ];
        }
        return ['free' => 'N/A', 'total' => 'N/A', 'percentage' => 0];
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
