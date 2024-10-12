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

    /**
     * @OA\Get(
     *     path="/api/v1/warehouses",
     *     summary="Lấy danh sách kho",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách kho thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="location", type="string", example="Bình Chánh"),
     *                 @OA\Property(property="acreage", type="string", example="1000"),
     *                 @OA\Property(property="number_of_shelves", type="string", example="100"),
     *                 @OA\Property(property="category_name", type="string", example="Material"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:57:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có kho nào",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Không có kho nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $warehouses = $this->warehouseService->getAllWarehouses();
            return response()->json([$warehouses, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/warehouses/product",
     *     summary="Lấy danh sách kho thành phẩm",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách kho thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Kho thành phẩm 1"),
     *                 @OA\Property(property="location", type="string", example="Bình Chánh"),
     *                 @OA\Property(property="acreage", type="string", example="1000"),
     *                 @OA\Property(property="number_of_shelves", type="string", example="100"),
     *                 @OA\Property(property="category_name", type="string", example="Product"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:57:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có kho nào",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có kho thành phẩm nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function productWarehouses()
    {
        try {
            $warehouses = $this->warehouseService->getProductWarehouses();
            return response()->json([$warehouses, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kho thành phẩm',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/warehouses/material",
     *     summary="Lấy danh sách kho nguyên vật liệu",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách kho thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="location", type="string", example="Bình Chánh"),
     *                 @OA\Property(property="acreage", type="string", example="1000"),
     *                 @OA\Property(property="number_of_shelves", type="string", example="100"),
     *                 @OA\Property(property="category_name", type="string", example="Material"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:57:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có kho nào",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có kho nguyên vật liệu nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function materialWarehouses()
    {
        try {
            $warehouses = $this->warehouseService->getMaterialWarehouses();
            return response()->json([$warehouses, 200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kho nguyên vật liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/warehouse/add",
     *     summary="Thêm kho mới",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "location", "acreage", "number_of_shelves", "category_id"},
     *             @OA\Property(property="name", type="string", example="Kho 1"),
     *             @OA\Property(property="location", type="string", example="Địa điểm 1"),
     *             @OA\Property(property="acreage", type="number", example=1000),
     *             @OA\Property(property="number_of_shelves", type="integer", example=20),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tạo kho thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="Kho 1"),
     *                 @OA\Property(property="location", type="string", example="Địa điểm 1"),
     *                 @OA\Property(property="acreage", type="number", example=1000),
     *                 @OA\Property(property="number_of_shelves", type="integer", example=20),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="id", type="integer", example=11),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:58:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-11T08:58:00.000000Z")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi tạo kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi tạo kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
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
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }



    /**
     * @OA\Get(
     *     path="/api/v1/warehouse/{id}",
     *     summary="Lấy thông tin chi tiết kho",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của kho",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin kho thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Kho nguyên vật liệu 1"),
     *             @OA\Property(property="location", type="string", example="Bình Chánh"),
     *             @OA\Property(property="acreage", type="string", example="1000"),
     *             @OA\Property(property="number_of_shelves", type="string", example="100"),
     *             @OA\Property(property="category_name", type="string", example="Material"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:57:58.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi tìm kiếm kho"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy kho."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi tìm kiếm kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi tìm kiếm kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
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
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
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
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/warehouse/delete/{id}",
     *     summary="Xóa kho",
     *     tags={"Warehouse"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa kho thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kho thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kho thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy kho."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi xóa kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kho thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
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
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }
}
