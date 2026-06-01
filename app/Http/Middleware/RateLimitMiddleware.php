<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $ip = $request->ip() ?? '127.0.0.1';
        $user = $request->user();
        
        // Check if IP is blocked
        if ($this->isIpBlocked($ip)) {
            return response()->json([
                'error' => 'Access Denied',
                'message' => 'Your IP address has been blocked due to suspicious activity.'
            ], 403);
        }
        
        // Get rate limit based on user type
        [$limit, $window] = $this->getRateLimit($user, $request);
        
        $key = 'rate_limit_' . md5($ip . ($user ? $user->id : 'guest') . $request->route()->getName());
        
        if (cache()->has($key)) {
            $requests = cache()->get($key, 0);
            if ($requests >= $limit) {
                // Log rate limit violation
                $this->logRateLimitViolation($request, $user, $requests);
                
                // Block IP if too many violations
                $this->checkAndBlockIp($ip);
                
                return response()->json([
                    'error' => 'Too many requests',
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'retry_after' => now()->addSeconds($window)->toDateTimeString()
                ], 429);
            }
            
            cache()->increment($key, 1, $window);
        } else {
            cache()->put($key, 1, $window);
        }
        
        $response = $next($request);
        
        // Add rate limiting headers
        $response->headers->set('X-RateLimit-Limit', (string)$limit);
        $response->headers->set('X-RateLimit-Remaining', (string)max(0, $limit - cache()->get($key, 0)));
        $response->headers->set('X-RateLimit-Reset', now()->addSeconds($window)->toDateTimeString());
        
        return $response;
    }
    
    /**
     * Get rate limit based on user type and request type.
     */
    private function getRateLimit($user, Request $request): array
    {
        // Base limits per minute
        $limits = [
            'guest' => [60, 60],           // 60 requests per minute
            'collector' => [120, 60],      // 120 requests per minute
            'artist' => [180, 60],         // 180 requests per minute
            'admin' => [300, 60],          // 300 requests per minute
        ];
        
        // Adjust limits for sensitive endpoints
        $sensitiveRoutes = ['login', 'register', 'password.request', 'password.reset'];
        $routeName = $request->route() ? $request->route()->getName() : '';
        
        if (in_array($routeName, $sensitiveRoutes)) {
            return [10, 300]; // 10 requests per 5 minutes for auth endpoints
        }
        
        // File upload endpoints have stricter limits
        if ($request->hasFile('file') || $request->hasFile('avatar') || $request->hasFile('image')) {
            return [5, 300]; // 5 uploads per 5 minutes
        }
        
        // API endpoints might have different limits
        if ($request->expectsJson()) {
            return [30, 60]; // 30 API requests per minute
        }
        
        $userType = 'guest';
        if ($user) {
            if ($user->isAdmin()) {
                $userType = 'admin';
            } elseif ($user->isArtist()) {
                $userType = 'artist';
            } elseif ($user->isCollector()) {
                $userType = 'collector';
            }
        }
        
        return $limits[$userType];
    }
    
    /**
     * Check if IP is blocked.
     */
    private function isIpBlocked(string $ip): bool
    {
        return cache()->has('blocked_ip_' . $ip);
    }
    
    /**
     * Log rate limit violations.
     */
    private function logRateLimitViolation(Request $request, $user, int $requests): void
    {
        \Log::warning('Rate limit violation', [
            'ip' => $request->ip(),
            'user_id' => $user ? $user->id : null,
            'user_type' => $user ? $user->role : 'guest',
            'requests' => $requests,
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
        ]);
    }
    
    /**
     * Check and block IP if too many violations.
     */
    private function checkAndBlockIp(string $ip): void
    {
        $violationKey = 'violations_' . $ip;
        $violations = cache()->get($violationKey, 0);
        
        if ($violations >= 10) { // Block after 10 violations
            cache()->put('blocked_ip_' . $ip, true, 3600); // Block for 1 hour
            cache()->forget($violationKey);
            
            \Log::critical('IP blocked due to repeated violations', [
                'ip' => $ip,
                'violations' => $violations,
            ]);
        } else {
            cache()->put($violationKey, $violations + 1, 3600); // Count violations for 1 hour
        }
    }
}
