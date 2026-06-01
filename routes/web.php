<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminController as ZohoAdminController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\OwnershipController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ResaleController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ArtistEarningsController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\AdminPageContentController;
use App\Http\Controllers\AdminFrontendController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// XAMPP: proxy legacy /public/admin and /public/public/admin URLs to internal /admin/* routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::any('/public/public/admin/{path?}', [\App\Http\Controllers\AdminPublicProxyController::class, '__invoke'])
        ->where('path', '.*')
        ->name('admin.proxy.double-public');

    Route::any('/public/admin/{path?}', [\App\Http\Controllers\AdminPublicProxyController::class, '__invoke'])
        ->where('path', '.*')
        ->name('admin.proxy.public');
});

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/summary', [CartController::class, 'summary'])->name('summary');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cartItemId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/promo-code', [CartController::class, 'applyPromoCode'])->name('promo.apply');
    Route::delete('/promo-code', [CartController::class, 'removePromoCode'])->name('promo.remove');
});

// Currency Routes
Route::prefix('currency')->name('currency.')->group(function () {
    Route::post('/switch', [CurrencyController::class, 'switch'])->name('switch');
    Route::get('/current', [CurrencyController::class, 'current'])->name('current');
    Route::post('/convert', [CurrencyController::class, 'convert'])->name('convert');
    Route::get('/rates', [CurrencyController::class, 'rates'])->name('rates');
    Route::post('/prices', [CurrencyController::class, 'prices'])->name('prices');
});

// Language Switch Route
Route::post('/language/switch', function () {
    $language = request('language');

    // Validate language
    if (in_array($language, ['en', 'my'])) {
        session(['locale' => $language]);
        app()->setLocale($language);

        // Auto-switch currency based on language
        if ($language === 'my' && session('currency', 'USD') === 'USD') {
            session(['currency' => 'MMK']);
        } elseif ($language === 'en' && session('currency', 'USD') === 'MMK') {
            session(['currency' => 'USD']);
        }

        return response()->json([
            'success' => true,
            'language' => $language,
            'currency' => session('currency')
        ]);
    }

    return response()->json(['success' => false], 400);
})->name('language.switch');

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

// Newsletter Route
Route::post('/newsletter/subscribe', [ContactController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// Artwork Routes
Route::get('/artworks', [ArtworkController::class, 'index'])->name('public.artworks.index');
Route::get('/artworks/{artwork}', [ArtworkController::class, 'show'])->name('public.artworks.show');

// Exhibition Routes
Route::get('/exhibitions', [ExhibitionController::class, 'index'])->name('public.exhibitions.index');
Route::get('/exhibitions/{slug}', [ExhibitionController::class, 'show'])->name('public.exhibitions.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// API Routes for AJAX requests
Route::get('/api/artworks/{artwork}', [ArtworkController::class, 'apiShow'])->name('api.artworks.show');
Route::get('/api/payment-methods/{method}/instructions', function ($methodId) {
    $method = \App\Models\PaymentMethod::find($methodId);
    return response()->json([
        'instructions' => $method ? $method->instructions : null
    ]);
});

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/guest', [CheckoutController::class, 'guest'])->name('checkout.guest');
Route::post('/checkout/process', [CheckoutController::class, 'authenticated'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Checkout Cart Management (AJAX endpoints)
Route::put('/checkout/cart/{cartItem}', [CheckoutController::class, 'updateCartItem'])->name('checkout.cart.update');
Route::delete('/checkout/cart/{cartItem}', [CheckoutController::class, 'removeCartItem'])->name('checkout.cart.remove');

// Payment Routes - Accessible to both guests and authenticated users
Route::get('/payment/{order}/{method}', [PaymentController::class, 'showManualPaymentForm'])->name('payment.manual');
Route::post('/payment/{order}/upload', [PaymentController::class, 'uploadPaymentProof'])->name('payment.upload');

// Shipment Routes
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/shipment', [ShipmentController::class, 'show'])->name('orders.shipment');
    Route::post('/orders/{order}/shipment', [ShipmentController::class, 'create'])->name('orders.shipment.create');
    Route::put('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('public.shipments.update');
    Route::post('/shipments/{shipment}/events', [ShipmentController::class, 'addEvent'])->name('public.shipments.add-event');
});
Route::get('/track', [ShipmentController::class, 'track'])->name('public.shipments.track');

// API Routes
Route::post('/api/shipping/calculate', function (Request $request) {
    $shippingService = app(\App\Services\ShippingService::class);
    $cart = \App\Models\Cart::getOrCreateCart();
    $cart->load('items.artwork');
    
    $method = $request->input('shipping_method', 'standard');
    $shippingCost = $shippingService->calculateCartShipping($cart, $method);
    
    return response()->json([
        'shipping_cost' => $shippingCost,
        'method' => $method,
    ]);
})->name('api.shipping.calculate');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('public.categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('public.categories.show');

// Artist Routes (Public)
Route::get('/artists', [ArtistController::class, 'index'])->name('public.artists.index');
Route::get('/artists/{slug}', [ArtistController::class, 'showBySlug'])->name('public.artists.show');
Route::get('/artists/id/{artist}', [ArtistController::class, 'show'])->name('public.artists.show.id');
Route::post('/artists/{id}/follow', [ArtistController::class, 'follow'])->name('public.artists.follow');

// API Routes for Artist Artworks (AJAX Load More)
Route::get('/api/artists/{slug}/artworks', [ArtistController::class, 'getArtworks'])->name('api.artists.artworks');

// Marketplace Routes
Route::get('/marketplace', [ResaleController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{slug}', [ResaleController::class, 'show'])->name('marketplace.show');

// Resale Management Routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/resales/my-listings', [ResaleController::class, 'myResales'])->name('resales.my-resales');
    Route::get('/resales/{resale}/edit', [ResaleController::class, 'edit'])->name('resales.edit');
    Route::put('/resales/{resale}', [ResaleController::class, 'update'])->name('resales.update');
    Route::delete('/resales/{resale}', [ResaleController::class, 'destroy'])->name('resales.destroy');
    Route::get('/resales/{resale}/purchase', [ResaleController::class, 'purchase'])->name('resales.purchase');
    Route::post('/resales/{resale}/purchase', [ResaleController::class, 'processPurchase'])->name('marketplace.purchase');
});

// User Transaction Routes
Route::middleware('auth')->prefix('transactions')->name('user.transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::get('/create/{artwork}', [TransactionController::class, 'create'])->name('create');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
});

// Ownership Routes
Route::middleware('auth')->prefix('ownership')->name('ownership.')->group(function () {
    Route::get('/my-artworks', [OwnershipController::class, 'myArtworks'])->name('my-artworks');
    Route::get('/{ownership}/certificate', [OwnershipController::class, 'downloadCertificate'])->name('certificate');
});

// Artwork Ownership History (public)
Route::get('/artworks/{artwork}/history', [OwnershipController::class, 'history'])->name('artworks.history');

// Custom Order Routes
Route::prefix('orders')->name('custom-orders.')->group(function () {
    // Public routes (require authentication)
    Route::middleware('auth')->group(function () {
        Route::get('/create', [CustomOrderController::class, 'create'])->name('create');
        Route::post('/', [CustomOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [CustomOrderController::class, 'show'])->name('show');
        Route::get('/{order}/track', [CustomOrderController::class, 'track'])->name('track');

        // Customer routes
        Route::get('/customer', [CustomOrderController::class, 'customerOrders'])->name('customer.index');
        Route::post('/{order}/customer-approve', [CustomOrderController::class, 'customerApprove'])->name('customer-approve');
        Route::post('/{order}/customer-reject', [CustomOrderController::class, 'customerReject'])->name('customer-reject');

        // Artist routes
        Route::post('/{order}/accept', [CustomOrderController::class, 'acceptOrder'])->name('accept');
        Route::post('/{order}/reject', [CustomOrderController::class, 'rejectOrder'])->name('reject');
        Route::post('/{order}/start-progress', [CustomOrderController::class, 'startProgress'])->name('start-progress');
        Route::post('/{order}/ready-review', [CustomOrderController::class, 'markReadyForReview'])->name('ready-review');

        // Admin routes
        Route::get('/admin', [CustomOrderController::class, 'adminIndex'])->name('admin.index');
    });
});

// Certificate Verification
Route::get('/verify/{certificate_code}', [CertificateController::class, 'verify'])->name('verify.certificate');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
    
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Rate-limit login attempts to mitigate brute-force
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    
    // Admin Login Route
    Route::get('admin', [AuthenticatedSessionController::class, 'createAdmin'])->name('admin.login');
    // Admin login should also be rate-limited
    Route::post('admin', [AuthenticatedSessionController::class, 'storeAdmin'])->middleware('throttle:5,1')->name('admin.login.post');
    
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Legacy profile route alias
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/dashboard/artist', [DashboardController::class, 'index'])->name('artist.dashboard');
    
    Route::get('/pending-approval', function () {
        return view('dashboard.pending');
    })->name('pending.approval');
    
    // Admin Routes
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');

        // Zoho-style module pages (App\Http\Controllers\Admin\AdminController)
        Route::get('/items', [ZohoAdminController::class, 'items'])->name('items');
        Route::get('/sales', [ZohoAdminController::class, 'sales'])->name('sales');
        Route::get('/customers', [ZohoAdminController::class, 'customers'])->name('customers');
        Route::get('/purchases', [ZohoAdminController::class, 'purchases'])->name('purchases');
        Route::get('/banking', [ZohoAdminController::class, 'banking'])->name('banking');
        Route::get('/reports', [ZohoAdminController::class, 'reports'])->name('reports');
        Route::get('/documents', [ZohoAdminController::class, 'documents'])->name('documents');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/pending', [AdminController::class, 'pendingUsers'])->name('users.pending');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/bulk-approve', [AdminController::class, 'bulkApproveUsers'])->name('users.bulk-approve');
        Route::delete('/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('users.bulk-delete');
        Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
        Route::post('/users/{id}/resend-verification', [AdminController::class, 'resendVerificationEmail'])->name('users.resend-verification');
        Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::post('/users/{id}/impersonate', [AdminController::class, 'impersonateUser'])->name('users.impersonate');
        Route::post('/users/stop-impersonation', [AdminController::class, 'stopImpersonation'])->name('users.stop-impersonation');
        
        // Artist Management
        Route::get('/artists', [AdminController::class, 'artists'])->name('artists');
        Route::get('/pending-artists', [AdminController::class, 'pendingArtists'])->name('pending-artists');
        Route::post('/artists/{artist}/approve', [AdminController::class, 'approveArtist'])->name('artists.approve');
        Route::post('/artists/{artist}/reject', [AdminController::class, 'rejectArtist'])->name('artists.reject');
        Route::get('/artists/{artist}/edit', [AdminController::class, 'editArtist'])->name('artists.edit');
        Route::put('/artists/{artist}', [AdminController::class, 'updateArtist'])->name('artists.update');
        Route::delete('/artists/{artist}', [AdminController::class, 'deleteArtist'])->name('artists.delete');
        
        // Artwork Management
        Route::get('/artworks', [AdminController::class, 'artworks'])->name('artworks');
        Route::get('/artworks/create', [AdminController::class, 'createArtwork'])->name('artworks.create');
        Route::post('/artworks', [AdminController::class, 'storeArtwork'])->name('artworks.store');
        Route::get('/artworks/pending', [AdminController::class, 'pendingArtworks'])->name('artworks.pending');
        Route::post('/artworks/{id}/approve', [AdminController::class, 'approveArtwork'])->name('artworks.approve');
        Route::post('/artworks/{id}/reject', [AdminController::class, 'rejectArtwork'])->name('artworks.reject');
        Route::get('/artworks/{id}/edit', [AdminController::class, 'editArtwork'])->name('artworks.edit');
        Route::get('/artworks/{id}', function ($id) {
            return redirect()->route('admin.artworks.edit', $id);
        })->name('artworks.show');
        Route::put('/artworks/{id}', [AdminController::class, 'updateArtwork'])->name('artworks.update');
        Route::delete('/artworks/{id}', [AdminController::class, 'deleteArtwork'])->name('artworks.delete');
        
        // Exhibition Management
        Route::get('/exhibitions', [ExhibitionController::class, 'adminIndex'])->name('exhibitions.index');
        Route::get('/exhibitions/create', [ExhibitionController::class, 'create'])->name('exhibitions.create');
        Route::post('/exhibitions', [ExhibitionController::class, 'store'])->name('exhibitions.store');
        Route::get('/exhibitions/{exhibition}/edit', [ExhibitionController::class, 'edit'])->name('exhibitions.edit');
        Route::put('/exhibitions/{exhibition}', [ExhibitionController::class, 'update'])->name('exhibitions.update');
        Route::delete('/exhibitions/{exhibition}', [ExhibitionController::class, 'destroy'])->name('exhibitions.destroy');
        
        // Blog Management
        Route::get('/blogs', [BlogController::class, 'adminIndex'])->name('blogs.index');
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
        Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
        
        // Marketplace Management
        Route::get('/marketplace', [AdminController::class, 'marketplace'])->name('marketplace');
        Route::get('/marketplace/listings', [AdminController::class, 'marketplaceListings'])->name('marketplace.listings');
        Route::get('/marketplace/pending', [AdminController::class, 'pendingMarketplace'])->name('marketplace.pending');
        Route::post('/marketplace/{resale}/approve', [AdminController::class, 'approveResale'])->name('marketplace.approve');
        Route::post('/marketplace/{resale}/reject', [AdminController::class, 'rejectResale'])->name('marketplace.reject');
        Route::get('/resales', [AdminController::class, 'resales'])->name('resales');
        
        // Category Management
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
        
        // Collection Management
        Route::get('/collections', [AdminController::class, 'collections'])->name('collections');
        Route::get('/collections/create', [AdminController::class, 'createCollection'])->name('collections.create');
        Route::get('/collections/{collection}/edit', [AdminController::class, 'editCollection'])->name('collections.edit');
        Route::post('/collections', [AdminController::class, 'storeCollection'])->name('collections.store');
        Route::put('/collections/{collection}', [AdminController::class, 'updateCollection'])->name('collections.update');
        Route::delete('/collections/{collection}', [AdminController::class, 'deleteCollection'])->name('collections.delete');
        
        // Support Management
        Route::get('/support', [AdminController::class, 'support'])->name('support');
        Route::get('/support/contacts', [AdminController::class, 'supportContacts'])->name('support.contacts');
        Route::post('/support/contacts/mark-read', [AdminController::class, 'markContactsRead'])->name('support.contacts.mark-read');
        Route::get('/support/contacts/export', [AdminController::class, 'exportContacts'])->name('support.contacts.export');
        Route::get('/support/contacts/{contact}', [AdminController::class, 'showContact'])->name('support.contacts.show');
        Route::post('/support/contacts/{contact}/respond', [AdminController::class, 'respondContact'])->name('support.contacts.respond');
        Route::post('/support/contacts/{contact}/resolve', [AdminController::class, 'resolveContact'])->name('support.contacts.resolve');
        Route::delete('/support/contacts/{contact}', [AdminController::class, 'deleteContact'])->name('support.contacts.delete');
        Route::get('/support/faq', [AdminController::class, 'supportFaq'])->name('support.faq');
        Route::get('/support/faq/create', [AdminController::class, 'createFaq'])->name('support.faq.create');
        Route::post('/support/faq', [AdminController::class, 'storeFaq'])->name('support.faq.store');
        Route::get('/support/faq/{faq}/edit', [AdminController::class, 'editFaq'])->name('support.faq.edit');
        Route::put('/support/faq/{faq}', [AdminController::class, 'updateFaq'])->name('support.faq.update');
        Route::delete('/support/faq/{faq}', [AdminController::class, 'deleteFaq'])->name('support.faq.delete');
        
        // Transaction Management
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions.index');
        Route::get('/transactions/pending', [AdminController::class, 'pendingTransactions'])->name('transactions.pending');
        Route::get('/transactions/{transaction}', [AdminController::class, 'showTransaction'])->name('transactions.show');
        Route::post('/transactions/{transaction}/refund', [AdminController::class, 'refundTransaction'])->name('transactions.refund');
        
        // Order Management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('orders.edit');
        Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::post('/orders/{order}/ship', [AdminController::class, 'createShipment'])->name('orders.ship');
        Route::post('/orders/{order}/cancel', [AdminController::class, 'cancelOrder'])->name('orders.cancel');
        
        // Shipment Management
        Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments.index');
        Route::get('/shipments/{shipment}', [AdminController::class, 'showShipment'])->name('shipments.show');
        Route::get('/shipments/{shipment}/edit', [AdminController::class, 'editShipment'])->name('shipments.edit');
        Route::put('/shipments/{shipment}', [AdminController::class, 'updateShipment'])->name('shipments.update');
        Route::post('/shipments/{shipment}/track', [AdminController::class, 'trackShipment'])->name('shipments.track');
        
        // Payment Methods Management
        Route::get('/payment-methods', [AdminController::class, 'paymentMethods'])->name('payment-methods.index');
        Route::get('/payment-methods/create', [AdminController::class, 'createPaymentMethod'])->name('payment-methods.create');
        Route::post('/payment-methods', [AdminController::class, 'storePaymentMethod'])->name('payment-methods.store');
        Route::get('/payment-methods/{paymentMethod}/edit', [AdminController::class, 'editPaymentMethod'])->name('payment-methods.edit');
        Route::put('/payment-methods/{paymentMethod}', [AdminController::class, 'updatePaymentMethod'])->name('payment-methods.update');
        Route::delete('/payment-methods/{paymentMethod}', [AdminController::class, 'deletePaymentMethod'])->name('payment-methods.delete');
        Route::post('/payment-methods/{paymentMethod}/activate', [AdminController::class, 'activatePaymentMethod'])->name('payment-methods.activate');
        Route::post('/payment-methods/{paymentMethod}/deactivate', [AdminController::class, 'deactivatePaymentMethod'])->name('payment-methods.deactivate');
        
        // Commissions Management
        Route::get('/commissions', [AdminController::class, 'commissions'])->name('commissions.index');
        Route::get('/commissions/{commission}', [AdminController::class, 'showCommission'])->name('commissions.show');
        Route::post('/commissions/{commission}/mark-paid', [AdminController::class, 'markCommissionPaid'])->name('commissions.mark-paid');
        
        // Wishlists Management
        Route::get('/wishlists', [AdminController::class, 'wishlists'])->name('wishlists.index');
        Route::delete('/wishlists/{wishlist}', [AdminController::class, 'deleteWishlist'])->name('wishlists.delete');
        
        // FAQs Management
        Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs.index');
        Route::get('/faqs/create', [AdminController::class, 'createFaq'])->name('faqs.create');
        Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faqs.store');
        Route::get('/faqs/{faq}/edit', [AdminController::class, 'editFaq'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [AdminController::class, 'updateFaq'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [AdminController::class, 'deleteFaq'])->name('faqs.delete');
        Route::post('/faqs/{faq}/publish', [AdminController::class, 'publishFaq'])->name('faqs.publish');
        Route::post('/faqs/{faq}/unpublish', [AdminController::class, 'unpublishFaq'])->name('faqs.unpublish');
        
        // Payment Verification Routes
        Route::get('/payment-verifications', [PaymentController::class, 'adminVerifications'])->name('payments.verifications');
        Route::post('/payment-proofs/{proof}/approve', [PaymentController::class, 'approvePayment'])->name('payments.approve');
        Route::post('/payment-proofs/{proof}/reject', [PaymentController::class, 'rejectPayment'])->name('payments.reject');
        
        // Payout Management Routes
        Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
        Route::get('/payouts/pending', [AdminPayoutController::class, 'pending'])->name('payouts.pending');
        Route::get('/payouts/{payout}', [AdminPayoutController::class, 'show'])->name('payouts.show');
        Route::post('/payouts/{payout}/process', [AdminPayoutController::class, 'process'])->name('payouts.process');
        Route::post('/payouts/{payout}/reject', [AdminPayoutController::class, 'reject'])->name('payouts.reject');
        Route::get('/revenue', [AdminPayoutController::class, 'revenue'])->name('revenue');
        Route::post('/revenue/fee-percentage', [AdminPayoutController::class, 'updateFeePercentage'])->name('revenue.fee-percentage');
        
        // Reports Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/dashboard', [AdminReportController::class, 'dashboard'])->name('dashboard');
            Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
            Route::get('/users', [AdminReportController::class, 'users'])->name('users');
            Route::get('/artworks', [AdminReportController::class, 'artworks'])->name('reports.artworks');
            Route::get('/marketplace', [AdminReportController::class, 'marketplace'])->name('marketplace');
            Route::get('/customers', [AdminReportController::class, 'customers'])->name('customers');
            Route::get('/financial', [AdminReportController::class, 'financial'])->name('financial');
            Route::get('/export/{type}', [AdminReportController::class, 'export'])->name('export');
        });

        // Settings Management
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings/site', [AdminController::class, 'updateSiteSettings'])->name('settings.site');
        
        // Page Content Management
        Route::prefix('page-contents')->name('page-contents.')->group(function () {
            Route::get('/', [AdminPageContentController::class, 'index'])->name('index');
            Route::get('/create', [AdminPageContentController::class, 'create'])->name('create');
            Route::post('/', [AdminPageContentController::class, 'store'])->name('store');
            Route::get('/{pageContent}/edit', [AdminPageContentController::class, 'edit'])->name('edit');
            Route::put('/{pageContent}', [AdminPageContentController::class, 'update'])->name('update');
            Route::delete('/{pageContent}', [AdminPageContentController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-update', [AdminPageContentController::class, 'bulkUpdate'])->name('bulk-update');
        });

        // Frontend Management Routes - 100% Frontend Control
        Route::prefix('frontend')->name('frontend.')->group(function () {
            Route::get('/', [AdminFrontendController::class, 'dashboard'])->name('dashboard');
            Route::get('/homepage', [AdminFrontendController::class, 'homepage'])->name('homepage');
            Route::post('/homepage/{section}', [AdminFrontendController::class, 'updateHomepageSection'])->name('homepage.update');
            Route::get('/navigation', [AdminFrontendController::class, 'navigation'])->name('navigation');
            Route::post('/navigation', [AdminFrontendController::class, 'updateNavigation'])->name('navigation.update');
            Route::get('/footer', [AdminFrontendController::class, 'footer'])->name('footer');
            Route::post('/footer', [AdminFrontendController::class, 'updateFooter'])->name('footer.update');
            Route::get('/theme', [AdminFrontendController::class, 'theme'])->name('theme');
            Route::post('/theme', [AdminFrontendController::class, 'updateTheme'])->name('theme.update');
            Route::get('/seo', [AdminFrontendController::class, 'seo'])->name('seo');
            Route::post('/seo', [AdminFrontendController::class, 'updateSeo'])->name('seo.update');
            Route::get('/social', [AdminFrontendController::class, 'social'])->name('social');
            Route::post('/social', [AdminFrontendController::class, 'updateSocial'])->name('social.update');
            Route::get('/contact', [AdminFrontendController::class, 'contact'])->name('contact');
            Route::post('/contact', [AdminFrontendController::class, 'updateContact'])->name('contact.update');
            Route::get('/announcements', [AdminFrontendController::class, 'announcements'])->name('announcements');
            Route::post('/announcements', [AdminFrontendController::class, 'updateAnnouncements'])->name('announcements.update');
            Route::post('/upload-image', [AdminFrontendController::class, 'uploadImage'])->name('upload-image');
        });

        Route::get('/settings/language', [AdminController::class, 'languageSettings'])->name('settings.language');
        Route::post('/settings/language', [AdminController::class, 'updateLanguageSettings'])->name('settings.language.update');
        Route::get('/settings/translations', [AdminController::class, 'translationEditor'])->name('settings.translations');
        Route::post('/settings/translations', [AdminController::class, 'updateTranslations'])->name('settings.translations.update');
        Route::get('/settings/currency', [AdminController::class, 'currencySettings'])->name('settings.currency');
        Route::post('/settings/currency', [AdminController::class, 'updateCurrencySettings'])->name('settings.currency.update');
        Route::get('/settings/email', [AdminController::class, 'emailSettings'])->name('settings.email');
        Route::post('/settings/email', [AdminController::class, 'updateEmailSettings'])->name('settings.email.update');
        Route::get('/settings/payment', [AdminController::class, 'paymentSettings'])->name('settings.payment');
        Route::post('/settings/payment', [AdminController::class, 'updatePaymentSettings'])->name('settings.payment.update');
        Route::get('/settings/shipping', [AdminController::class, 'shippingSettings'])->name('settings.shipping');
        Route::post('/settings/shipping', [AdminController::class, 'updateShippingSettings'])->name('settings.shipping.update');
        Route::get('/settings/security', [AdminController::class, 'securitySettings'])->name('settings.security');
        Route::post('/settings/security', [AdminController::class, 'updateSecuritySettings'])->name('settings.security.update');
        Route::get('/settings/backup', [AdminController::class, 'backupSettings'])->name('settings.backup');
        Route::post('/settings/backup', [AdminController::class, 'updateBackupSettings'])->name('settings.backup.update');
        Route::post('/settings/backup/create', [AdminController::class, 'createBackup'])->name('settings.backup.create');
        Route::get('/settings/logs', [AdminController::class, 'systemLogs'])->name('settings.logs');
        Route::get('/settings/cache', [AdminController::class, 'cacheSettings'])->name('settings.cache');
        Route::post('/settings/cache', [AdminController::class, 'updateCacheSettings'])->name('settings.cache.update');
        Route::match(['get', 'post'], '/cache/clear', [AdminController::class, 'clearCache'])->name('cache.clear');
        Route::post('/settings/cache/clear', [AdminController::class, 'clearCache'])->name('settings.cache.clear');
        Route::get('/settings/general', [AdminController::class, 'generalSettings'])->name('settings.general');
        Route::put('/settings/general', [AdminController::class, 'updateGeneralSettings'])->name('settings.general.update');
        Route::post('/settings/sync-images', [AdminController::class, 'syncImages'])->name('settings.sync.images');
        Route::get('/settings/image-optimization', [AdminController::class, 'getImageOptimizationSuggestions'])->name('settings.image.optimization');

        // Images Settings Routes
        Route::get('/settings/images', [AdminController::class, 'imagesSettings'])->name('settings.images');
        Route::put('/settings/images', [AdminController::class, 'updateImagesSettings'])->name('settings.images.update');

        // XAMPP routes for admin settings
        Route::get('/admin/settings/general', [AdminController::class, 'generalSettings'])->name('admin.settings.general');
        Route::put('/admin/settings/general', [AdminController::class, 'updateGeneralSettings'])->name('admin.settings.general.update');
        Route::post('/admin/settings/sync-images', [AdminController::class, 'syncImages'])->name('admin.settings.sync.images');
        Route::get('/admin/settings/image-optimization', [AdminController::class, 'getImageOptimizationSuggestions'])->name('admin.settings.image.optimization');

        // XAMPP routes for images settings
        Route::get('/admin/settings/images', [AdminController::class, 'imagesSettings'])->name('admin.settings.images');
        Route::put('/admin/settings/images', [AdminController::class, 'updateImagesSettings'])->name('admin.settings.images.update');
    });
    
    // Artist Routes
    Route::prefix('artist')->middleware('artist')->name('artist.')->group(function () {
        Route::get('/artworks', [ArtistController::class, 'artworks'])->name('artworks');
        Route::get('/artworks/create', [ArtistController::class, 'createArtwork'])->name('artworks.create');
        Route::post('/artworks', [ArtistController::class, 'storeArtwork'])->name('artworks.store');
        Route::get('/artworks/{artwork}/edit', [ArtistController::class, 'editArtwork'])->name('artworks.edit');
        Route::put('/artworks/{artwork}', [ArtistController::class, 'updateArtwork'])->name('artworks.update');
        Route::delete('/artworks/{artwork}', [ArtistController::class, 'deleteArtwork'])->name('artworks.delete');
        Route::get('/sales', [ArtistController::class, 'sales'])->name('sales');
        Route::get('/profile', [ArtistController::class, 'profile'])->name('profile');
        Route::put('/profile', [ArtistController::class, 'updateProfile'])->name('profile.update');
        Route::get('/followers', [ArtistController::class, 'followers'])->name('followers');
        Route::get('/analytics', [ArtistController::class, 'analytics'])->name('analytics');
        Route::get('/orders', [CustomOrderController::class, 'artistOrders'])->name('orders');
        
        // Artist Earnings Routes
        Route::get('/earnings', [ArtistEarningsController::class, 'dashboard'])->name('earnings');
        Route::get('/earnings/commissions', [ArtistEarningsController::class, 'commissions'])->name('earnings.commissions');
        Route::get('/earnings/payouts', [ArtistEarningsController::class, 'payouts'])->name('earnings.payouts');
        Route::post('/earnings/payouts/request', [ArtistEarningsController::class, 'requestPayout'])->name('earnings.payouts.request');
        Route::get('/earnings/tax-report', [ArtistEarningsController::class, 'downloadTaxReport'])->name('earnings.tax-report');
    });
    
    // Collector Routes
    Route::prefix('collector')->middleware('collector')->name('collector.')->group(function () {
        Route::get('/dashboard', [CollectorController::class, 'dashboard'])->name('dashboard');
        Route::get('/artworks', [CollectorController::class, 'artworks'])->name('collector.artworks');
        Route::get('/purchases', [CollectorController::class, 'purchases'])->name('purchases');
        Route::get('/resales', [CollectorController::class, 'resales'])->name('resales');
        Route::get('/resales/create/{artwork}', [CollectorController::class, 'createResale'])->name('resales.create');
        Route::post('/resales', [CollectorController::class, 'storeResale'])->name('resales.store');
        Route::get('/wishlist', [CollectorController::class, 'wishlist'])->name('wishlist');
        Route::get('/following', [CollectorController::class, 'following'])->name('following');
        Route::get('/profile', [CollectorController::class, 'profile'])->name('profile');
        Route::put('/profile', [CollectorController::class, 'updateProfile'])->name('profile.update');
        Route::post('/ownership/{artwork}/transfer', [CollectorController::class, 'transferOwnership'])->name('ownership.transfer');
        Route::get('/ownership/{ownership}/certificate', [CollectorController::class, 'downloadCertificate'])->name('ownership.certificate');
        Route::get('/analytics', [CollectorController::class, 'analytics'])->name('analytics');
        Route::post('/artworks/{artwork}/like', [CollectorController::class, 'toggleLike'])->name('collector.artworks.like');
    });
});





Route::get('/collections', function () {
    return view('collections.index');
})->name('collections');

// Certificate Verification Form (no code provided)
Route::get('/verify', function () {
    return view('verify.index');
})->name('verify.form');

// Documentation Route
Route::get('/documentations', function () {
    return view('documentations');
})->name('documentations');

// Sitemap Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemaps/artists.xml', [SitemapController::class, 'artists'])->name('sitemap.artists');
Route::get('/sitemaps/artworks.xml', [SitemapController::class, 'artworks'])->name('sitemap.artworks');
Route::get('/sitemaps/blogs.xml', [SitemapController::class, 'blogs'])->name('sitemap.blogs');
Route::get('/sitemaps/collections.xml', [SitemapController::class, 'collections'])->name('sitemap.collections');
Route::get('/sitemap_index.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index');

// Public pages XAMPP compatibility routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/public/about', function () {
        return view('about');
    })->name('about.public');

    Route::get('/public/faq', function () {
        return view('faq');
    })->name('faq.public');

    Route::get('/public/privacy', function () {
        return view('privacy');
    })->name('privacy.public');

    Route::get('/public/terms', function () {
        return view('terms');
    })->name('terms.public');

    Route::get('/public/cookies', function () {
        return view('cookies');
    })->name('cookies.public');
});
