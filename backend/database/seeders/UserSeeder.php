<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::table('users')->insert([
            [
                'name' => 'Quản trị viên',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0123456789',
                'role_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Lê Hạnh Hải Đăng',
                'email' => 'dang@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0833109609',
                'role_id' => 2,
                'created_at' => now(),
            ],
            [
                'name' => 'Bùi Thục Đoan',
                'email' => 'doan@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0123456788',
                'role_id' => 3,
                'created_at' => now(),
            ],
            [
                'name' => 'Nguyễn Huỳnh Hương',
                'email' => 'huong@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0123456787',
                'role_id' => 4,
                'created_at' => now(),
            ],
            [
                'name' => 'Nhân viên kho 01',
                'email' => 'nvk@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0123456782',
                'role_id' => 4,
                'created_at' => now(),
            ],
            [
                'name' => 'Nhân viên kho 02',
                'email' => 'nvk1@gmail.com',
                'password' => bcrypt('123456'),
                'phone' => '0123456781',
                'role_id' => 4,
                'created_at' => now(),
            ],

        ]);
    }
}
