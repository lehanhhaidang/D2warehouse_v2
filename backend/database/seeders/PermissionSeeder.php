<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            //Users

            'view_users',
            'create_users',
            'update_users',
            'delete_users',


            //Warehouse

            'view_warehouse',
            'create_warehouse',
            'update_warehouse',
            'delete_warehouse',

            //Products
            'view_products',
            'create_products',
            'update_products',
            'delete_products',

            //Materials

            'view_materials',
            'create_materials',
            'update_materials',
            'delete_materials',

            //ProductReceipts

            'view_product_receipts',
            'create_product_receipts',
            'update_product_receipts',
            'delete_product_receipts',

            //ProductExports

            'view_product_exports',
            'create_product_exports',
            'update_product_exports',
            'delete_product_exports',

            //Propose

            'view_propose',
            'create_propose',
            'update_propose',
            'delete_propose',

        ];

        foreach ($permissions as $permission) {
            Permission::factory()->create([
                'name' => $permission,
            ]);
        }
    }
}
