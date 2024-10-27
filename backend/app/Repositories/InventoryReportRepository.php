<?php

namespace App\Repositories;

use App\Models\InventoryReport;

class InventoryReportRepository
{
    public function getAllInventoryReportWithDetails()
    {
        return InventoryReport::with('inventoryReportDetails')->get();
    }
}
