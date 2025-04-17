<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserRoleSeeder::class,
            AdminSeeder::class, 
            PlatformFeaturesTableSeeder::class,
            MDASeeder::class,
            PayGroupSeeder::class,
            GradeLevelSeeder::class,
            StepSeeder::class,
            LGASeeder::class,
        ]);
       

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
