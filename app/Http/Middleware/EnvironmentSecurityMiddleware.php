<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnvironmentSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->validateEnvironmentSecurity();

        return $next($request);
    }

    /**
     * Validate environment security settings.
     */
    private function validateEnvironmentSecurity(): void
    {
        $errors = [];

        // Check if debug mode is disabled in production
        if (app()->environment('production') && config('app.debug')) {
            $errors[] = 'APP_DEBUG must be set to false in production environment';
        }

        // Check if APP_KEY is set and not the default
        if (empty(config('app.key')) || config('app.key') === 'base64:SomeRandomString') {
            $errors[] = 'APP_KEY must be set to a strong, randomly generated value';
        }

        // Check if the application URL is properly set
        if (config('app.url') === 'http://localhost' && app()->environment('production')) {
            $errors[] = 'APP_URL must be set to the actual production domain';
        }

        // Check database connection security
        $this->validateDatabaseSecurity($errors);

        // Check session security
        $this->validateSessionSecurity($errors);

        // Check cache security
        $this->validateCacheSecurity($errors);

        // Log security issues and abort if critical
        if (!empty($errors)) {
            Log::critical('Environment security validation failed', [
                'errors' => $errors,
                'environment' => app()->environment(),
                'url' => request()->fullUrl(),
                'ip' => request()->ip()
            ]);

            // In production, abort the request for critical security issues
            if (app()->environment('production')) {
                $criticalErrors = array_filter($errors, function($error) {
                    return str_contains($error, 'APP_DEBUG') || str_contains($error, 'APP_KEY');
                });

                if (!empty($criticalErrors)) {
                    abort(500, 'Application security configuration error. Please contact administrator.');
                }
            }
        }
    }

    /**
     * Validate database security settings.
     */
    private function validateDatabaseSecurity(array &$errors): void
    {
        $connection = config('database.default');
        
        if ($connection === 'mysql' || $connection === 'mariadb') {
            // Check if database credentials are not default
            if (config("database.connections.{$connection}.username") === 'root' && 
                app()->environment('production')) {
                $errors[] = 'Database should not use root user in production';
            }

            // Check if SSL is enabled for database connection
            if (empty(config("database.connections.{$connection}.options")) && 
                app()->environment('production')) {
                Log::warning('Database SSL connection not configured', [
                    'connection' => $connection
                ]);
            }
        }
    }

    /**
     * Validate session security settings.
     */
    private function validateSessionSecurity(array &$errors): void
    {
        $driver = config('session.driver');
        
        // Check if session driver is secure
        if ($driver === 'file' && app()->environment('production')) {
            Log::warning('Using file session driver in production is not recommended', [
                'driver' => $driver
            ]);
        }

        // Check session lifetime
        $lifetime = config('session.lifetime');
        if ($lifetime > 1440) { // 24 hours
            Log::warning('Session lifetime is very long', [
                'lifetime' => $lifetime
            ]);
        }

        // Check if session encryption is enabled
        if (!config('session.encrypt') && app()->environment('production')) {
            $errors[] = 'Session encryption should be enabled in production';
        }
    }

    /**
     * Validate cache security settings.
     */
    private function validateCacheSecurity(array &$errors): void
    {
        $driver = config('cache.default');
        
        // Check if cache driver is appropriate for production
        if ($driver === 'file' && app()->environment('production')) {
            Log::warning('Using file cache driver in production is not recommended', [
                'driver' => $driver
            ]);
        }

        // Check Redis security if used
        if ($driver === 'redis' || config('cache.stores.redis')) {
            $redisPassword = config('database.redis.default.password');
            if (empty($redisPassword) && app()->environment('production')) {
                Log::warning('Redis password not configured in production');
            }
        }
    }
}
