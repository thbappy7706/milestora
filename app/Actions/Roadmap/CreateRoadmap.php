<?php

namespace App\Actions\Roadmap;

use App\Enums\RoadmapStatus;
use App\Models\Roadmap;
use App\Models\User;

class CreateRoadmap
{
    /**
     * Create a draft roadmap for the given user.
     *
     * @param  array{title: string, description?: string|null, kind: string, daily_goal_minutes: int, start_date?: string|null, target_end_date?: string|null}  $attributes
     */
    public function handle(User $user, array $attributes): Roadmap
    {
        return $user->roadmaps()->create([
            ...$attributes,
            'status' => RoadmapStatus::Draft,
        ]);
    }
}
