<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Art News', 'Artist Spotlight', 'Gallery Updates', 'Art Techniques', 'Market Trends', 'Exhibition Reviews'];
        
        return [
            'user_id' => User::factory()->admin(),
            'title' => $this->faker->sentence(6),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraphs(10, true),
            'featured_image' => $this->faker->imageUrl(1920, 1080),
            'category' => $this->faker->randomElement($categories),
            'tags' => $this->faker->words(5),
            'is_published' => $this->faker->boolean(70),
            'is_featured' => $this->faker->boolean(20),
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'meta_title' => $this->faker->sentence(8),
            'meta_description' => $this->faker->sentence(15),
            'meta_keywords' => $this->faker->words(5, true),
            'views_count' => $this->faker->numberBetween(0, 5000),
        ];
    }

    /**
     * Indicate that the blog post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the blog post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the blog post is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the blog post is written by an artist.
     */
    public function artist(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory()->artist(),
        ]);
    }
}
