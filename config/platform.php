<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Fee Percentage
    |--------------------------------------------------------------------------
    |
    | This is the percentage fee charged by the platform on each sale.
    | This can be modified through the admin panel settings.
    | Default is 30% (0.30 as decimal).
    |
    */
    'fee_percentage' => env('PLATFORM_FEE_PERCENTAGE', 30.00),

    /*
    |--------------------------------------------------------------------------
    | Minimum Platform Fee
    |--------------------------------------------------------------------------
    |
    | The minimum fee amount charged per transaction, regardless of percentage.
    | Set to 0 to disable minimum fee.
    |
    */
    'minimum_fee' => env('PLATFORM_MINIMUM_FEE', 0),

    /*
    |--------------------------------------------------------------------------
    | Maximum Platform Fee
    |--------------------------------------------------------------------------
    |
    | The maximum fee amount charged per transaction, regardless of percentage.
    | Set to 0 to disable maximum fee cap.
    |
    */
    'maximum_fee' => env('PLATFORM_MAXIMUM_FEE', 0),

    /*
    |--------------------------------------------------------------------------
    | Platform Name
    |--------------------------------------------------------------------------
    |
    | The name of the platform displayed throughout the application.
    |
    */
    'name' => env('PLATFORM_NAME', 'Panchi Gallery'),

    /*
    |--------------------------------------------------------------------------
    | Platform Contact
    |--------------------------------------------------------------------------
    |
    | Default contact information for the platform.
    |
    */
    'contact_email' => env('PLATFORM_CONTACT_EMAIL', 'support@panchigallery.com'),
    'contact_phone' => env('PLATFORM_CONTACT_PHONE', ''),

    /*
    |--------------------------------------------------------------------------
    | Commission Calculation Method
    |--------------------------------------------------------------------------
    |
    | How the platform fee is calculated:
    | - 'percentage': Simple percentage of sale amount
    | - 'tiered': Tiered rates based on sale amount (future feature)
    |
    */
    'calculation_method' => env('PLATFORM_FEE_METHOD', 'percentage'),
];
