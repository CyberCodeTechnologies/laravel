<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\Category;
use App\Models\Ownership;
use App\Models\Resale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RealisticContentSeeder extends Seeder
{
    public function run(): void
    {
        // Use existing artists from MyanmarArtistSeeder
        $artists = $this->createArtists();

        // Create Categories
        $categories = $this->createCategories();

        // Create Realistic Artworks
        $artworks = $this->createArtworks($artists, $categories);

        // Create Collectors and Resale Listings
        $this->createCollectorsAndResales($artworks);

        $this->command->info('Realistic Myanmar art content seeded successfully!');
        $this->command->info('Created: '.count($artists).' artists, '.count($categories).' categories, '.count($artworks).' artworks');
    }

    private function createArtists(): array
    {
        // Use existing artists from MyanmarArtistSeeder
        $artistEmails = [
            'komoukgyi@panchigallery.com',
            'thikeoo@panchigallery.com', 
            'soenaingsoe@panchigallery.com',
            'khinmaungwin@panchigallery.com',
            'thawkokokyaw@panchigallery.com'
        ];

        $artists = [];
        foreach ($artistEmails as $email) {
            $artist = User::where('email', $email)->first();
            if ($artist) {
                $artists[] = $artist;
            }
        }

        return $artists;
    }

    private function createCategories(): array
    {
        $categoriesData = [
            ['name' => 'Oil Paintings', 'slug' => 'oil-paintings', 'description' => 'Traditional and contemporary oil paintings'],
            ['name' => 'Acrylic Art', 'slug' => 'acrylic-art', 'description' => 'Modern acrylic artworks'],
            ['name' => 'Watercolors', 'slug' => 'watercolors', 'description' => 'Delicate watercolor paintings'],
            ['name' => 'Digital Art', 'slug' => 'digital-art', 'description' => 'Digital artworks and illustrations'],
            ['name' => 'Contemporary', 'slug' => 'contemporary', 'description' => 'Modern and experimental works'],
            ['name' => 'Landscapes', 'slug' => 'landscapes', 'description' => 'Natural and urban landscapes'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $category = Category::firstOrCreate([
                'slug' => $data['slug']
            ], [
                'name' => $data['name'],
                'description' => $data['description'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
            $categories[] = $category;
        }

        return $categories;
    }

    private function createArtworks(array $artists, array $categories): array
    {
        $artworks = [];
        
        // Create a few sample artworks using existing artists
        $artworksData = [
            [
                'title' => 'Golden Pagoda Sunset',
                'artist_index' => 0,
                'category_index' => 0,
                'price' => 2800,
                'medium' => 'oil',
                'dimensions' => '80 x 100 cm',
                'year' => 2023,
                'description' => 'A stunning depiction of Shwedagon Pagoda at sunset, capturing the golden glow and spiritual atmosphere of Myanmar\'s most sacred site.',
                'image' => 'https://images.unsplash.com/photo-1553603227-2358e3a6a145?w=800&h=1000&fit=crop',
            ],
            [
                'title' => 'Mythical Naga Guardians',
                'artist_index' => 1,
                'category_index' => 3,
                'price' => 3500,
                'medium' => 'acrylic',
                'dimensions' => '100 x 80 cm',
                'year' => 2024,
                'description' => 'A mesmerizing abstract piece blending acrylic paints with genuine gold leaf, representing cosmic balance and unity.',
                'image' => 'https://images.unsplash.com/photo-1549887534-1541e9326642?w=800&h=1000&fit=crop',
            ],
            [
                'title' => 'Meditating Buddha',
                'artist_index' => 2,
                'category_index' => 1,
                'price' => 4500,
                'medium' => 'oil',
                'dimensions' => '100 x 120 cm',
                'year' => 2023,
                'description' => 'A serene depiction of Buddha in meditation, inspired by ancient murals in Bagan temples.',
                'image' => 'https://images.unsplash.com/photo-1601821765780-754fa98637c1?w=800&h=1000&fit=crop',
            ],
            [
                'title' => 'Market Day in Yangon',
                'artist_index' => 3,
                'category_index' => 4,
                'price' => 1800,
                'medium' => 'oil',
                'dimensions' => '80 x 60 cm',
                'year' => 2024,
                'description' => 'Hyper-realistic portrayal of a bustling Yangon market, capturing vibrant colors and energy of daily life.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=1000&fit=crop',
            ],
            [
                'title' => 'Irrawaddy River Morning',
                'artist_index' => 4,
                'category_index' => 5,
                'price' => 3200,
                'medium' => 'watercolor',
                'dimensions' => '120 x 80 cm',
                'year' => 2023,
                'description' => 'A majestic view of Irrawaddy River at dawn, with fishing boats silhouetted against rising sun.',
                'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=1000&fit=crop',
            ],
        ];

        foreach ($artworksData as $data) {
            if (isset($artists[$data['artist_index']]) && isset($categories[$data['category_index']])) {
                $artwork = Artwork::create([
                    'title' => $data['title'],
                    'artist_id' => $artists[$data['artist_index']]->id,
                    'category_id' => $categories[$data['category_index']]->id,
                    'price' => $data['price'],
                    'currency' => 'USD',
                    'medium' => $data['medium'],
                    'dimensions' => $data['dimensions'],
                    'year' => $data['year'],
                    'description' => $data['description'],
                    'images' => [$data['image']],
                    'status' => 'approved',
                    'is_featured' => rand(0, 10) > 7,
                    'views_count' => rand(50, 500),
                    'likes_count' => rand(10, 100),
                    'slug' => Str::slug($data['title']),
                ]);
                $artworks[] = $artwork;
            }
        }

        return $artworks;
    }

    private function createCollectorsAndResales(array $artworks): void
    {
        // Create sample collectors
        $collectorsData = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john.smith@example.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.j@example.com'],
            ['first_name' => 'Michael', 'last_name' => 'Chen', 'email' => 'michael.chen@example.com'],
        ];

        foreach ($collectorsData as $collectorData) {
            $collector = User::firstOrCreate([
                'email' => $collectorData['email']
            ], [
                'first_name' => $collectorData['first_name'],
                'last_name' => $collectorData['last_name'],
                'name' => $collectorData['first_name'] . ' ' . $collectorData['last_name'],
                'password' => Hash::make('password'),
                'role' => 'collector',
                'is_active' => true,
                'is_verified' => true,
            ]);

            // Create ownership for some artworks
            if (count($artworks) > 0) {
                $randomArtwork = $artworks[array_rand($artworks)];
                
                Ownership::create([
                    'artwork_id' => $randomArtwork->id,
                    'owner_id' => $collector->id,
                    'is_current_owner' => true,
                    'acquired_at' => now()->subMonths(rand(1, 12)),
                    'purchase_price' => $randomArtwork->price * 0.9, // Original purchase price
                ]);
            }
        }
    }
}
