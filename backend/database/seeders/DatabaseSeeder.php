<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        User::factory()->create([
            'name' => 'Dang',
            'email' => 'dang@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '0123456789',
        ]);

        $roles = [
            'Admin',
            'Manager',
            'Director',
            'Employee',
        ];

        foreach ($roles as $role) {
            Role::factory()->create([
                'name' => $role,
            ]);
        }

        $permissions = [
            'Create User',
            'Read User',
            'Update User',
            'Delete User',
        ];

        foreach ($permissions as $permission) {
            Permission::factory()->create([
                'name' => $permission,
            ]);
        }
    }
}
