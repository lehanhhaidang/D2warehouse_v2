<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Dang',
            'email' => 'dang@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '0123456789',
            'role_id' => 1,
        ]);
    }
}
