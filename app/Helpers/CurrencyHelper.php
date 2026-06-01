<?php

namespace App\Helpers;

use App\Services\CurrencyService;

class CurrencyHelper
{
    /**
     * Format amount according to current currency.
     */
    public static function format(?float $amount, string $currency = null): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->format($amount ?? 0, $currency);
    }

    /**
     * Convert amount to current currency and format.
     */
    public static function convertAndFormat(float $amount, string $fromCurrency): string
    {
        $currencyService = app(CurrencyService::class);
        $convertedAmount = $currencyService->convertToCurrent($amount, $fromCurrency);
        return $currencyService->format($convertedAmount);
    }

    /**
     * Get current currency symbol.
     */
    public static function symbol(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getSymbol();
    }

    /**
     * Get current currency code.
     */
    public static function code(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getCurrentCurrency();
    }

    /**
     * Convert amount to current currency.
     */
    public static function convert(float $amount, string $fromCurrency): float
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->convertToCurrent($amount, $fromCurrency);
    }

    /**
     * Format price for artwork in current currency.
     */
    public static function formatArtworkPrice($artwork): string
    {
        if (method_exists($artwork, 'getPriceInCurrency')) {
            $currentCurrency = app(CurrencyService::class)->getCurrentCurrency();
            return $artwork->getFormattedPriceInCurrency($currentCurrency);
        }

        return self::format($artwork->price ?? 0, $artwork->currency ?? 'USD');
    }

    /**
     * Format transaction price in current currency.
     */
    public static function formatTransactionPrice($transaction): string
    {
        if (method_exists($transaction, 'getPriceInCurrency')) {
            $currentCurrency = app(CurrencyService::class)->getCurrentCurrency();
            return $transaction->getFormattedPriceInCurrency($currentCurrency);
        }

        return self::format($transaction->price ?? 0, $transaction->currency ?? 'USD');
    }

    /**
     * Format resale price in current currency.
     */
    public static function formatResalePrice($resale): string
    {
        if (method_exists($resale, 'getPriceInCurrency')) {
            $currentCurrency = app(CurrencyService::class)->getCurrentCurrency();
            return $resale->getFormattedPriceInCurrency($currentCurrency);
        }

        return self::format($resale->price ?? 0, $resale->currency ?? 'USD');
    }

    /**
     * Get exchange rate between two currencies.
     */
    public static function exchangeRate(string $from, string $to): float
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getExchangeRate($from, $to);
    }

    /**
     * Check if currency is supported.
     */
    public static function isSupported(string $currency): bool
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->isSupported($currency);
    }

    /**
     * Get all supported currencies.
     */
    public static function getSupportedCurrencies(): array
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getSupportedCurrencies();
    }
}

if (!function_exists('format_price')) {
    /**
     * Global helper to format price
     */
    function format_price($amount, $currency = null): string
    {
        return \App\Helpers\CurrencyHelper::format((float) $amount, $currency);
    }
}

if (!function_exists('convert_and_format_price')) {
    /**
     * Global helper to convert and format price
     */
    function convert_and_format_price(float $amount, string $fromCurrency): string
    {
        return \App\Helpers\CurrencyHelper::convertAndFormat($amount, $fromCurrency);
    }
}
