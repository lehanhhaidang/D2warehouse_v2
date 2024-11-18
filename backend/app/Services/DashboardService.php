<?php

namespace App\Services;

use App\Models\Shelf;
use App\Repositories\Interface\DashboardRepositoryInterface;
use Exception;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }


    public function getDashboardData()
    {
        try {
            return [
                'userCount' => $this->dashboardRepository->userCount(),
                'warehouseCount' => $this->dashboardRepository->getWarehouseCount(),
                'shelfCount' => $this->dashboardRepository->getShelfCount(),
                'shelfCountsByWarehouseId' => $this->getShelfCountByWarehouses(),
                'productCount' => $this->dashboardRepository->getProductCount(),
                'materialCount' => $this->dashboardRepository->getMaterialCount(),
                'proposeCount' => $this->dashboardRepository->getPropsoeCount(),
                'importProductProposeCount' => $this->dashboardRepository->getImportProductProposeCount(),
                'exportProductProposeCount' => $this->dashboardRepository->getExportProductProposeCount(),
                'importMaterialProposeCount' => $this->dashboardRepository->getImportMaterialProposeCount(),
                'exportMaterialProposeCount' => $this->dashboardRepository->getExportMaterialProposeCount(),
                'productReceiptCount' => $this->dashboardRepository->getProductReceiptCount(),
                'productExportCount' => $this->dashboardRepository->getProductExportCount(),
                'materialReceiptCount' => $this->dashboardRepository->getMaterialReceiptCount(),
                'materialExportCount' => $this->dashboardRepository->getMaterialExportCount(),
                'inventoryReportCount' => $this->dashboardRepository->getInventoryReportCount(),
            ];
        } catch (Exception $e) {
            throw new Exception('Không thể lấy dữ liệu, vui lòng thử lại sau.');
        }
    }

    public function getShelfCountByWarehouses()
    {
        // Lấy tất cả warehouse_id từ bảng shelves
        $warehouseIds = Shelf::pluck('warehouse_id')->unique();

        // Tạo một mảng để lưu kết quả
        $shelfCounts = [];

        foreach ($warehouseIds as $warehouseId) {
            // Lấy số lượng kệ cho mỗi warehouse_id
            $shelfCounts[$warehouseId] = $this->dashboardRepository->getShelfCountByWarehouse($warehouseId);
        }

        return $shelfCounts;
    }
}
