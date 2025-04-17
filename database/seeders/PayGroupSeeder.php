<?php

namespace Database\Seeders;

use App\Models\PayGroup;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PayGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payGroups = [
            ['paygroup' => 'CONPSS', 'paygroup_code' => 'CP'],
            ['paygroup' => 'CONHESS', 'paygroup_code' => 'CH'],
            ['paygroup' => 'CONPCASS', 'paygroup_code' => 'CC'],
            ['paygroup' => 'LEGISLATURE', 'paygroup_code' => 'LGT'],
        ];

        foreach ($payGroups as $group) {
            PayGroup::firstOrCreate($group);
        }
    }
}
