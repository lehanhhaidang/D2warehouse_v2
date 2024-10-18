<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductExportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productExport = [
            [
                'name' => 'Phiếu xuất kho thành phẩm 1',
                'warehouse_id' => 2,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Phiếu xuất kho thành phẩm 2',
                'warehouse_id' => 2,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho thành phẩm 3',
                'warehouse_id' => 2,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho thành phẩm 4',
                'warehouse_id' => 2,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho thành phẩm 5',
                'warehouse_id' => 2,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ]

        ];

        DB::table('product_exports')->insert($productExport);

        $productExportDetails = [
            [
                'product_export_id' => 1,
                'product_id' => 1,
                'shelf_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 1,
                'product_id' => 3,
                'shelf_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 2,
                'product_id' => 2,
                'shelf_id' => 2,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 2,
                'product_id' => 4,
                'shelf_id' => 4,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 3,
                'product_id' => 1,
                'shelf_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 3,
                'product_id' => 3,
                'shelf_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 4,
                'product_id' => 2,
                'shelf_id' => 2,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 4,
                'product_id' => 4,
                'shelf_id' => 4,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 5,
                'product_id' => 1,
                'shelf_id' => 1,
                'unit' => 'chai',
                'quantity' => 100,
            ],
            [
                'product_export_id' => 5,
                'product_id' => 3,
                'shelf_id' => 3,
                'unit' => 'chai',
                'quantity' => 100,
            ],
        ];

        DB::table('product_export_details')->insert($productExportDetails);
    }
}
