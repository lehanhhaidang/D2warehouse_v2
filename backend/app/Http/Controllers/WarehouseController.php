<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }
    public function index()
    {
        try {
            $warehouses = $this->warehouseService->getAllWarehouses();
            return response()->json([$warehouses, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kho',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request)
    {
        try {
            $warehouse = $this->warehouseService->storeWarehouse($request);
            return response()->json([$warehouse, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi tạo kho',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $warehouse = $this->warehouseService->getAWarehouse($id);
            return response()->json([$warehouse, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi tìm kiếm kho',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWarehouseRequest $request, $id)
    {
        try {
            $warehouse = $this->warehouseService->updateWarehouse($request, $id);
            return response()->json([
                'message' => 'Cập nhật kho thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cập nhật kho thất bại',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $warehouse = $this->warehouseService->deleteWarehouse($id);
            return response()->json([
                'message' => 'Xóa kho thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xóa kho thất bại',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}
