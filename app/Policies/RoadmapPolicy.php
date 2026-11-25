<?php

namespace App\Policies;

use App\Models\Roadmap;
use App\Models\User;

class RoadmapPolicy
{
    /**
     * Determine whether the user can browse their roadmaps.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the roadmap.
     */
    public function view(User $user, Roadmap $roadmap): bool
    {
        return $this->owns($user, $roadmap);
    }

    /**
     * Determine whether the user can create roadmaps.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the roadmap.
     */
    public function update(User $user, Roadmap $roadmap): bool
    {
        return $this->owns($user, $roadmap);
    }

    /**
     * Determine whether the user can delete the roadmap.
     */
    public function delete(User $user, Roadmap $roadmap): bool
    {
        return $this->owns($user, $roadmap);
    }

    /**
     * Determine whether the user can restore the roadmap.
     */
    public function restore(User $user, Roadmap $roadmap): bool
    {
        return $this->owns($user, $roadmap);
    }

    /**
     * Determine whether the user can permanently delete the roadmap.
     */
    public function forceDelete(User $user, Roadmap $roadmap): bool
    {
        return $this->owns($user, $roadmap);
    }

    /**
     * Determine whether the user owns the roadmap.
     */
    private function owns(User $user, Roadmap $roadmap): bool
    {
        return $roadmap->user_id === $user->id;
    }
}
