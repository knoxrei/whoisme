<?php

namespace App\Policies;

use App\Models\Pastebin;
use App\Models\User;

class PostPastebin
{
  public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pastebin $pastebin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pastebin $pastebin): bool
    {
        if ($pastebin->user_id === $user->id) {
            return true;
        }

        if ($user->identification && in_array($user->identification->role, [\App\Enum\Role::OWNER, \App\Enum\Role::MODERATOR])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pastebin $pastebin): bool
    {
        if ($pastebin->user_id === $user->id) {
            return true;
        }

        if ($user->identification && in_array($user->identification->role, [\App\Enum\Role::OWNER, \App\Enum\Role::MODERATOR])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pastebin $pastebin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pastebin $pastebin): bool
    {
        return false;
    }
}
