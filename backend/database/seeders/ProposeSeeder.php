<?php

namespace Database\Seeders;

use App\Enum\ProposeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proposes = [
            [
                'name' => "Phiếu đề xuất nhập thành phẩm 01",
                'type' => "DXNTP",
                'status' => 0,
                'order_id' => null,
                'warehouse_id' => 2,
                'assigned_to' => null,
                'description' => "Đề xuất nhập thành phẩm cho kho thành phẩm 1. Các sản phẩm cần nhập được liệt kê chi tiết trong phiếu.",
                'created_by' => 4,
                'created_at' => now(),
            ],
            [
                'name' => "Phiếu đề xuất xuất thành phẩm 01",
                'type' => "DXXTP",
                'status' => 0,
                'order_id' => 1,
                'warehouse_id' => 2,
                'assigned_to' => null,
                'description' => "Đề xuất xuất thành phẩm cho kho thành phẩm 1. Các sản phẩm cần xuất được liệt kê chi tiết trong phiếu.",
                'created_by' => 4,
                'created_at' => now(),

            ],
            [
                'name' => "Phiếu đề xuất nhập nguyên vật liệu 01",
                'type' => "DXNNVL",
                'status' => 0,
                'order_id' => null,
                'warehouse_id' => 1,
                'assigned_to' => 4,
                'description' => "Đề xuất nhập nguyên vật liệu cho kho nguyên vật liệu 1. Các sản phẩm cần nhập được liệt kê chi tiết trong phiếu.",
                'created_by' => 2,
                'created_at' => now(),

            ],
            [
                'name' => "Phiếu đề xuất xuất nguyên vật liệu 01",
                'type' => "DXXNVL",
                'status' => 0,
                'order_id' => null,
                'warehouse_id' => 1,
                'assigned_to' => 4,
                'description' => "Đề xuất xuất nguyên vật liệu cho kho nguyên vật liệu 1. Các sản phẩm cần xuất được liệt kê chi tiết trong phiếu.",
                'created_by' => 2,
                'created_at' => now(),

            ],
        ];


        DB::table('proposes')->insert($proposes);

        $proposeDetails = [
            [
                'propose_id' => 1,
                'product_id' => 1,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 1,
                'product_id' => 2,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 1,
                'product_id' => 3,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 2,
                'product_id' => 1,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 2,
                'product_id' => 2,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 2,
                'product_id' => 3,
                'material_id' => null,
                'quantity' => 100,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 3,
                'material_id' => 1,
                'product_id' => null,
                'quantity' => 100,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 3,
                'material_id' => 2,
                'product_id' => null,
                'quantity' => 100,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 4,
                'material_id' => 1,
                'product_id' => null,
                'quantity' => 100,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 4,
                'material_id' => 2,
                'product_id' => null,
                'quantity' => 100,
                'unit' => 'Kg',
            ],

        ];

        DB::table('propose_details')->insert($proposeDetails);
    }
}
