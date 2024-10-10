<?php

namespace App\Repositories\Interface;

interface ProductReceiptRepositoryInterface
{
    /**
     * Lấy tất cả các phiếu nhập kho cùng với chi tiết.
     *
     * @return \Illuminate\Support\Collection
     */




    public function getAllProductReceiptsWithDetails();

    public function getProductReceiptWithDetails($id);

    public function createProductReceipt(array $data);

    public function createProductReceiptDetail(array $detail);

    public function createShelfDetail(array $detail);

    public function updateProductQuantity($productId, $quantity);

    public function findShelfDetail($shelf_id, $product_id);

    public function getShelfDetails($shelfId);

    public function updateShelfDetailQuantity($shelf_detail_id, $newQuantity);
}
