<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vaccine::create([
            'name' => 'Verocell',
            'vendor' => 'verocell',
        ]);

        Vaccine::create([
            'name' => 'AstraZenca',
            'vendor' => 'AstraZenca',
        ]);

        Vaccine::create([
            'name' => 'Pfizer',
            'vendor' => 'Pfizer',
        ]);
    }
}
