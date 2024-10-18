<?php

namespace App\Repositories;

use App\Repositories\Interface\ProductExportRepositoryInterface;

use App\Models\Product;
use App\Models\ProductExport;
use App\Models\ProductExportDetail;
use App\Models\ShelfDetail;

class ProductExportRepository implements ProductExportRepositoryInterface
{

    public function getAllProductExportsWithDetails()
    {
        return ProductExport::with(['details.product', 'details.shelf', 'details.product.category', 'warehouse', 'user'])
            ->get();
    }

    public function getProductExportWithDetails($id)
    {
        return ProductExport::with(['details.product', 'details.shelf', 'details.product.category', 'warehouse', 'user'])
            ->where('id', $id)
            ->first();
    }

    public function createProductExport(array $data)
    {
        return ProductExport::create($data);
    }

    public function createProductExportDetail(array $detail)
    {
        return ProductExportDetail::create($detail);
    }

    public function updateProductQuantity($productId, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->quantity -= $quantity;
            $product->save();
        }
    }

    public function findShelfDetail($shelf_id, $product_id)
    {
        return ShelfDetail::where('shelf_id', $shelf_id)
            ->where('product_id', $product_id)
            ->first();
    }

    public function updateShelfDetailQuantity($shelf_detail_id, $newQuantity)
    {
        return ShelfDetail::where('id', $shelf_detail_id)
            ->update(['quantity' => $newQuantity]);
    }

    public function createShelfDetail(array $detail)
    {
        return ShelfDetail::create($detail);
    }
}
