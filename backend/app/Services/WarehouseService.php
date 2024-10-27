<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\Interface\WarehouseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseService
{
    protected $warehouseRepository;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function getAllWarehouses()
    {

        $warehouses = $this->warehouseRepository->all();

        if ($warehouses->isEmpty()) {
            throw new \Exception('Hiện tại không có kho nào.', 404);
        }

        return $warehouses;
    }

    public function getProductWarehouses()
    {
        $warehouses = $this->warehouseRepository->allProductWarehouses();

        if ($warehouses->isEmpty()) {
            throw new \Exception('Hiện tại không có kho thành phẩm nào.', 404);
        }

        return $warehouses;
    }

    public function getMaterialWarehouses()
    {
        $warehouses = $this->warehouseRepository->allMaterialWarehouses();

        if ($warehouses->isEmpty()) {
            throw new \Exception('Hiện tại không có kho nguyên vật liệu nào.', 404);
        }

        return $warehouses;
    }


    public function getAWarehouse($id)
    {
        $warehouse = $this->warehouseRepository->find($id);

        if (!$warehouse) {
            throw new \Exception('Không tìm thấy kho.', 404);
        }
        return $warehouse;
    }

    public function storeWarehouse($request)
    {
        try {
            $data = [
                'name' => $request->name,
                'location' => $request->location,
                'acreage' => $request->acreage,
                'number_of_shelves' => $request->number_of_shelves,
                'category_id' => $request->category_id,
            ];
            return $this->warehouseRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function updateWarehouse($request, $id)
    {
        try {
            // Tìm kho dựa trên id
            $warehouse = $this->warehouseRepository->find($id);
            if (!$warehouse) {
                throw new ModelNotFoundException('Không tìm thấy kho.', 404);
            }

            // Cập nhật dữ liệu
            $data = [
                'name' => $request->name,
                'location' => $request->location,
                'acreage' => $request->acreage,
                'number_of_shelves' => $request->number_of_shelves,
                'category_id' => $request->category_id,
            ];

            return $this->warehouseRepository->update($id, $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteWarehouse($id)
    {
        $warehouse = $this->warehouseRepository->find($id);
        if (!$warehouse) {
            throw new ModelNotFoundException('Không tìm thấy kho.', 404);
        }
        return $this->warehouseRepository->delete($id);
    }


    public function showProductOrMaterialByWarehouse($id)
    {
        $warehouse = Warehouse::find($id);


        if (!$warehouse) {
            throw new \Exception('Warehouse not found', 404);
        }

        if ($warehouse->category_id == 1) {
            $materials = Material::all(['id', 'name', 'unit', 'category_id']);
            return $materials;
        } elseif ($warehouse->category_id == 2) {
            $products = Product::all(['id', 'name', 'unit', 'category_id']);
            return $products;
        } else {
            throw new \Exception('Invalid warehouse category', 400);
        }
    }
}
