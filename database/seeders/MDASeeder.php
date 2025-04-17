<?php

namespace Database\Seeders;

use App\Models\MDA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MDASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mdas = [
            ['mda' => 'Ministry of Justice & Public Order', 'mda_code' => 'MOJPO'],
            ['mda' => 'Ministry of Finance, Budget & Economic Planning', 'mda_code' => 'MOFBEP'],
            ['mda' => 'Ministry of Power, Renewable Energy and Transport', 'mda_code' => 'MOPRET'],
            ['mda' => 'Ministry of Information, Culture & Tourism', 'mda_code' => 'MOICT'],
            ['mda' => 'Ministry of Lands, Survey & Solid Minerals', 'mda_code' => 'MOLSSM'],
            ['mda' => 'Ministry of Works, Housing & Urban Development', 'mda_code' => 'MOWHUD'],
            ['mda' => 'Ministry of Agriculture and Food Security', 'mda_code' => 'MOAFS'],
            ['mda' => 'Ministry of Health & Human Services', 'mda_code' => 'MOHHS'],
            ['mda' => 'Ministry of Industry, Trade & Investment', 'mda_code' => 'MITI'],
            ['mda' => 'Ministry of Cooperatives and Rural Development', 'mda_code' => 'MCRD'],
            ['mda' => 'Ministry of Education & Knowledge Management', 'mda_code' => 'MOEKM'],
            ['mda' => 'Ministry of Water Resources, Environment & Climate Change', 'mda_code' => 'MOWRECC'],
            ['mda' => 'Ministry of Communications and Digital Economy', 'mda_code' => 'MOCDE'],
            ['mda' => 'Ministry of Humanitarian Affairs & Disaster Management', 'mda_code' => 'MOHADM'],
            ['mda' => 'Ministry of Women Affairs & Social Welfare', 'mda_code' => 'MOWASW'],
            ['mda' => 'Ministry of Youths, Sports & Creativity', 'mda_code' => 'MOYSC'],
            ['mda' => 'Ministry of Science and Technology', 'mda_code' => 'MOST'],
        ];

        foreach ($mdas as $mda) {
            MDA::firstOrCreate($mda);
        }
    }
}
