<?php

namespace App\Repositories\Interface;


interface ProductExportRepositoryInterface
{
    public function getAllProductExportsWithDetails();

    public function getProductExportWithDetails($id);

    public function createProductExport(array $data);

    public function createProductExportDetail(array $detail);

    public function updateProductQuantity($productId, $quantity);

    public function findShelfDetail($shelf_id, $product_id);

    public function updateShelfDetailQuantity($shelf_detail_id, $newQuantity);

    public function createShelfDetail(array $detail);
}
