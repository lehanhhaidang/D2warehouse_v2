<?php

namespace App\Http\Controllers;

use App\Events\Material\MaterialCreated;
use App\Events\Material\MaterialDeleted;
use App\Events\Material\MaterialUpdated;
use App\Http\Requests\Material\StoreMaterialRequest;
use App\Models\Material;
use App\Repositories\Interface\MaterialRepositoryInterface;
use App\Services\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    protected $materialRepository;
    protected $materialService;

    public function __construct(
        MaterialService $materialService
    ) {
        $this->materialService = $materialService;
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/materials",
     *     summary="Lấy danh sách tất cả nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách nguyên vật liệu",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="unit", type="string", example="bao"),
     *                 @OA\Property(property="quantity", type="integer", example=50),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-25T03:42:10.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có nguyên vật liệu nào",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách nguyên vật liệu"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy nguyên vật liệu nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách nguyên vật liệu"),
     *             @OA\Property(property="error", type="string", example="Error message here"),
     *             @OA\Property(property="status", type="integer", example=500),
     *         )
     *     )
     * )
     */


    public function index()
    {
        try {
            $products = $this->materialService->getAllMaterials();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách nguyên vật liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/material/add",
     *     summary="Thêm mới nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "unit", "quantity", "category_id", "status"},
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="unit", type="string", example="bao"),
     *                 @OA\Property(property="quantity", type="integer", example=50),
     *                 @OA\Property(property="category_id", type="integer", example=3),
     *                 @OA\Property(property="material_img", type="string", format="binary" , example="image.jpg"),
     *                 @OA\Property(property="status", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thêm nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Thêm nguyên vật liệu thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm nguyên vật liệu thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function store(StoreMaterialRequest $request)
    {
        try {
            $material = $this->materialService->storeMaterial($request);

            event(new MaterialCreated($material));

            return response()->json([
                'message' => 'Thêm nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm nguyên vật liệu: ' . $e->getMessage());

            return response()->json([
                'message' => 'Thêm nguyên vật liệu thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/material/{id}",
     *     summary="Lấy thông tin nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=3
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=3),
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="unit", type="string", example="bao"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *             @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-25T03:42:10.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin nguyên vật liệu"),
     *             @OA\Property(property="error", type="string", example="Không tim thấy nguyên vật liệu"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin nguyên vật liệu"),
     *             @OA\Property(property="error", type="string", example="Error message here"),
     *             @OA\Property(property="status", type="integer", example=500),
     *         )
     *     )
     * )
     */




    public function show($id)
    {
        try {
            $product = $this->materialService->getMaterial($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin nguyên vật liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/material/update/{id}",
     *     summary="Cập nhật nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=3
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="unit", type="string", example="bao"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="material_img", type="string", nullable=true, format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật nguyên vật liệu thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật nguyên vật liệu thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function update(StoreMaterialRequest $request, $id)
    {
        try {
            $this->materialService->updateMaterial($request, $id);

            event(new MaterialUpdated($id));
            return response()->json([
                'message' => 'Cập nhật nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật nguyên vật liệu: ' . $e->getMessage());

            return response()->json([
                'message' => 'Cập nhật nguyên vật liệu thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ],  $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/material/delete/{id}",
     *     summary="Xóa nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu này",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Đã xảy ra lỗi khi xóa nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi xóa nguyên vật liệu"),
     *             @OA\Property(property="error", type="string", example="Error message here"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function destroy($id)
    {
        try {
            // Kiểm tra tồn tại nguyên vật liệu trước khi xóa

            // Tiến hành xóa nguyên vật liệu
            $this->materialService->deleteMaterial($id);

            event(new MaterialDeleted($id));

            return response()->json([
                'message' => 'Xóa nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xóa nguyên vật liệu',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
