<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;

class CurrencyStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'currency:status {--currency= : Show status for specific currency}';

    /**
     * The console command description.
     */
    protected $description = 'Display current currency configuration and exchange rates';

    /**
     * Execute the console command.
     */
    public function handle(CurrencyService $currencyService): int
    {
        $this->info('=== Currency Status ===');
        
        // Configuration
        $this->info("\nConfiguration:");
        $this->line("Default Currency: " . config('currency.default'));
        $this->line("Current Currency: " . $currencyService->getCurrentCurrency());
        $this->line("Auto Update: " . (config('currency.auto_update') ? 'Enabled' : 'Disabled'));
        $this->line("API Service: " . config('currency.api.service', 'None'));

        // Supported currencies
        $this->info("\nSupported Currencies:");
        $supported = $currencyService->getSupportedCurrencies();
        $this->table(
            ['Code', 'Name', 'Symbol', 'Precision'],
            collect($supported)->map(function ($config, $code) {
                return [$code, $config['name'], $config['symbol'], $config['precision']];
            })
        );

        // Current exchange rates
        $this->info("\nCurrent Exchange Rates:");
        $rates = $currencyService->getExchangeRates();
        $baseCurrency = config('currency.default');
        
        $this->line("Base Currency: {$baseCurrency}");
        
        $ratesTable = [];
        foreach ($rates as $currency => $rate) {
            if ($currency !== $baseCurrency) {
                $ratesTable[] = [
                    $baseCurrency . ' → ' . $currency,
                    number_format($rate, 6),
                    '1 ' . $baseCurrency . ' = ' . number_format($rate, 2) . ' ' . $currency
                ];
            }
        }
        
        if (!empty($ratesTable)) {
            $this->table(['Pair', 'Rate', 'Equivalent'], $ratesTable);
        }

        // Historical rates summary
        $this->info("\nHistorical Rates Summary:");
        $totalRecords = ExchangeRate::count();
        $latestUpdate = ExchangeRate::orderBy('effective_date', 'desc')->first();
        
        $this->line("Total Records: {$totalRecords}");
        $this->line("Latest Update: " . ($latestUpdate ? $latestUpdate->effective_date->format('Y-m-d H:i:s') : 'None'));

        // Recent rates for specific currency if requested
        if ($this->option('currency')) {
            $currency = strtoupper($this->option('currency'));
            $this->showCurrencyHistory($currencyService, $currency);
        }

        return 0;
    }

    /**
     * Show historical rates for a specific currency.
     */
    private function showCurrencyHistory(CurrencyService $currencyService, string $currency): void
    {
        if (!$currencyService->isSupported($currency)) {
            $this->error("Currency {$currency} is not supported.");
            return;
        }

        $this->info("\nRecent History for {$currency}:");
        
        $baseCurrency = config('currency.default');
        $recentRates = ExchangeRate::where('from_currency', $baseCurrency)
                                  ->where('to_currency', $currency)
                                  ->orderBy('effective_date', 'desc')
                                  ->limit(10)
                                  ->get();

        if ($recentRates->isEmpty()) {
            $this->line("No historical data found for {$currency}.");
            return;
        }

        $this->table(
            ['Date', 'Rate', 'Change'],
            $recentRates->map(function ($rate, $key) use ($recentRates) {
                $change = '';
                if ($key < $recentRates->count() - 1) {
                    $prevRate = $recentRates->get($key + 1);
                    $diff = $rate->rate - $prevRate->rate;
                    $change = $diff > 0 ? '↑' . number_format($diff, 6) : '↓' . number_format(abs($diff), 6);
                }
                
                return [
                    $rate->effective_date->format('Y-m-d H:i'),
                    number_format($rate->rate, 6),
                    $change
                ];
            })
        );
    }
}
