<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(8)->create();

        User::updateOrCreate(
            ['email' => 'admin@fundhub.test'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'startup@fundhub.test'],
            [
                'name' => 'Sample Startup',
                'password' => Hash::make('password'),
                'role' => 'startup',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'investor@fundhub.test'],
            [
                'name' => 'Sample Investor',
                'password' => Hash::make('password'),
                'role' => 'investor',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Additional demo accounts for testing
        User::updateOrCreate(
            ['email' => 'admin@larawell.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'maya.investor@larawell.test'],
            [
                'name' => 'Maya Investor',
                'password' => Hash::make('password'),
                'role' => 'investor',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'daniel.angel@larawell.test'],
            [
                'name' => 'Daniel Angel',
                'password' => Hash::make('password'),
                'role' => 'investor',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'ava.startup@larawell.test'],
            [
                'name' => 'Ava Startup',
                'password' => Hash::make('password'),
                'role' => 'startup',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'noah.startup@larawell.test'],
            [
                'name' => 'Noah Startup',
                'password' => Hash::make('password'),
                'role' => 'startup',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'zara.startup@larawell.test'],
            [
                'name' => 'Zara Startup',
                'password' => Hash::make('password'),
                'role' => 'startup',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Seed demo chat data (startup, investor, request, messages)
        $this->call(\Database\Seeders\ChatDemoSeeder::class);
    }
}
