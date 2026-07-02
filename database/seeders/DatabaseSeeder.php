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
        $users = [
            [
                'name' => 'Hiren Darji',
                'email' => 'info@crmtechs.com',
                'username' => 'crmtechs',
                'status' => 'active',
            ],
            [
                'name' => 'Jayant Lad',
                'email' => 'jayant.lad@crmtechs.com',
                'username' => 'jayant.lad',
                'status' => 'active',
            ],
        ];

        foreach ($users as $user)
        {
            $existingUser = User::where('username', $user['username'])->first();

            if (!$existingUser)
            {
                User::factory()->create($user);
            }
        }
    }
}
