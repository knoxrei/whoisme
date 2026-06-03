<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PasswordVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_verify_argon2id_password()
    {
        // Ensure we are using argon2id
        config(['hashing.driver' => 'argon2id']);

        $password = 'password123';
        $user = User::factory()->create([
            'password' => $password, // This will be hashed as argon2id due to 'hashed' cast
        ]);

        $verifier = new PasswordVerifier();
        $this->assertTrue($verifier->verify($user, $password));
    }

    public function test_it_can_verify_bcrypt_password_when_argon2id_is_default()
    {
        config(['hashing.driver' => 'argon2id']);

        $password = 'password123';
        $bcryptHash = password_hash($password, PASSWORD_BCRYPT);

        $user = User::factory()->create();
        // Manually set a bcrypt hash, bypassing the 'hashed' cast if possible or just setting it directly
        $user->forceFill(['password' => $bcryptHash])->save();

        $verifier = new PasswordVerifier();
        
        // This is expected to fail or throw based on the bug report
        try {
            $result = $verifier->verify($user, $password);
            $this->assertTrue($result, 'Failed to verify bcrypt password');
        } catch (\RuntimeException $e) {
            $this->fail('Caught RuntimeException: ' . $e->getMessage());
        }
    }
}
