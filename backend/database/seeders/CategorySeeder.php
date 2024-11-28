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
            // Danh mục cha - Material
            [
                'name' => 'Material',
                'type' => 'category',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Product',
                'type' => 'category',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => null,
            ],

            // Danh mục con của Material
            [
                'name' => 'Nhựa HDPE',
                'type' => 'material',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Nhựa PET',
                'type' => 'material',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],

            // Danh mục con của Product
            [
                'name' => 'Chai nhựa HDPE',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Xô nhựa HDPE',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ nhựa HDPE',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai nhựa PET',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Xô nhựa PET',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ nhựa PET',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Can nhựa HDPE',
                'type' => 'product',
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
