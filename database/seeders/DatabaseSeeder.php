<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'username' => 'testuser',
                'status' => 'active',
            ],
            [
                'name' => 'Hiren Darji',
                'email' => 'hiren.darji@example.com',
                'username' => 'hiren.darji',
                'status' => 'active',
            ],
            [
                'name' => 'Jayant Lad',
                'email' => 'jayant.lad@example.com',
                'username' => 'jayant.lad',
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            $existingUser = User::where('username', $user['username'])->first();
            if (!$existingUser) {
                User::factory()->create($user);
            }
        }
    }
}
