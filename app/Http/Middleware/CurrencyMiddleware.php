<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrencyMiddleware
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip currency handling for the currency switch endpoint (controller handles it)
        if ($request->is('currency/switch')) {
            // Share currency data with all views
            view()->share('currentCurrency', $this->currencyService->getCurrentCurrency());
            view()->share('currencyService', $this->currencyService);
            view()->share('supportedCurrencies', $this->currencyService->getSupportedCurrencies());

            return $next($request);
        }

        // Initialize currency from cookie if session doesn't have it
        $this->currencyService->initializeFromCookie();

        // Handle currency switching from query parameter or JSON body
        $newCurrency = $request->input('currency');
        if ($newCurrency && $newCurrency !== $this->currencyService->getCurrentCurrency()) {
            if ($this->currencyService->isSupported($newCurrency)) {
                $this->currencyService->setCurrentCurrency($newCurrency);
            }
        }

        // Share currency data with all views
        view()->share('currentCurrency', $this->currencyService->getCurrentCurrency());
        view()->share('currencyService', $this->currencyService);
        view()->share('supportedCurrencies', $this->currencyService->getSupportedCurrencies());

        return $next($request);
    }
}
