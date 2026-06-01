<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'currency:update-rates {--force : Force update even if recently updated}';

    /**
     * The console command description.
     */
    protected $description = 'Update currency exchange rates from external API';

    /**
     * Execute the console command.
     */
    public function handle(CurrencyService $currencyService): int
    {
        $this->info('Updating currency exchange rates...');

        if (!config('currency.auto_update')) {
            $this->error('Auto update is disabled in currency configuration.');
            return 1;
        }

        $apiService = config('currency.api.service');
        $apiKey = config('currency.api.key');

        if (!$apiKey) {
            $this->error('Exchange rate API key is not configured.');
            return 1;
        }

        try {
            $rates = $this->fetchRatesFromAPI($apiService, $apiKey);
            
            if (empty($rates)) {
                $this->error('Failed to fetch rates from API.');
                return 1;
            }

            // Update currency service rates
            $currencyService->updateExchangeRates($rates);

            // Record historical rates
            $this->recordHistoricalRates($rates);

            $this->info('Exchange rates updated successfully!');
            $this->table(['Currency', 'Rate'], collect($rates)->map(function ($rate, $currency) {
                return [$currency, $rate];
            }));

            return 0;
        } catch (\Exception $e) {
            $this->error('Error updating exchange rates: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Fetch rates from external API.
     */
    private function fetchRatesFromAPI(string $service, string $apiKey): array
    {
        $baseCurrency = config('currency.default', 'USD');
        
        switch ($service) {
            case 'fixer':
                return $this->fetchFromFixer($apiKey, $baseCurrency);
            
            case 'exchangerate-api':
                return $this->fetchFromExchangeRateAPI($apiKey, $baseCurrency);
            
            default:
                throw new \InvalidArgumentException("Unsupported API service: {$service}");
        }
    }

    /**
     * Fetch rates from Fixer.io API.
     */
    private function fetchFromFixer(string $apiKey, string $baseCurrency): array
    {
        $response = Http::get("http://data.fixer.io/api/latest", [
            'access_key' => $apiKey,
            'base' => $baseCurrency,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch rates from Fixer API');
        }

        $data = $response->json();

        if (!$data['success']) {
            throw new \Exception('Fixer API error: ' . ($data['error']['info'] ?? 'Unknown error'));
        }

        return $data['rates'];
    }

    /**
     * Fetch rates from ExchangeRate-API.
     */
    private function fetchFromExchangeRateAPI(string $apiKey, string $baseCurrency): array
    {
        $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}");

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch rates from ExchangeRate-API');
        }

        $data = $response->json();

        if ($data['result'] !== 'success') {
            throw new \Exception('ExchangeRate-API error: ' . ($data['error-type'] ?? 'Unknown error'));
        }

        return $data['conversion_rates'];
    }

    /**
     * Record historical rates in database.
     */
    private function recordHistoricalRates(array $rates): void
    {
        $baseCurrency = config('currency.default', 'USD');
        $supportedCurrencies = array_keys(config('currency.supported', []));

        foreach ($supportedCurrencies as $targetCurrency) {
            if ($targetCurrency === $baseCurrency) {
                continue;
            }

            if (isset($rates[$targetCurrency])) {
                ExchangeRate::recordRate(
                    $baseCurrency,
                    $targetCurrency,
                    $rates[$targetCurrency]
                );
            }
        }
    }
}
