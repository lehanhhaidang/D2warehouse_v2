<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'Kho nguyên vật liệu 1',
                'location' => 'Bình Chánh',
                'acreage' => 1000,
                'number_of_shelves' => 100,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Kho thành phẩm 1',
                'location' => 'Bình Chánh',
                'acreage' => 100,
                'number_of_shelves' => 200,
                'created_at' => now(),
                'updated_at' => null,
            ],

        ]);
    }
}
