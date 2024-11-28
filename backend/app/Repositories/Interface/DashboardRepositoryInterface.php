<?php

namespace App\Repositories\Interface;

interface DashboardRepositoryInterface
{
    public function userCount();
    public function getWarehouseCount();
    public function getShelfCount();
    public function getShelfCountByWarehouse($warehouseId);
    public function getProductCount();
    public function getMaterialCount();
    public function getPropsoeCount();
    public function getImportProductProposeCount();
    public function getExportProductProposeCount();
    public function getImportMaterialProposeCount();
    public function getExportMaterialProposeCount();
    public function getProductReceiptCount();
    public function getProductExportCount();
    public function getMaterialReceiptCount();
    public function getMaterialExportCount();
    public function getInventoryReportCount();
    public function getProductCategoryCount();

    public function getMaterialCategoryCount();

    public function totalReceiptExportNote();

    public function getAllReceiptExportWithDetails();
}
