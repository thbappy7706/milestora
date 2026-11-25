<?php

namespace Database\Factories;

use App\Enums\TrackingMode;
use App\Models\NodeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NodeType>
 */
class NodeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * A null user_id makes the type a system type visible to every account.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'key' => fake()->unique()->lexify('custom_??????'),
            'label' => fake()->words(2, true),
            'icon' => 'circle',
            'color' => '#6366f1',
            'counts_toward_progress' => true,
            'default_tracking_mode' => TrackingMode::Checkbox,
        ];
    }

    /**
     * Indicate that the type belongs to a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (): array => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the type is excluded from progress rollups.
     */
    public function withoutProgress(): static
    {
        return $this->state(fn (): array => [
            'counts_toward_progress' => false,
        ]);
    }
}
