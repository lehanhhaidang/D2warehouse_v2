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
                // Gắn một số permission cho Manager
                $role->permissions()->sync($permissions->whereIn('name', [
                    'Read User',
                    'Update User',
                ])->pluck('id'));
            } elseif ($roleName == 'Nhân viên kho') {
                // Gắn chỉ một vài permission cho Employee
                $role->permissions()->sync($permissions->whereIn('name', [
                    'Read User',
                ])->pluck('id'));
            }
        }
    }
}
