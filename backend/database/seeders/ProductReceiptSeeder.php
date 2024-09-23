<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productReceipt = [
            [
                'name' => 'Phiếu nhập kho thành phẩm 1',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho thành phẩm 2',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho thành phẩm 3',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho thành phẩm 4',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu nhập kho thành phẩm 5',
                'receive_date' => Carbon::now(),
                'status' => 1,
                'user_id' => 4, // Sửa lại theo id của user có sẵn trong DB
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]

        ];

        DB::table('product_receipts')->insert($productReceipt);

        $productReceiptDetails = [
            [
                'product_receipt_id' => 1,
                'product_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 1,
                'product_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 2,
                'product_id' => 2,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 2,
                'product_id' => 4,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 3,
                'product_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 3,
                'product_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 4,
                'product_id' => 2,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 4,
                'product_id' => 4,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 5,
                'product_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_receipt_id' => 5,
                'product_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
        ];

        DB::table('product_receipt_details')->insert($productReceiptDetails);
    }
}
