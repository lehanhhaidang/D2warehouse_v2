<?php

namespace App\Repositories\Interface;

interface InventoryReportRepositoryInterface
{
    public function getAllInventoryReportWithDetails();

    public function getInventoryReportWithDetails($id);


    public function createInventoryReport(array $data);

    public function createInventoryReportDetail(array $detail);


    public function updateInventoryReport(array $data, $id);

    public function updateInventoryReportDetail(array $detail, $id);

    public function deleteInventoryReport($id);
}
