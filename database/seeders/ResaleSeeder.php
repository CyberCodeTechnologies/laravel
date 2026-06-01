<?php

namespace Database\Seeders;

use App\Models\Resale;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResaleSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing artworks and users
        $artworks = Artwork::where('status', 'approved')->get();
        $collectors = User::where('role', 'collector')->get();

        if ($artworks->isEmpty()) {
            echo "No artworks found. Please run SimpleArtworkSeeder first.\n";
            return;
        }

        if ($collectors->isEmpty()) {
            echo "No collectors found. Please run the main DatabaseSeeder first.\n";
            return;
        }

        // Create resale listings
        foreach ($artworks->take(6) as $artwork) {
            $owner = $collectors->random();
            
            Resale::create([
                'artwork_id' => $artwork->id,
                'owner_id' => $owner->id,
                'asking_price' => $artwork->price * rand(110, 150) / 100, // 10-50% markup
                'minimum_price' => $artwork->price * rand(100, 105) / 100, // 0-5% above original
                'description' => 'This artwork is being resold by the current owner. It\'s in excellent condition and comes with the original certificate of authenticity.',
                'status' => 'listed',
                'is_verified' => true,
                'listed_at' => now()->subDays(rand(1, 30)),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        echo "Created resale listings successfully!\n";
    }
}
