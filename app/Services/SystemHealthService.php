<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SystemHealthService
{
    /**
     * Get disk usage percentage
     */
    public function getDiskUsage(): array
    {
        try {
            $free = disk_free_space(storage_path());
            $total = disk_total_space(storage_path());
            if ($total > 0) {
                return [
                    'free' => $this->formatBytes($free),
                    'total' => $this->formatBytes($total),
                    'percentage' => round((($total - $free) / $total) * 100, 2),
                ];
            }
        } catch (\Exception $e) {
            // Handle cases where disk functions are disabled
        }
        
        return ['free' => 'N/A', 'total' => 'N/A', 'percentage' => 0];
    }

    /**
     * Format bytes to human readable
     */
    public function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get comprehensive system health status
     */
    public function getStatus(): array
    {
        return [
            'disk_usage' => $this->getDiskUsage(),
            'cache_status' => Cache::getStore() instanceof \Illuminate\Cache\FileStore ? 'File' : (Cache::getStore() instanceof \Illuminate\Cache\DatabaseStore ? 'Database' : 'Other'),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
        ];
    }
}
