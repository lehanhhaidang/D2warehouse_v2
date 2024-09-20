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
                'unit' => 'bao',
                'quantity' => 50,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Nhựa PET',
                'unit' => 'bao',
                'quantity' => 50,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
