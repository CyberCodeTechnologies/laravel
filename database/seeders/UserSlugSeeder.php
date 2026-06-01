<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNull('slug')->get();

        foreach ($users as $user) {
            $slug = strtolower(trim($user->first_name . '-' . $user->last_name));
            $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
            
            $originalSlug = $slug;
            $counter = 1;
            
            while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            
            $user->slug = $slug;
            $user->saveQuietly();
            
            $this->command->info("Generated slug '{$slug}' for user ID {$user->id}");
        }
    }
}
