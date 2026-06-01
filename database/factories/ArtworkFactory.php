<?php

namespace Database\Factories;

use App\Models\Artwork;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artwork>
 */
class ArtworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Artwork::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artist_id' => User::factory()->artist(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(3),
            'medium' => $this->faker->randomElement(['Oil on Canvas', 'Acrylic on Canvas', 'Watercolor', 'Digital Art', 'Mixed Media']),
            'dimensions' => $this->faker->randomElement(['24 x 30 cm', '50 x 70 cm', '100 x 100 cm', '80 x 120 cm']),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'currency' => 'USD',
            'price_usd' => $this->faker->randomFloat(2, 100, 5000),
            'price_mmk' => $this->faker->randomFloat(0, 210000, 10500000),
            'year' => $this->faker->year(),
            'images' => [$this->faker->imageUrl(800, 600)],
            'status' => 'approved',
            'is_featured' => false,
            'views_count' => $this->faker->numberBetween(0, 1000),
            'likes_count' => $this->faker->numberBetween(0, 100),
            'stock' => $this->faker->numberBetween(1, 10),
            'weight' => $this->faker->randomFloat(2, 0.5, 10),
            'is_digital' => false,
        ];
    }

    /**
     * Indicate that the artwork is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the artwork is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
