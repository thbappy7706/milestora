<?php

namespace Database\Factories;

use App\Enums\NodeStatus;
use App\Enums\TrackingMode;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Roadmap;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Node>
 */
class NodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'roadmap_id' => Roadmap::factory(),
            'node_type_id' => NodeType::factory(),
            'parent_id' => null,
            'path' => '',
            'depth' => 0,
            'position' => 0,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'tracking_mode' => TrackingMode::Checkbox,
            'status' => NodeStatus::Todo,
            'target_value' => null,
            'current_value' => null,
            'unit' => null,
            'estimate_minutes' => fake()->numberBetween(15, 180),
            'difficulty' => fake()->numberBetween(1, 5),
            'weight' => 1,
            'due_date' => null,
            'start_date' => null,
            'completed_at' => null,
            'score_self' => null,
            'evidence_url' => null,
            'import_key' => null,
            'confidence' => null,
            'recurrence' => null,
            'meta' => null,
        ];
    }

    /**
     * Place the node under the given parent.
     */
    public function childOf(Node $parent): static
    {
        return $this->state(fn (): array => [
            'roadmap_id' => $parent->roadmap_id,
            'parent_id' => $parent->id,
            'depth' => $parent->depth + 1,
        ]);
    }

    /**
     * Indicate that the node is completed.
     */
    public function done(): static
    {
        return $this->state(fn (): array => [
            'status' => NodeStatus::Done,
            'completed_at' => now(),
        ]);
    }

    /**
     * Assign the materialized path once the node has an id.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Node $node): void {
            $path = $node->parent_id === null
                ? "/{$node->id}/"
                : Node::query()->findOrFail($node->parent_id)->path.$node->id.'/';

            $node->forceFill(['path' => $path])->saveQuietly();
        });
    }
}
