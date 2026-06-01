<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Painting', 'slug' => 'painting', 'description' => 'Traditional and contemporary paintings', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Sculpture', 'slug' => 'sculpture', 'description' => 'Three-dimensional artworks', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Photography', 'slug' => 'photography', 'description' => 'Photographic artworks and prints', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Digital Art', 'slug' => 'digital-art', 'description' => 'Digital and new media artworks', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Mixed Media', 'slug' => 'mixed-media', 'description' => 'Artworks using multiple materials', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Drawing', 'slug' => 'drawing', 'description' => 'Drawings and illustrations', 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Printmaking', 'slug' => 'printmaking', 'description' => 'Printed artworks and editions', 'is_active' => true, 'sort_order' => 7],
            ['name' => 'Textile Art', 'slug' => 'textile-art', 'description' => 'Textile-based artworks', 'is_active' => true, 'sort_order' => 8],
            ['name' => 'Ceramics', 'slug' => 'ceramics', 'description' => 'Ceramic artworks and pottery', 'is_active' => true, 'sort_order' => 9],
            ['name' => 'Installation', 'slug' => 'installation', 'description' => 'Installation art', 'is_active' => true, 'sort_order' => 10],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
