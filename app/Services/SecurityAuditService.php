<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;

class SecurityAuditService
{
    /**
     * Log security events with comprehensive context.
     */
    public function logSecurityEvent(string $event, array $context = [], string $level = 'info'): void
    {
        $auditData = [
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'user_id' => auth()->id(),
            'user_role' => auth()->check() ? auth()->user()->role : 'guest',
            'session_id' => session()->getId(),
            'context' => $context,
        ];

        // Store in audit log
        $this->storeAuditLog($auditData);

        // Log to Laravel log
        Log::log($level, "Security Audit: {$event}", $auditData);

        // Trigger alerts for critical events
        if (in_array($event, $this->getCriticalEvents())) {
            $this->triggerSecurityAlert($auditData);
        }
    }

    /**
     * Store audit log in database or cache.
     */
    private function storeAuditLog(array $auditData): void
    {
        $auditKey = 'security_audit_' . date('Y-m-d');
        $logs = Cache::get($auditKey, []);
        
        // Add to beginning of array (most recent first)
        array_unshift($logs, $auditData);
        
        // Keep only last 1000 entries per day
        $logs = array_slice($logs, 0, 1000);
        
        // Store for 30 days
        Cache::put($auditKey, $logs, 30 * 24 * 60 * 60);
    }

    /**
     * Get list of critical security events.
     */
    private function getCriticalEvents(): array
    {
        return [
            'admin_access_denied',
            'session_hijacking_attempt',
            'ip_blocked',
            'multiple_failed_logins',
            'privilege_escalation_attempt',
            'suspicious_file_upload',
            'database_access_violation',
            'environment_security_breach',
        ];
    }

    /**
     * Trigger security alerts for critical events.
     */
    private function triggerSecurityAlert(array $auditData): void
    {
        // Store critical alerts for immediate review
        $alertKey = 'security_alerts';
        $alerts = Cache::get($alertKey, []);
        
        $alertData = [
            'id' => uniqid(),
            'event' => $auditData['event'],
            'timestamp' => $auditData['timestamp'],
            'ip' => $auditData['ip'],
            'user_id' => $auditData['user_id'],
            'user_role' => $auditData['user_role'],
            'context' => $auditData['context'],
            'status' => 'active',
        ];

        array_unshift($alerts, $alertData);
        
        // Keep only last 100 alerts
        $alerts = array_slice($alerts, 0, 100);
        
        Cache::put($alertKey, $alerts, 24 * 60 * 60); // 24 hours

        // Log critical alert
        Log::critical('SECURITY ALERT: ' . $auditData['event'], $auditData);
    }

    /**
     * Log authentication events.
     */
    public function logAuthEvent(string $action, bool $success, array $context = []): void
    {
        $event = "auth_{$action}_" . ($success ? 'success' : 'failure');
        
        $this->logSecurityEvent($event, array_merge($context, [
            'success' => $success,
            'action' => $action,
        ]), $success ? 'info' : 'warning');
    }

    /**
     * Log admin actions.
     */
    public function logAdminAction(string $action, array $context = []): void
    {
        $this->logSecurityEvent('admin_action', array_merge($context, [
            'admin_action' => $action,
        ]), 'info');
    }

    /**
     * Log data access events.
     */
    public function logDataAccess(string $resource, string $action, array $context = []): void
    {
        $this->logSecurityEvent('data_access', array_merge($context, [
            'resource' => $resource,
            'data_action' => $action,
        ]), 'info');
    }

    /**
     * Log file upload events.
     */
    public function logFileUpload(string $filename, string $fileType, int $fileSize, bool $success): void
    {
        $this->logSecurityEvent('file_upload', [
            'filename' => $filename,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'success' => $success,
        ], $success ? 'info' : 'warning');
    }

    /**
     * Log rate limiting events.
     */
    public function logRateLimitEvent(string $ip, int $requests, int $limit): void
    {
        $this->logSecurityEvent('rate_limit_exceeded', [
            'requests' => $requests,
            'limit' => $limit,
            'excess' => $requests - $limit,
        ], 'warning');
    }

    /**
     * Get recent security events.
     */
    public function getRecentEvents(int $limit = 50): array
    {
        $events = [];
        
        // Get events from last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $auditKey = 'security_audit_' . $date;
            $dayEvents = Cache::get($auditKey, []);
            $events = array_merge($events, $dayEvents);
        }

        // Sort by timestamp (most recent first)
        usort($events, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return array_slice($events, 0, $limit);
    }

    /**
     * Get active security alerts.
     */
    public function getActiveAlerts(): array
    {
        return Cache::get('security_alerts', []);
    }

    /**
     * Get security statistics.
     */
    public function getSecurityStats(): array
    {
        $stats = [
            'total_events_today' => 0,
            'critical_alerts' => 0,
            'failed_logins_today' => 0,
            'blocked_ips' => 0,
            'active_sessions' => 0,
        ];

        // Count today's events
        $today = date('Y-m-d');
        $auditKey = 'security_audit_' . $today;
        $todayEvents = Cache::get($auditKey, []);
        $stats['total_events_today'] = count($todayEvents);

        // Count failed logins today
        $stats['failed_logins_today'] = count(array_filter($todayEvents, function($event) {
            return str_contains($event['event'], 'auth_login_failure');
        }));

        // Count critical alerts
        $alerts = $this->getActiveAlerts();
        $stats['critical_alerts'] = count($alerts);

        // Count blocked IPs
        $blockedIps = 0;
        for ($i = 0; $i < 24; $i++) {
            $hour = now()->subHours($i)->format('Y-m-d-H');
            $blockedKey = "blocked_ips_{$hour}";
            $blockedIps += Cache::get($blockedKey, 0);
        }
        $stats['blocked_ips'] = $blockedIps;

        // Count active sessions (approximate)
        $activeSessions = 0;
        $users = User::all();
        foreach ($users as $user) {
            $activityKey = "session_activity_{$user->id}";
            if (Cache::has($activityKey)) {
                $activeSessions++;
            }
        }
        $stats['active_sessions'] = $activeSessions;

        return $stats;
    }

    /**
     * Clear old audit logs.
     */
    public function clearOldLogs(int $daysToKeep = 30): void
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        for ($i = 0; $i < 365; $i++) { // Check up to a year
            $date = now()->subDays($i)->format('Y-m-d');
            if ($date < $cutoffDate->format('Y-m-d')) {
                $auditKey = 'security_audit_' . $date;
                Cache::forget($auditKey);
            }
        }

        Log::info('Old security audit logs cleared', [
            'days_kept' => $daysToKeep,
            'cutoff_date' => $cutoffDate->format('Y-m-d'),
        ]);
    }
}
