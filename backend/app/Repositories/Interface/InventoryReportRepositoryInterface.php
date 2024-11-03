<?php

namespace App\Repositories\Interface;

interface InventoryReportRepositoryInterface
{
    public function getAllInventoryReportWithDetails();

    public function getInventoryReportWithDetails($id);


    public function createInventoryReport(array $data);

    public function createInventoryReportDetail(array $detail);
}
