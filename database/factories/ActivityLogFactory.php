<?php

namespace Database\Factories;

use App\Enums\ActivityType;
use App\Models\ActivityLog;
use App\Models\Node;
use App\Models\Roadmap;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'roadmap_id' => Roadmap::factory(),
            'node_id' => null,
            'type' => ActivityType::Time,
            'minutes' => fake()->numberBetween(5, 120),
            'value' => null,
            'note' => fake()->optional()->sentence(),
            'logged_at' => now(),
            'local_date' => now()->toDateString(),
        ];
    }

    /**
     * Attach the log to a node, deriving its owner and roadmap.
     */
    public function forNode(Node $node): static
    {
        return $this->state(fn (): array => [
            'user_id' => $node->roadmap->user_id,
            'roadmap_id' => $node->roadmap_id,
            'node_id' => $node->id,
        ]);
    }
}
