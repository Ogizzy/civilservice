<?php

namespace Database\Seeders;

use App\Models\PlatformFeature;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlatformFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'feature' => 'Employee Management',
            'description' => 'Manage all employee records including personal information, employment details, and service history'
            ],
            [
                'feature' => 'MDA Management',
              'description' => 'Create, view, update and delete Ministries, Departments and Agencies (MDAs)'
            ],
            [
                'feature' => 'Payroll Integration',
               'description' => 'Integration with payroll system for salary processing'
            ],
            [
                'feature' => 'Reports',
               'description' => 'Generate various reports including employee lists, retirement reports, and analytics'
            ],
            [
                'feature' => 'Transfer',
              'description' => 'Manage employee transfers between MDAs with complete history tracking'
            ],
            [
                'feature' => 'Promotion',
                'description' => 'Handle employee promotions including Grade Level and Step Advancements'
            ],
            [
                'feature' => 'Retirement',
              'description' => 'Track and manage employee retirement dates and processes'
            ],
            [
                'feature' => 'Document Management',
                  'description' => 'Upload, store and manage employee documents and certificates'
            ],
            [
                'feature' => 'User Management',
                'description' => 'Create and manage system users with different access levels'
            ],
            [
                'feature' => 'Leave Management',
                'description' => 'Manage employee leave applications and approvals'
            ],
            [
                'feature' => 'Commendations and Awards',
               'description' => 'Record and track employee commendations and awards'
            ],
            [
                'feature' => 'System Settings',
                'description' => 'Configure system-wide settings and preferences'
            ]
        ];

        foreach ($features as $feature) {
            PlatformFeature::create($feature);
        }

        $this->command->info('Platform features seeded successfully!');
    
    }
}
