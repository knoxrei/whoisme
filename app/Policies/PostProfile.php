<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\Identification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostProfile
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Identification $identification): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->identification->role == Role::MODERATOR || $user->identification->role == Role::OWNER) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Identification $identification): bool
    {
        if ($user->id === $identification->user_id || $user->identification->role == Role::MODERATOR || $user->identification->role == Role::OWNER) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Identification $identification): bool
    {
        if ($identification->role == Role::MODERATOR || $user->identification->role == Role::OWNER) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Identification $identification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Identification $identification): bool
    {
        return false;
    }
}
