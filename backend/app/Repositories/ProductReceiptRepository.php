<?php

namespace App\Repositories;

use App\Repositories\Interface\ProductReceiptRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\ProductReceiptDetail;
use App\Models\ShelfDetail;

class ProductReceiptRepository implements ProductReceiptRepositoryInterface
{


    public function getAllProductReceiptsWithDetails()
    {
        return ProductReceipt::with(['details.product', 'details.shelf', 'details.product.category', 'warehouse', 'user'])
            ->get();
    }


    public function getProductReceiptWithDetails($id)
    {
        return ProductReceipt::with(['details.product', 'details.shelf', 'details.product.category', 'warehouse', 'user'])
            ->where('id', $id)
            ->first();
    }


    public function createProductReceipt(array $data)
    {
        return ProductReceipt::create($data);
    }

    public function createProductReceiptDetail(array $detail)
    {
        return ProductReceiptDetail::create($detail);
    }

    public function createShelfDetail(array $detail)
    {
        return ShelfDetail::create($detail);
    }

    public function updateProductQuantity($productId, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->quantity += $quantity;
            $product->save();
        }
    }

    public function findShelfDetail($shelf_id, $product_id)
    {
        return ShelfDetail::where('shelf_id', $shelf_id)
            ->where('product_id', $product_id)
            ->first();
    }

    public function getShelfDetails($shelfId)
    {
        return ShelfDetail::where('shelf_id', $shelfId)->get();
    }

    public function updateShelfDetailQuantity($shelf_detail_id, $newQuantity)
    {
        return ShelfDetail::where('id', $shelf_detail_id)
            ->update(['quantity' => $newQuantity]);
    }
}
