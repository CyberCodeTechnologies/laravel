<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database backup with compression';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting database backup...');
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql.gz';
        $path = storage_path('backups/' . $filename);
        
        // Create backups directory if it doesn't exist
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Create database backup using escaped shell arguments to avoid injection
        $username = (string) config('database.connections.mysql.username');
        $password = (string) config('database.connections.mysql.password');
        $database = (string) config('database.connections.mysql.database');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --single-transaction --quick --lock-tables --routines --triggers --events --hex-blob --default-character-set=utf8mb4 %s | gzip > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        exec($command, $output, $returnCode);
        
        if ($returnCode === 0) {
            $this->info("Database backup created: {$filename}");
            $this->info("Backup size: " . filesize($path) . " bytes");
            
            // Keep only last 7 backups
            $this->cleanupOldBackups();
            
            return Command::SUCCESS;
        } else {
            $this->error("Database backup failed!");
            return Command::FAILURE;
        }
    }
    
    /**
     * Clean up old backups (keep only last 7)
     */
    private function cleanupOldBackups(): void
    {
        $backupDir = storage_path('backups');
        $files = glob($backupDir . 'backup_*.sql.gz');
        
        // Sort by creation time (newest first)
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        // Keep only last 7 backups
        if (count($files) > 7) {
            $toDelete = array_slice($files, 0, count($files) - 7);
            
            foreach ($toDelete as $file) {
                unlink($file);
                $this->info("Deleted old backup: " . basename($file));
            }
        }
    }
}
