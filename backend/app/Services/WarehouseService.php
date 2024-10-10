<?php

namespace App\Services;

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
            throw new \Exception('Hiện tại không có kho nào.');
        }

        return $warehouses;
    }
    public function getAWarehouse($id)
    {
        $warehouse = $this->warehouseRepository->find($id);

        if (!$warehouse) {
            throw new \Exception('Không tìm thấy kho.');
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
                throw new ModelNotFoundException('Không tìm thấy kho.');
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
            throw new ModelNotFoundException('Không tìm thấy kho.');
        }
        return $this->warehouseRepository->delete($id);
    }
}
