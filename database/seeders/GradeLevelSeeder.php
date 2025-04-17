<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level' => '1'],
            ['level' => '2'],
            ['level' => '3'],
            ['level' => '4'],
            ['level' => '5'],
            ['level' => '6'],
            ['level' => '7'],
            ['level' => '8'],
            ['level' => '9'],
            ['level' => '10'],
            ['level' => '11'],
            ['level' => '12'],
            ['level' => '13'],
            ['level' => '14'],
            ['level' => '15'],
            ['level' => '16'],
            ['level' => '17'],
        ];

        foreach ($levels as $level) {
            GradeLevel::firstOrCreate($level);
        }
    }
}
