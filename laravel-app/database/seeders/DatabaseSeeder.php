<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Comment;
use App\Models\Profile;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 3 STANDARD users and 2 ADMIN users
        $standardUser = User::factory(3)->create();
        $adminUser    = User::factory(2)->admin()->create();

        $users = $standardUser->merge($adminUser);
        $profilesStandard = $profilesAdmin = [];

        // Create 5 profiles from STANDARD users
        for ($i = 0; $i < 5; $i++) {
            $profilesStandard[] = Profile::factory()
                ->count(1)
                ->for($standardUser->random())
                ->create();
        }

        // Create 3 profiles from ADMIN users
        for ($i = 0; $i < 3; $i++) {
            $profilesAdmin[] = Profile::factory()
                ->count(1)
                ->for($adminUser->random())
                ->create();
        }

        // Create comments for a STANDARD profile
        for ($i = 0; $i < 10; $i++) {
            $profile = $profilesStandard[array_rand($profilesStandard)];
            Comment::factory(1)
                ->for($profile[0])
                ->for($standardUser->random())
                ->create();
        }

        $profiles = Profile::all();
        $profiles->shuffle();

        // Create a comment for each ADMIN profile
        foreach ($adminUser as $admin) {
            Comment::factory(1)
                ->for($profiles->pop())
                ->for($admin)
                ->create();
        }
    }
}
