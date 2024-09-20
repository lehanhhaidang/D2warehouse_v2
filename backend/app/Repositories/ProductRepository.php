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
        return Product::all();
    }
    public function find($id)
    {
        return Product::find($id);
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
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return true;
        }
        return false;
    }
}
