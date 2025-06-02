<?php

namespace Database\Seeders;

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
        // Create 5 STANDARD users and 5 ADMIN users
        $standardUser = User::factory(5)->create();
        $adminUser    = User::factory(5)->admin()->create();

        $users = $standardUser->merge($adminUser);
        $profilesStandard = $profilesAdmin = [];

        // Create 10 profiles from ADMIN users
        for ($i = 0; $i < 10; $i++) {
            $profilesAdmin[] = Profile::factory()
                ->count(1)
                ->for($adminUser->random())
                ->create();
        }

        $profiles = Profile::all();
        $profiles->shuffle();

        // Create a comment for each ADMIN user
        foreach ($adminUser as $admin) {
            Comment::factory(1)
                ->for($profiles->pop())
                ->for($admin)
                ->create();
        }
    }
}
