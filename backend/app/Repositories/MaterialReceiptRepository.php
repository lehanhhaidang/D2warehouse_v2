<?php

namespace App\Repositories;

use App\Repositories\Interface\MaterialReceiptRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Material;
use App\Models\MaterialReceipt;
use App\Models\MaterialReceiptDetail;
use App\Models\ShelfDetail;

class MaterialReceiptRepository implements MaterialReceiptRepositoryInterface
{


    public function getAllMaterialReceiptsWithDetails()
    {
        return MaterialReceipt::with(['details.Material', 'details.shelf', 'details.material.category', 'warehouse', 'user'])
            ->get();
    }


    public function getMaterialReceiptWithDetails($id)
    {
        return MaterialReceipt::with(['details.material', 'details.shelf', 'details.material.category', 'warehouse', 'user'])
            ->where('id', $id)
            ->first();
    }


    public function createMaterialReceipt(array $data)
    {
        return MaterialReceipt::create($data);
    }

    public function createMaterialReceiptDetail(array $detail)
    {
        return MaterialReceiptDetail::create($detail);
    }

    public function createShelfDetail(array $detail)
    {
        return ShelfDetail::create($detail);
    }

    public function updateMaterialQuantity($materialId, $quantity)
    {
        $material = Material::find($materialId);
        if ($material) {
            $material->quantity += $quantity;
            $material->save();
        }
    }

    public function findShelfDetail($shelf_id, $material_id)
    {
        return ShelfDetail::where('shelf_id', $shelf_id)
            ->where('Material_id', $material_id)
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
