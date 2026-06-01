<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\ArtistManagementController;
use App\Http\Controllers\Api\Admin\ArtworkModerationController;
use App\Http\Controllers\Api\Admin\SettingsController;
use App\Http\Controllers\Api\Admin\ExhibitionManagementController;
use App\Http\Controllers\Api\Admin\BlogManagementController;
use App\Http\Controllers\Api\Admin\CollectionManagementController;
use App\Http\Controllers\Api\Admin\CommissionManagementController;
use App\Http\Controllers\Api\Admin\PayoutManagementController;
use App\Http\Controllers\Api\Admin\ShipmentManagementController;
use App\Http\Controllers\Api\Admin\PaymentProofManagementController;
use App\Http\Controllers\Api\Admin\PromoCodeManagementController;
use App\Http\Controllers\Api\Admin\ContactMessageManagementController;
use App\Http\Controllers\Api\Admin\FaqManagementController;
use App\Http\Controllers\Api\Admin\PageContentManagementController;
use App\Http\Controllers\Api\Admin\ExchangeRateManagementController;
use App\Http\Controllers\Api\Admin\RoleManagementController;
use App\Http\Controllers\Api\Admin\PermissionManagementController;
use App\Http\Controllers\Api\Admin\CategoryManagementController;
use App\Http\Controllers\Api\Admin\OrderManagementController;
use App\Http\Controllers\Api\Admin\TransactionManagementController;
use App\Http\Controllers\Api\Public\ArtworkController as PublicArtworkController;
use App\Http\Controllers\Api\Public\ArtistController as PublicArtistController;
use App\Http\Controllers\Api\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Public Routes
Route::prefix('public')->group(function () {
    Route::get('artworks', [PublicArtworkController::class, 'index']);
    Route::get('artworks/{slug}', [PublicArtworkController::class, 'show']);
    Route::get('artworks/{slug}/similar', [PublicArtworkController::class, 'similar']);
    Route::get('categories', [PublicArtworkController::class, 'categories']);
    
    Route::get('artists', [PublicArtistController::class, 'index']);
    Route::get('artists/{slug}', [PublicArtistController::class, 'show']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Analytics
    Route::get('dashboard', [AnalyticsController::class, 'dashboard']);
    Route::get('health', [SettingsController::class, 'health']);
    
    // User Management
    Route::apiResource('users', UserManagementController::class);
    Route::post('users/{id}/approve', [UserManagementController::class, 'approve']);
    Route::post('users/{id}/reject', [UserManagementController::class, 'reject']);
    Route::post('users/bulk-action', [UserManagementController::class, 'bulkAction']);
    
    // Artist Management
    Route::apiResource('artists', ArtistManagementController::class);
    Route::post('artists/{id}/approve', [ArtistManagementController::class, 'approve']);
    Route::post('artists/{id}/reject', [ArtistManagementController::class, 'reject']);
    
    // Artwork Moderation
    Route::apiResource('artworks', ArtworkModerationController::class);
    Route::post('artworks/{id}/approve', [ArtworkModerationController::class, 'approve']);
    Route::post('artworks/{id}/reject', [ArtworkModerationController::class, 'reject']);
    
    // Resale Moderation
    Route::get('resales', [ArtworkModerationController::class, 'resales']);
    Route::post('resales/{id}/approve', [ArtworkModerationController::class, 'approveResale']);

    // Settings
    Route::get('settings', [SettingsController::class, 'index']);
    Route::put('settings', [SettingsController::class, 'update']);
    
    // Exhibition Management
    Route::apiResource('exhibitions', ExhibitionManagementController::class);
    Route::post('exhibitions/{id}/restore', [ExhibitionManagementController::class, 'restore']);
    Route::post('exhibitions/{id}/artists', [ExhibitionManagementController::class, 'addArtists']);
    Route::delete('exhibitions/{id}/artists', [ExhibitionManagementController::class, 'removeArtists']);
    Route::post('exhibitions/{id}/artworks', [ExhibitionManagementController::class, 'addArtworks']);
    Route::delete('exhibitions/{id}/artworks', [ExhibitionManagementController::class, 'removeArtworks']);
    Route::put('exhibitions/{id}/status', [ExhibitionManagementController::class, 'updateStatus']);
    Route::post('exhibitions/{id}/toggle-featured', [ExhibitionManagementController::class, 'toggleFeatured']);
    Route::post('exhibitions/{id}/toggle-published', [ExhibitionManagementController::class, 'togglePublished']);
    
    // Blog Management
    Route::apiResource('blogs', BlogManagementController::class);
    Route::post('blogs/{id}/restore', [BlogManagementController::class, 'restore']);
    Route::put('blogs/{id}/status', [BlogManagementController::class, 'updateStatus']);
    Route::post('blogs/{id}/toggle-featured', [BlogManagementController::class, 'toggleFeatured']);
    
    // Collection Management
    Route::apiResource('collections', CollectionManagementController::class);
    Route::post('collections/{id}/artworks', [CollectionManagementController::class, 'addArtworks']);
    Route::delete('collections/{id}/artworks', [CollectionManagementController::class, 'removeArtworks']);
    Route::put('collections/{id}/order', [CollectionManagementController::class, 'updateOrder']);
    Route::post('collections/{id}/toggle-featured', [CollectionManagementController::class, 'toggleFeatured']);
    Route::post('collections/{id}/toggle-active', [CollectionManagementController::class, 'toggleActive']);
    
    // Commission Management
    Route::apiResource('commissions', CommissionManagementController::class)->only(['index', 'show', 'update']);
    Route::post('commissions/{id}/mark-paid', [CommissionManagementController::class, 'markAsPaid']);
    Route::get('commissions/statistics', [CommissionManagementController::class, 'statistics']);
    Route::post('commissions/bulk-mark-paid', [CommissionManagementController::class, 'bulkMarkAsPaid']);
    
    // Payout Management
    Route::apiResource('payouts', PayoutManagementController::class)->only(['index', 'show', 'update']);
    Route::post('payouts/{id}/process', [PayoutManagementController::class, 'process']);
    Route::post('payouts/{id}/complete', [PayoutManagementController::class, 'complete']);
    Route::post('payouts/{id}/reject', [PayoutManagementController::class, 'reject']);
    Route::get('payouts/statistics', [PayoutManagementController::class, 'statistics']);
    Route::post('payouts/bulk-process', [PayoutManagementController::class, 'bulkProcess']);
    
    // Shipment Management
    Route::apiResource('shipments', ShipmentManagementController::class)->only(['index', 'show', 'update']);
    Route::put('shipments/{id}/status', [ShipmentManagementController::class, 'updateStatus']);
    Route::post('shipments/{id}/tracking-event', [ShipmentManagementController::class, 'addTrackingEvent']);
    Route::get('shipments/{id}/track', [ShipmentManagementController::class, 'track']);
    Route::get('shipments/statistics', [ShipmentManagementController::class, 'statistics']);
    
    // Payment Proof Management
    Route::apiResource('payment-proofs', PaymentProofManagementController::class)->only(['index', 'show', 'update']);
    Route::post('payment-proofs/{id}/approve', [PaymentProofManagementController::class, 'approve']);
    Route::post('payment-proofs/{id}/reject', [PaymentProofManagementController::class, 'reject']);
    Route::get('payment-proofs/statistics', [PaymentProofManagementController::class, 'statistics']);
    Route::post('payment-proofs/bulk-approve', [PaymentProofManagementController::class, 'bulkApprove']);
    Route::post('payment-proofs/bulk-reject', [PaymentProofManagementController::class, 'bulkReject']);
    
    // Promo Code Management
    Route::apiResource('promo-codes', PromoCodeManagementController::class);
    Route::post('promo-codes/{id}/toggle-active', [PromoCodeManagementController::class, 'toggleActive']);
    Route::post('promo-codes/{id}/reset-usage', [PromoCodeManagementController::class, 'resetUsage']);
    Route::get('promo-codes/statistics', [PromoCodeManagementController::class, 'statistics']);
    
    // Contact Message Management
    Route::apiResource('contact-messages', ContactMessageManagementController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('contact-messages/{id}/mark-read', [ContactMessageManagementController::class, 'markAsRead']);
    Route::post('contact-messages/{id}/mark-unread', [ContactMessageManagementController::class, 'markAsUnread']);
    Route::put('contact-messages/{id}/status', [ContactMessageManagementController::class, 'updateStatus']);
    Route::post('contact-messages/{id}/respond', [ContactMessageManagementController::class, 'respond']);
    Route::post('contact-messages/bulk-mark-read', [ContactMessageManagementController::class, 'bulkMarkAsRead']);
    Route::post('contact-messages/bulk-delete', [ContactMessageManagementController::class, 'bulkDelete']);
    Route::get('contact-messages/statistics', [ContactMessageManagementController::class, 'statistics']);
    
    // FAQ Management
    Route::apiResource('faqs', FaqManagementController::class);
    Route::post('faqs/{id}/toggle-published', [FaqManagementController::class, 'togglePublished']);
    Route::put('faqs/order', [FaqManagementController::class, 'updateOrder']);
    Route::get('faqs/categories', [FaqManagementController::class, 'categories']);
    Route::get('faqs/statistics', [FaqManagementController::class, 'statistics']);
    
    // Page Content Management
    Route::apiResource('page-contents', PageContentManagementController::class);
    Route::post('page-contents/{id}/toggle-active', [PageContentManagementController::class, 'toggleActive']);
    Route::post('page-contents/bulk-update', [PageContentManagementController::class, 'bulkUpdate']);
    Route::get('page-contents/by-page-section', [PageContentManagementController::class, 'getByPageSection']);
    Route::get('page-contents/pages', [PageContentManagementController::class, 'pages']);
    Route::get('page-contents/sections', [PageContentManagementController::class, 'sections']);
    
    // Exchange Rate Management
    Route::apiResource('exchange-rates', ExchangeRateManagementController::class);
    Route::get('exchange-rates/current', [ExchangeRateManagementController::class, 'getCurrentRate']);
    Route::get('exchange-rates/history', [ExchangeRateManagementController::class, 'getHistory']);
    Route::post('exchange-rates/auto-update', [ExchangeRateManagementController::class, 'autoUpdate']);
    Route::get('exchange-rates/statistics', [ExchangeRateManagementController::class, 'statistics']);
    
    // Role Management
    Route::apiResource('roles', RoleManagementController::class);
    Route::post('roles/{id}/permissions', [RoleManagementController::class, 'assignPermissions']);
    Route::get('roles/available-permissions', [RoleManagementController::class, 'availablePermissions']);
    Route::post('roles/{id}/toggle-active', [RoleManagementController::class, 'toggleActive']);
    
    // Permission Management
    Route::apiResource('permissions', PermissionManagementController::class);
    Route::get('permissions/modules', [PermissionManagementController::class, 'modules']);
    Route::post('permissions/{id}/toggle-active', [PermissionManagementController::class, 'toggleActive']);
    
    // Category Management
    Route::apiResource('categories', CategoryManagementController::class);
    Route::post('categories/{id}/toggle-active', [CategoryManagementController::class, 'toggleActive']);
    Route::put('categories/order', [CategoryManagementController::class, 'updateOrder']);
    
    // Order Management
    Route::apiResource('orders', OrderManagementController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{id}/cancel', [OrderManagementController::class, 'cancel']);
    Route::post('orders/{id}/refund', [OrderManagementController::class, 'refund']);
    Route::post('orders/{id}/hold', [OrderManagementController::class, 'putOnHold']);
    Route::post('orders/{id}/release', [OrderManagementController::class, 'releaseFromHold']);
    Route::get('orders/statistics', [OrderManagementController::class, 'statistics']);
    
    // Transaction Management
    Route::apiResource('transactions', TransactionManagementController::class)->only(['index', 'show', 'update']);
    Route::post('transactions/{id}/complete', [TransactionManagementController::class, 'complete']);
    Route::get('transactions/statistics', [TransactionManagementController::class, 'statistics']);
});
