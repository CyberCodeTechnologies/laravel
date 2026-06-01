<?php

namespace App\Services;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class CurrencyService
{
    /**
     * Get the current currency.
     */
    public function getCurrentCurrency(): string
    {
        $defaultCurrency = GeneralSetting::getValue('default_currency', config('currency.default', 'USD'));
        return Session::get(config('currency.session_key'), $defaultCurrency);
    }

    /**
     * Set the current currency.
     */
    public function setCurrentCurrency(string $currency): void
    {
        if ($this->isSupported($currency)) {
            Session::put(config('currency.session_key'), $currency);
            Cookie::queue(
                config('currency.cookie.name'),
                $currency,
                config('currency.cookie.expiration'),
                config('currency.cookie.path'),
                config('currency.cookie.domain'),
                config('currency.cookie.secure'),
                config('currency.cookie.http_only')
            );
        }
    }

    /**
     * Get all supported currencies.
     */
    public function getSupportedCurrencies(): array
    {
        $enabledCurrencies = GeneralSetting::getValue('enabled_currencies');
        if ($enabledCurrencies) {
            $enabledArray = json_decode($enabledCurrencies, true);
            $allSupported = config('currency.supported', []);
            return array_intersect_key($allSupported, array_flip($enabledArray));
        }
        return config('currency.supported', []);
    }

    /**
     * Check if a currency is supported.
     */
    public function isSupported(string $currency): bool
    {
        return array_key_exists($currency, $this->getSupportedCurrencies());
    }

    /**
     * Convert amount from one currency to another.
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = $this->getExchangeRates();
        
        if (!isset($rates[$from]) || !isset($rates[$to])) {
            throw new \InvalidArgumentException("Exchange rate not available for {$from} to {$to}");
        }

        // Convert to base currency first, then to target currency
        $baseAmount = $amount / $rates[$from];
        return $baseAmount * $rates[$to];
    }

    /**
     * Convert amount to the current currency.
     */
    public function convertToCurrent(float $amount, string $fromCurrency): float
    {
        return $this->convert($amount, $fromCurrency, $this->getCurrentCurrency());
    }

    /**
     * Get exchange rates.
     */
    public function getExchangeRates(): array
    {
        return Cache::remember('exchange_rates', 3600, function () {
            $dbRateMMK = GeneralSetting::getValue('exchange_rate_mmk');
            $configRates = config('currency.exchange_rates', []);
            
            if ($dbRateMMK !== null) {
                $configRates['MMK'] = (float) $dbRateMMK;
            }
            
            return $configRates;
        });
    }

    /**
     * Update exchange rates.
     */
    public function updateExchangeRates(array $rates): void
    {
        Cache::put('exchange_rates', $rates, 3600);
    }

    /**
     * Format amount according to currency rules.
     */
    public function format(float $amount, string $currency = null): string
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        $config = $this->getCurrencyConfig($currency);

        if (!$config) {
            throw new \InvalidArgumentException("Currency {$currency} is not supported");
        }

        $formattedAmount = number_format(
            $amount,
            $config['precision'],
            $config['decimal_separator'],
            $config['thousands_separator']
        );

        if ($config['position'] === 'before') {
            return $config['symbol'] . $formattedAmount;
        }

        return $formattedAmount . $config['symbol'];
    }

    /**
     * Get currency configuration.
     */
    public function getCurrencyConfig(string $currency): ?array
    {
        return $this->getSupportedCurrencies()[$currency] ?? null;
    }

    /**
     * Get currency symbol.
     */
    public function getSymbol(string $currency = null): string
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        $config = $this->getCurrencyConfig($currency);
        
        return $config['symbol'] ?? '';
    }

    /**
     * Get currency name.
     */
    public function getName(string $currency = null): string
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        $config = $this->getCurrencyConfig($currency);
        
        return $config['name'] ?? '';
    }

    /**
     * Initialize currency from cookie if available.
     */
    public function initializeFromCookie(): void
    {
        if (!Session::has(config('currency.session_key'))) {
            $currency = Cookie::get(config('currency.cookie.name'));
            if ($currency && $this->isSupported($currency)) {
                Session::put(config('currency.session_key'), $currency);
            }
        }
    }

    /**
     * Get exchange rate between two currencies.
     */
    public function getExchangeRate(string $from, string $to): float
    {
        if ($from === $to) {
            return 1.0;
        }

        $rates = $this->getExchangeRates();
        
        if (!isset($rates[$from]) || !isset($rates[$to])) {
            throw new \InvalidArgumentException("Exchange rate not available for {$from} to {$to}");
        }

        return $rates[$to] / $rates[$from];
    }

    /**
     * Calculate price in different currencies.
     */
    public function calculatePriceInCurrencies(float $baseAmount, string $baseCurrency): array
    {
        $prices = [];
        $supportedCurrencies = $this->getSupportedCurrencies();

        foreach ($supportedCurrencies as $currency => $config) {
            $prices[$currency] = [
                'amount' => $this->convert($baseAmount, $baseCurrency, $currency),
                'formatted' => $this->format($this->convert($baseAmount, $baseCurrency, $currency), $currency),
                'symbol' => $config['symbol'],
                'code' => $currency,
            ];
        }

        return $prices;
    }
}
