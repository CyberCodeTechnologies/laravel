<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $this->validateSessionSecurity($request);
        }

        return $next($request);
    }

    /**
     * Validate session security settings.
     */
    private function validateSessionSecurity(Request $request): void
    {
        $user = $request->user();
        $sessionId = Session::getId();

        // Check for concurrent sessions
        $this->checkConcurrentSessions($user, $sessionId);

        // Check session age
        $this->checkSessionAge($user);

        // Check session IP consistency
        $this->checkSessionIpConsistency($request, $user);

        // Check session user agent consistency
        $this->checkSessionUserAgentConsistency($request, $user);

        // Update session activity
        $this->updateSessionActivity($user, $request);
    }

    /**
     * Check for concurrent sessions and limit them.
     */
    private function checkConcurrentSessions($user, string $currentSessionId): void
    {
        $maxConcurrentSessions = $this->getMaxConcurrentSessions($user);
        
        // Get all active sessions for this user
        $userSessionsKey = "user_sessions_{$user->id}";
        $activeSessions = cache()->get($userSessionsKey, []);

        // Remove expired sessions
        $activeSessions = array_filter($activeSessions, function($session) {
            return cache()->has("session_active_{$session}");
        });

        // Add current session if not already present
        if (!in_array($currentSessionId, $activeSessions)) {
            $activeSessions[] = $currentSessionId;
        }

        // If too many sessions, remove the oldest ones
        if (count($activeSessions) > $maxConcurrentSessions) {
            $sessionsToRemove = array_slice($activeSessions, 0, count($activeSessions) - $maxConcurrentSessions);
            
            foreach ($sessionsToRemove as $oldSessionId) {
                // Invalidate old session
                cache()->forget("session_active_{$oldSessionId}");
                
                Log::info('Session invalidated due to concurrent session limit', [
                    'user_id' => $user->id,
                    'session_id' => $oldSessionId,
                    'reason' => 'concurrent_limit_exceeded'
                ]);
            }

            // Update active sessions list
            $activeSessions = array_diff($activeSessions, $sessionsToRemove);
        }

        // Store updated sessions list
        cache()->put($userSessionsKey, array_values($activeSessions), 3600);
        
        // Mark current session as active
        cache()->put("session_active_{$currentSessionId}", true, 3600);
    }

    /**
     * Get maximum concurrent sessions based on user role.
     */
    private function getMaxConcurrentSessions($user): int
    {
        if ($user->isAdmin()) {
            return 5; // Admins can have 5 concurrent sessions
        } elseif ($user->isArtist()) {
            return 3; // Artists can have 3 concurrent sessions
        } else {
            return 2; // Regular users can have 2 concurrent sessions
        }
    }

    /**
     * Check session age and force logout if too old.
     */
    private function checkSessionAge($user): void
    {
        $maxSessionAge = $this->getMaxSessionAge($user);
        $sessionStartTime = session('session_start_time', now());
        
        if (now()->diffInMinutes($sessionStartTime) > $maxSessionAge) {
            Log::info('Session expired due to age limit', [
                'user_id' => $user->id,
                'session_age' => now()->diffInMinutes($sessionStartTime),
                'max_age' => $maxSessionAge
            ]);

            auth()->logout();
            Session::flush();
            
            abort(401, 'Session expired due to inactivity. Please login again.');
        }
    }

    /**
     * Get maximum session age based on user role.
     */
    private function getMaxSessionAge($user): int
    {
        if ($user->isAdmin()) {
            return 480; // 8 hours for admins
        } elseif ($user->isArtist()) {
            return 360; // 6 hours for artists
        } else {
            return 240; // 4 hours for regular users
        }
    }

    /**
     * Check if session IP has changed (potential session hijacking).
     */
    private function checkSessionIpConsistency(Request $request, $user): void
    {
        $currentIp = $request->ip();
        $originalIp = session('original_ip');

        if (!$originalIp) {
            session(['original_ip' => $currentIp]);
            return;
        }

        // Allow for some IP variation (e.g., mobile networks)
        if ($currentIp !== $originalIp && !$this->isIpVariationAllowed($currentIp, $originalIp)) {
            Log::warning('Potential session hijacking detected - IP changed', [
                'user_id' => $user->id,
                'original_ip' => $originalIp,
                'current_ip' => $currentIp,
                'user_agent' => $request->userAgent()
            ]);

            // Invalidate session
            auth()->logout();
            Session::flush();
            
            abort(401, 'Security alert: Session invalidated due to IP address change.');
        }
    }

    /**
     * Check if IP variation is allowed (same subnet).
     */
    private function isIpVariationAllowed(string $currentIp, string $originalIp): bool
    {
        // For IPv4, check if they're in the same /24 subnet
        if (filter_var($currentIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && 
            filter_var($originalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            
            $currentSubnet = substr($currentIp, 0, strrpos($currentIp, '.'));
            $originalSubnet = substr($originalIp, 0, strrpos($originalIp, '.'));
            
            return $currentSubnet === $originalSubnet;
        }

        return false;
    }

    /**
     * Check if session user agent has changed.
     */
    private function checkSessionUserAgentConsistency(Request $request, $user): void
    {
        $currentUserAgent = $request->userAgent();
        $originalUserAgent = session('original_user_agent');

        if (!$originalUserAgent) {
            session(['original_user_agent' => $currentUserAgent]);
            return;
        }

        if ($currentUserAgent !== $originalUserAgent) {
            Log::warning('Potential session hijacking detected - User Agent changed', [
                'user_id' => $user->id,
                'original_ua' => $originalUserAgent,
                'current_ua' => $currentUserAgent,
                'ip' => $request->ip()
            ]);

            // Invalidate session
            auth()->logout();
            Session::flush();
            
            abort(401, 'Security alert: Session invalidated due to browser change.');
        }
    }

    /**
     * Update session activity tracking.
     */
    private function updateSessionActivity($user, Request $request): void
    {
        $activityKey = "session_activity_{$user->id}";
        $lastActivity = session('last_activity', now());

        // Update last activity time
        session(['last_activity' => now()]);

        // Track session activity for monitoring
        $activityData = [
            'last_seen' => now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
        ];

        cache()->put($activityKey, $activityData, 3600);

        // Set session start time if not set
        if (!session('session_start_time')) {
            session(['session_start_time' => now()]);
        }
    }
}
