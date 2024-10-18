<?php

namespace App\Repositories;

use App\Repositories\Interface\MaterialExportRepositoryInterface;

use App\Models\Material;
use App\Models\MaterialExport;
use App\Models\MaterialExportDetail;
use App\Models\ShelfDetail;

class MaterialExportRepository implements MaterialExportRepositoryInterface
{

    public function getAllmaterialExportsWithDetails()
    {
        return materialExport::with(['details.material', 'details.shelf', 'details.material.category', 'warehouse', 'user'])
            ->get();
    }

    public function getmaterialExportWithDetails($id)
    {
        return materialExport::with(['details.material', 'details.shelf', 'details.material.category', 'warehouse', 'user'])
            ->where('id', $id)
            ->first();
    }

    public function creatematerialExport(array $data)
    {
        return materialExport::create($data);
    }

    public function creatematerialExportDetail(array $detail)
    {
        return materialExportDetail::create($detail);
    }

    public function updatematerialQuantity($materialId, $quantity)
    {
        $material = material::find($materialId);
        if ($material) {
            $material->quantity -= $quantity;
            $material->save();
        }
    }

    public function findShelfDetail($shelf_id, $material_id)
    {
        return ShelfDetail::where('shelf_id', $shelf_id)
            ->where('material_id', $material_id)
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
