<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\CurrencyServiceProvider;
use App\Providers\ComposerServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    CurrencyServiceProvider::class,
    ComposerServiceProvider::class,
];
