<?php

namespace Database\Factories;

use App\Enums\RoadmapKind;
use App\Enums\RoadmapStatus;
use App\Models\Roadmap;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Roadmap>
 */
class RoadmapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            // Generated from the title by Roadmap::booted() so a title override wins.
            'slug' => null,
            'description' => fake()->optional()->paragraph(),
            'kind' => RoadmapKind::Learning,
            'status' => RoadmapStatus::Draft,
            'start_date' => now()->toDateString(),
            'target_end_date' => now()->addMonths(3)->toDateString(),
            'daily_goal_minutes' => 120,
            'active_weekdays' => [1, 2, 3, 4, 5, 6],
            'timezone' => 'Asia/Dhaka',
            'scoring_config' => null,
            'source_import_id' => null,
            'is_template' => false,
            'is_public' => false,
            'share_token' => null,
            'meta' => ['emoji' => '🎯', 'color' => '#6366f1'],
        ];
    }

    /**
     * Indicate that the roadmap is a reusable template.
     */
    public function template(): static
    {
        return $this->state(fn (): array => [
            'is_template' => true,
            'is_public' => true,
            'status' => RoadmapStatus::Completed,
        ]);
    }

    /**
     * Indicate that the roadmap is publicly shareable.
     */
    public function published(): static
    {
        return $this->state(fn (): array => [
            'is_public' => true,
            'share_token' => Str::random(40),
        ]);
    }
}
