<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CacheOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize application cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting cache optimization...');
        
        // Clear all caches
        $this->call('cache:clear');
        
        // Optimize for production
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        
        // Optimize autoloader
        $this->call('optimize');
        
        $this->info('Cache optimization completed successfully!');
        
        return Command::SUCCESS;
    }
}
