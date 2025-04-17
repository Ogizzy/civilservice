<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role if it doesn't exist
        $adminRole = UserRole::firstOrCreate([
            'role' => 'BDIC Super Admin'
        ]);

        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@bdic.ng'],
            [
                'surname' => 'BDIC',
                'first_name' => 'Admin',
                'other_names' => 'Marcel',
                'email' => 'admin@bdic.ng',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make("123456"),
                'role_id' => $adminRole->id,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        $this->command->info('Admin user seeded successfully!');
        $this->command->info('Email: admin@bdic.ng');
        $this->command->info('Password: 123456');
    }
}
