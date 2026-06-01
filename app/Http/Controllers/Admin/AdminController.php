<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Revenue calculations
        $revenueThisMonth = \App\Models\Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $revenueLastMonth = \App\Models\Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // Stats
        $stats = [
            'total_users' => \App\Models\User::count(),
            'pending_artists' => \App\Models\User::where('role', 'artist')->where('is_approved', false)->count(),
            'total_artworks' => \App\Models\Artwork::count(),
            'pending_artworks' => \App\Models\Artwork::where('status', 'pending')->count(),
            'pending_resales' => \App\Models\Resale::where('status', 'pending')->count(),
        ];

        // Engagement stats
        $engagementStats = [
            'new_users_this_month' => \App\Models\User::whereMonth('created_at', now()->month)->count(),
            'total_likes' => \App\Models\Like::count(),
            'total_wishlists' => \App\Models\Wishlist::count(),
            'active_carts' => \App\Models\Cart::count(),
            'total_views' => \App\Models\Artwork::sum('views_count') ?? 0,
        ];

        // Secondary stats
        $completedTransactions = \App\Models\Transaction::where('status', 'completed')->count();
        $totalTransactionAmount = \App\Models\Transaction::where('status', 'completed')->sum('amount');
        $averageOrderValue = $completedTransactions > 0 ? $totalTransactionAmount / $completedTransactions : 0;

        $secondaryStats = [
            'total_artists' => \App\Models\User::where('role', 'artist')->where('is_approved', true)->count(),
            'total_collectors' => \App\Models\User::where('role', 'collector')->count(),
            'active_resales' => \App\Models\Resale::where('status', 'listed')->count(),
            'completed_transactions' => $completedTransactions,
            'pending_transactions' => \App\Models\Transaction::where('status', 'pending')->count(),
            'approved_artworks' => \App\Models\Artwork::where('status', 'approved')->count(),
            'average_order_value' => $averageOrderValue,
        ];

        // Top categories
        $topCategories = \App\Models\Category::withCount('artworks')
            ->orderBy('artworks_count', 'desc')
            ->take(5)
            ->get();

        // Pending items
        $pendingItems = [
            'payment_proofs' => \App\Models\PaymentProof::where('status', 'pending')->get(),
        ];

        // Recent data
        $recentTransactions = \App\Models\Transaction::with(['buyer', 'artwork'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = \App\Models\User::latest()->take(5)->get();

        $recentArtworks = \App\Models\Artwork::with('artist')->latest()->take(5)->get();

        // Sales chart data (last 6 months)
        $salesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $salesChartData[] = [
                'month' => $month->format('M Y'),
                'sales' => \App\Models\Transaction::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('amount'),
                'transactions' => \App\Models\Transaction::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
            ];
        }

        // System health
        $systemHealth = [
            'disk_usage' => [
                'percentage' => 45, // Placeholder - implement actual disk usage check
            ],
            'cache_status' => app()['cache']->getDefaultDriver(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        return view('admin.dashboard', compact(
            'revenueThisMonth',
            'revenueGrowth',
            'stats',
            'engagementStats',
            'secondaryStats',
            'topCategories',
            'pendingItems',
            'recentTransactions',
            'recentUsers',
            'recentArtworks',
            'salesChartData',
            'systemHealth'
        ));
    }

    public function items(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Artworks & Items',
            'pageTitle' => 'All Artworks',
            'subtitle' => 'Manage your gallery inventory and artworks.',
            'breadcrumb' => [
                ['label' => 'Inventory'],
                ['label' => 'Artworks & Items'],
            ],
            'tabs' => ['All Items', 'Active', 'Inactive'],
            'filterChips' => ['All', 'Paintings', 'Sculptures', 'Prints', 'Low Stock'],
            'createLabel' => 'New Item',
            'tableHeaders' => ['Name', 'SKU', 'Rate', 'Stock', 'Status'],
            'tableRows' => [
                ['<a href="#">Sunset Over Jaipur</a>', 'ART-1089', '₹ 85,000', '1', '<span class="zoho-badge zoho-badge-success">In Stock</span>'],
                ['<a href="#">Monsoon Series #4</a>', 'ART-1090', '₹ 1,20,000', '1', '<span class="zoho-badge zoho-badge-success">In Stock</span>'],
                ['<a href="#">Abstract Blue</a>', 'ART-1042', '₹ 45,000', '0', '<span class="zoho-badge zoho-badge-danger">Out of Stock</span>'],
            ],
        ]);
    }

    public function sales(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Invoices',
            'pageTitle' => 'All Invoices',
            'subtitle' => 'Create and manage sales invoices.',
            'breadcrumb' => [
                ['label' => 'Sales'],
                ['label' => 'Invoices'],
            ],
            'tabs' => ['All', 'Draft', 'Sent', 'Overdue', 'Paid'],
            'filterChips' => ['All', 'This Month', 'Last Month', 'This Year'],
            'createLabel' => 'New Invoice',
            'tableHeaders' => ['Date', 'Invoice#', 'Customer', 'Amount', 'Status'],
            'tableRows' => [
                ['15 May 2026', '<a href="#">INV-00042</a>', 'Jane Collector', '₹ 1,25,000', '<span class="zoho-badge zoho-badge-warning">Overdue</span>'],
                ['10 May 2026', '<a href="#">INV-00041</a>', 'Art House Mumbai', '₹ 2,40,000', '<span class="zoho-badge zoho-badge-success">Paid</span>'],
                ['05 May 2026', '<a href="#">INV-00040</a>', 'Ravi Sharma', '₹ 65,000', '<span class="zoho-badge zoho-badge-info">Sent</span>'],
            ],
        ]);
    }

    public function customers(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Customers',
            'pageTitle' => 'All Customers',
            'subtitle' => 'Manage collectors, galleries, and buyers.',
            'breadcrumb' => [
                ['label' => 'Sales'],
                ['label' => 'Customers'],
            ],
            'tabs' => ['All', 'Active', 'Inactive'],
            'createLabel' => 'New Customer',
            'tableHeaders' => ['Name', 'Email', 'Phone', 'Receivables', 'Status'],
            'tableRows' => [
                ['<a href="#">Jane Collector</a>', 'jane@example.com', '+91 98765 43210', '₹ 1,25,000', '<span class="zoho-badge zoho-badge-success">Active</span>'],
                ['<a href="#">Art House Mumbai</a>', 'contact@arthouse.in', '+91 22 1234 5678', '₹ 0', '<span class="zoho-badge zoho-badge-success">Active</span>'],
            ],
        ]);
    }

    public function purchases(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Bills',
            'pageTitle' => 'All Bills',
            'subtitle' => 'Track vendor bills and expenses.',
            'breadcrumb' => [
                ['label' => 'Purchases'],
                ['label' => 'Bills'],
            ],
            'tabs' => ['All', 'Open', 'Overdue', 'Paid'],
            'createLabel' => 'New Bill',
            'tableHeaders' => ['Date', 'Bill#', 'Vendor', 'Amount', 'Status'],
            'tableRows' => [
                ['12 May 2026', '<a href="#">BILL-00018</a>', 'Frame Suppliers Co.', '₹ 8,400', '<span class="zoho-badge zoho-badge-info">Open</span>'],
            ],
        ]);
    }

    public function banking(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Banking',
            'pageTitle' => 'Banking Overview',
            'subtitle' => 'Connect accounts and reconcile transactions.',
            'breadcrumb' => [['label' => 'Banking']],
            'tabs' => ['Overview', 'Accounts', 'Reconciliation'],
            'createLabel' => 'Add Account',
            'tableHeaders' => ['Account', 'Bank', 'Balance', 'Last Synced'],
            'tableRows' => [
                ['<a href="#">HDFC Current A/C</a>', 'HDFC Bank', '₹ 12,45,800', 'Today'],
            ],
        ]);
    }

    public function reports(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Reports',
            'pageTitle' => 'Reports Centre',
            'subtitle' => 'Financial and operational reports for your gallery.',
            'breadcrumb' => [['label' => 'Reports']],
            'tabs' => ['Favourites', 'Business Overview', 'Sales', 'Purchases'],
            'filterChips' => ['Profit & Loss', 'Balance Sheet', 'Sales by Item', 'Aging Summary'],
            'tableHeaders' => ['Report Name', 'Category', 'Last Run'],
            'tableRows' => [
                ['<a href="#">Profit and Loss</a>', 'Business Overview', '14 May 2026'],
                ['<a href="#">Sales by Customer</a>', 'Sales', '10 May 2026'],
            ],
        ]);
    }

    public function documents(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Documents',
            'pageTitle' => 'All Documents',
            'subtitle' => 'Store and organise gallery documents.',
            'breadcrumb' => [['label' => 'Documents']],
            'createLabel' => 'Upload',
            'tableHeaders' => ['Name', 'Folder', 'Uploaded', 'Size'],
            'tableRows' => [
                ['<a href="#">Q1 Sales Report.pdf</a>', 'Reports', '13 May 2026', '2.4 MB'],
                ['<a href="#">Artist Agreement - Sharma.pdf</a>', 'Contracts', '01 May 2026', '890 KB'],
            ],
        ]);
    }

    public function settings(): View
    {
        return view('admin.module', [
            'moduleTitle' => 'Settings',
            'pageTitle' => 'All Settings',
            'subtitle' => 'Configure your organisation preferences.',
            'breadcrumb' => [['label' => 'Settings']],
            'showWidgets' => false,
            'tabs' => ['Organisation', 'Users', 'Taxes', 'Templates', 'Automation'],
            'filterChips' => ['Profile', 'Branding', 'Currencies', 'Opening Balances'],
        ]);
    }
}
