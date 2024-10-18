<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaterialExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaterialExportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialExport = [
            [
                'name' => 'Phiếu xuất kho nguyên vật liệu 1',
                'warehouse_id' => 1,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho nguyên vật liệu 2',
                'warehouse_id' => 1,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho nguyên vật liệu 3',
                'warehouse_id' => 1,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho nguyên vật liệu 4',
                'warehouse_id' => 1,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Phiếu xuất kho nguyên vật liệu 5',
                'warehouse_id' => 1,
                'export_date' => Carbon::now(),
                'status' => 1,
                'created_by' => 3,
                'created_at' => Carbon::now(),

            ]

        ];

        DB::table('material_exports')->insert($materialExport);

        $materialExportDetails = [
            [
                'material_export_id' => 1,
                'material_id' => 1,
                'shelf_id' => 7,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 1,
                'material_id' => 1,
                'shelf_id' => 9,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 2,
                'material_id' => 2,
                'shelf_id' => 8,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 2,
                'material_id' => 2,
                'shelf_id' => 8,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 3,
                'material_id' => 1,
                'shelf_id' => 7,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 3,
                'material_id' => 1,
                'shelf_id' => 9,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 4,
                'material_id' => 1,
                'shelf_id' => 7,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 4,
                'material_id' => 2,
                'shelf_id' => 10,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 5,
                'material_id' => 1,
                'shelf_id' => 7,
                'unit' => 'bao',
                'quantity' => 100,
            ],
            [
                'material_export_id' => 5,
                'material_id' => 1,
                'shelf_id' => 9,
                'unit' => 'bao',
                'quantity' => 100,
            ],
        ];

        DB::table('material_export_details')->insert($materialExportDetails);
    }
}
