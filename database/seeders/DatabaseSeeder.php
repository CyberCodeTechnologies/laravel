<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Artwork;
use App\Models\Certificate;
use App\Models\Ownership;
use App\Models\Transaction;
use App\Models\Resale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed general settings
        $this->call([GeneralSettingSeeder::class]);

        // Seed payment methods
        $this->call([PaymentMethodSeeder::class]);

        // Seed Myanmar artists
        $this->call([MyanmarArtistSeeder::class]);

        // Seed realistic Myanmar art content
        $this->call([RealisticContentSeeder::class]);

        // Seed exhibitions
        $this->call([ExhibitionSeeder::class]);

        // Seed blog posts
        $this->call([BlogSeeder::class]);

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@panchigallery.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create sample artists
        $artists = User::factory(5)->create([
            'role' => 'artist',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample collectors
        $collectors = User::factory(10)->create([
            'role' => 'collector',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Oil Paintings', 'slug' => 'oil-paintings', 'description' => 'Traditional and contemporary oil paintings', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Acrylic Art', 'slug' => 'acrylic-art', 'description' => 'Modern acrylic artworks', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Watercolors', 'slug' => 'watercolors', 'description' => 'Delicate watercolor paintings', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Digital Art', 'slug' => 'digital-art', 'description' => 'Digital artworks and illustrations', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Photography', 'slug' => 'photography', 'description' => 'Artistic photography', 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create sample artworks
        foreach ($artists as $artist) {
            for ($i = 1; $i <= 5; $i++) {
                $artwork = Artwork::create([
                    'artist_id' => $artist->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'title' => "Artwork {$i} by {$artist->first_name} {$artist->last_name}",
                    'slug' => 'artwork-' . $i . '-' . uniqid(),
                    'description' => "This is a beautiful artwork created by {$artist->first_name} {$artist->last_name}. It represents the rich cultural heritage of Myanmar through modern artistic expression.",
                    'medium' => ['oil', 'acrylic', 'watercolor', 'digital', 'photography'][array_rand(['oil', 'acrylic', 'watercolor', 'digital', 'photography'])],
                    'dimensions' => rand(30, 120) . ' x ' . rand(40, 150) . ' cm',
                    'year' => rand(2020, 2024),
                    'price' => rand(500, 5000),
                    'currency' => 'USD',
                    'status' => 'approved',
                    'views_count' => rand(50, 500),
                    'images' => ['https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=800&h=1000&fit=crop'],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Create certificate for approved artwork
                Certificate::create([
                    'artwork_id' => $artwork->id,
                    'artist_id' => $artist->id,
                    'certificate_code' => Certificate::generateCertificateCode(),
                    'issue_date' => now(),
                    'certificate_text' => "Certificate of Authenticity for {$artwork->title}",
                    'is_verified' => true,
                ]);
            }
        }

        // Create sample transactions
        $artworks = Artwork::where('status', 'approved')->get();
        foreach ($collectors as $collector) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $artwork = $artworks->random();
                $artist = $artwork->artist;
                
                $transaction = Transaction::create([
                    'artwork_id' => $artwork->id,
                    'buyer_id' => $collector->id,
                    'seller_id' => $artist->id,
                    'transaction_id' => 'TXN' . strtoupper(uniqid()),
                    'amount' => $artwork->price,
                    'platform_fee' => $artwork->price * 0.15,
                    'seller_earnings' => $artwork->price * 0.85,
                    'status' => 'completed',
                    'completed_at' => now()->subDays(rand(1, 60)),
                ]);

                // Create ownership
                Ownership::create([
                    'artwork_id' => $artwork->id,
                    'owner_id' => $collector->id,
                    'purchase_price' => $artwork->price,
                    'acquired_at' => $transaction->completed_at,
                    'is_current_owner' => true,
                    'transaction_type' => 'sale',
                ]);

                // Mark artwork as sold
                $artwork->update(['status' => 'sold']);
            }
        }

        // Create sample resales
        $soldArtworks = Artwork::where('status', 'sold')->get();
        foreach ($soldArtworks->take(5) as $artwork) {
            $owner = $artwork->current_owner;
            
            Resale::create([
                'artwork_id' => $artwork->id,
                'owner_id' => $owner->id,
                'asking_price' => $artwork->price * rand(1.2, 1.8),
                'minimum_price' => $artwork->price * 1.1,
                'description' => "This artwork is being resold by the current owner. It's in excellent condition and comes with the original certificate of authenticity.",
                'status' => 'listed',
                'is_verified' => true,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        echo "Database seeded successfully!\n";
        echo "Created:\n";
        echo "- 1 Admin user (admin@panchigallery.com)\n";
        echo "- 5 Artists\n";
        echo "- 10 Collectors\n";
        echo "- 5 Categories\n";
        echo "- 25 Artworks\n";
        echo "- 25 Certificates\n";
        echo "- Sample transactions and ownerships\n";
        echo "- Sample resale listings\n";
    }
}
