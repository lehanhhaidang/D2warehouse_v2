<?php

namespace App\Repositories\Interface;

interface MaterialReceiptRepositoryInterface
{
    public function getAllMaterialReceiptsWithDetails();

    public function getMaterialReceiptsWithDetails($id);

    public function createMaterialReceipt(array $data);

    public function createMaterialReceiptDetail(array $detail);

    public function createShelfDetail(array $detail);

    public function updateMaterialQuantity($materialId, $quantity);

    public function findShelfDetail($shelf_id, $material_id);

    public function updateShelfDetailQuantity($shelfDetailId, $quantity);

    public function getShelfDetails($shelf_id);
}
