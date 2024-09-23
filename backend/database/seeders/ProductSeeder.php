<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReceipt;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Chai nhựa HDPE 1 lít xanh',
                'unit' => 'chai',
                'quantity' => 100,
                'status' => 1,
                'category_id' => 4,
                'color_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai nhựa HDPE 1 lít đỏ',
                'unit' => 'chai',
                'quantity' => 100,
                'status' => 1,
                'category_id' => 4,
                'color_id' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai nhựa HDPE 1 lít vàng',
                'unit' => 'chai',
                'quantity' => 100,
                'status' => 1,
                'category_id' => 4,
                'color_id' => 3,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai nhựa HDPE 1 lít trắng',
                'unit' => 'chai',
                'quantity' => 100,
                'status' => 1,
                'category_id' => 4,
                'color_id' => 4,
                'created_at' => now(),
                'updated_at' => null,
            ],
        ]);
    }
}
