<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Resale;
use App\Models\Ownership;
use App\Models\Like;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * Reports dashboard with key metrics overview
     */
    public function dashboard()
    {
        // Time periods for comparison
        $now = now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $thisYear = $now->copy()->startOfYear();
        $lastYear = $now->copy()->subYear()->startOfYear();

        // Revenue metrics
        $revenueThisMonth = Transaction::where('status', 'completed')
            ->where('completed_at', '>=', $thisMonth)
            ->sum('amount');
        
        $revenueLastMonth = Transaction::where('status', 'completed')
            ->where('completed_at', '>=', $lastMonth)
            ->where('completed_at', '<', $thisMonth)
            ->sum('amount');

        $revenueThisYear = Transaction::where('status', 'completed')
            ->where('completed_at', '>=', $thisYear)
            ->sum('amount');

        $revenueLastYear = Transaction::where('status', 'completed')
            ->where('completed_at', '>=', $lastYear)
            ->where('completed_at', '<', $thisYear)
            ->sum('amount');

        // User metrics
        $totalUsers = User::count();
        $totalArtists = User::where('role', 'artist')->count();
        $approvedArtists = User::where('role', 'artist')->where('is_approved', true)->count();
        $totalCollectors = User::where('role', 'collector')->count();
        $newUsersThisMonth = User::where('created_at', '>=', $thisMonth)->count();
        $newUsersLastMonth = User::where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $thisMonth)->count();

        // Artwork metrics
        $totalArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)->count();
        $approvedArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'approved')->count();
        $pendingArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'pending')->count();
        $soldArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'sold')->count();
        $newArtworksThisMonth = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $thisMonth)->count();

        // Transaction metrics
        $totalTransactions = Transaction::count();
        $completedTransactions = Transaction::where('status', 'completed')->count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $transactionsThisMonth = Transaction::where('created_at', '>=', $thisMonth)->count();

        // Marketplace metrics
        $totalResales = Resale::count();
        $activeResales = Resale::where('status', 'listed')->count();
        $pendingResales = Resale::where('status', 'pending')->count();
        $soldResales = Resale::where('status', 'sold')->count();

        // Engagement metrics
        $totalLikes = Like::count();
        $totalWishlists = Wishlist::count();
        $activeCarts = Cart::whereHas('items')->count();
        $averageOrderValue = Transaction::where('status', 'completed')->avg('amount');

        // Top performing categories
        $topCategories = Category::withCount(['artworks'])
            ->orderBy('artworks_count', 'desc')
            ->take(5)
            ->get();

        // Recent activity
        $recentTransactions = Transaction::with(['buyer', 'seller', 'artwork'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.reports.dashboard', compact([
            'revenueThisMonth', 'revenueLastMonth', 'revenueThisYear', 'revenueLastYear',
            'totalUsers', 'totalArtists', 'approvedArtists', 'totalCollectors', 'newUsersThisMonth', 'newUsersLastMonth',
            'totalArtworks', 'approvedArtworks', 'pendingArtworks', 'soldArtworks', 'newArtworksThisMonth',
            'totalTransactions', 'completedTransactions', 'pendingTransactions', 'transactionsThisMonth',
            'totalResales', 'activeResales', 'pendingResales', 'soldResales',
            'totalLikes', 'totalWishlists', 'activeCarts', 'averageOrderValue',
            'topCategories', 'recentTransactions', 'recentUsers'
        ]));
    }

    /**
     * Sales & Revenue reports
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, quarter, year
        $startDate = $this->getStartDate($period);
        $endDate = now();

        // Revenue trends
        $revenueData = Transaction::where('status', 'completed')
            ->where('completed_at', '>=', $startDate)
            ->where('completed_at', '<=', $endDate)
            ->selectRaw('DATE(completed_at) as date, SUM(amount) as revenue, COUNT(*) as transactions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sales by category
        $salesByCategory = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->where('transactions.completed_at', '<=', $endDate)
            ->join('artworks', 'transactions.artwork_id', '=', 'artworks.id')
            ->join('categories', 'artworks.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(transactions.amount) as revenue, COUNT(*) as sales')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Top selling artworks
        $topArtworks = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->join('artworks', 'transactions.artwork_id', '=', 'artworks.id')
            ->selectRaw('artworks.title, artworks.id, SUM(transactions.amount) as revenue, COUNT(*) as sales')
            ->groupBy('artworks.id', 'artworks.title')
            ->orderBy('sales', 'desc')
            ->take(10)
            ->get();

        // Revenue by payment method
        $revenueByPaymentMethod = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->selectRaw('payment_method, SUM(amount) as revenue, COUNT(*) as transactions')
            ->groupBy('payment_method')
            ->orderBy('revenue', 'desc')
            ->get();

        // Primary vs Resale sales
        $primarySales = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->where('transactions.type', 'primary_sale')
            ->sum('amount');

        $resaleSales = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->where('transactions.type', 'resale')
            ->sum('amount');

        // Average transaction value
        $avgTransactionValue = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->avg('amount');

        // Conversion rates
        $totalViews = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $startDate)
            ->sum('views_count');
        
        $totalSales = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->count();

        $conversionRate = $totalViews > 0 ? ($totalSales / $totalViews) * 100 : 0;

        return view('admin.reports.sales', compact([
            'period', 'revenueData', 'salesByCategory', 'topArtworks', 
            'revenueByPaymentMethod', 'primarySales', 'resaleSales', 
            'avgTransactionValue', 'conversionRate'
        ]));
    }

    /**
     * Users & Artists reports
     */
    public function users(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // User registration trends
        $userRegistrations = User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as users')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Users by role
        $usersByRole = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();

        // Artist statistics
        $totalArtists = User::where('role', 'artist')->count();
        $approvedArtists = User::where('role', 'artist')->where('is_approved', true)->count();
        $pendingArtists = User::where('role', 'artist')->where('is_approved', false)->count();
        $newArtistsThisPeriod = User::where('role', 'artist')
            ->where('created_at', '>=', $startDate)
            ->count();
        
        // Collector statistics
        $totalCollectors = User::where('role', 'collector')->count();

        // Top artists by sales
        $topArtistsBySales = Transaction::where('transactions.status', 'completed')
            ->join('users', 'transactions.seller_id', '=', 'users.id')
            ->where('users.role', 'artist')
            ->selectRaw('users.name, users.id, SUM(transactions.amount) as revenue, COUNT(*) as sales')
            ->groupBy('users.id', 'users.name')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // Top artists by artworks
        $topArtistsByArtworks = User::where('role', 'artist')
            ->withCount(['artworks'])
            ->orderBy('artworks_count', 'desc')
            ->take(10)
            ->get();

        // User activity metrics (using updated_at as proxy for activity)
        $activeUsers = User::where('updated_at', '>=', $startDate)->count();
        $inactiveUsers = User::where('updated_at', '<', $startDate)
            ->count();

        // Geographic distribution (if location data available)
        $usersByLocation = User::whereNotNull('location')
            ->selectRaw('location, COUNT(*) as count')
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // User engagement metrics
        $avgArtworksPerArtist = $totalArtists > 0 ? 
            Artwork::withoutGlobalScope(ApprovedScope::class)
                ->join('users', 'artworks.artist_id', '=', 'users.id')
                ->where('users.role', 'artist')
                ->count() / $totalArtists : 0;

        return view('admin.reports.users', compact([
            'period', 'userRegistrations', 'usersByRole', 
            'totalArtists', 'approvedArtists', 'pendingArtists', 'newArtistsThisPeriod',
            'totalCollectors',
            'topArtistsBySales', 'topArtistsByArtworks', 'activeUsers', 'inactiveUsers',
            'usersByLocation', 'avgArtworksPerArtist'
        ]));
    }

    /**
     * Artworks & Inventory reports
     */
    public function artworks(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Artwork creation trends
        $artworkCreations = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as artworks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Artworks by status
        $artworksByStatus = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Artworks by category
        $artworksByCategory = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->join('categories', 'artworks.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        // Most viewed artworks
        $mostViewedArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->with(['artist', 'category'])
            ->get();

        // Most liked artworks
        $mostLikedArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->withCount(['likes'])
            ->orderBy('likes_count', 'desc')
            ->take(10)
            ->with(['artist', 'category'])
            ->get();

        // Price distribution
        $priceRanges = [
            '0-100' => [0, 100],
            '100-500' => [100, 500],
            '500-1000' => [500, 1000],
            '1000-5000' => [1000, 5000],
            '5000+' => [5000, 999999]
        ];

        $priceDistribution = [];
        foreach ($priceRanges as $label => [$min, $max]) {
            $count = Artwork::withoutGlobalScope(ApprovedScope::class)
                ->where('price', '>=', $min)
                ->where('price', '<', $max === 999999 ? 999999 : $max)
                ->count();
            $priceDistribution[$label] = $count;
        }

        // Medium distribution
        $mediumDistribution = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->selectRaw('medium, COUNT(*) as count')
            ->whereNotNull('medium')
            ->groupBy('medium')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Artwork statistics
        $totalArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)->count();
        $approvedArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'approved')->count();
        $pendingArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'pending')->count();
        $soldArtworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'sold')->count();
        $newArtworksThisMonth = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Inventory metrics
        $totalInventoryValue = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'approved')
            ->sum('price');

        $avgPrice = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'approved')
            ->avg('price');

        return view('admin.reports.artworks', compact([
            'period', 'artworkCreations', 'artworksByStatus', 'artworksByCategory',
            'mostViewedArtworks', 'mostLikedArtworks', 'priceDistribution', 
            'mediumDistribution', 'totalInventoryValue', 'avgPrice',
            'totalArtworks', 'approvedArtworks', 'pendingArtworks', 'soldArtworks', 'newArtworksThisMonth'
        ]));
    }

    /**
     * Marketplace Activity reports
     */
    public function marketplace(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Resale listing trends
        $resaleListings = Resale::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as listings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Resales by status
        $resalesByStatus = Resale::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Marketplace activity
        $totalListings = Resale::count();
        $activeListings = Resale::where('status', 'listed')->count();
        $soldListings = Resale::where('status', 'sold')->count();
        $pendingListings = Resale::where('status', 'pending')->count();

        // Average listing price (handle missing column gracefully)
        try {
            $avgListingPrice = Resale::avg('asking_price');
            $avgSoldPrice = Resale::where('status', 'sold')->avg('asking_price');
        } catch (\Illuminate\Database\QueryException $e) {
            // If asking_price column doesn't exist, set default values
            $avgListingPrice = 0;
            $avgSoldPrice = 0;
        }

        // Time to sell analysis
        $timeToSell = Resale::where('status', 'sold')
            ->whereNotNull('sold_at')
            ->whereNotNull('listed_at')
            ->selectRaw('AVG(DATEDIFF(sold_at, listed_at)) as avg_days')
            ->value('avg_days');

        // Top reselling artists
        $topResellingArtists = Resale::join('users', 'resales.owner_id', '=', 'users.id')
            ->selectRaw('users.name, COUNT(resales.id) as listings, SUM(CASE WHEN resales.status = "sold" THEN 1 ELSE 0 END) as sold')
            ->groupBy('users.id', 'users.name')
            ->orderBy('listings', 'desc')
            ->take(10)
            ->get();

        // Most resold artworks
        $mostResoldArtworks = Resale::join('artworks', 'resales.artwork_id', '=', 'artworks.id')
            ->selectRaw('artworks.title, COUNT(resales.id) as resale_count')
            ->groupBy('artworks.id', 'artworks.title')
            ->orderBy('resale_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.marketplace', compact([
            'period', 'resaleListings', 'resalesByStatus',
            'totalListings', 'activeListings', 'soldListings', 'pendingListings',
            'avgListingPrice', 'avgSoldPrice', 'timeToSell',
            'topResellingArtists', 'mostResoldArtworks'
        ]));
    }

    /**
     * Customer Analytics reports
     */
    public function customers(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Customer acquisition trends
        $customerAcquisition = User::where('role', 'collector')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as customers')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Customer segments
        $customerSegments = [
            'new_customers' => User::where('role', 'collector')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'returning_customers' => User::where('role', 'collector')
                ->where('created_at', '<', $startDate)
                ->whereHas('purchases', function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                })
                ->count(),
            'vip_customers' => User::where('role', 'collector')
                ->withCount(['purchases'])
                ->having('purchases_count', '>=', 5)
                ->count()
        ];

        // Top customers by spending
        $topCustomersBySpending = Transaction::where('transactions.status', 'completed')
            ->join('users', 'transactions.buyer_id', '=', 'users.id')
            ->where('users.role', 'collector')
            ->selectRaw('users.name, users.id, SUM(transactions.amount) as total_spent, COUNT(*) as purchases')
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get();

        // Customer lifetime value
        try {
            $customerLifetimeValue = Transaction::where('status', 'completed')
                ->join('users', 'transactions.buyer_id', '=', 'users.id')
                ->where('users.role', 'collector')
                ->groupBy('transactions.buyer_id')
                ->selectRaw('SUM(transactions.amount) as total_spent')
                ->pluck('total_spent')
                ->avg();
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback if status column doesn't exist
            $customerLifetimeValue = 0;
        }

        // Purchase frequency
        try {
            $avgPurchaseFrequency = Transaction::where('status', 'completed')
                ->join('users', 'transactions.buyer_id', '=', 'users.id')
                ->where('users.role', 'collector')
                ->groupBy('transactions.buyer_id')
                ->selectRaw('COUNT(*) as purchase_count')
                ->pluck('purchase_count')
                ->avg();
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback if status column doesn't exist
            $avgPurchaseFrequency = 0;
        }

        // Cart abandonment rate
        $totalCartsCreated = Cart::where('created_at', '>=', $startDate)->count();
        $completedPurchases = Transaction::where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', $startDate)
            ->count();
        
        $cartAbandonmentRate = $totalCartsCreated > 0 ? 
            (($totalCartsCreated - $completedPurchases) / $totalCartsCreated) * 100 : 0;

        // Wishlist analysis
        $wishlistToPurchaseRate = Wishlist::where('created_at', '>=', $startDate)
            ->whereHas('user', function ($query) {
                $query->where('role', 'collector');
            })
            ->count();

        return view('admin.reports.customers', compact([
            'period', 'customerAcquisition', 'customerSegments',
            'topCustomersBySpending', 'customerLifetimeValue', 'avgPurchaseFrequency',
            'cartAbandonmentRate', 'wishlistToPurchaseRate'
        ]));
    }

    /**
     * Financial Summary reports
     */
    public function financial(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Revenue breakdown
        $totalRevenue = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->sum('amount');

        $platformFees = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->sum('platform_fee');

        $artistEarnings = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->sum('seller_earnings');

        // Revenue by transaction type
        $revenueByType = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->selectRaw('type, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Revenue by currency
        $revenueByCurrency = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->selectRaw('currency, SUM(amount) as revenue, COUNT(*) as count')
            ->groupBy('currency')
            ->get();

        // Payout statistics
        $totalPayouts = Payout::where('processed_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingPayouts = Payout::where('status', 'pending')->sum('amount');

        // Monthly revenue trends
        $monthlyRevenue = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->selectRaw('YEAR(completed_at) as year, MONTH(completed_at) as month, SUM(amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Profit margins
        $profitMargin = $totalRevenue > 0 ? ($platformFees / $totalRevenue) * 100 : 0;

        // Average transaction value by period
        $avgTransactionValue = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->avg('amount');

        return view('admin.reports.financial', compact([
            'period', 'totalRevenue', 'platformFees', 'artistEarnings',
            'revenueByType', 'revenueByCurrency', 'totalPayouts', 'pendingPayouts',
            'monthlyRevenue', 'profitMargin', 'avgTransactionValue'
        ]));
    }

    /**
     * Export reports to CSV
     */
    public function export(Request $request, $type)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        switch ($type) {
            case 'sales':
                return $this->exportSalesData($startDate);
            case 'users':
                return $this->exportUsersData($startDate);
            case 'artworks':
                return $this->exportArtworksData($startDate);
            case 'financial':
                return $this->exportFinancialData($startDate);
            default:
                return back()->with('error', 'Invalid report type for export');
        }
    }

    /**
     * Helper method to get start date based on period
     */
    private function getStartDate($period)
    {
        return match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth()
        };
    }

    /**
     * Export sales data to CSV
     */
    private function exportSalesData($startDate)
    {
        $sales = Transaction::where('transactions.status', 'completed')
            ->where('transactions.completed_at', '>=', $startDate)
            ->with(['buyer', 'seller', 'artwork.category'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sales_report_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Transaction ID', 'Date', 'Buyer', 'Seller', 'Artwork', 'Category', 
                'Amount', 'Currency', 'Platform Fee', 'Artist Earnings', 'Type'
            ]);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->transaction_id,
                    $sale->completed_at->format('Y-m-d H:i:s'),
                    $sale->buyer->name,
                    $sale->seller->name,
                    $sale->artwork->title,
                    $sale->artwork->category->name,
                    $sale->amount,
                    $sale->currency,
                    $sale->platform_fee,
                    $sale->seller_earnings,
                    $sale->type
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users data to CSV
     */
    private function exportUsersData($startDate)
    {
        $users = User::withCount(['artworks', 'purchases', 'sales'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users_report_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Status', 'Approved', 
                'Artworks Count', 'Purchases Count', 'Sales Count', 
                'Total Revenue', 'Join Date', 'Last Login'
            ]);

            foreach ($users as $user) {
                $totalRevenue = Transaction::where('seller_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('amount');

                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->is_approved ? 'Yes' : 'No',
                    $user->artworks_count,
                    $user->purchases_count,
                    $user->sales_count,
                    $totalRevenue,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export artworks data to CSV
     */
    private function exportArtworksData($startDate)
    {
        $artworks = Artwork::withoutGlobalScope(ApprovedScope::class)
            ->with(['artist', 'category'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=artworks_report_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($artworks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Title', 'Artist', 'Category', 'Price', 'Currency', 
                'Status', 'Views', 'Likes', 'Created Date', 'Medium', 'Dimensions'
            ]);

            foreach ($artworks as $artwork) {
                fputcsv($file, [
                    $artwork->id,
                    $artwork->title,
                    $artwork->artist->name,
                    $artwork->category->name,
                    $artwork->price,
                    $artwork->currency,
                    $artwork->status,
                    $artwork->views_count,
                    $artwork->likes_count,
                    $artwork->created_at->format('Y-m-d H:i:s'),
                    $artwork->medium,
                    $artwork->dimensions
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export financial data to CSV
     */
    private function exportFinancialData($startDate)
    {
        $transactions = Transaction::where('transactions.completed_at', '>=', $startDate)
            ->where('transactions.status', 'completed')
            ->with(['buyer', 'seller'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=financial_report_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Transaction ID', 'Date', 'Buyer', 'Seller', 'Amount', 'Currency',
                'Platform Fee', 'Artist Earnings', 'Type', 'Payment Method'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_id,
                    $transaction->completed_at->format('Y-m-d H:i:s'),
                    $transaction->buyer->name,
                    $transaction->seller->name,
                    $transaction->amount,
                    $transaction->currency,
                    $transaction->platform_fee,
                    $transaction->seller_earnings,
                    $transaction->type,
                    $transaction->payment_method
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
