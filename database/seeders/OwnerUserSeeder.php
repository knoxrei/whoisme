<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enum\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Avoid duplicate owner seeding if one already exists
        $user = User::where('email', 'owner@kun')
            ->orWhere('username', 'owner')
            ->first();

        if (!$user) {
            $user = User::create([
                'username' => 'owner',
                'email' => 'owner@kun',
                'password' => Hash::make('password'),
                'last_active' => now(),
                'email_verified_at' => now(),
            ]);

            $user->identification()->create([
                'role' => Role::OWNER,
                'reputation' => 100,
                'bio' => '',
            ]);

        }
    }
}
