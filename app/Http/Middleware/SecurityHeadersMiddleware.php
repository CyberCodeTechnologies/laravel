<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $response = $next($request);
        
        // Content Security Policy - Allow inline styles and scripts for compatibility
        $viteServers = "";
        $nextjsServers = "";
        if (app()->environment('local', 'testing')) {
            // Allow Vite dev server in development (only use localhost, not IPv6)
            $viteServers = " http://localhost:5174 https://localhost:5174 http://localhost:5175 https://localhost:5175";
            // Allow Next.js dev server in development
            $nextjsServers = " http://localhost:3000 http://127.0.0.1:3000";
        }
        
        $csp = "default-src 'self'; script-src 'self' https://cdn.jsdelivr.net{$viteServers}{$nextjsServers} 'unsafe-inline' 'unsafe-hashes'; style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net{$viteServers}{$nextjsServers} 'unsafe-inline'; style-src-elem 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net{$viteServers}{$nextjsServers} 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https: data: https://fonts.gstatic.com https://cdn.jsdelivr.net; connect-src 'self' https: ws: wss:{$viteServers}{$nextjsServers}; media-src 'self' https:; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; upgrade-insecure-requests;";
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Enable XSS protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // HSTS (only on HTTPS)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;
    }
}
