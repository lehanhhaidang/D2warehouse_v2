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

    public function updateInventoryReport(int $id, array $data)
    {
        $inventoryReport = InventoryReport::find($id);

        if ($inventoryReport) {
            $inventoryReport->update($data);
            return $inventoryReport;
        }

        return null;
    }

    // Cập nhật propose detail theo id
    public function updateInventoryReportDetail(int $id, array $data)
    {
        $inventoryReportDetail = InventoryReportDetail::find($id);

        if ($inventoryReportDetail) {
            $inventoryReportDetail->update($data);
            return $inventoryReportDetail;
        }

        return null;
    }

    public function deleteInventoryReport($id)
    {
        return InventoryReport::where('id', $id)->delete();
    }

    public function deleteInventoryReportDetailsByInventoryReportId(int $inventoryReportId)
    {
        return InventoryReportDetail::where('inventory_report_id', $inventoryReportId)->delete();
    }
}
