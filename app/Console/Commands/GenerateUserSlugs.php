<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUserSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for all users that do not have one';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::whereNull('slug')->orWhere('slug', '')->get();
        
        $count = $users->count();
        
        if ($count === 0) {
            $this->info('All users already have slugs.');
            return;
        }
        
        $this->info("Generating slugs for {$count} users...");
        
        $bar = $this->output->createProgressBar($count);
        
        foreach ($users as $user) {
            $slug = $user->generateSlug();
            $user->slug = $slug;
            $user->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated slugs for {$count} users.");
    }
}
