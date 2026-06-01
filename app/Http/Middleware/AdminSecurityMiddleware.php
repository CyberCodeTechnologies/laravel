<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityAuditService;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurityMiddleware
{
    private $auditService;

    public function __construct(SecurityAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure user is authenticated and is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            $this->auditService->logSecurityEvent('admin_access_denied', [
                'attempted_url' => $request->fullUrl(),
                'method' => $request->method(),
            ], 'warning');
            
            abort(403, 'Unauthorized access.');
        }

        // Additional security checks for admin actions
        $sensitiveActions = ['delete', 'destroy', 'backup', 'restore', 'import', 'export', 'mass_update'];
        
        if (in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete'])) {
            $route = $request->route();
            if ($route && in_array($route->getName(), $sensitiveActions)) {
                $this->auditService->logAdminAction($route->getName(), [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'input_keys' => array_keys($request->all()),
                ]);
            }
        }

        return $next($request);
    }
}
