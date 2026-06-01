<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Models\Resale;
use App\Models\Faq;
use App\Models\GeneralSetting;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
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

    
    /**
     * Display all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['name', 'email', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display all artworks.
     */
    public function artworks(Request $request)
    {
        $query = Artwork::withoutGlobalScope(ApprovedScope::class)->with(['artist', 'category']);
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $artworks = $query->latest()->paginate(40);
        $categories = Category::orderBy('name')->get();
        
        return view('admin.artworks.index', compact('artworks', 'categories'));
    }

    /**
     * Approve an artwork.
     */
    public function approveArtwork($id)
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        
        // Update converted prices before approval
        $currencyService = app(\App\Services\CurrencyService::class);
        $artwork->update([
            'status' => 'approved',
            'approved_at' => now(),
            'currency' => $artwork->currency ?? 'USD',
            'price_usd' => ($artwork->currency ?? 'USD') === 'USD' 
                ? $artwork->price 
                : $currencyService->convert($artwork->price, $artwork->currency ?? 'USD', 'USD'),
            'price_mmk' => ($artwork->currency ?? 'USD') === 'MMK' 
                ? $artwork->price 
                : $currencyService->convert($artwork->price, $artwork->currency ?? 'USD', 'MMK'),
        ]);
        
        // Generate certificate for approved artwork
        if (!$artwork->certificate()->exists()) {
            $this->generateCertificate($artwork);
        }
        
        return back()->with('success', 'Artwork approved successfully.');
    }

    /**
     * Reject an artwork.
     */
    public function rejectArtwork(Request $request, $id)
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        $artwork->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        
        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork rejected successfully.');
    }

    /**
     * Display pending artworks.
     */
    public function pendingArtworks(Request $request)
    {
        $query = Artwork::withoutGlobalScope(ApprovedScope::class)->with(['artist', 'category'])
            ->where('status', 'pending');
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('artist', function ($artistQuery) use ($request) {
                      $artistQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $artworks = $query->latest()->paginate(40);
        $categories = Category::orderBy('name')->get();
        
        return view('admin.artworks.pending', compact('artworks', 'categories'));
    }

    /**
     * Display all artists.
     */
    public function artists(Request $request)
    {
        $query = User::where('role', 'artist');
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        $artists = $query->latest()->paginate(15);
        
        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Display pending artists.
     */
    public function pendingArtists()
    {
        $artists = User::where('role', 'artist')
            ->where('is_approved', false)
            ->latest()
            ->paginate(15);
        
        return view('admin.artists.pending', compact('artists'));
    }

    /**
     * Approve an artist.
     */
    public function approveArtist(User $artist)
    {
        $artist->update(['is_approved' => true]);
        
        // Notify artist about approval
        // This would be implemented with a notification system
        
        return back()->with('success', 'Artist approved successfully.');
    }

    /**
     * Reject an artist.
     */
    public function rejectArtist(Request $request, User $artist)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        // Notify artist about rejection
        // This would be implemented with a notification system
        
        return back()->with('success', 'Artist rejected successfully.');
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::withCount('artworks')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Show the form for editing a category.
     */
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);
        
        $data = $request->except('image');
        $data['slug'] = \Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            // Validate file type and size
            $image = $request->file('image');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) { // 2MB limit for category image
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            $data['image'] = $image->store('categories', 'public');
        }
        
        Category::create($data);
        
        return back()->with('success', 'Category created successfully.');
    }

    /**
     * Update a category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);
        
        $data = $request->except('image');
        
        if ($request->name !== $category->name) {
            $data['slug'] = \Str::slug($request->name);
        }
        
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            // Validate file type and size
            $image = $request->file('image');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) { // 2MB limit for category image
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            $data['image'] = $image->store('categories', 'public');
        }
        
        $category->update($data);
        
        return back()->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(Category $category)
    {
        if ($category->artworks()->exists()) {
            return back()->with('error', 'Cannot delete category that contains artworks.');
        }
        
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return back()->with('success', 'Category deleted successfully.');
    }

    /**
     * Display all transactions.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['buyer', 'seller', 'artwork']);
        
        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display all resales.
     */
    public function resales(Request $request)
    {
        $query = Resale::with(['artwork.artist', 'artwork.category', 'owner']);
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('artwork', function ($subQ) use ($request) {
                    $subQ->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $resales = $query->latest()->paginate(15);
        
        return view('admin.resales.index', compact('resales'));
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        return view('admin.settings.index');
    }

    // User Management Methods
    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,artist,collector'],
            'avatar' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
            'is_approved' => ['boolean'],
        ]);

        $isApproved = $request->boolean('is_approved', true);
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'is_approved' => $isApproved,
            'status' => $isApproved ? 'approved' : 'pending',
        ];

        // Handle avatar upload with enhanced security
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            
            // Enhanced file validation
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($image->getMimeType(), $allowedMimes)) {
                return back()->with('error', 'Invalid file type. Only JPEG, PNG, JPG, GIF, and WebP images are allowed.');
            }
            
            // Additional security checks
            if ($image->getSize() > 2 * 1024 * 1024) {
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            // Validate actual image content
            if (!@getimagesize($image->getPathname())) {
                return back()->with('error', 'Invalid image file. The file must be a valid image.');
            }
            
            // Generate unique filename to prevent overwrites
            $filename = uniqid('avatar_', true) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('avatars', $filename, 'public');
            
            if (!$path) {
                return back()->with('error', 'Failed to upload avatar. Please try again.');
            }
            
            $data['avatar'] = $path;
        }

        $user = User::create($data);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,artist,collector'],
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cover_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'participate_in_orders' => ['nullable', 'boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'artist_statement' => ['nullable', 'string', 'max:2000'],
            'education' => ['nullable', 'array'],
            'exhibitions' => ['nullable', 'array'],
            'awards' => ['nullable', 'array'],
            'press' => ['nullable', 'array'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'years_active' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isApproved = $request->has('is_approved');
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'is_approved' => $isApproved,
            'status' => $isApproved ? 'approved' : 'pending',
            'bio' => $request->bio,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'specialization' => $request->specialization,
            'artist_statement' => $request->artist_statement,
            'education' => $request->education,
            'exhibitions' => $request->exhibitions,
            'awards' => $request->awards,
            'press' => $request->press,
            'location' => $request->location,
            'is_verified' => $request->boolean('is_verified', false),
            'years_active' => $request->years_active,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle participate_in_orders checkbox (unchecked checkboxes don't send values)
        if ($user->role === 'artist' || $request->role === 'artist') {
            $data['participate_in_orders'] = $request->has('participate_in_orders');
        } else {
            // Non-artists should not participate in orders
            $data['participate_in_orders'] = false;
        }

        // Handle avatar upload with enhanced security
        if ($request->hasFile('avatar')) {
            \Log::info('Avatar file detected', ['file' => $request->file('avatar')->getClientOriginalName()]);
            
            $image = $request->file('avatar');
            
            // Enhanced file validation
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($image->getMimeType(), $allowedMimes)) {
                \Log::error('Invalid MIME type', ['mime' => $image->getMimeType()]);
                return back()->withInput()->with('error', 'Invalid file type. Only JPEG, PNG, JPG, GIF, and WebP images are allowed.');
            }
            
            // Additional security checks
            if ($image->getSize() > 2 * 1024 * 1024) {
                \Log::error('File size too large', ['size' => $image->getSize()]);
                return back()->withInput()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            // Validate actual image content
            if (!@getimagesize($image->getPathname())) {
                \Log::error('Invalid image content', ['file' => $image->getPathname()]);
                return back()->withInput()->with('error', 'Invalid image file. The file must be a valid image.');
            }
            
            // Delete old avatar if exists
            if ($user->avatar) {
                $oldPath = storage_path('app/public/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                Storage::disk('public')->delete($user->avatar);
                \Log::info('Old avatar deleted', ['old_avatar' => $user->avatar]);
            }
            
            // Generate unique filename to prevent overwrites
            $filename = uniqid('avatar_', true) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('avatars', $filename, 'public');
            
            if (!$path) {
                \Log::error('Failed to store avatar', ['filename' => $filename]);
                return back()->withInput()->with('error', 'Failed to upload avatar. Please try again.');
            }
            
            $data['avatar'] = $path;
            \Log::info('Avatar stored successfully', ['path' => $path]);
        }

        // Handle cover image upload with enhanced security
        if ($request->hasFile('cover_image')) {
            \Log::info('Cover image file detected', ['file' => $request->file('cover_image')->getClientOriginalName()]);
            
            $image = $request->file('cover_image');
            
            // Enhanced file validation
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($image->getMimeType(), $allowedMimes)) {
                \Log::error('Invalid MIME type for cover image', ['mime' => $image->getMimeType()]);
                return back()->withInput()->with('error', 'Invalid file type. Only JPEG, PNG, JPG, GIF, and WebP images are allowed.');
            }
            
            // Additional security checks
            if ($image->getSize() > 2 * 1024 * 1024) {
                \Log::error('Cover image file size too large', ['size' => $image->getSize()]);
                return back()->withInput()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            // Validate actual image content
            if (!@getimagesize($image->getPathname())) {
                \Log::error('Invalid cover image content', ['file' => $image->getPathname()]);
                return back()->withInput()->with('error', 'Invalid image file. The file must be a valid image.');
            }
            
            // Delete old cover image if exists
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
                \Log::info('Old cover image deleted', ['old_cover_image' => $user->cover_image]);
            }
            
            // Generate unique filename to prevent overwrites
            $filename = uniqid('cover_', true) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('covers', $filename, 'public');
            
            if (!$path) {
                \Log::error('Failed to store cover image', ['filename' => $filename]);
                return back()->withInput()->with('error', 'Failed to upload cover image. Please try again.');
            }
            
            $data['cover_image'] = $path;
            \Log::info('Cover image stored successfully', ['path' => $path]);
        }

        try {
            // Use forceFill to update guarded fields (role, is_active, status, is_approved)
            $user->forceFill($data);
            $user->save();
            \Log::info('User updated', ['user_id' => $user->id, 'avatar' => $user->avatar]);

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
                $user->save();
            }

            return redirect()->route('admin.users')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to update user', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            return back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function pendingUsers()
    {
        $users = User::where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.users.pending', compact('users'));
    }

    /**
     * Approve a user.
     */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'approved',
            'is_approved' => true,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User approved successfully.'
            ]);
        }

        return back()->with('success', 'User approved successfully.');
    }

    /**
     * Reject a user.
     */
    public function rejectUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'status' => 'rejected',
            'is_approved' => false,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User rejected successfully.'
            ]);
        }

        return redirect()->route('admin.users.pending')
            ->with('success', 'User rejected successfully.');
    }

    /**
     * Bulk approve users.
     */
    public function bulkApproveUsers(Request $request)
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $count = User::whereIn('id', $request->user_ids)->update([
            'status' => 'approved',
            'is_approved' => true,
        ]);

        return redirect()->route('admin.users')
            ->with('success', "Successfully approved {$count} user(s).");
    }

    /**
     * Bulk delete users.
     */
    public function bulkDeleteUsers(Request $request)
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        // Prevent deleting current user
        $userIds = collect($request->user_ids)->filter(function ($id) {
            return $id != auth()->id();
        });

        $count = User::whereIn('id', $userIds)->delete();

        return redirect()->route('admin.users')
            ->with('success', "Successfully deleted {$count} user(s).");
    }

    /**
     * Export users to CSV.
     */
    public function exportUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Joined', 'Last Login']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->is_approved ? 'Approved' : 'Pending',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Resend verification email to user.
     */
    public function resendVerificationEmail($id)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return back()->with('error', 'User email is already verified.');
        }

        // Send verification email
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent successfully.');
    }

    /**
     * Toggle user active status (suspend/activate).
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $user->update([
            'is_active' => !($user->is_active ?? true),
            'status' => !($user->is_active ?? true) ? 'suspended' : 'approved',
        ]);

        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Impersonate a user (login as that user).
     */
    public function impersonateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        // Store the original admin ID in session
        session(['impersonator_id' => auth()->id()]);

        // Login as the target user
        auth()->login($user);

        return redirect('/')->with('success', 'You are now impersonating ' . $user->name . '.');
    }

    /**
     * Stop impersonating and return to admin account.
     */
    public function stopImpersonation()
    {
        if (!session()->has('impersonator_id')) {
            return redirect('/')->with('error', 'No active impersonation session.');
        }

        $adminId = session()->get('impersonator_id');
        $admin = User::findOrFail($adminId);

        // Clear the impersonation session
        session()->forget('impersonator_id');

        // Login back as admin
        auth()->login($admin);

        return redirect()->route('admin.users')->with('success', 'You have returned to your admin account.');
    }

    // Artist Management Methods
    public function editArtist(User $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function updateArtist(Request $request, User $artist)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $artist->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'participate_in_orders' => ['nullable', 'boolean'],
        ]);

        $data = $request->except('avatar');

        // Handle participate_in_orders checkbox (unchecked checkboxes don't send values)
        $data['participate_in_orders'] = $request->has('participate_in_orders');

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($artist->avatar) {
                Storage::disk('public')->delete($artist->avatar);
            }
            
            // Validate file type and size
            $image = $request->file('avatar');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) { // 2MB limit
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            
            $data['avatar'] = $image->store('avatars', 'public');
        }

        $artist->update($data);

        return redirect()->route('admin.artists')
            ->with('success', 'Artist updated successfully.');
    }

    public function deleteArtist(User $artist)
    {
        if ($artist->artworks()->exists()) {
            return back()->with('error', 'Cannot delete artist that has artworks.');
        }

        $artist->delete();

        return redirect()->route('admin.artists')
            ->with('success', 'Artist deleted successfully.');
    }

    // Artwork Management Methods
    /**
     * Show the form for creating a new artwork.
     */
    public function createArtwork()
    {
        $categories = Category::orderBy('name')->get();
        $artists = User::where('role', 'artist')->where('is_approved', true)->orderBy('name')->get();
        return view('admin.artworks.create', compact('categories', 'artists'));
    }

    /**
     * Store a newly created artwork.
     */
    public function storeArtwork(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'artist_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'string', 'in:oil,acrylic,watercolor,digital,photography,sculpture,mixed_media,traditional'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable', 'in:pending,approved,sold'],
        ]);

        // Handle image uploads
        $images = [];
        foreach ($request->file('images') as $image) {
            // Validate file type and size
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                return back()->with('error', 'File size too large. Maximum size is 5MB.');
            }
            $path = $image->store('artworks', 'public');
            $images[] = $path;
        }

        $currency = 'USD';
        $currencyService = app(\App\Services\CurrencyService::class);
        
        $artwork = Artwork::create([
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'artist_id' => $request->artist_id,
            'category_id' => $request->category_id,
            'medium' => $request->medium,
            'dimensions' => $request->dimensions,
            'price' => $request->price,
            'year' => $request->year,
            'images' => $images,
            'status' => $request->status ?? 'pending',
            'currency' => $currency,
            'price_usd' => $request->price,
            'price_mmk' => $currencyService->convert($request->price, $currency, 'MMK'),
            'stock' => 1,
            'weight' => null,
            'is_digital' => false,
            'is_featured' => false,
        ]);

        // Generate certificate if approved
        if ($artwork->status === 'approved') {
            $this->generateCertificate($artwork);
        }

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork created successfully.');
    }

    public function editArtwork($id)
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $artists = User::where('role', 'artist')->where('is_approved', true)->orderBy('name')->get();
        return view('admin.artworks.edit', compact('artwork', 'categories', 'artists'));
    }

    public function updateArtwork(Request $request, $id)
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'artist_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'medium' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'currency' => ['nullable', 'in:USD,MMK'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_digital' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:pending,approved,rejected,sold'],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        $data = $request->except(['images']);

        // Handle checkbox fields
        $data['is_digital'] = $request->has('is_digital');
        $data['is_featured'] = $request->has('is_featured');

        // Update slug if title changed
        if ($request->title !== $artwork->title) {
            $data['slug'] = \Str::slug($request->title) . '-' . uniqid();
        }

        // Update converted prices if price or currency changed
        if ($request->price != $artwork->price || ($request->currency && $request->currency != $artwork->currency)) {
            $data['currency'] = $request->currency ?? $artwork->currency;
            $currencyService = app(\App\Services\CurrencyService::class);
            $data['price_usd'] = $data['currency'] === 'USD' 
                ? $request->price 
                : $currencyService->convert($request->price, $data['currency'], 'USD');
            $data['price_mmk'] = $data['currency'] === 'MMK' 
                ? $request->price 
                : $currencyService->convert($request->price, $data['currency'], 'MMK');
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($artwork->images && is_array($artwork->images)) {
                foreach ($artwork->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Upload new images
            $images = [];
            foreach ($request->file('images') as $image) {
                // Validate file type and size
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
                }
                if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                    return back()->with('error', 'File size too large. Maximum size is 5MB.');
                }
                $path = $image->store('artworks', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        $artwork->update($data);

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork updated successfully.');
    }

    public function deleteArtwork($id)
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        
        // Delete images if they exist
        if ($artwork->images && is_array($artwork->images)) {
            foreach ($artwork->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $artwork->delete();

        return redirect()->route('admin.artworks')
            ->with('success', 'Artwork deleted successfully.');
    }

    // Marketplace Management Methods
    public function marketplace(Request $request)
    {
        $query = Resale::with(['artwork.artist', 'artwork.category', 'owner']);
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('artwork', function ($subQ) use ($request) {
                    $subQ->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $resales = $query->latest()->paginate(15);
        
        return view('admin.marketplace.index', compact('resales'));
    }

    public function marketplaceListings()
    {
        $resales = Resale::with(['artwork.artist', 'artwork.category', 'owner'])
            ->where('status', 'listed')
            ->latest()
            ->paginate(15);

        return view('admin.marketplace.listings', compact('resales'));
    }

    public function pendingMarketplace()
    {
        $resales = Resale::with(['artwork.artist', 'artwork.category', 'owner'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.marketplace.pending', compact('resales'));
    }

    public function approveResale(Resale $resale)
    {
        $resale->update([
            'status' => 'listed',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Resale listing approved successfully.');
    }

    public function rejectResale(Request $request, Resale $resale)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $resale->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', 'Resale listing rejected successfully.');
    }

    // Support Management Methods
    public function support()
    {
        $stats = [
            'total' => \App\Models\ContactMessage::count(),
            'pending' => \App\Models\ContactMessage::where('status', 'pending')->count(),
            'responded' => \App\Models\ContactMessage::where('status', 'responded')->count(),
            'resolved' => \App\Models\ContactMessage::where('status', 'resolved')->count(),
            'faq_count' => Faq::count(),
            'unread' => \App\Models\ContactMessage::where('is_read', false)->count(),
        ];

        $recentContacts = \App\Models\ContactMessage::latest()->take(5)->get();

        return view('admin.support.index', compact('stats', 'recentContacts'));
    }

    public function supportContacts(Request $request)
    {
        return view('admin.support.contacts');
    }

    public function showContact(\App\Models\ContactMessage $contact)
    {
        $contact->markAsRead();

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.support.partials.contact-detail', compact('contact'));
        }

        return view('admin.support.show', compact('contact'));
    }

    public function resolveContact(Request $request, \App\Models\ContactMessage $contact)
    {
        $contact->update([
            'status' => 'resolved',
            'is_read' => true,
            'admin_notes' => $request->input('admin_notes', $contact->admin_notes),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Contact marked as resolved.');
    }

    public function respondContact(Request $request, \App\Models\ContactMessage $contact)
    {
        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        $contact->update([
            'status' => 'responded',
            'is_read' => true,
            'admin_notes' => $validated['response'],
            'responded_at' => now(),
        ]);

        return redirect()
            ->route('admin.support.contacts.show', $contact)
            ->with('success', 'Response saved. Follow up with the customer via email if needed.');
    }

    public function deleteContact(\App\Models\ContactMessage $contact)
    {
        $contact->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.support.contacts')
            ->with('success', 'Contact message deleted.');
    }

    public function markContactsRead(Request $request)
    {
        $ids = $request->input('ids', []);

        if (is_array($ids) && count($ids) > 0) {
            \App\Models\ContactMessage::whereIn('id', $ids)->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Export contact messages as CSV (applies optional filters: search, status, date_filter)
     */
    public function exportContacts(Request $request)
    {
        $query = \App\Models\ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('date_filter')) {
            $query->whereDate('created_at', $request->get('date_filter'));
        }

        $filename = 'contacts-export-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Subject', 'Message', 'Status', 'Is Read', 'Created At']);

            $query->orderBy('created_at', 'desc')->chunk(200, function ($contacts) use ($handle) {
                foreach ($contacts as $c) {
                    fputcsv($handle, [
                        $c->id,
                        $c->name,
                        $c->email,
                        $c->subject,
                        $c->message,
                        $c->status,
                        $c->is_read ? 'yes' : 'no',
                        $c->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function supportFaq()
    {
        $faqs = Faq::ordered()->get();
        $categories = Faq::distinct()->pluck('category')->filter();
        
        return view('admin.support.faq', compact('faqs', 'categories'));
    }

    public function createFaq()
    {
        $categories = Faq::distinct()->pluck('category')->filter();
        return view('admin.support.faq.create', compact('categories'));
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'question_en' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'question_my' => 'nullable|string|max:255',
            'answer_my' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_published' => 'boolean',
        ]);

        $faq = Faq::create($validated);

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ created successfully.');
    }

    public function editFaq(Faq $faq)
    {
        $categories = Faq::distinct()->pluck('category')->filter();
        return view('admin.support.faq.edit', compact('faq', 'categories'));
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'question_en' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'question_my' => 'nullable|string|max:255',
            'answer_my' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_published' => 'boolean',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ updated successfully.');
    }

    public function deleteFaq(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ deleted successfully.');
    }

    // Transaction Management Methods
    public function pendingTransactions()
    {
        $transactions = Transaction::with(['buyer', 'seller', 'artwork'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.transactions.pending', compact('transactions'));
    }

    public function showTransaction(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function refundTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $transaction->update([
            'status' => 'refunded',
            'refund_reason' => $request->reason,
            'refunded_at' => now(),
        ]);

        return back()->with('success', 'Transaction refunded successfully.');
    }

    // Settings Management Methods
    public function updateSiteSettings(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_keywords' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Update settings in database
        GeneralSetting::setValue('site_name', $request->site_name, 'text', 'general');
        GeneralSetting::setValue('site_description', $request->site_description, 'text', 'general');
        GeneralSetting::setValue('site_keywords', $request->site_keywords, 'text', 'general');
        GeneralSetting::setValue('contact_email', $request->contact_email, 'text', 'general');
        GeneralSetting::setValue('contact_phone', $request->contact_phone, 'text', 'general');
        GeneralSetting::setValue('address', $request->address, 'text', 'general');

        return back()->with('success', 'Site settings updated successfully.');
    }

    public function languageSettings()
    {
        $defaultLanguage = GeneralSetting::getValue('default_language', config('app.locale', 'en'));
        $enabledLanguages = GeneralSetting::getValue('enabled_languages', json_encode(['en', 'my']));
        $autoDetect = GeneralSetting::getValue('auto_detect_language', '0');

        return view('admin.settings.language', [
            'defaultLanguage' => $defaultLanguage,
            'enabledLanguages' => json_decode($enabledLanguages, true) ?? ['en', 'my'],
            'autoDetect' => $autoDetect === '1',
        ]);
    }

    public function updateLanguageSettings(Request $request)
    {
        $request->validate([
            'default_language' => ['required', 'in:en,my'],
            'enabled_languages' => ['required', 'array'],
            'enabled_languages.*' => ['in:en,my'],
            'auto_detect' => ['nullable'],
        ]);

        try {
            // Persist to database using GeneralSetting
            $defaultSetting = GeneralSetting::setValue('default_language', $request->default_language, 'text', 'language');
            $enabledSetting = GeneralSetting::setValue('enabled_languages', json_encode($request->enabled_languages), 'json', 'language');
            $autoDetectSetting = GeneralSetting::setValue('auto_detect_language', $request->has('auto_detect') ? '1' : '0', 'boolean', 'language');

            // Verify database save was successful
            if (!$defaultSetting || !$enabledSetting || !$autoDetectSetting) {
                throw new \Exception('Failed to save settings to database');
            }

            // Update session to reflect new settings immediately
            session(['locale' => $request->default_language]);
            app()->setLocale($request->default_language);

            // Update config for current request
            config(['app.locale' => $request->default_language]);
            config(['app.enabled_locales' => $request->enabled_languages]);

            // Clear application cache to ensure settings take effect
            try {
                \Cache::forget('general_settings');
            } catch (\Exception $e) {
                // Cache clear failed, but settings are still saved to database
                \Log::warning('Failed to clear cache after language settings update', ['error' => $e->getMessage()]);
            }

            return back()->with('success', 'Language settings updated successfully and synced to database.');
        } catch (\Exception $e) {
            \Log::error('Failed to update language settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update language settings: ' . $e->getMessage());
        }
    }

    public function translationEditor(Request $request)
    {
        $language = $request->get('lang', 'en');
        $file = $request->get('file', 'messages');
        
        $translations = [];
        $langPath = resource_path("lang/{$language}");
        
        if (file_exists($langPath)) {
            $files = glob($langPath . '/*.php');
            foreach ($files as $langFile) {
                $filename = basename($langFile, '.php');
                $translations[$filename] = include $langFile;
            }
        }
        
        $currentTranslations = $translations[$file] ?? [];
        
        return view('admin.settings.translations', compact(
            'language',
            'file',
            'translations',
            'currentTranslations'
        ));
    }

    public function updateTranslations(Request $request)
    {
        $request->validate([
            'language' => ['required', 'in:en,my'],
            'file' => ['required', 'string'],
            'translations' => ['required', 'array'],
        ]);

        $language = $request->language;
        $file = $request->file;
        $translations = $request->translations;

        $langPath = resource_path("lang/{$language}");
        $filePath = $langPath . "/{$file}.php";

        // Create directory if it doesn't exist
        if (!file_exists($langPath)) {
            mkdir($langPath, 0755, true);
        }

        // Convert array to PHP array format
        $phpContent = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        // Write to file
        file_put_contents($filePath, $phpContent);

        // Clear cache
        \Artisan::call('cache:clear');

        return back()->with('success', 'Translations updated successfully.');
    }

    public function currencySettings()
    {
        // Load settings from database with fallback to config
        $defaultCurrency = \App\Models\GeneralSetting::getValue('default_currency', config('currency.default', 'USD'));
        $exchangeRateMMK = \App\Models\GeneralSetting::getValue('exchange_rate_mmk', config('currency.exchange_rates.MMK', 4400));
        $enabledCurrencies = \App\Models\GeneralSetting::getValue('enabled_currencies', json_encode(['USD', 'MMK', 'EUR', 'GBP']));
        
        return view('admin.settings.currency', compact('defaultCurrency', 'exchangeRateMMK', 'enabledCurrencies'));
    }

    public function updateCurrencySettings(Request $request)
    {
        $request->validate([
            'default_currency' => ['required', 'in:USD,MMK,EUR,GBP'],
            'enabled_currencies' => ['required', 'array'],
            'enabled_currencies.*' => ['in:USD,MMK,EUR,GBP'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
        ]);

        // Save to database using GeneralSetting model
        \App\Models\GeneralSetting::setValue('default_currency', $request->default_currency, 'text', 'currency');
        \App\Models\GeneralSetting::setValue('exchange_rate_mmk', $request->exchange_rate, 'number', 'currency');
        \App\Models\GeneralSetting::setValue('enabled_currencies', json_encode($request->enabled_currencies), 'json', 'currency');

        // Also update .env file for backward compatibility
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // Update default currency
            $envContent = preg_replace('/DEFAULT_CURRENCY=.*/', 'DEFAULT_CURRENCY=' . $request->default_currency, $envContent);

            // Update MMK exchange rate
            $envContent = preg_replace('/EXCHANGE_RATE_MMK=.*/', 'EXCHANGE_RATE_MMK=' . $request->exchange_rate, $envContent);

            file_put_contents($envPath, $envContent);
        }

        // Clear config and application cache
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        
        // Clear exchange rates cache specifically
        Cache::forget('exchange_rates');

        return back()->with('success', 'Currency settings updated successfully and synced to database.');
    }

    public function emailSettings()
    {
        // Load email settings from database with fallback to config
        $settings = [
            'mail_driver' => GeneralSetting::getValue('mail_driver', config('mail.default', 'smtp')),
            'mail_host' => GeneralSetting::getValue('mail_host', config('mail.mailers.smtp.host', '')),
            'mail_port' => GeneralSetting::getValue('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_username' => GeneralSetting::getValue('mail_username', config('mail.mailers.smtp.username', '')),
            'mail_password' => '', // Never display password
            'mail_encryption' => GeneralSetting::getValue('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_from_address' => GeneralSetting::getValue('mail_from_address', config('mail.from.address', '')),
            'mail_from_name' => GeneralSetting::getValue('mail_from_name', config('mail.from.name', 'Panchi Gallery')),
        ];

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_driver' => ['required', 'in:smtp,mail,sendmail,log'],
            'mail_host' => ['required_if:mail_driver,smtp', 'nullable', 'string'],
            'mail_port' => ['required_if:mail_driver,smtp', 'nullable', 'integer'],
            'mail_username' => ['required_if:mail_driver,smtp', 'nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['nullable', 'in:tls,ssl'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ]);

        // Save email settings to database
        $settingsData = [
            'mail_driver' => $request->mail_driver,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];

        // Only save password if provided (not empty)
        if ($request->filled('mail_password')) {
            $settingsData['mail_password'] = $request->mail_password;
        }

        // Save each setting to database
        foreach ($settingsData as $key => $value) {
            GeneralSetting::setValue($key, $value, 'text', 'email');
        }

        // Sync settings to config
        config(['mail.default' => $request->mail_driver]);
        config(['mail.mailers.smtp.host' => $request->mail_host]);
        config(['mail.mailers.smtp.port' => $request->mail_port]);
        config(['mail.mailers.smtp.username' => $request->mail_username]);
        if ($request->filled('mail_password')) {
            config(['mail.mailers.smtp.password' => $request->mail_password]);
        }
        config(['mail.mailers.smtp.encryption' => $request->mail_encryption]);
        config(['mail.from.address' => $request->mail_from_address]);
        config(['mail.from.name' => $request->mail_from_name]);

        // Clear config and application cache
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');

        return back()->with('success', 'Email settings updated successfully and synced to database.');
    }

    public function paymentSettings()
    {
        $paymentGateways = json_decode(GeneralSetting::getValue('payment_gateways', '["stripe","paypal","kbzpay","wavepay"]'), true) ?? ['stripe', 'paypal', 'kbzpay', 'wavepay'];
        
        return view('admin.settings.payment', [
            'platformFee' => GeneralSetting::getValue('platform_fee_percentage', '30'),
            'paymentGateways' => $paymentGateways,
            'autoApprovePayments' => GeneralSetting::getValue('auto_approve_payments', '0') === '1',
        ]);
    }

    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'platform_fee' => ['required', 'numeric', 'min:0', 'max:100'],
            'payment_gateways' => ['array'],
            'payment_gateways.*' => ['in:stripe,paypal,kbzpay,wavepay'],
            'auto_approve_payments' => ['boolean'],
        ]);

        // Save platform fee to database
        GeneralSetting::setValue('platform_fee_percentage', $request->input('platform_fee'), 'number', 'payment');

        // Save payment gateways to database (as JSON array)
        $paymentGateways = $request->input('payment_gateways', []);
        GeneralSetting::setValue('payment_gateways', json_encode($paymentGateways), 'text', 'payment');

        // Save auto approve payments setting
        GeneralSetting::setValue('auto_approve_payments', $request->boolean('auto_approve_payments') ? '1' : '0', 'boolean', 'payment');

        // Clear cache to ensure new settings are loaded
        Cache::forget('general_settings');

        return back()->with('success', 'Payment settings updated successfully.');
    }

    public function shippingSettings()
    {
        return view('admin.settings.shipping');
    }

    public function updateShippingSettings(Request $request)
    {
        $request->validate([
            'standard_shipping_cost' => ['required', 'numeric', 'min:0'],
            'express_shipping_cost' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['required', 'numeric', 'min:0'],
            'processing_days' => ['required', 'integer', 'min:1', 'max:30'],
            'international_shipping' => ['boolean'],
        ]);

        // Update shipping settings in database
        GeneralSetting::setValue('standard_shipping_cost', $request->standard_shipping_cost, 'number', 'shipping');
        GeneralSetting::setValue('express_shipping_cost', $request->express_shipping_cost, 'number', 'shipping');
        GeneralSetting::setValue('free_shipping_threshold', $request->free_shipping_threshold, 'number', 'shipping');
        GeneralSetting::setValue('processing_days', $request->processing_days, 'number', 'shipping');
        GeneralSetting::setValue('international_enabled', $request->boolean('international_shipping', false), 'boolean', 'shipping');

        // Clear cache
        Cache::forget('general_settings');

        return back()->with('success', 'Shipping settings updated successfully.');
    }

    public function securitySettings()
    {
        $settings = \App\Models\GeneralSetting::byGroup('security')->pluck('value', 'key')->toArray();
        
        return view('admin.settings.security', compact('settings'));
    }

    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'session_timeout' => ['required', 'integer', 'min:5', 'max:10080'],
            'max_login_attempts' => ['required', 'integer', 'min:3', 'max:10'],
            'password_min_length' => ['required', 'integer', 'min:6', 'max:32'],
            'password_require_uppercase' => ['nullable'],
            'password_require_lowercase' => ['nullable'],
            'password_require_numbers' => ['nullable'],
            'password_require_special' => ['nullable'],
            'force_2fa_admin' => ['nullable'],
        ]);

        // Save all security settings to database
        \App\Models\GeneralSetting::setValue('session_timeout', $request->session_timeout, 'number', 'security');
        \App\Models\GeneralSetting::setValue('max_login_attempts', $request->max_login_attempts, 'number', 'security');
        \App\Models\GeneralSetting::setValue('password_min_length', $request->password_min_length, 'number', 'security');
        \App\Models\GeneralSetting::setValue('password_require_uppercase', $request->has('password_require_uppercase') ? '1' : '0', 'boolean', 'security');
        \App\Models\GeneralSetting::setValue('password_require_lowercase', $request->has('password_require_lowercase') ? '1' : '0', 'boolean', 'security');
        \App\Models\GeneralSetting::setValue('password_require_numbers', $request->has('password_require_numbers') ? '1' : '0', 'boolean', 'security');
        \App\Models\GeneralSetting::setValue('password_require_special', $request->has('password_require_special') ? '1' : '0', 'boolean', 'security');
        \App\Models\GeneralSetting::setValue('force_2fa_admin', $request->has('force_2fa_admin') ? '1' : '0', 'boolean', 'security');

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('general_settings');

        return back()->with('success', 'Security settings updated successfully.');
    }

    public function backupSettings()
    {
        $backupFrequency = GeneralSetting::getValue('backup_frequency', 'daily');
        $backupRetention = GeneralSetting::getValue('backup_retention', '30');
        
        return view('admin.settings.backup', compact('backupFrequency', 'backupRetention'));
    }

    public function updateBackupSettings(Request $request)
    {
        $request->validate([
            'backup_frequency' => ['required', 'in:daily,weekly,monthly,manual'],
            'backup_retention' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        GeneralSetting::setValue('backup_frequency', $request->backup_frequency, 'text', 'backup');
        GeneralSetting::setValue('backup_retention', $request->backup_retention, 'number', 'backup');

        return back()->with('success', 'Backup settings updated successfully.');
    }

    public function createBackup(Request $request)
    {
        $request->validate([
            'backup_type' => ['required', 'in:full,structure'],
            'include_files' => ['nullable', 'boolean'],
        ]);

        try {
            $databaseName = env('DB_DATABASE', 'panchi_gallery');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $host = env('DB_HOST', 'localhost');

            $timestamp = now()->format('Y-m-d_H-i-s');
            $backupDir = storage_path('app/backups');

            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $backupFile = $backupDir . '/backup_' . $timestamp . '.sql';

            // Use Symfony Process for secure command execution
            $process = new \Symfony\Component\Process\Process([
                'mysqldump',
                '-h', $host,
                '-u', $username,
                $password ? '-p' . $password : '',
                $databaseName
            ]);

            if ($request->backup_type === 'structure') {
                $process = new \Symfony\Component\Process\Process([
                    'mysqldump',
                    '-h', $host,
                    '-u', $username,
                    $password ? '-p' . $password : '',
                    '--no-data',
                    $databaseName
                ]);
            }

            // Set output file
            $process->setOutput($backupFile);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception('Backup command failed: ' . $process->getErrorOutput());
            }

            if ($request->boolean('include_files')) {
                $filesBackupDir = $backupDir . '/files_' . $timestamp;
                $publicDir = public_path();
                
                $zip = new \ZipArchive();
                $zipFile = $backupDir . '/backup_with_files_' . $timestamp . '.zip';
                
                if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
                    $zip->addFile($backupFile, 'database.sql');
                    
                    $filesToBackup = ['images', 'admin/images', 'admin/avatars'];
                    foreach ($filesToBackup as $folder) {
                        $folderPath = $publicDir . '/' . $folder;
                        if (is_dir($folderPath)) {
                            $files = new \RecursiveIteratorIterator(
                                new \RecursiveDirectoryIterator($folderPath),
                                \RecursiveIteratorIterator::LEAVES_ONLY
                            );
                            
                            foreach ($files as $name => $file) {
                                if (!$file->isDir()) {
                                    $filePath = $file->getRealPath();
                                    $relativePath = substr($filePath, strlen($publicDir) + 1);
                                    $zip->addFile($filePath, 'files/' . $relativePath);
                                }
                            }
                        }
                    }
                    
                    $zip->close();
                    unlink($backupFile);
                    $backupFile = $zipFile;
                }
            }

            GeneralSetting::setValue('last_backup', $timestamp, 'text', 'backup');

            return back()->with('success', 'Backup created successfully at ' . basename($backupFile));
        } catch (\Exception $e) {
            \Log::error('Backup creation failed: ' . $e->getMessage());
            return back()->with('error', 'Backup creation failed: ' . $e->getMessage());
        }
    }

    public function systemLogs()
    {
        $logs = [];
        // Implement log reading logic
        return view('admin.settings.logs', compact('logs'));
    }

    public function cacheSettings()
    {
        $cacheDriver = GeneralSetting::getValue('cache_driver', config('cache.default'));
        $cacheTtl = GeneralSetting::getValue('cache_ttl', 60);
        $enablePageCache = GeneralSetting::getValue('enable_page_cache', '0') === '1';

        return view('admin.settings.cache', compact('cacheDriver', 'cacheTtl', 'enablePageCache'));
    }

    public function updateCacheSettings(Request $request)
    {
        $request->validate([
            'cache_driver' => ['required', 'in:file,database,redis,memcached'],
            'cache_ttl' => ['required', 'integer', 'min:1', 'max:10080'],
            'enable_page_cache' => ['nullable', 'boolean'],
        ]);

        GeneralSetting::setValue('cache_driver', $request->cache_driver, 'text', 'cache');
        GeneralSetting::setValue('cache_ttl', $request->cache_ttl, 'number', 'cache');
        GeneralSetting::setValue('enable_page_cache', $request->has('enable_page_cache') ? '1' : '0', 'boolean', 'cache');

        // Clear cache after updating settings
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');

        return back()->with('success', 'Cache settings updated successfully.');
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');

        return back()->with('success', 'Cache cleared successfully.');
    }

    // General Settings Methods
    public function generalSettings()
    {
        $settings = \App\Models\GeneralSetting::orderBy('group')->orderBy('sort_order')->get();
        $groupedSettings = $settings->groupBy('group');
        return view('admin.settings.general', compact('groupedSettings'));
    }

    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        try {
            \DB::beginTransaction();
            $updatedKeys = [];

            foreach ($request->settings as $settingData) {
                $setting = \App\Models\GeneralSetting::where('key', $settingData['key'])->first();

                if ($setting) {
                    // Handle image uploads with enhanced validation
                    if ($setting->type === 'image' && $request->hasFile('settings.' . $settingData['key'] . '.file')) {
                        $file = $request->file('settings.' . $settingData['key'] . '.file');

                        // Enhanced image validation using ImageSyncService
                        $validation = \App\Services\ImageSyncService::validateImage($file);
                        if (!$validation['valid']) {
                            throw new \Exception('Image validation failed: ' . implode(', ', $validation['errors']));
                        }

                        // Validate image
                        $validated = $request->validate([
                            'settings.' . $settingData['key'] . '.file' => ['image', 'mimes:jpg,jpeg,png,gif,webp,svg', 'max:2048'],
                        ]);

                        // Delete old image if exists
                        if ($setting->value && !filter_var($setting->value, FILTER_VALIDATE_URL)) {
                            Storage::disk('public')->delete($setting->value);
                        }

                        // Store new image with optimized naming
                        $fileName = $setting->key . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('settings', $fileName, 'public');
                        $setting->value = $path;
                    } elseif (isset($settingData['value'])) {
                        // Validate JSON type settings
                        if ($setting->type === 'json' && !empty($settingData['value'])) {
                            $decoded = json_decode($settingData['value']);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                throw new \Exception("Invalid JSON format for setting '{$setting->label}': " . json_last_error_msg());
                            }
                        }
                        $setting->value = $settingData['value'];
                    }

                    $setting->save();
                    $updatedKeys[] = $setting->key;
                }
            }

            \DB::commit();

            // Clear cache and sync images
            try {
                \Cache::forget('general_settings');
                \Cache::flush();
                
                // Sync all images using ImageSyncService
                $syncResults = \App\Services\ImageSyncService::syncAllImages();
                $imageSyncCount = count(array_filter($syncResults, fn($r) => $r['status'] === 'success'));
                
                \Log::info('Image sync completed', [
                    'total_images' => count($syncResults),
                    'successful_syncs' => $imageSyncCount,
                    'results' => $syncResults
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to clear cache or sync images after general settings update', ['error' => $e->getMessage()]);
            }

            return back()->with('success', 'General settings updated successfully and synced to database.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to update general settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update general settings: ' . $e->getMessage());
        }
    }

    /**
     * Sync all images and show sync status
     */
    public function syncImages()
    {
        try {
            $syncResults = \App\Services\ImageSyncService::syncAllImages();
            $allResults = \App\Services\ImageSyncService::getAllSyncResults();
            
            return response()->json([
                'success' => true,
                'message' => 'Image synchronization completed',
                'results' => $syncResults,
                'summary' => [
                    'total' => count($syncResults),
                    'successful' => count(array_filter($syncResults, fn($r) => $r['status'] === 'success')),
                    'warnings' => count(array_filter($syncResults, fn($r) => $r['status'] === 'warning')),
                    'errors' => count(array_filter($syncResults, fn($r) => $r['status'] === 'error')),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image synchronization failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get optimization suggestions for images
     */
    public function getImageOptimizationSuggestions(Request $request)
    {
        $key = $request->input('key');
        
        if (!$key) {
            return response()->json(['error' => 'Setting key is required'], 400);
        }
        
        $suggestions = \App\Services\ImageSyncService::getOptimizationSuggestions($key);
        
        return response()->json([
            'key' => $key,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Display images settings page.
     */
    public function imagesSettings()
    {
        // Get only image-type settings grouped by their group
        $settings = \App\Models\GeneralSetting::where('type', 'image')
            ->orderBy('sort_order')
            ->orderBy('group')
            ->get();

        // Group settings by their group
        $groupedSettings = $settings->groupBy('group');

        // Define image categories with icons and descriptions
        $imageCategories = [
            'branding' => [
                'icon' => 'fa-paint-brush',
                'title' => 'Branding',
                'description' => 'Logo, favicon, and main brand identity images',
            ],
            'home' => [
                'icon' => 'fa-home',
                'title' => 'Homepage',
                'description' => 'Hero banners and homepage section images',
            ],
            'about' => [
                'icon' => 'fa-info-circle',
                'title' => 'About Page',
                'description' => 'Images for the about page sections',
            ],
            'artists' => [
                'icon' => 'fa-palette',
                'title' => 'Artists Page',
                'description' => 'Hero and section images for artists page',
            ],
            'artworks' => [
                'icon' => 'fa-image',
                'title' => 'Artworks Page',
                'description' => 'Hero and gallery section images',
            ],
            'contact' => [
                'icon' => 'fa-envelope',
                'title' => 'Contact Page',
                'description' => 'Contact page hero and background images',
            ],
            'auth' => [
                'icon' => 'fa-lock',
                'title' => 'Authentication',
                'description' => 'Login and register page images',
            ],
            'footer' => [
                'icon' => 'fa-shoe-prints',
                'title' => 'Footer',
                'description' => 'Footer section images and badges',
            ],
        ];

        return view('admin.settings.images', compact('groupedSettings', 'imageCategories'));
    }

    /**
     * Update images settings.
     */
    public function updateImagesSettings(Request $request)
    {
        $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        try {
            \DB::beginTransaction();
            $updatedKeys = [];
            $syncResults = [];

            foreach ($request->settings as $settingData) {
                $setting = \App\Models\GeneralSetting::where('key', $settingData['key'])
                    ->where('type', 'image')
                    ->first();

                if ($setting) {
                    // Handle image upload
                    if ($request->hasFile('settings.' . $settingData['key'] . '.file')) {
                        $file = $request->file('settings.' . $settingData['key'] . '.file');

                        // Validate image using ImageSyncService
                        $validation = \App\Services\ImageSyncService::validateImage($file);
                        if (!$validation['valid']) {
                            throw new \Exception($setting->label . ': ' . implode(', ', $validation['errors']));
                        }

                        // Additional validation
                        $validated = $request->validate([
                            'settings.' . $settingData['key'] . '.file' => ['image', 'mimes:jpg,jpeg,png,gif,webp,svg', 'max:2048'],
                        ]);

                        // Delete old image if exists
                        if ($setting->value && !filter_var($setting->value, FILTER_VALIDATE_URL)) {
                            Storage::disk('public')->delete($setting->value);
                        }

                        // Store new image
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $setting->key . '_' . time() . '.' . $extension;
                        $path = $file->storeAs('settings', $fileName, 'public');
                        $setting->value = $path;
                        $updatedKeys[] = $setting->key;
                    }

                    $setting->save();
                }
            }

            \DB::commit();

            // Sync images after update
            try {
                \Cache::forget('general_settings');
                $syncResults = \App\Services\ImageSyncService::syncAllImages();

                \Log::info('Images settings updated', [
                    'updated_keys' => $updatedKeys,
                    'sync_results' => $syncResults
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to sync images after settings update', ['error' => $e->getMessage()]);
            }

            $message = 'Images settings updated successfully.';
            if (!empty($updatedKeys)) {
                $message .= ' ' . count($updatedKeys) . ' image(s) uploaded.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to update images settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update images: ' . $e->getMessage());
        }
    }

    // Collection Management Methods
    public function collections()
    {
        $collections = \App\Models\Collection::with('artworks')->latest()->paginate(15);
        return view('admin.collections.index', compact('collections'));
    }

    public function createCollection()
    {
        return view('admin.collections.create');
    }

    public function storeCollection(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'curator_id' => ['nullable', 'exists:users,id'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $data = $request->except('featured_image');
        $data['slug'] = \Str::slug($request->title);

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) {
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            $data['featured_image'] = $image->store('collections', 'public');
        }

        \App\Models\Collection::create($data);

        return redirect()->route('admin.collections')
            ->with('success', 'Collection created successfully.');
    }

    public function editCollection(\App\Models\Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function updateCollection(Request $request, \App\Models\Collection $collection)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'curator_id' => ['nullable', 'exists:users,id'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $data = $request->except('featured_image');

        if ($request->title !== $collection->title) {
            $data['slug'] = \Str::slug($request->title);
        }

        if ($request->hasFile('featured_image')) {
            if ($collection->featured_image) {
                Storage::disk('public')->delete($collection->featured_image);
            }
            $image = $request->file('featured_image');
            if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
            }
            if ($image->getSize() > 2 * 1024 * 1024) {
                return back()->with('error', 'File size too large. Maximum size is 2MB.');
            }
            $data['featured_image'] = $image->store('collections', 'public');
        }

        $collection->update($data);

        return redirect()->route('admin.collections')
            ->with('success', 'Collection updated successfully.');
    }

    public function deleteCollection(\App\Models\Collection $collection)
    {
        if ($collection->featured_image) {
            Storage::disk('public')->delete($collection->featured_image);
        }

        $collection->delete();

        return redirect()->route('admin.collections')
            ->with('success', 'Collection deleted successfully.');
    }

    /**
     * Generate certificate for artwork.
     */
    private function generateCertificate(Artwork $artwork)
    {
        // This would integrate with the CertificateController
        // For now, we'll create a basic certificate
        Certificate::create([
            'artwork_id' => $artwork->id,
            'artist_id' => $artwork->artist_id,
            'certificate_code' => Certificate::generateCertificateCode(),
            'issue_date' => now(),
            'certificate_text' => "Certificate of Authenticity for {$artwork->title}",
            'is_verified' => true,
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function paymentMethods()
    {
        $paymentMethods = \App\Models\PaymentMethod::orderBy('sort_order')->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function createPaymentMethod()
    {
        return view('admin.payment-methods.create');
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code',
            'type' => 'required|in:stripe,paypal,bank_transfer,mobile_payment',
            'is_active' => 'boolean',
            'requires_manual_verification' => 'boolean',
            'instructions' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'qr_code' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['logo', 'qr_code', 'is_active', 'requires_manual_verification']);
        $data['is_active'] = $request->has('is_active');
        $data['requires_manual_verification'] = $request->has('requires_manual_verification');
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('payment_methods', 'public');
        }

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('payment_qr', 'public');
        }

        \App\Models\PaymentMethod::create($data);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method created successfully.');
    }

    public function editPaymentMethod(\App\Models\PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function updatePaymentMethod(Request $request, \App\Models\PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code,' . $paymentMethod->id,
            'type' => 'required|in:stripe,paypal,bank_transfer,mobile_payment',
            'is_active' => 'boolean',
            'requires_manual_verification' => 'boolean',
            'instructions' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'qr_code' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['logo', 'qr_code', 'is_active', 'requires_manual_verification']);
        $data['is_active'] = $request->has('is_active');
        $data['requires_manual_verification'] = $request->has('requires_manual_verification');
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('payment_methods', 'public');
        }

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('payment_qr', 'public');
        }

        $paymentMethod->update($data);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function deletePaymentMethod(\App\Models\PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }

    public function togglePaymentMethodStatus(\App\Models\PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        return redirect()->back()->with('success', 'Status toggled successfully.');
    }

    public function statistics()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'artists' => User::where('role', 'artist')->count(),
                'collectors' => User::where('role', 'collector')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'pending_artists' => User::where('role', 'artist')->where('is_approved', false)->count(),
            ],
            'artworks' => [
                'total' => Artwork::withoutGlobalScope(ApprovedScope::class)->count(),
                'approved' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'approved')->count(),
                'pending' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'pending')->count(),
                'sold' => Artwork::withoutGlobalScope(ApprovedScope::class)->where('status', 'sold')->count(),
            ],
            'transactions' => [
                'total' => Transaction::count(),
                'completed' => Transaction::where('status', 'completed')->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'total_amount' => Transaction::where('status', 'completed')->sum('amount'),
                'platform_fees' => Transaction::where('status', 'completed')->sum('platform_fee'),
            ],
            'resales' => [
                'total' => Resale::count(),
                'listed' => Resale::where('status', 'listed')->count(),
                'pending' => Resale::where('status', 'pending')->count(),
                'sold' => Resale::where('status', 'sold')->count(),
            ],
        ];
        
        return response()->json($stats);
    }

    /**
     * Display all orders.
     */
    public function orders(Request $request)
    {
        $query = \App\Models\Order::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_custom')) {
            if ($request->is_custom == '1') {
                $query->where('order_type', 'custom');
            } else {
                $query->where('order_type', '!=', 'custom')->orWhereNull('order_type');
            }
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order details.
     */
    public function showOrder($id)
    {
        $order = \App\Models\Order::with(['items.artwork', 'user', 'artist', 'paymentProofs.paymentMethod'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Edit order details.
     */
    public function editOrder($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update order details.
     */
    public function updateOrder(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled,refunded,failed',
            'payment_status' => 'required|in:pending,completed,failed,refunded',
            'shipping_method' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $order->update($request->all());

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Create a shipment for the order.
     */
    public function createShipment(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        // This usually redirects to a shipment creation form or handles it via AJAX
        return view('admin.shipments.create', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled in its current state.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order cancelled successfully.');
    }
}
