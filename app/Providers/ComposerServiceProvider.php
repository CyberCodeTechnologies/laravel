<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\CurrencyService;
use App\Models\Cart;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share currency variables with all views
        View::composer('*', function ($view) {
            $currencyService = app(CurrencyService::class);
            
            // Get cart count for all views
            $cartCount = 0;
            try {
                $cart = Cart::getOrCreateCart();
                $cartCount = $cart->item_count;
            } catch (\Exception $e) {
                // Silently fail if cart tables don't exist yet
            }
            
            $view->with([
                'currentCurrency' => $currencyService->getCurrentCurrency(),
                'supportedCurrencies' => $currencyService->getSupportedCurrencies(),
                'cartCount' => $cartCount,
            ]);
        });

        // Share admin URL helper with all admin views
        View::composer(['admin.*', 'layouts.admin'], function ($view) {
            $view->with('adminUrl', fn (string $name, array $params = []) => \App\Helpers\AdminRouteHelper::url($name, $params));
            $view->with('adminRoute', fn (string $name, array $params = []) => \App\Helpers\AdminRouteHelper::url("admin.{$name}", $params));
        });
    }
}
