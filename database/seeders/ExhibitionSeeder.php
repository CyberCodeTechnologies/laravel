<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exhibition;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExhibitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved artworks and artists
        $artworks = Artwork::where('status', 'approved')->get();
        $artists = User::where('role', 'artist')->where('is_approved', true)->get();

        if ($artworks->isEmpty()) {
            $this->command->info('No artworks found. Skipping exhibition seeding.');
            return;
        }

        $exhibitions = [
            [
                'title' => 'Myanmar Contemporary Art Festival 2024',
                'description' => 'A celebration of contemporary Myanmar art featuring works from emerging and established artists. This festival showcases the diversity and creativity of Myanmar\'s art scene, with pieces ranging from traditional techniques to modern interpretations.',
                'start_date' => '2024-06-15',
                'end_date' => '2024-08-30',
                'venue' => 'National Museum of Myanmar',
                'address' => '66/74 Pyay Road',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'status' => 'ongoing',
                'is_featured' => true,
                'is_published' => true,
                'meta_title' => 'Myanmar Contemporary Art Festival 2024 - Panchi Gallery',
                'meta_description' => 'Join us for the Myanmar Contemporary Art Festival 2024, featuring works from emerging and established artists.',
                'meta_keywords' => 'Myanmar art, contemporary art, art festival, Yangon exhibition',
            ],
            [
                'title' => 'Golden Heritage Exhibition',
                'description' => 'An exhibition exploring Myanmar\'s rich cultural heritage through traditional and modern artistic expressions. Features paintings, sculptures, and installations that celebrate the country\'s artistic traditions.',
                'start_date' => '2024-09-01',
                'end_date' => '2024-11-15',
                'venue' => 'Panchi Gallery Main Hall',
                'address' => '123 Bogyoke Street',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'status' => 'upcoming',
                'is_featured' => true,
                'is_published' => true,
                'meta_title' => 'Golden Heritage Exhibition - Panchi Gallery',
                'meta_description' => 'Explore Myanmar\'s rich cultural heritage through traditional and modern artistic expressions.',
                'meta_keywords' => 'heritage art, Myanmar culture, traditional art, cultural exhibition',
            ],
            [
                'title' => 'Emerging Artists Showcase',
                'description' => 'A platform for talented emerging artists to display their work to a wider audience. This exhibition highlights fresh perspectives and innovative techniques from the next generation of Myanmar artists.',
                'start_date' => '2024-12-01',
                'end_date' => '2025-01-31',
                'venue' => 'Gallery 21',
                'address' => '45 Kaba Aye Pagoda Road',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'status' => 'upcoming',
                'is_featured' => false,
                'is_published' => true,
                'meta_title' => 'Emerging Artists Showcase - Panchi Gallery',
                'meta_description' => 'Discover fresh perspectives from the next generation of Myanmar artists.',
                'meta_keywords' => 'emerging artists, new artists, art showcase, young artists',
            ],
            [
                'title' => 'Buddhist Art Through the Ages',
                'description' => 'A retrospective exhibition tracing the evolution of Buddhist art in Myanmar from ancient times to contemporary interpretations. Features rare artifacts alongside modern artistic responses.',
                'start_date' => '2024-03-01',
                'end_date' => '2024-05-30',
                'venue' => 'National Museum of Myanmar',
                'address' => '66/74 Pyay Road',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'status' => 'completed',
                'is_featured' => false,
                'is_published' => true,
                'meta_title' => 'Buddhist Art Through the Ages - Panchi Gallery',
                'meta_description' => 'Trace the evolution of Buddhist art in Myanmar from ancient times to contemporary interpretations.',
                'meta_keywords' => 'Buddhist art, religious art, Myanmar history, art history',
            ],
            [
                'title' => 'Nature and Spirituality',
                'description' => 'An exhibition exploring the connection between nature and spirituality in Myanmar art. Artists present works that reflect on the natural world and its spiritual significance in Myanmar culture.',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-28',
                'venue' => 'Panchi Gallery Annex',
                'address' => '125 Sule Pagoda Road',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'status' => 'cancelled',
                'is_featured' => false,
                'is_published' => false,
                'meta_title' => 'Nature and Spirituality Exhibition - Panchi Gallery',
                'meta_description' => 'Explore the connection between nature and spirituality in Myanmar art.',
                'meta_keywords' => 'nature art, spirituality, environmental art, Myanmar nature',
            ],
        ];

        foreach ($exhibitions as $index => $exhibitionData) {
            $slug = \Illuminate\Support\Str::slug($exhibitionData['title']);
            
            // Create exhibition
            $exhibition = Exhibition::firstOrCreate(['slug' => $slug], $exhibitionData);
            
            // Attach random artworks (3-6 per exhibition)
            if ($exhibition->wasRecentlyCreated && !$artworks->isEmpty()) {
                $selectedArtworks = $artworks->random(rand(3, min(6, $artworks->count())));
                foreach ($selectedArtworks as $artwork) {
                    $exhibition->artworks()->attach($artwork->id, ['order' => rand(0, 10)]);
                }
            }
            
            // Attach random artists (2-4 per exhibition)
            if ($exhibition->wasRecentlyCreated && !$artists->isEmpty()) {
                $selectedArtists = $artists->random(rand(2, min(4, $artists->count())));
                $exhibition->artists()->attach($selectedArtists->pluck('id'));
            }
        }

        $this->command->info('Exhibitions seeded successfully.');
    }
}
