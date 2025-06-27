<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::updateOrCreate(
            ['code' => 'AL'],
            [
                'name' => 'Annual Leave',
                'description' => 'Annual Leave entitlement',
                'max_days_per_year' => 30,
                'is_active' => true,
            ]
        );

        LeaveType::updateOrCreate(
            ['code' => 'SL'],
            [
                'name' => 'Sick Leave',
                'description' => 'Sick Leave entitlement',
                'max_days_per_year' => 15,
                'is_active' => true,
            ]
        );

        LeaveType::updateOrCreate(
            ['code' => 'ML'],
            [
                'name' => 'Maternity Leave',
                'description' => 'Maternity Leave entitlement',
                'max_days_per_year' => 90,
                'is_active' => true,
            ]
        );
    }
}
