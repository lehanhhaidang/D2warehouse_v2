<?php

namespace Database\Seeders;

use App\Models\MaterialReceipt;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            WarehouseSeeder::class,
            MaterialSeeder::class,
            ProductSeeder::class,
            ShelfSeeder::class,
            OrderSeeder::class,
            ProposeSeeder::class,
            // ProductReceiptSeeder::class,
            // ProductExportSeeder::class,
            // MaterialReceiptSeeder::class,
            // MaterialExportSeeder::class,
            InventoryReportSeeder::class,
            WarehouseStaffSeeder::class,
            ProductMaterialFormula::class,


        ]);
    }
}
