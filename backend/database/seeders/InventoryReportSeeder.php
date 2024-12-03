<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventory_report = [
            [
                'name' => 'Báo cáo kiểm kê thành phẩm 11/02/2024',
                'status' => 0,
                'description' => 'Báo cáo kiểm kê kho thành phẩm 1 ngày 11/02/2024',
                'created_by' => 4,
                'warehouse_id' => 2,
                'created_at' => now(),
            ],
            // [
            //     'name' => 'Báo cáo tồn kho 2',
            //     'status' => 0,
            //     'description' => 'Báo cáo tồn kho 2',
            //     'created_by' => 1,
            //     'warehouse_id' => 1,
            // ],
            // [
            //     'name' => 'Báo cáo tồn kho 3',
            //     'status' => 0,
            //     'description' => 'Báo cáo tồn kho 3',
            //     'created_by' => 1,
            //     'warehouse_id' => 1,
            // ],
        ];

        DB::table('inventory_reports')->insert($inventory_report);

        $inventory_report_details = [

            [
                'inventory_report_id' => 1,
                'product_id' => 1,
                'material_id' => null,
                'shelf_id' => 1,
                'expected_quantity' => 100,
                'actual_quantity' => 100,
                'note' => null,
            ],
            [
                'inventory_report_id' => 1,
                'product_id' => 2,
                'material_id' => null,
                'shelf_id' => 2,
                'expected_quantity' => 100,
                'actual_quantity' => 100,
                'note' => null,
            ],
            [
                'inventory_report_id' => 1,
                'product_id' => 3,
                'material_id' => null,
                'shelf_id' => 3,
                'expected_quantity' => 100,
                'actual_quantity' => 100,
                'note' => null,
            ],

        ];

        DB::table('inventory_report_details')->insert($inventory_report_details);
    }
}
