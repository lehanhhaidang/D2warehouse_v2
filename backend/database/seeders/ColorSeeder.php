<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            [
                'name' => 'Đỏ',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Xanh',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Vàng',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Trắng',
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Đen',
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
