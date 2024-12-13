<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Quản lý kho',
            'Giám đốc',
            'Nhân viên kho',
        ];

        $managerRoles = [
            //Warehouses
            'view_warehouses',

            //Products
            'view_products',


            //Materials
            'view_materials',

            //Shelves
            'view_shelves',

            //Product Receipts
            'view_product_receipts',

            //Product Exports
            'view_product_exports',

            //Material Receipts
            'view_material_receipts',

            //Material Exports
            'view_material_exports',

            //Inventory Reports
            'view_inventory_reports',
            'confirm_inventory_report',
            'reject_inventory_report',

            //Proposes
            'view_proposes',
            'create_proposes',
            'update_proposes',
            'delete_proposes',
            'send_propose',
            'accept_propose',
            'reject_propose',

        ];

        $employeeRoles = [

            //Warehouses
            'view_warehouses',

            //Products
            'view_products',

            //Materials
            'view_materials',

            //Shelves
            'view_shelves',

            //Product Receipts
            'view_product_receipts',
            'create_product_receipts',

            //Product Exports
            'view_product_exports',
            'create_product_exports',

            //Material Receipts
            'view_material_receipts',
            'create_material_receipts',

            //Material Exports
            'view_material_exports',
            'create_material_exports',

            //Inventory Reports
            'view_inventory_reports',
            'create_inventory_reports',
            'update_inventory_reports',
            'delete_inventory_reports',
            'send_inventory_report',

            //Proposes
            'view_proposes',
            'create_proposes',
            'update_proposes',
            'delete_proposes',
            'send_propose',

            //Orders
            'view_orders',
            'update_orders',




        ];

        $directorRoles = [

            //Warehouses
            'view_warehouses',

            //Products
            'view_products',
            'update_products',

            //Materials
            'view_materials',
            'update_materials',

            //SHevles

            'view_shelves',
            'update_shelves',

            //Product Receipts
            'view_product_receipts',

            //Product Exports
            'view_product_exports',

            //Material Receipts
            'view_material_receipts',

            //Material Exports
            'view_material_exports',

            //Inventory Reports
            'view_inventory_reports',
            'confirm_inventory_report',
            'reject_inventory_report',

            //Proposes
            'view_proposes',
            'accept_propose',
            'reject_propose',
        ];



        foreach ($roles as $roleName) {
            // Tạo role
            $role = Role::factory()->create([
                'name' => $roleName,
            ]);

            // Gắn permission cho từng role
            $permissions = Permission::all(); // Lấy tất cả permissions
            if ($roleName == 'Admin') {
                // Gắn tất cả các permission cho Admin
                $role->permissions()->sync($permissions->pluck('id'));
            } elseif ($roleName == 'Quản lý kho') {

                // permission cho Manager
                $role->permissions()->sync($permissions->whereIn(
                    'name',
                    // 'view_warehouse',
                    // 'view_products',
                    // 'view_materials',
                    // 'view_product_receipts',
                    // 'view_product_exports',
                    // 'view_propose',
                    // 'create_propose',
                    // 'accept_propose'
                    $managerRoles

                )->pluck('id'));
            } elseif ($roleName == 'Nhân viên kho') {

                //  permission cho Employee
                $role->permissions()->sync($permissions->whereIn(
                    'name',

                    // 'view_warehouse',
                    // 'view_products',
                    // 'view_materials',
                    // 'view_product_receipts',
                    // 'view_product_exports',
                    // 'view_proposes',
                    // 'create_proposes',

                    // //
                    // 'create_product_receipts',
                    // 'create_product_exports',
                    // 'create_material_receipts',
                    // 'create_material_exports',

                    $employeeRoles,

                )->pluck('id'));
            } elseif ($roleName == 'Giám đốc') {

                // permission cho Employee
                $role->permissions()->sync($permissions->whereIn(
                    'name',

                    // 'view_warehouse',
                    // 'view_products',
                    // 'view_materials',
                    // 'view_product_receipts',
                    // 'view_product_exports',
                    // 'view_propose',
                    $directorRoles,

                )->pluck('id'));
            }
        }
    }
}
