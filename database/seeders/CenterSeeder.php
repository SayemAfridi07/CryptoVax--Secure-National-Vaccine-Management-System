<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Center::create([
            'name' => 'Dhaka',
            'address' => 'dhaka',
        ]);

        Center::create([
            'name' => 'Gopalganj',
            'address' => 'gopalganj',
        ]);

        Center::create([
            'name' => 'Khulna',
            'address' => 'khulna',
        ]);
    }
}
