<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Switch to a different currency.
     */
    public function switch(Request $request): JsonResponse
    {
        $currency = $request->input('currency');
        
        if (!$this->currencyService->isSupported($currency)) {
            return response()->json([
                'success' => false,
                'message' => 'Currency not supported',
            ], 400);
        }

        $this->currencyService->setCurrentCurrency($currency);

        return response()->json([
            'success' => true,
            'currency' => $currency,
            'symbol' => $this->currencyService->getSymbol($currency),
            'name' => $this->currencyService->getName($currency),
        ]);
    }

    /**
     * Get current currency information.
     */
    public function current(): JsonResponse
    {
        $currency = $this->currencyService->getCurrentCurrency();
        
        return response()->json([
            'currency' => $currency,
            'symbol' => $this->currencyService->getSymbol($currency),
            'name' => $this->currencyService->getName($currency),
            'supported' => $this->currencyService->getSupportedCurrencies(),
        ]);
    }

    /**
     * Convert amount between currencies.
     */
    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        try {
            $convertedAmount = $this->currencyService->convert(
                $request->input('amount'),
                $request->input('from'),
                $request->input('to')
            );

            return response()->json([
                'success' => true,
                'original_amount' => $request->input('amount'),
                'converted_amount' => $convertedAmount,
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'formatted_original' => $this->currencyService->format($request->input('amount'), $request->input('from')),
                'formatted_converted' => $this->currencyService->format($convertedAmount, $request->input('to')),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get exchange rates.
     */
    public function rates(): JsonResponse
    {
        return response()->json([
            'rates' => $this->currencyService->getExchangeRates(),
            'base_currency' => config('currency.default'),
            'last_updated' => now()->toISOString(),
        ]);
    }

    /**
     * Get prices in all supported currencies.
     */
    public function prices(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
        ]);

        try {
            $prices = $this->currencyService->calculatePriceInCurrencies(
                $request->input('amount'),
                $request->input('currency')
            );

            return response()->json([
                'success' => true,
                'prices' => $prices,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
