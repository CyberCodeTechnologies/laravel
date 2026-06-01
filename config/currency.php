<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This option determines the default currency that will be used throughout
    | the application when no specific currency is specified. Users can
    | override this setting through the currency switcher.
    |
    */
    'default' => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | Here you may specify which currencies are supported by your application.
    | These currencies will be available in the currency switcher and can
    | be used for pricing and transactions.
    |
    */
    'supported' => [
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'code' => 'USD',
            'precision' => 2,
            'decimal_separator' => '.',
            'thousands_separator' => ',',
            'position' => 'before', // 'before' or 'after'
        ],
        'MMK' => [
            'name' => 'Myanmar Kyat',
            'symbol' => 'Ks',
            'code' => 'MMK',
            'precision' => 0,
            'decimal_separator' => '.',
            'thousands_separator' => ',',
            'position' => 'before',
        ],
        'EUR' => [
            'name' => 'Euro',
            'symbol' => '€',
            'code' => 'EUR',
            'precision' => 2,
            'decimal_separator' => ',',
            'thousands_separator' => '.',
            'position' => 'before',
        ],
        'GBP' => [
            'name' => 'British Pound',
            'symbol' => '£',
            'code' => 'GBP',
            'precision' => 2,
            'decimal_separator' => '.',
            'thousands_separator' => ',',
            'position' => 'before',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates
    |--------------------------------------------------------------------------
    |
    | Exchange rates relative to the default currency. These rates are used
    | for currency conversion throughout the application. You can update
    | these rates manually or integrate with an exchange rate API.
    |
    */
    'exchange_rates' => [
        'USD' => 1.0, // Base currency
        'MMK' => env('EXCHANGE_RATE_MMK', 4400.0), // 1 USD = 4400 MMK
        'EUR' => env('EXCHANGE_RATE_EUR', 0.92), // 1 USD = 0.92 EUR (example rate)
        'GBP' => env('EXCHANGE_RATE_GBP', 0.79), // 1 USD = 0.79 GBP (example rate)
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Update Exchange Rates
    |--------------------------------------------------------------------------
    |
    | Enable automatic exchange rate updates from external API. When enabled,
    | the system will periodically fetch updated rates from the configured
    | exchange rate service.
    |
    */
    'auto_update' => env('AUTO_UPDATE_EXCHANGE_RATES', false),

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate API
    |--------------------------------------------------------------------------
    |
    | Configuration for external exchange rate API service. This is used
    | when auto_update is enabled to fetch current exchange rates.
    |
    */
    'api' => [
        'service' => env('EXCHANGE_RATE_API', 'fixer'),
        'key' => env('EXCHANGE_RATE_API_KEY'),
        'url' => env('EXCHANGE_RATE_API_URL'),
        'update_frequency' => env('EXCHANGE_RATE_UPDATE_FREQUENCY', 24), // hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Session Key
    |--------------------------------------------------------------------------
    |
    | The session key used to store the user's selected currency.
    |
    */
    'session_key' => 'currency',

    /*
    |--------------------------------------------------------------------------
    | Currency Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the currency preference cookie.
    |
    */
    'cookie' => [
        'name' => 'currency',
        'expiration' => 60 * 24 * 30, // 30 days
        'path' => '/',
        'domain' => null,
        'secure' => env('APP_ENV') === 'production',
        'http_only' => true,
    ],
];
