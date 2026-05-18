<?php

namespace App\Policies;

use App\Models\User;
use App\Enum\Role;

class UserPolicy
{
    /**
     * Determine whether the user can manage user accounts.
     */
    public function manage(User $user): bool
    {
        $role = $user->identification?->role ?? Role::MEMBER;
        return $role === Role::OWNER || $role === Role::MODERATOR;
    }

    /**
     * Determine whether the user can update the target user's account details.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        $currentRole = $currentUser->identification?->role ?? Role::MEMBER;
        $targetRole = $targetUser->identification?->role ?? Role::MEMBER;

        // 1. Cannot edit their own account!
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        // 2. Moderator cannot edit Owner
        if ($targetRole === Role::OWNER && $currentRole !== Role::OWNER) {
            return false;
        }

        // 3. Cannot edit a user with a role equal to theirs (e.g. Moderator cannot edit Moderator)
        if ($targetRole === $currentRole) {
            return false;
        }

        return $currentRole === Role::OWNER || $currentRole === Role::MODERATOR;
    }

    /**
     * Determine whether the user can ban the target user.
     */
    public function ban(User $currentUser, User $targetUser): bool
    {
        return $this->update($currentUser, $targetUser);
    }

    /**
     * Determine whether the user can delete the target user.
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        return $this->update($currentUser, $targetUser);
    }
}
