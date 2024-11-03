<?php

namespace App\Repositories;

use App\Models\InventoryReport;
use App\Models\InventoryReportDetail;

class InventoryReportRepository
{
    public function getAllInventoryReportWithDetails()
    {
        return InventoryReport::with('inventoryReportDetails.product', 'inventoryReportDetails.material', 'warehouse', 'user', 'inventoryReportDetails.shelf')->get();
    }

    public function getInventoryReportWithDetails($id)
    {
        return InventoryReport::with('inventoryReportDetails.product', 'inventoryReportDetails.material', 'warehouse', 'user', 'inventoryReportDetails.shelf')->where('id', $id)->first();
    }

    public function createInventoryReport(array $data)
    {
        return InventoryReport::create($data);
    }

    public function createInventoryReportDetail(array $detail)
    {
        return InventoryReportDetail::create($detail);
    }
}
