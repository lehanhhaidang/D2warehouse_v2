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
                'name' => "Phiếu đề xuất 1",
                'type' => "DXNTP",
                'status' => ProposeStatus::PENDING_SEND->value,
                'warehouse_id' => 2,
                'description' => "Đề xuất nhập thành phẩm cho kho thành phẩm 1. Các sản phẩm cần nhập được liệt kê chi tiết trong phiếu.",
                'created_by' => 3,
                'created_at' => now(),
            ],
            [
                'name' => "Phiếu đề xuất 2",
                'type' => "DXXTP",
                'status' => ProposeStatus::APPROVED->value,
                'warehouse_id' => 2,
                'description' => "Đề xuất xuất thành phẩm cho kho thành phẩm 1. Các sản phẩm cần xuất được liệt kê chi tiết trong phiếu.",
                'created_by' => 2,
                'created_at' => now(),

            ],
            [
                'name' => "Phiếu đề xuất 3",
                'type' => "DXNNVL",
                'status' => ProposeStatus::PENDING->value,
                'warehouse_id' => 2,
                'description' => "Đề xuất nhập nguyên vật liệu cho kho nguyên vật liệu 1. Các sản phẩm cần nhập được liệt kê chi tiết trong phiếu.",
                'created_by' => 3,
                'created_at' => now(),

            ],
            [
                'name' => "Phiếu đề xuất 4",
                'type' => "DXXNVL",
                'status' => ProposeStatus::REJECTED->value,
                'warehouse_id' => 2,
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
                'quantity' => 10,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 1,
                'product_id' => 2,
                'material_id' => null,
                'quantity' => 20,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 1,
                'product_id' => 3,
                'material_id' => null,
                'quantity' => 30,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 2,
                'product_id' => 4,
                'material_id' => null,
                'quantity' => 10,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 2,
                'product_id' => 4,
                'material_id' => null,
                'quantity' => 20,
                'unit' => 'Chai',
            ],
            [
                'propose_id' => 3,
                'material_id' => 1,
                'product_id' => null,
                'quantity' => 10,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 3,
                'material_id' => 2,
                'product_id' => null,
                'quantity' => 20,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 3,
                'material_id' => 2,
                'product_id' => null,
                'quantity' => 30,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 4,
                'material_id' => 2,
                'product_id' => null,
                'quantity' => 10,
                'unit' => 'Kg',
            ],
            [
                'propose_id' => 4,
                'material_id' => 1,
                'product_id' => null,
                'quantity' => 20,
                'unit' => 'Kg',
            ],

        ];

        DB::table('propose_details')->insert($proposeDetails);
    }
}
