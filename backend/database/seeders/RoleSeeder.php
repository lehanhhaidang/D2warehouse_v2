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
                $role->permissions()->sync($permissions->whereIn('name', [
                    'view_warehouse',
                    'view_products',
                    'view_materials',
                    'view_product_receipts',
                    'view_product_exports',
                    'view_propose',
                    'create_propose',

                ])->pluck('id'));
            } elseif ($roleName == 'Nhân viên kho') {

                //  permission cho Employee
                $role->permissions()->sync($permissions->whereIn('name', [

                    'view_warehouse',
                    'view_products',
                    'view_materials',
                    'view_product_receipts',
                    'view_product_exports',
                    'view_propose',
                    'create_propose',

                ])->pluck('id'));
            } elseif ($roleName == 'Giám đốc') {

                // permission cho Employee
                $role->permissions()->sync($permissions->whereIn('name', [

                    'view_warehouse',
                    'view_products',
                    'view_materials',
                    'view_product_receipts',
                    'view_product_exports',
                    'view_propose',

                ])->pluck('id'));
            }
        }
    }
}
