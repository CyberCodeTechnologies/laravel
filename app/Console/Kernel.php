<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Update exchange rates daily if auto-update is enabled
        if (config('currency.auto_update')) {
            $schedule->command('currency:update-rates')
                    ->daily()
                    ->description('Update currency exchange rates');
        }

        // Backup database daily at 2 AM
        $schedule->command('db:backup')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
