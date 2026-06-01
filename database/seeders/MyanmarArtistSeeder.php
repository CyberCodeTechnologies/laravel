<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MyanmarArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artists = [
            [
                'first_name' => 'Ko',
                'last_name' => 'Mouk Gyi',
                'email' => 'komoukgyi@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Yangon, Myanmar',
                'years_active' => 15,
                'bio' => 'Ko Mouk Gyi is a renowned Myanmar artist specializing in traditional oil paintings that capture the essence of Myanmar culture and landscapes. With over 15 years of experience, his work has been exhibited in galleries across Southeast Asia.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'ko-mouk-gyi',
            ],
            [
                'first_name' => 'Thike',
                'last_name' => 'Oo',
                'email' => 'thikeoo@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Mandalay, Myanmar',
                'years_active' => 12,
                'bio' => 'Thike Oo is a master watercolor artist known for his delicate brushwork and vibrant interpretations of Myanmar\'s natural beauty. His paintings often feature rural landscapes and traditional Myanmar scenes.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'thike-oo',
            ],
            [
                'first_name' => 'Soe Naing',
                'last_name' => 'Soe',
                'email' => 'soenaingsoe@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Yangon, Myanmar',
                'years_active' => 8,
                'bio' => 'Soe Naing Soe is a contemporary acrylic artist who brings modern techniques to traditional Myanmar themes. His bold use of color and innovative compositions have earned him recognition in the regional art scene.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'soe-naing-soe',
            ],
            [
                'first_name' => 'Fuji Ko',
                'last_name' => 'Pauk',
                'email' => 'fujikopauk@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Yangon, Myanmar',
                'years_active' => 15,
                'bio' => 'Fuji Ko Pauk (ခင်မောင်ဝင်း) is a contemporary Myanmar artist known for his unique blend of traditional Burmese motifs with modern artistic expressions.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'fuji-ko-pauk',
            ],
            [
                'first_name' => 'Maung',
                'last_name' => 'Kyaing',
                'email' => 'maungkyaing@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Bagan, Myanmar',
                'years_active' => 25,
                'bio' => 'Maung Kyaing (မောက်ကြီး) is a renowned artist from Bagan specializing in Buddhist-themed artworks inspired by the ancient temple murals and spiritual traditions of Myanmar.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'maung-kyaing',
            ],
            [
                'first_name' => 'Khin Maung',
                'last_name' => 'Win',
                'email' => 'khinmaungwin@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Naypyidaw, Myanmar',
                'years_active' => 6,
                'bio' => 'Khin Maung Win is a pioneering digital artist in Myanmar, blending traditional artistic elements with modern digital techniques. His work explores the intersection of Myanmar heritage and contemporary digital culture.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'khin-maung-win',
            ],
            [
                'first_name' => 'Thaw Ko Ko',
                'last_name' => 'Kyaw',
                'email' => 'thawkokokyaw@panchigallery.com',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'is_verified' => true,
                'email_verified_at' => now(),
                'location' => 'Bagan, Myanmar',
                'years_active' => 10,
                'bio' => 'Thaw Ko Ko Kyaw is an acclaimed photographer specializing in capturing Myanmar\'s ancient temples and cultural heritage. His photographic work has been featured in international publications and exhibitions.',
                'avatar' => 'images/placeholder-avatar.jpg',
                'cover_image' => 'images/placeholder-artwork.jpg',
                'slug' => 'thaw-ko-kyaw',
            ],
        ];

        foreach ($artists as $artistData) {
            User::firstOrCreate(
                ['email' => $artistData['email']],
                $artistData
            );
        }

        $this->command->info('Myanmar artists seeded successfully!');
    }
}
