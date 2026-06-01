<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    private $auditService;

    public function __construct(SecurityAuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('admin');
    }

    /**
     * Display security dashboard.
     */
    public function dashboard()
    {
        $stats = $this->auditService->getSecurityStats();
        $recentEvents = $this->auditService->getRecentEvents(20);
        $activeAlerts = $this->auditService->getActiveAlerts();

        return view('admin.security.dashboard', compact('stats', 'recentEvents', 'activeAlerts'));
    }

    /**
     * Display security events log.
     */
    public function events(Request $request)
    {
        $events = $this->auditService->getRecentEvents(100);
        
        // Filter events if requested
        if ($request->has('event_type')) {
            $events = array_filter($events, function($event) use ($request) {
                return str_contains($event['event'], $request->event_type);
            });
        }

        if ($request->has('user_role')) {
            $events = array_filter($events, function($event) use ($request) {
                return $event['user_role'] === $request->user_role;
            });
        }

        return view('admin.security.events', compact('events'));
    }

    /**
     * Display active security alerts.
     */
    public function alerts()
    {
        $alerts = $this->auditService->getActiveAlerts();
        
        return view('admin.security.alerts', compact('alerts'));
    }

    /**
     * Clear security alerts.
     */
    public function clearAlerts(Request $request)
    {
        $alertIds = $request->input('alert_ids', []);
        
        if (empty($alertIds)) {
            // Clear all alerts
            Cache::forget('security_alerts');
            $this->auditService->logAdminAction('clear_all_security_alerts');
        } else {
            // Clear specific alerts
            $alerts = $this->auditService->getActiveAlerts();
            $remainingAlerts = array_filter($alerts, function($alert) use ($alertIds) {
                return !in_array($alert['id'], $alertIds);
            });
            
            Cache::put('security_alerts', array_values($remainingAlerts), 24 * 60 * 60);
            $this->auditService->logAdminAction('clear_specific_security_alerts', [
                'cleared_count' => count($alertIds),
            ]);
        }

        return redirect()->route('admin.security.alerts')
            ->with('success', 'Security alerts cleared successfully.');
    }

    /**
     * Display blocked IPs.
     */
    public function blockedIps()
    {
        $blockedIps = [];
        
        // Get all blocked IPs from cache
        for ($i = 0; $i < 24; $i++) {
            $hour = now()->subHours($i)->format('Y-m-d-H');
            $hourlyBlocked = [];
            
            // Scan cache for blocked IP keys
            $cacheKeys = Cache::getKeys();
            foreach ($cacheKeys as $key) {
                if (str_starts_with($key, 'blocked_ip_')) {
                    $ip = substr($key, 11); // Remove 'blocked_ip_' prefix
                    $blockedIps[$ip] = [
                        'ip' => $ip,
                        'blocked_at' => now()->subHours($i),
                        'reason' => 'Rate limit violations',
                    ];
                }
            }
        }

        return view('admin.security.blocked-ips', compact('blockedIps'));
    }

    /**
     * Unblock IP addresses.
     */
    public function unblockIps(Request $request)
    {
        $ips = $request->input('ips', []);
        
        foreach ($ips as $ip) {
            Cache::forget('blocked_ip_' . $ip);
            Cache::forget('violations_' . $ip);
        }

        $this->auditService->logAdminAction('unblock_ips', [
            'unblocked_ips' => $ips,
            'count' => count($ips),
        ]);

        return redirect()->route('admin.security.blocked-ips')
            ->with('success', count($ips) . ' IP addresses unblocked successfully.');
    }

    /**
     * Display session activity.
     */
    public function sessions()
    {
        $activeSessions = [];
        
        // Get all users with active sessions
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $activityKey = "session_activity_{$user->id}";
            if (Cache::has($activityKey)) {
                $activity = Cache::get($activityKey);
                $activeSessions[] = [
                    'user' => $user,
                    'activity' => $activity,
                    'session_age' => now()->diffInMinutes($activity['last_seen']),
                ];
            }
        }

        // Sort by last activity (most recent first)
        usort($activeSessions, function($a, $b) {
            return strtotime($b['activity']['last_seen']) - strtotime($a['activity']['last_seen']);
        });

        return view('admin.security.sessions', compact('activeSessions'));
    }

    /**
     * Force logout user sessions.
     */
    public function forceLogout(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        foreach ($userIds as $userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                // Clear user sessions
                $userSessionsKey = "user_sessions_{$userId}";
                Cache::forget($userSessionsKey);
                
                // Clear session activity
                $activityKey = "session_activity_{$userId}";
                Cache::forget($activityKey);
                
                // Clear all individual session keys
                $cacheKeys = Cache::getKeys();
                foreach ($cacheKeys as $key) {
                    if (str_starts_with($key, 'session_active_')) {
                        Cache::forget($key);
                    }
                }
            }
        }

        $this->auditService->logAdminAction('force_logout_users', [
            'user_ids' => $userIds,
            'count' => count($userIds),
        ]);

        return redirect()->route('admin.security.sessions')
            ->with('success', count($userIds) . ' users logged out successfully.');
    }

    /**
     * Display security settings.
     */
    public function settings()
    {
        $settings = [
            'rate_limit_guest' => config('security.rate_limits.guest', 60),
            'rate_limit_collector' => config('security.rate_limits.collector', 120),
            'rate_limit_artist' => config('security.rate_limits.artist', 180),
            'rate_limit_admin' => config('security.rate_limits.admin', 300),
            'max_concurrent_sessions_admin' => config('security.max_sessions.admin', 5),
            'max_concurrent_sessions_artist' => config('security.max_sessions.artist', 3),
            'max_concurrent_sessions_collector' => config('security.max_sessions.collector', 2),
            'session_timeout_admin' => config('security.session_timeouts.admin', 480),
            'session_timeout_artist' => config('security.session_timeouts.artist', 360),
            'session_timeout_collector' => config('security.session_timeouts.collector', 240),
        ];

        return view('admin.security.settings', compact('settings'));
    }

    /**
     * Update security settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'rate_limit_guest' => 'required|integer|min:10|max:1000',
            'rate_limit_collector' => 'required|integer|min:10|max:1000',
            'rate_limit_artist' => 'required|integer|min:10|max:1000',
            'rate_limit_admin' => 'required|integer|min:10|max:1000',
            'max_concurrent_sessions_admin' => 'required|integer|min:1|max:10',
            'max_concurrent_sessions_artist' => 'required|integer|min:1|max:10',
            'max_concurrent_sessions_collector' => 'required|integer|min:1|max:10',
            'session_timeout_admin' => 'required|integer|min:30|max:1440',
            'session_timeout_artist' => 'required|integer|min:30|max:1440',
            'session_timeout_collector' => 'required|integer|min:30|max:1440',
        ]);

        // Store settings in cache (in production, these should be in config files)
        foreach ($validated as $key => $value) {
            Cache::put('security_settings.' . $key, $value, 24 * 60 * 60);
        }

        $this->auditService->logAdminAction('update_security_settings', [
            'settings_updated' => array_keys($validated),
        ]);

        return redirect()->route('admin.security.settings')
            ->with('success', 'Security settings updated successfully.');
    }

    /**
     * Generate security report.
     */
    public function generateReport(Request $request)
    {
        $period = $request->input('period', '7'); // days
        $reportData = [
            'period' => $period,
            'generated_at' => now(),
            'stats' => $this->auditService->getSecurityStats(),
            'top_events' => $this->getTopEvents($period),
            'security_trends' => $this->getSecurityTrends($period),
            'recommendations' => $this->generateSecurityRecommendations(),
        ];

        $this->auditService->logAdminAction('generate_security_report', [
            'period' => $period,
        ]);

        return view('admin.security.report', compact('reportData'));
    }

    /**
     * Get top security events for the period.
     */
    private function getTopEvents(int $period): array
    {
        $eventCounts = [];
        
        for ($i = 0; $i < $period; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $auditKey = 'security_audit_' . $date;
            $events = Cache::get($auditKey, []);
            
            foreach ($events as $event) {
                $eventType = $event['event'];
                if (!isset($eventCounts[$eventType])) {
                    $eventCounts[$eventType] = 0;
                }
                $eventCounts[$eventType]++;
            }
        }

        arsort($eventCounts);
        return array_slice($eventCounts, 0, 10, true);
    }

    /**
     * Get security trends for the period.
     */
    private function getSecurityTrends(int $period): array
    {
        $trends = [];
        
        for ($i = 0; $i < $period; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $auditKey = 'security_audit_' . $date;
            $events = Cache::get($auditKey, []);
            
            $trends[$date] = [
                'total_events' => count($events),
                'failed_logins' => count(array_filter($events, function($e) { return str_contains($e['event'], 'login_failure'); })),
                'admin_actions' => count(array_filter($events, function($e) { return str_contains($e['event'], 'admin_action'); })),
                'security_alerts' => count(array_filter($events, function($e) { return in_array($e['event'], ['admin_access_denied', 'session_hijacking_attempt', 'ip_blocked']); })),
            ];
        }

        return array_reverse($trends);
    }

    /**
     * Generate security recommendations based on current data.
     */
    private function generateSecurityRecommendations(): array
    {
        $recommendations = [];
        $stats = $this->auditService->getSecurityStats();
        
        if ($stats['failed_logins_today'] > 10) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'High number of failed login attempts detected. Consider implementing additional authentication measures.',
                'action' => 'Review login logs and consider 2FA implementation.',
            ];
        }
        
        if ($stats['critical_alerts'] > 5) {
            $recommendations[] = [
                'type' => 'critical',
                'message' => 'Multiple critical security alerts require immediate attention.',
                'action' => 'Review active alerts and take appropriate action.',
            ];
        }
        
        if ($stats['blocked_ips'] > 20) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'High number of blocked IPs detected. This may indicate a coordinated attack.',
                'action' => 'Monitor attack patterns and consider additional protection.',
            ];
        }
        
        return $recommendations;
    }
}
