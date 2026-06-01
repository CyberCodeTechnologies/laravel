<?php

namespace App\Providers;

use App\Services\CurrencyService;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrencyService::class, function ($app) {
            return new CurrencyService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register blade components
        $this->loadViewComponentsAs('currency', [
            \App\View\Components\Currency::class,
            \App\View\Components\Price::class,
        ]);
    }
}
