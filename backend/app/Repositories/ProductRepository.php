<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\Interface\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Display a listing of the resource.
     */

    public function all()
    {
        return Product::select(
            'products.id',
            'products.name',
            'categories.id as category_id',
            'categories.name as category_name',
            'colors.id as color_id',
            'colors.name as color_name',
            'products.unit',
            'products.quantity',
            'products.product_img',
            'products.status',
            'products.created_at',
            'products.updated_at',

        )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('colors', 'products.color_id', '=', 'colors.id')
            ->get();
    }

    public function find($id)
    {
        return Product::select(
            'products.id',
            'products.name',
            'categories.id as category_id',
            'categories.name as category_name',
            'colors.id as color_id',
            'colors.name as color_name',
            'products.unit',
            'products.quantity',
            'products.product_img',
            'products.status',
            'products.created_at',
            'products.updated_at',

        )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('colors', 'products.color_id', '=', 'colors.id')
            ->where('products.id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }
    public function update($id, array $data)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }
    public function delete($id)
    {
        Product::destroy($id);
    }



    private function findShelfForProduct($id)
    {
        return Product::select('shelf_id')
            ->where('id', $id)
            ->first();
    }
}
