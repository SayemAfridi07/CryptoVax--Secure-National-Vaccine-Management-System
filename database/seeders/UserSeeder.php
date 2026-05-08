<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(User::where('email', 'admin@test.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'role' => '1',
            ]);
        }

        if(User::where('email', 'operator@test.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test Operator',
                'email' => 'operator@test.com',
                'role' => '2',
                'center_id' => 1,
            ]);
        }
    }
}
