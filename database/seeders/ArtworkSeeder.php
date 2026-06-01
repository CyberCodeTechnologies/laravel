<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artwork;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ArtworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved artists and categories
        $artists = User::where('role', 'artist')->where('is_approved', true)->get();
        $categories = Category::where('is_active', true)->get();

        if ($artists->isEmpty() || $categories->isEmpty()) {
            $this->command->info('No artists or categories found. Skipping artwork seeding.');
            return;
        }

        $artworks = [
            [
                'title' => 'Mythical Naga Guardians',
                'description' => 'A mesmerizing abstract piece blending acrylic paints with genuine gold leaf, representing cosmic balance and unity.',
                'medium' => 'acrylic',
                'dimensions' => '100 x 80 cm',
                'year' => 2024,
                'price' => 3500.00,
                'currency' => 'USD',
                'price_usd' => 3500.00,
                'price_mmk' => 15400000,
                'status' => 'approved',
                'is_featured' => false,
                'views_count' => 455,
                'likes_count' => 19,
                'stock' => 1,
                'images' => ['https://images.unsplash.com/photo-1549887534-1541e9326642?w=800&h=1000&fit=crop'],
            ],
            [
                'title' => 'Golden Pagoda Sunset',
                'description' => 'Traditional Myanmar landscape featuring golden pagodas against a vibrant sunset sky.',
                'medium' => 'oil',
                'dimensions' => '120 x 90 cm',
                'year' => 2024,
                'price' => 2800.00,
                'currency' => 'USD',
                'price_usd' => 2800.00,
                'price_mmk' => 12320000,
                'status' => 'approved',
                'is_featured' => true,
                'views_count' => 892,
                'likes_count' => 34,
                'stock' => 1,
                'images' => ['https://images.unsplash.com/photo-1578945639689-3c6a2a3c5c1c?w=800&h=1000&fit=crop'],
            ],
            [
                'title' => 'Buddhist Meditation',
                'description' => 'Contemporary interpretation of Buddhist meditation practices in modern Myanmar.',
                'medium' => 'mixed_media',
                'dimensions' => '80 x 60 cm',
                'year' => 2023,
                'price' => 1800.00,
                'currency' => 'USD',
                'price_usd' => 1800.00,
                'price_mmk' => 7920000,
                'status' => 'approved',
                'is_featured' => false,
                'views_count' => 234,
                'likes_count' => 12,
                'stock' => 1,
                'images' => ['https://images.unsplash.com/photo-1544968348-90a29d8b8e8c?w=800&h=1000&fit=crop'],
            ],
            [
                'title' => 'Yangon Street Life',
                'description' => 'Vibrant street scene capturing the energy and chaos of Yangon city life.',
                'medium' => 'photography',
                'dimensions' => '60 x 40 cm',
                'year' => 2024,
                'price' => 450.00,
                'currency' => 'USD',
                'price_usd' => 450.00,
                'price_mmk' => 1980000,
                'status' => 'pending',
                'is_featured' => false,
                'views_count' => 156,
                'likes_count' => 8,
                'stock' => 10,
                'images' => ['https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=1000&fit=crop'],
            ],
            [
                'title' => 'Traditional Weaving Patterns',
                'description' => 'Digital artwork inspired by traditional Myanmar textile patterns.',
                'medium' => 'digital',
                'dimensions' => 'Digital File',
                'year' => 2024,
                'price' => 120.00,
                'currency' => 'USD',
                'price_usd' => 120.00,
                'price_mmk' => 528000,
                'status' => 'approved',
                'is_featured' => false,
                'views_count' => 78,
                'likes_count' => 5,
                'stock' => 999,
                'images' => ['https://images.unsplash.com/photo-1528459801416-a9e53bbf4e40?w=800&h=1000&fit=crop'],
            ],
        ];

        foreach ($artworks as $index => $artworkData) {
            $artist = $artists->random();
            $category = $categories->random();
            
            $artworkData['artist_id'] = $artist->id;
            $artworkData['category_id'] = $category->id;
            $artworkData['slug'] = \Illuminate\Support\Str::slug($artworkData['title']);
            $artworkData['approved_at'] = $artworkData['status'] === 'approved' ? now() : null;

            Artwork::firstOrCreate(['slug' => $artworkData['slug']], $artworkData);
        }
    }
}
