<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReceipt;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Chai 1 lít TRONG HDPE( TN )',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798888/storage/pphpxs2ylfjzld4arime.jpg',
                'status' => 1,
                'category_id' => 5,
                'color_id' => 4,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai 1 lít miệng rộng phi 64',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798698/storage/sd1msuba1rrntcsd2iuq.jpg',
                'status' => 1,
                'category_id' => 5,
                'color_id' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai 500ml 3 vạch Vàng',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798836/storage/wtlvcgfcbruld6cxlsre.jpg',
                'status' => 1,
                'category_id' => 5,
                'color_id' => 3,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai 500ml HDPE 2 đầu',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798726/storage/urzqc3wm6bkibrne9qyu.jpg',
                'status' => 1,
                'category_id' => 5,
                'color_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai 500ml HDPE xanh biển',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798770/storage/sfivbxquep54wlkfndoe.jpg',
                'status' => 1,
                'category_id' => 5,
                'color_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],

            //Can nhựa HDPE
            [
                'name' => 'Can 20 lít Tròn trắng',
                'unit' => 'Can',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798897/storage/vyl6qckr0ei8kgfcxdni.jpg',
                'status' => 1,
                'category_id' => 11,
                'color_id' => 4,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Can 20 lít Vuông xanh dương',
                'unit' => 'Can',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798782/storage/ny46r9tvhd2rsfwzbwzm.jpg',
                'status' => 1,
                'category_id' => 11,
                'color_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Can 2 lít hdpe Vàng',
                'unit' => 'Can',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798847/storage/dkierusrpbp1nraqcmmr.jpg',
                'status' => 1,
                'category_id' => 11,
                'color_id' => 3,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Can 5 lít ( 2q ) Ốm',
                'unit' => 'Can',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798937/storage/m9y7farwlnexss8y3fga.jpg',
                'status' => 1,
                'category_id' => 11,
                'color_id' => 5,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Can 5 lít 3 vạch hdpe',
                'unit' => 'Can',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798712/storage/thoinnv0mhh3wfsbn8hi.jpg',
                'status' => 1,
                'category_id' => 11,
                'color_id' => 1,
                'created_at' => now(),
                'updated_at' => null,
            ],

            //Hủ nhựa HDPE

            [
                'name' => 'Hủ 2kg Đen nhiều vạch',
                'unit' => 'Hủ',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798909/storage/qhfz5rarnrb7tvfvhcdn.jpg',
                'status' => 1,
                'category_id' => 7,
                'color_id' => 4,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ 100g Tròn hdpe',
                'unit' => 'Hủ',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798798/storage/mhiiogkldn3rtp3ztwh3.jpg',
                'status' => 1,
                'category_id' => 7,
                'color_id' => 2,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ 1kg Vuông Cạnh hdpe',
                'unit' => 'Hủ',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798863/storage/jwgj2hgvxq2fkvepkgua.jpg',
                'status' => 1,
                'category_id' => 7,
                'color_id' => 3,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Hủ 500g Vuông Trắng ( ĐT )',
                'unit' => 'Hủ',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798946/storage/l4jvgpqfyvwhjcjlmfwp.jpg',
                'status' => 1,
                'category_id' => 7,
                'color_id' => 5,
                'created_at' => now(),
                'updated_at' => null,
            ],

            //Chai PET

            [
                'name' => 'Chai pet 500ml tròn',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798924/storage/gfidfdya1wjgalekcou5.jpg',
                'status' => 1,
                'category_id' => 8,
                'color_id' => 4,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'Chai Pet 1 lít vàng - nắp quặn',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798874/storage/qsnsspjm5lwg0n4gbdig.jpg',
                'status' => 1,
                'category_id' => 8,
                'color_id' => 3,
                'created_at' => now(),
                'updated_at' => null,
            ],
            [
                'name' => 'CHAI PET 1L - NẮP QUẶN',
                'unit' => 'Chai',
                'quantity' => 0,
                'product_img' => 'https://res.cloudinary.com/doxrobe1v/image/upload/v1732798988/storage/x6ptc9xm8rstch4rspqz.jpg',
                'status' => 1,
                'category_id' => 8,
                'color_id' => 5,
                'created_at' => now(),
                'updated_at' => null,
            ],


        ]);
    }
}
