<?php

namespace App\Http\Controllers;

use App\Events\MaterialExport\MaterialExportCreated;
use App\Http\Requests\MaterialExport\StoreMaterialExportRequest;
use App\Models\MaterialExport;
use App\Services\MaterialExportService;
use Illuminate\Http\Request;

class MaterialExportController extends Controller
{
    protected $materialExportService;

    public function __construct(MaterialExportService $materialExportService)
    {
        $this->materialExportService = $materialExportService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/material-exports",
     *     tags={"Material Export"},
     *     summary="Lấy danh sách phiếu xuất kho nguyên vật liệu",
     *     description="Lấy danh sách tất cả các phiếu xuất kho nguyên vật liệu cùng với thông tin chi tiết của từng phiếu",
     *     operationId="getAllMaterialExportsWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách phiếu xuất kho nguyên vật liệu",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Phiếu xuất kho nguyên vật liệu 1"),
     *                     @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                     @OA\Property(property="export_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                     @OA\Property(property="status", type="integer", example=1),
     *                     @OA\Property(property="note", type="string", example=null),
     *                     @OA\Property(property="created_by", type="string", example="Bùi Thục Đoan"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                     @OA\Property(
     *                         property="details",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="material_export_id", type="integer", example=1),
     *                             @OA\Property(property="unit", type="string", example="bao"),
     *                             @OA\Property(property="quantity", type="integer", example=100),
     *                             @OA\Property(property="material_name", type="string", example="Nhựa HDPE"),
     *                             @OA\Property(property="category_name", type="string", example="Nhựa HDPE"),
     *                             @OA\Property(property="shelf_name", type="string", example="Kệ 1")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu xuất kho nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có phiếu xuất kho nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách phiếu xuất kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {

            $materialExports = $this->materialExportService->getAllMaterialExportsWithDetails();

            return response()->json(
                [
                    'data' => $materialExports,
                    'status' => 200
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/material-export/add",
     *     tags={"Material Export"},
     *     summary="Tạo phiếu xuất kho mới",
     *     description="Tạo một phiếu xuất kho mới cùng với chi tiết của nó",
     *     operationId="createMaterialExport",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="export_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="note", type="string", example=null),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="material_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_id", type="integer", example=7),
     *                     @OA\Property(property="color_id", type="integer", example=2),
     *                     @OA\Property(property="unit", type="string", example="chai"),
     *                     @OA\Property(property="quantity", type="integer", example=100)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo phiếu xuất kho thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tạo phiếu xuất kho thành công"),
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Test"),
     *                 @OA\Property(property="export_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="note", type="string", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-24T10:22:21.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="material_id", type="integer", example=1),
     *                         @OA\Property(property="shelf_id", type="integer", example=7),
     *                         @OA\Property(property="color_id", type="integer", example=2),
     *                         @OA\Property(property="unit", type="string", example="chai"),
     *                         @OA\Property(property="quantity", type="integer", example=100)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi tạo phiếu xuất kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra trong quá trình tạo phiếu xuất kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function store(StoreMaterialExportRequest $request)
    {
        try {
            // Tạo phiếu xuất kho và chi tiết
            $materialExport = $this->materialExportService->creatematerialExportWithDetails($request->validated());

            event(new MaterialExportCreated($materialExport));

            return response()->json([
                'message' => 'Tạo phiếu xuất kho thành công',
                'status' => 201,
                'data' => $materialExport,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình tạo phiếu xuất kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/material-export/{id}",
     *     tags={"Material Export"},
     *     summary="Lấy chi tiết phiếu xuất kho nguyên vật liệu",
     *     description="Lấy thông tin chi tiết của một phiếu xuất kho nguyên vật liệu cùng với các chi tiết của nó",
     *     operationId="getMaterialExportWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của phiếu xuất kho nguyên vật liệu"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin chi tiết phiếu xuất kho nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Phiếu xuất kho nguyên vật liệu 1"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="export_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="note", type="string", example=null),
     *                 @OA\Property(property="created_by", type="string", example="Bùi Thục Đoan"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="material_export_id", type="integer", example=1),
     *                         @OA\Property(property="unit", type="string", example="bao"),
     *                         @OA\Property(property="quantity", type="integer", example=100),
     *                         @OA\Property(property="material_name", type="string", example="Nhựa HDPE"),
     *                         @OA\Property(property="category_name", type="string", example="Nhựa HDPE"),
     *                         @OA\Property(property="shelf_name", type="string", example="Kệ 1")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu xuất kho này",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu xuất kho này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy chi tiết phiếu xuất kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $materialExport = $this->materialExportService->getMaterialExportWithDetails($id);

            return response()->json(
                [
                    'data' => $materialExport,
                    'status' => 200
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
