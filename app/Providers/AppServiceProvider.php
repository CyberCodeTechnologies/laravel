<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optionally force HTTPS scheme for generated URLs. Controlled by FORCE_HTTPS env var.
        if (env('FORCE_HTTPS', true) && !$this->app->runningInConsole()) {
            URL::forceScheme('https');
        }

        // Ensure Vite asset URLs include the application's base path when served from a subdirectory
        Vite::createAssetPathsUsing(function (string $path) {
            $base = rtrim(str_replace('/public/public', '/public', url('/')), '/');

            return $base.'/'.ltrim($path, '/');
        });
    }
}
