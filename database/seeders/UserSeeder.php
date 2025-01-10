<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Import Str

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) Str::uuid(), // Generate a UUID
            'name' => 'Adriannn',
            'username' => 'admon',
            'email' => 'Adriannn.devs@gmail.com',
            'phone' => '0813345454545',
            'password' => bcrypt('pastibisa'),
        ]);
    }
}
