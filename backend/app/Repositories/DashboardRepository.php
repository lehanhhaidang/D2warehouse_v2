<?php

namespace App\Repositories;

use App\Models\InventoryReport;
use App\Models\Material;
use App\Models\MaterialExport;
use App\Models\MaterialReceipt;
use App\Models\Product;
use App\Models\ProductExport;
use App\Models\ProductReceipt;
use App\Models\Propose;
use App\Models\Shelf;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\Interface\DashboardRepositoryInterface;
use App\Services\MaterialExportService;
use App\Services\MaterialReceiptService;
use App\Services\ProductExportService;
use App\Services\ProductReceiptService;

class DashboardRepository implements DashboardRepositoryInterface
{

    protected $productReceiptService, $productExportService, $materialReceiptService, $materialExportService;

    public function __construct(
        ProductReceiptService $productReceiptService,
        ProductExportService $productExportService,
        MaterialReceiptService $materialReceiptService,
        MaterialExportService $materialExportService
    ) {
        $this->productReceiptService = $productReceiptService;
        $this->productExportService = $productExportService;
        $this->materialReceiptService = $materialReceiptService;
        $this->materialExportService = $materialExportService;
    }
    public function userCount()
    {
        return User::count();
    }
    public function getWarehouseCount()
    {
        return Warehouse::count();
    }

    public function getShelfCount()
    {
        return Shelf::count();
    }

    public function getShelfCountByWarehouse($warehouseId)
    {
        return Shelf::where('warehouse_id', $warehouseId)->count();
    }
    public function getProductCount()
    {
        return Product::count();
    }

    public function getMaterialCount()
    {
        return Material::count();
    }

    public function getPropsoeCount()
    {
        return Propose::count();
    }

    public function getImportProductProposeCount()
    {
        if (Propose::where('type', 'DXNTP')) {
            return Propose::where('type', 'DXNTP')->count();
        }
        return null;
    }

    public function getExportProductProposeCount()
    {
        if (Propose::where('type', 'DXXTP')) {
            return Propose::where('type', 'DXXTP')->count();
        }
        return null;
    }

    public function getImportMaterialProposeCount()
    {
        if (Propose::where('type', 'DXNNVL')) {
            return Propose::where('type', 'DXNNVL')->count();
        }
        return null;
    }

    public function getExportMaterialProposeCount()
    {
        if (Propose::where('type', 'DXXNVL')) {
            return Propose::where('type', 'DXXNVL')->count();
        }
        return null;
    }

    public function getProductReceiptCount()
    {
        return ProductReceipt::count();
    }

    public function getProductExportCount()
    {
        return ProductExport::count();
    }

    public function getMaterialReceiptCount()
    {
        return MaterialReceipt::count();
    }

    public function getMaterialExportCount()
    {
        return MaterialExport::count();
    }

    public function getInventoryReportCount()
    {
        return InventoryReport::count();
    }

    public function getProductCategoryCount()
    {
        // Tính tổng quantity của các sản phẩm theo danh mục
        $categoryQuantities = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category_name, SUM(products.quantity) as total_quantity')
            ->groupBy('categories.name')
            ->get();

        // Chuyển kết quả thành mảng
        return $categoryQuantities->toArray();
    }

    public function getMaterialCategoryCount()
    {
        $categoryQuantities = Material::query()
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category_name, SUM(materials.quantity) as total_quantity')
            ->groupBy('categories.name')
            ->get();

        // Chuyển kết quả thành mảng
        return $categoryQuantities->toArray();
    }

    public function totalReceiptExportNote()
    {
        return ProductReceipt::count() + ProductExport::count() + MaterialReceipt::count() + MaterialExport::count();
    }

    public function getAllReceiptExportWithDetails()
    {
        $productReceipts = $this->productReceiptService->getAllProductReceiptsWithDetails();
        $productExports = $this->productExportService->getAllProductExportsWithDetails();
        $materialReceipts = $this->materialReceiptService->getAllMaterialReceiptsWithDetails();
        $materialExports = $this->materialExportService->getAllMaterialExportsWithDetails();

        // Gộp tất cả dữ liệu lại thành một mảng
        $allReceiptsAndExports = array_merge(
            $productReceipts->toArray(),
            $productExports->toArray(),
            $materialReceipts->toArray(),
            $materialExports->toArray()
        );

        return $allReceiptsAndExports;
    }
}
