<?php

namespace Database\Seeders;

use App\Models\LGA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LGASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lgas = [
            ['lga' => 'Ado'],
            ['lga' => 'Agatu'],
            ['lga' => 'Apa'],
            ['lga' => 'Buruku'],
            ['lga' => 'Makurdi'],
            ['lga' => 'Gboko'],
            ['lga' => 'Guma'],
            ['lga' => 'Gwer East'],
            ['lga' => 'Gwer West'],
            ['lga' => 'Konshisha'],
            ['lga' => 'Otukpo'],
            ['lga' => 'Katsina-Ala'],
            ['lga' => 'Vandeikya'],
        ];

        foreach ($lgas as $lga) {
            LGA::firstOrCreate($lga);
        }
    }
}
