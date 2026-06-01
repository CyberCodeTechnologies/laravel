<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Resale;
use App\Models\PaymentProof;
use App\Models\Like;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Scopes\ApprovedScope;
use App\Services\SystemHealthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends ApiController
{
    protected SystemHealthService $healthService;

    public function __construct(SystemHealthService $healthService)
    {
        $this->healthService = $healthService;
    }

    /**
     * Get admin dashboard analytics
     */
    public function dashboard(): JsonResponse
    {
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
            'total_sales' => (float) Transaction::where('status', 'completed')->sum('amount'),
            'monthly_sales' => (float) Transaction::where('status', 'completed')
                ->whereMonth('completed_at', $now->month)
                ->whereYear('completed_at', $now->year)
                ->sum('amount'),
            'last_month_sales' => (float) Transaction::where('status', 'completed')
                ->whereBetween('completed_at', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount'),
            'yearly_sales' => (float) Transaction::where('status', 'completed')
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
            'average_order_value' => (float) Transaction::where('status', 'completed')->avg('amount') ?? 0,
        ];

        // Engagement Metrics
        $engagementStats = [
            'total_likes' => Like::count(),
            'total_wishlists' => Wishlist::count(),
            'active_carts' => Cart::whereHas('items')->count(),
            'total_views' => (int) Artwork::withoutGlobalScope(ApprovedScope::class)->sum('views_count') ?? 0,
            'new_users_this_month' => User::where('created_at', '>=', $thisMonthStart)->count(),
        ];

        // Revenue Analytics
        $revenueThisMonth = (float) Transaction::where('status', 'completed')
            ->whereBetween('completed_at', [$thisMonthStart, $now])
            ->sum('amount');
        $revenueLastMonth = (float) Transaction::where('status', 'completed')
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
            ->take(5)
            ->values();

        // Recent Activity (Simplified for API)
        $recentActivity = [
            'users' => User::latest()->take(5)->get(),
            'artworks' => Artwork::withoutGlobalScope(ApprovedScope::class)->with('artist:id,name,email')->latest()->take(5)->get(),
            'transactions' => Transaction::with(['buyer:id,name', 'seller:id,name', 'artwork:id,title'])->latest()->take(5)->get(),
        ];

        // Sales Data for Charts
        $salesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $salesChartData[] = [
                'month' => $month->format('M Y'),
                'sales' => (float) Transaction::where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->sum('amount'),
                'transactions' => Transaction::where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->count(),
            ];
        }

        return $this->success([
            'stats' => $stats,
            'secondary_stats' => $secondaryStats,
            'engagement_stats' => $engagementStats,
            'revenue_metrics' => [
                'this_month' => $revenueThisMonth,
                'last_month' => $revenueLastMonth,
                'growth_percentage' => round($revenueGrowth, 2),
            ],
            'top_categories' => $topCategories,
            'top_artists' => $topArtists,
            'recent_activity' => $recentActivity,
            'sales_chart' => $salesChartData,
            'system_health' => $this->healthService->getStatus(),
        ]);
    }
}
