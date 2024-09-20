<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Chai nhựa HDPE',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Xô nhựa HDPE',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ nhựa HDPE',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai nhựa PET',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Xô nhựa PET',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ nhựa PET',
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
