<?php

namespace App\Http\Controllers;

use App\Events\Shelf\ShelfCreated;
use App\Events\Shelf\ShelfDeleted;
use App\Events\Shelf\ShelfUpdated;
use App\Http\Requests\Shelf\StoreShelfRequest;
use App\Http\Requests\Shelf\UpdateShelfRequest;
use App\Repositories\Interface\ShelfRepositoryInterface;
use App\Services\ShelfService;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    protected $shelfRepository;
    protected $shelfService;

    public function __construct(
        ShelfRepositoryInterface $shelfRepository,
        ShelfService $shelfService
    ) {
        $this->shelfRepository = $shelfRepository;
        $this->shelfService = $shelfService;
    }



    /**
     * @OA\Get(
     *     path="/api/v1/shelves",
     *     summary="Lấy danh sách các kệ hàng",
     *     tags={"Shelf"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách các kệ hàng",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=7),
     *                 @OA\Property(property="name", type="string", example="Kệ 1"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="category_name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-10T14:13:35.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hiện tại không có kệ hàng nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có kệ hàng nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách kệ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $shelf = $this->shelfService->getAllShelf();
            return response()->json($shelf, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kệ hàng',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,

            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/shelf/add",
     *     summary="Tạo một kệ hàng mới",
     *     tags={"Shelf"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test thêm kệ hàng"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="number_of_levels", type="integer", example=8),
     *             @OA\Property(property="storage_capacity", type="integer", example=1000),
     *             @OA\Property(property="category_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo kệ hàng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tạo kệ hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=11),
     *                 @OA\Property(property="name", type="string", example="Test thêm kệ hàng"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="number_of_levels", type="integer", example=8),
     *                 @OA\Property(property="storage_capacity", type="integer", example=1000),
     *                 @OA\Property(property="category_id", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:58:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-11T08:58:00.000000Z")
     *             ),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Tạo kệ hàng thất bại",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tạo kệ hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function store(StoreShelfRequest $request)
    {
        try {
            $shelf = $this->shelfService->storeShelf($request);

            event(new ShelfCreated($shelf));
            return response()->json([
                'message' => 'Tạo kệ hàng thành công',
                'data' => $shelf,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tạo kệ hàng thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/shelf/{id}",
     *     summary="Lấy thông tin chi tiết của một kệ hàng",
     *     tags={"Shelf"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của kệ hàng",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin chi tiết của kệ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Kệ 1"),
     *             @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *             @OA\Property(property="category_name", type="string", example="Chai nhựa HDPE"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:42:38.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kệ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy kệ hàng với ID: 1111"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin kệ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function show($id)
    {
        try {
            $shelf = $this->shelfService->findAShelf($id);

            return response()->json($shelf, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/shelf/update/{id}",
     *     summary="Cập nhật thông tin kệ hàng",
     *     tags={"Shelf"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của kệ hàng cần cập nhật",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Kệ hàng mới"),
     *             @OA\Property(property="number_of_levels", type="integer", example=10),
     *             @OA\Property(property="storage_capacity", type="integer", example=2000),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="category_id", type="integer", example=5),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật kệ hàng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cập nhật kệ hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Kệ hàng mới"),
     *                 @OA\Property(property="number_of_levels", type="integer", example=10),
     *                 @OA\Property(property="storage_capacity", type="integer", example=2000),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-11T08:58:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-11T09:00:00.000000Z"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="category_id", type="integer", example=5),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kệ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cập nhật kệ hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy kệ hàng với ID: 1"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật kệ hàng thất bại",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cập nhật kệ hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function update(UpdateShelfRequest $request, $id)
    {

        try {
            $shelf = $this->shelfService->updateShelf($request, $id);

            event(new ShelfUpdated($shelf));
            return response()->json([
                'message' => 'Cập nhật kệ hàng thành công',
                'data' => $shelf,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/shelf/delete/{id}",
     *     summary="Xóa kệ hàng",
     *     tags={"Shelf"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của kệ hàng cần xóa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa kệ hàng thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kệ hàng thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kệ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kệ hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy kệ hàng."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Xóa kệ hàng thất bại",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa kệ hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {

        try {

            $this->shelfService->deleteShelf($id);
            event(new ShelfDeleted($id));

            return response()->json([
                'message' => 'Xóa kệ hàng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/shelves/filter",
     *     tags={"Shelf"},
     *     summary="Lọc kệ hàng",
     *     description="Lọc danh sách các kệ hàng dựa trên kho hàng, sản phẩm, hoặc nguyên vật liệu.",
     *     operationId="filterShelves",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="warehouse_id",
     *         in="query",
     *         description="ID của kho hàng",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         description="ID của sản phẩm (tùy chọn, nếu có)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="material_id",
     *         in="query",
     *         description="ID của nguyên vật liệu (tùy chọn, nếu có)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=null
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lọc kệ thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Kệ 2")
     *             )),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lọc kệ hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lọc kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function filterShelves(Request $request)
    {
        try {
            $warehouseId = $request->input('warehouse_id');
            $productId = $request->input('product_id');
            $materialId = $request->input('material_id');

            // Gọi service để lọc kệ
            $shelves = $this->shelfService->filterShelves($warehouseId, $productId, $materialId);

            return response()->json([
                'data' => $shelves,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lọc kệ hàng',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ],  500);
        }
    }


    public function getShelvesWithDetails()
    {
        try {
            $shelves = $this->shelfService->getShelvesWithDetails();
            return response()->json($shelves, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' =>  500,
            ],  500);
        }
    }

    public function getShelvesWithDetailsByWarehouseId($id)
    {
        try {
            $shelves = $this->shelfService->getShelvesWithDetailsByWarehouseId($id);
            return response()->json($shelves, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' =>  500,
            ],  500);
        }
    }

    public function getShelfDetailsById($id)
    {
        try {
            $shelfDetails = $this->shelfService->getShelfDetailsById($id);
            return response()->json($shelfDetails, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' =>  500,
            ],  500);
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/shelves/details-filter/{warehouseId}",
     *     summary="Lấy danh sách kệ hàng theo ID kho hàng",
     *     description="Lấy thông tin chi tiết của các kệ hàng trong một kho cụ thể, bao gồm thông tin sản phẩm, nguyên vật liệu.",
     *     tags={"Shelf"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="warehouseId",
     *         in="path",
     *         required=true,
     *         description="ID của kho hàng",
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách kệ hàng và chi tiết",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Kệ 1"),
     *                     @OA\Property(property="number_of_levels", type="integer", example=5),
     *                     @OA\Property(property="storage_capacity", type="integer", example=5000),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="category_id", type="integer", example=5),
     *                     @OA\Property(property="warehouse_id", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", example="2024-11-28T13:07:58.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *                     @OA\Property(property="category_name", type="string", example="Chai nhựa HDPE"),
     *                     @OA\Property(
     *                         property="details",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="shelf_id", type="integer", example=1),
     *                             @OA\Property(property="product_id", type="integer", example=1),
     *                             @OA\Property(property="material_id", type="integer", nullable=true, example=null),
     *                             @OA\Property(property="quantity", type="integer", example=100),
     *                             @OA\Property(property="product_name", type="string", example="Chai 1 lít TRONG HDPE( TN )"),
     *                             @OA\Property(property="material_name", type="string", nullable=true, example=null)
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin kệ hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin kệ hàng"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function getShelfItemsByWarehouseId($warehouseId)
    {
        try {
            $shelfItems = $this->shelfService->getShelfItemsByWarehouseId($warehouseId);

            return response()->json([
                'data' => $shelfItems,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin kệ hàng',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ],  500);
        }
    }
}
