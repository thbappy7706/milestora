<?php

namespace Database\Factories;

use App\Models\Node;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'node_id' => Node::factory(),
            'title' => fake()->sentence(3),
            'url' => fake()->optional()->url(),
            'kind' => fake()->randomElement(['video', 'doc', 'book', 'course', 'repo', 'other']),
            'done' => false,
        ];
    }
}
