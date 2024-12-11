<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductMaterialFormula extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_material_formulas')->insert([
            [
                'product_id' => 1,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 2,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 3,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 4,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 5,
            ],
            [
                'product_id' => 5,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 5,
            ],
            [
                'product_id' => 6,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 50,
            ],
            [
                'product_id' => 7,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 50,
            ],
            [
                'product_id' => 8,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 20,
            ],
            [
                'product_id' => 9,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 30,
            ],
            [
                'product_id' => 10,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 30,
            ],
            [
                'product_id' => 11,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 20,
            ],
            [
                'product_id' => 12,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 2,
            ],
            [
                'product_id' => 13,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 14,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 5,
            ],
            [
                'product_id' => 15,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 5,
            ],
            [
                'product_id' => 16,
                'material_id' => 2,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 17,
                'material_id' => 2,
                'product_quantity' => 100,
                'material_quantity' => 10,
            ],
            [
                'product_id' => 18,
                'material_id' => 1,
                'product_quantity' => 100,
                'material_quantity' => 30,
            ],
        ]);
    }
}
