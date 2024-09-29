<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shelves = [
            [
                'name' => 'Kệ 1',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),


            ],
            [
                'name' => 'Kệ 2',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 3',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 4',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 5',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 6',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],

            //Kệ cho kho nguyên vật liệu
            [
                'name' => 'Kệ 1',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 2',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 3',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 4',
                'number_of_levels' => 5,
                'storage_capacity' => 500,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
        ];
        DB::table('shelves')->insert($shelves);


        $shelvesDetails = [
            //Ke cho kho san pham
            [
                'shelf_id' => 1,
                'product_id' => 1,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 2,
                'product_id' => 2,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 3,
                'product_id' => 3,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 4,
                'product_id' => 4,
                'material_id' => null,
                'quantity' => 100,
            ],

            //Ke cho kho nguyen vat lieu
            [
                'shelf_id' => 7,
                'product_id' => null,
                'material_id' => 1,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 8,
                'product_id' => null,
                'material_id' => 2,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 9,
                'product_id' => null,
                'material_id' => 3,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 10,
                'product_id' => null,
                'material_id' => 4,
                'quantity' => 100,
            ],


        ];

        DB::table('shelf_details')->insert($shelvesDetails);
    }
}
