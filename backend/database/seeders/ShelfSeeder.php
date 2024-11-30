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
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 2',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 3',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 4',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 5',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 6',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 7',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 8',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 9',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 10',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 11',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 12',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 13',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 14',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 15',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 16',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 17',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 18',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 19',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 20',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 21',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 22',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 23',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 24',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 25',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 5,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 26',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 6,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 27',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 7,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 28',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 8,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 29',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 9,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 30',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 10,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 31',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 11,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 32',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 11,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 33',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 11,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 34',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 11,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 35',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 2,
                'category_id' => 11,
                'created_at' => now(),

            ],






            //Kệ cho kho nguyên vật liệu
            [
                'name' => 'Kệ 1',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 2',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 3',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 4',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 5',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 6',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 7',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 8',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 9',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 10',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 11',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 12',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 13',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 14',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 15',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 16',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 17',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 18',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 4,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 19',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
                'warehouse_id' => 1,
                'category_id' => 3,
                'created_at' => now(),

            ],
            [
                'name' => 'Kệ 20',
                'number_of_levels' => 5,
                'storage_capacity' => 5000,
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
                'quantity' => 1800,
            ],
            [
                'shelf_id' => 2,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 2300,
            ],
            [
                'shelf_id' => 3,
                'product_id' => 11,
                'material_id' => null,
                'quantity' => 300,
            ],
            [
                'shelf_id' => 4,
                'product_id' => 15,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 5,
                'product_id' => 5,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 6,
                'product_id' => 6,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 7,
                'product_id' => 2,
                'material_id' => null,
                'quantity' => 2300,
            ],
            [
                'shelf_id' => 8,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 9,
                'product_id' => 12,
                'material_id' => null,
                'quantity' => 300,
            ],
            [
                'shelf_id' => 10,
                'product_id' => 15,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 11,
                'product_id' => 11,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 12,
                'product_id' => 12,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 13,
                'product_id' => 3,
                'material_id' => null,
                'quantity' => 800,
            ],
            [
                'shelf_id' => 14,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 15,
                'product_id' => 12,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 16,
                'product_id' => 16,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 17,
                'product_id' => 17,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 18,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 19,
                'product_id' => 4,
                'material_id' => null,
                'quantity' => 500,
            ],
            [
                'shelf_id' => 20,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 21,
                'product_id' => 13,
                'material_id' => null,
                'quantity' => 200,
            ],
            [
                'shelf_id' => 22,
                'product_id' => 17,
                'material_id' => null,
                'quantity' => 100,
            ],
            // [
            //     'shelf_id' => 23,
            //     'product_id' => 23,
            //     'material_id' => null,
            //     'quantity' => 100,
            // ],
            // [
            //     'shelf_id' => 24,
            //     'product_id' => 24,
            //     'material_id' => null,
            //     'quantity' => 100,
            // ],
            [
                'shelf_id' => 25,
                'product_id' => 5,
                'material_id' => null,
                'quantity' => 300,
            ],
            [
                'shelf_id' => 26,
                'product_id' => 18,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 27,
                'product_id' => 14,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 28,
                'product_id' => 17,
                'material_id' => null,
                'quantity' => 100,
            ],
            // [
            //     'shelf_id' => 29,
            //     'product_id' => 29,
            //     'material_id' => null,
            //     'quantity' => 100,
            // ],
            // [
            //     'shelf_id' => 30,
            //     'product_id' => 30,
            //     'material_id' => null,
            //     'quantity' => 100,
            // ],
            [
                'shelf_id' => 31,
                'product_id' => 6,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 32,
                'product_id' => 7,
                'material_id' => null,
                'quantity' => 400,
            ],
            [
                'shelf_id' => 33,
                'product_id' => 8,
                'material_id' => null,
                'quantity' => 100,
            ],
            [
                'shelf_id' => 34,
                'product_id' => 9,
                'material_id' => null,
                'quantity' => 300,
            ],
            [
                'shelf_id' => 35,
                'product_id' => 10,
                'material_id' => null,
                'quantity' => 200,
            ],



            //Ke cho kho nguyen vat lieu
            [
                'shelf_id' => 36,
                'product_id' => null,
                'material_id' => 1,
                'quantity' => 2000,
            ],
            [
                'shelf_id' => 37,
                'product_id' => null,
                'material_id' => 2,
                'quantity' => 1000,
            ],
            [
                'shelf_id' => 38,
                'product_id' => null,
                'material_id' => 1,
                'quantity' => 3000,
            ],
            [
                'shelf_id' => 39,
                'product_id' => null,
                'material_id' => 2,
                'quantity' => 1000,
            ],


        ];

        DB::table('shelf_details')->insert($shelvesDetails);
    }
}
