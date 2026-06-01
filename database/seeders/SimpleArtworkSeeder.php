<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SimpleArtworkSeeder extends Seeder
{
    public function run(): void
    {
        $artist = User::where('role', 'artist')->first();
        $category = Category::first();

        if (!$artist || !$category) {
            echo "Artist or category not found. Please run the main DatabaseSeeder first.\n";
            return;
        }

        for ($i = 1; $i <= 9; $i++) {
            Artwork::create([
                'uuid' => Str::uuid(),
                'artist_id' => $artist->id,
                'category_id' => $category->id,
                'title' => 'Sample Artwork ' . $i,
                'slug' => 'sample-artwork-' . $i . '-' . uniqid(),
                'description' => 'A beautiful sample artwork for testing the artwork details display.',
                'type' => 'painting',
                'medium' => 'Oil',
                'surface' => 'Canvas',
                'width' => 50 + $i * 5,
                'height' => 60 + $i * 5,
                'dimensions_unit' => 'cm',
                'year_created' => 2024,
                'price' => 500 + $i * 200,
                'currency' => 'USD',
                'status' => 'approved',
                'availability' => 'available',
                'views_count' => rand(10, 100),
            ]);
        }

        echo "Created 9 sample artworks successfully!\n";
    }
}
