<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\ComposerServiceProvider::class,
        \App\Providers\RepositoryServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\CurrencyMiddleware::class,
            \App\Http\Middleware\RateLimitMiddleware::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\FileUploadSecurityMiddleware::class,
            \App\Http\Middleware\EnvironmentSecurityMiddleware::class,
            \App\Http\Middleware\SessionSecurityMiddleware::class,
        ]);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.security' => \App\Http\Middleware\AdminSecurityMiddleware::class,
            'artist' => \App\Http\Middleware\ArtistMiddleware::class,
            'collector' => \App\Http\Middleware\CollectorMiddleware::class,
            'approved' => \App\Http\Middleware\ApprovedMiddleware::class,
            'permission' => \App\Http\Middleware\HasPermission::class,
            'role' => \App\Http\Middleware\HasRole::class,
            'any_role' => \App\Http\Middleware\HasAnyRole::class,
        ]);

        // Exclude only non-sensitive routes from CSRF verification
        // Admin routes MUST have CSRF protection enabled
        $middleware->validateCsrfTokens(except: [
            'currency/switch',
            'currency/convert',
            'currency/prices',
            'currency/rates',
            'language/switch',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
