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
                'status' => 1,
                'note' => 'Giao hàng sớm',
                'total_price' => '6550000',

            ],
        ];

        DB::table('orders')->insert($order);

        $order_details = [
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 100,
                'price' => '500',
                'total_price' => '5000000',
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 100,
                'price' => '750',
                'total_price' => '750000',
            ],
            [
                'order_id' => 1,
                'product_id' => 3,
                'quantity' => 300,
                'price' => '100',
                'total_price' => '800000',
            ],
        ];

        DB::table('order_details')->insert($order_details);
    }
}
