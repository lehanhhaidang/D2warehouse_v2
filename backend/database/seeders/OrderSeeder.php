<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = [
            [
                'name' => 'Đơn hàng 1',
                'customer_name' => 'Châu Hoàng Khải Long',
                'customer_email' => 'longchau@yahoo.com',
                'customer_phone' => '0987654321',
                'customer_address' => 'Hà Nội',
                'order_date' => '2024-08-12 17:45:14',
                'delivery_date' => '2024-08-12 17:45:14',
                'status' => 0,
                'note' => 'Giao hàng sớm',
                'total_price' => '1550000',

            ],
            [
                'name' => 'Đơn hàng 2',
                'customer_name' => 'Vũ Thị Hồng Nhung',
                'customer_email' => 'nhungthivu@gmail.com',
                'customer_phone' => '0987654322',
                'customer_address' => 'Hồ chí Minh',
                'order_date' => '2024-08-12 17:45:14',
                'delivery_date' => '2024-08-12 17:45:14',
                'status' => 0,
                'note' => 'Giao hàng sớm',
                'total_price' => '1550000',

            ],
            [
                'name' => 'Đơn hàng 3',
                'customer_name' => 'Hoàng Đức Phát',
                'customer_email' => 'phathoang@yahoo.com',
                'customer_phone' => '0987654323',
                'customer_address' => 'Hải Phòng',
                'order_date' => '2024-08-12 17:45:14',
                'delivery_date' => '2024-08-12 17:45:14',
                'status' => 0,
                'note' => 'Giao hàng sớm',
                'total_price' => '1550000',

            ],
        ];

        DB::table('orders')->insert($order);

        $order_details = [
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 1000,
                'price' => '5000',
                'total_price' => '500000',
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 100,
                'price' => '7500',
                'total_price' => '750000',
            ],
            [
                'order_id' => 1,
                'product_id' => 3,
                'quantity' => 300,
                'price' => '1000',
                'total_price' => '300000',
            ],
            [
                'order_id' => 2,
                'product_id' => 1,
                'quantity' => 100,
                'price' => '5000',
                'total_price' => '500000',
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'quantity' => 100,
                'price' => '7500',
                'total_price' => '750000',
            ],
            [
                'order_id' => 2,
                'product_id' => 3,
                'quantity' => 300,
                'price' => '1000',
                'total_price' => '300000',
            ],
            [
                'order_id' => 3,
                'product_id' => 1,
                'quantity' => 100,
                'price' => '5000',
                'total_price' => '500000',
            ],
            [
                'order_id' => 3,
                'product_id' => 17,
                'quantity' => 1000,
                'price' => '7500',
                'total_price' => '750000',
            ],
            [
                'order_id' => 3,
                'product_id' => 3,
                'quantity' => 300,
                'price' => '1000',
                'total_price' => '300000',
            ],
        ];

        DB::table('order_details')->insert($order_details);
    }
}
