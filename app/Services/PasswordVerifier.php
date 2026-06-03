<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordVerifier
{
    /**
     * Verify a plain password against stored hash (bcrypt, argon2i, argon2id).
     */
    public function verify(User $user, string $plainPassword): bool
    {
        $hash = $user->getAuthPassword();

        if (empty($hash)) {
            return false;
        }

        // Native password_verify handles multiple algorithms (bcrypt, argon2id, etc.)
        // based on the hash prefix, and it doesn't throw on mismatch.
        if (password_verify($plainPassword, $hash)) {
            return true;
        }

        // Fallback to Laravel's Hash::check for any custom hasher logic,
        // but catch exceptions like "This password does not use the Argon2id algorithm".
        try {
            return Hash::check($plainPassword, $hash);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
