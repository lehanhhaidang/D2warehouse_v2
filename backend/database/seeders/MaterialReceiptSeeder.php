<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MaterialReceipt;

class MaterialReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialReceipt = [
            [
                'name' => 'Phiếu nhập kho nguyên vật liệu 1',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho nguyên vật liệu 2',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho nguyên vật liệu 3',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho nguyên vật liệu 4',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho nguyên vật liệu 5',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]

        ];

        DB::table('material_receipts')->insert($materialReceipt);

        $materialReceiptDetails = [
            [
                'material_receipt_id' => 1,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 1,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 2,
                'material_id' => 2,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 2,
                'material_id' => 2,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 3,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 3,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 4,
                'material_id' => 2,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 4,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 5,
                'material_id' => 1,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_receipt_id' => 5,
                'material_id' => 2,
                'unit' => 'bao',
                'quantity' => 100,
            ],
        ];

        DB::table('material_receipt_details')->insert($materialReceiptDetails);
    }
}
