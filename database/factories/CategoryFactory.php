<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Painting',
                'Sculpture',
                'Photography',
                'Digital Art',
                'Mixed Media',
                'Drawing',
                'Printmaking',
                'Installation',
                'Textile Art',
                'Ceramics'
            ]),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(10),
            'is_active' => true,
        ];
    }
}
