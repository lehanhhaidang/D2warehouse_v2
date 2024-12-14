<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            [
                'name' => 'Nhựa HDPE',
                'unit' => 'KG',
                'quantity' => 2000,
                'category_id' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Nhựa PET',
                'unit' => 'KG',
                'quantity' => 1000,
                'category_id' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
