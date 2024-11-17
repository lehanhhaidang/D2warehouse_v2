<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class WarehouseStaffSeeder extends Seeder
{
    public function run(): void
    {
        // Dữ liệu mẫu: user_id từ 1 đến 4, sẽ đồng thời thuộc warehouse 1 và 2
        $userIds = [1, 2, 3, 4, 5, 6];
        $warehouses = [1, 2];

        $data = [];

        foreach ($userIds as $userId) {
            foreach ($warehouses as $warehouseId) {
                $data[] = [
                    'user_id' => $userId,
                    'warehouse_id' => $warehouseId,
                    'assigned_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Chèn dữ liệu vào bảng warehouse_staff
        DB::table('warehouse_staff')->insert($data);
    }
}
