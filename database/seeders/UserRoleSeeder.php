<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            // ['role' => 'BDIC Super Admin'],
            ['role' => 'Head of Service'],
            ['role' => 'Commissioner'],
            ['role' => 'Director'],
            ['role' => 'Permanent Secretary'],
            ['role' => 'Employee'],
        ];

        foreach ($roles as $role) {
            UserRole::firstOrCreate($role);
        }
    }
}
