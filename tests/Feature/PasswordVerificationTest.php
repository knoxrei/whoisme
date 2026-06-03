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
        // Create a bcrypt hash manually
        $bcryptHash = password_hash($password, PASSWORD_BCRYPT);

        $user = User::factory()->create();
        // Manually set a bcrypt hash, bypassing the 'hashed' cast
        $user->forceFill(['password' => $bcryptHash])->save();

        $verifier = new PasswordVerifier();
        
        // This should now work without throwing an exception
        $this->assertTrue($verifier->verify($user, $password), 'Failed to verify bcrypt password');
    }

    public function test_it_returns_false_for_invalid_password()
    {
        $user = User::factory()->create([
            'password' => 'correct_password',
        ]);

        $verifier = new PasswordVerifier();
        $this->assertFalse($verifier->verify($user, 'wrong_password'));
    }

    public function test_it_handles_empty_hash()
    {
        $user = User::factory()->create();
        $user->forceFill(['password' => ''])->save();

        $verifier = new PasswordVerifier();
        $this->assertFalse($verifier->verify($user, 'password'));
    }
}
