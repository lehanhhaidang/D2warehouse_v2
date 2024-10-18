<?php

namespace App\Repositories\Interface;


interface MaterialExportRepositoryInterface
{
    public function getAllMaterialExportsWithDetails();

    public function getMaterialExportWithDetails($id);

    public function createMaterialExport(array $data);

    public function createMaterialExportDetail(array $detail);

    public function updateMaterialQuantity($materialId, $quantity);

    public function findShelfDetail($shelf_id, $material_id);

    public function updateShelfDetailQuantity($shelf_detail_id, $newQuantity);

    public function createShelfDetail(array $detail);
}
