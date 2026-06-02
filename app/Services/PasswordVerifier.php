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

        if ($hash === null || $hash === '') {
            return false;
        }

        if (Hash::check($plainPassword, $hash)) {
            return true;
        }

        return password_verify($plainPassword, $hash);
    }
}
