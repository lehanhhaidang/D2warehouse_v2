<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialReceipt\StoreMaterialReceiptRequest;
use App\Models\MaterialReceipt;
use App\Repositories\Interface\MaterialReceiptRepositoryInterface;
use App\Services\MaterialReceiptService;
use Illuminate\Http\Request;

class MaterialReceiptController extends Controller
{

    protected $materialReceiptRepository;
    protected $materialReceiptService;

    public function __construct(
        MaterialReceiptRepositoryInterface $materialReceiptRepository,
        MaterialReceiptService $materialReceiptService
    ) {
        $this->materialReceiptRepository = $materialReceiptRepository;
        $this->materialReceiptService = $materialReceiptService;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/material-receipts",
     *     tags={"Material Receipt"},
     *     summary="Lấy danh sách phiếu nhập kho nguyên vật liệu",
     *     description="Lấy danh sách tất cả các phiếu nhập kho nguyên vật liệu cùng với thông tin chi tiết của từng phiếu",
     *     operationId="getAllMaterialReceiptsWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách phiếu nhập kho nguyên vật liệu",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Phiếu nhập kho nguyên vật liệu 1"),
     *                     @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                     @OA\Property(property="receive_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                     @OA\Property(property="status", type="integer", example=1),
     *                     @OA\Property(property="note", type="string", example=null),
     *                     @OA\Property(property="created_by", type="string", example="Nguyễn Huỳnh Hương"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                     @OA\Property(
     *                         property="details",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="material_receipt_id", type="integer", example=1),
     *                             @OA\Property(property="unit", type="string", example="kg"),
     *                             @OA\Property(property="quantity", type="integer", example=200),
     *                             @OA\Property(property="material_name", type="string", example="Nhựa HDPE"),
     *                             @OA\Property(property="category_name", type="string", example="Nguyên vật liệu nhựa"),
     *                             @OA\Property(property="shelf_name", type="string", example="Kệ 2")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu nhập kho nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có phiếu nhập kho nguyên vật liệu nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách phiếu nhập kho",
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
            $materialReceipts = $this->materialReceiptService->getAllMaterialReceiptsWithDetails();

            return response()->json([
                'data' => $materialReceipts,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/material-receipt/{id}",
     *     tags={"Material Receipt"},
     *     summary="Lấy chi tiết phiếu nhập kho nguyên vật liệu",
     *     description="Lấy thông tin chi tiết của một phiếu nhập kho nguyên vật liệu cùng với các chi tiết của nó",
     *     operationId="getMaterialReceiptWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của phiếu nhập kho nguyên vật liệu"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin chi tiết phiếu nhập kho nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Phiếu nhập kho nguyên vật liệu 2"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho nguyên vật liệu 1"),
     *                 @OA\Property(property="receive_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="note", type="string", example=null),
     *                 @OA\Property(property="created_by", type="string", example="Nguyễn Huỳnh Hương"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="material_receipt_id", type="integer", example=2),
     *                         @OA\Property(property="unit", type="string", example="bao"),
     *                         @OA\Property(property="quantity", type="integer", example=100),
     *                         @OA\Property(property="material_name", type="string", example="Nhựa PET"),
     *                         @OA\Property(property="category_name", type="string", example="Nhựa PET"),
     *                         @OA\Property(property="shelf_name", type="string", example="Kệ 2")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu nhập kho này",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu nhập kho này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy chi tiết phiếu nhập kho",
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
            $materialReceipt = $this->materialReceiptService->getMaterialReceiptWithDetails($id);

            return response()->json([
                'data' => $materialReceipt,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/material-receipt/add",
     *     tags={"Material Receipt"},
     *     summary="Tạo phiếu nhập kho mới",
     *     description="Tạo một phiếu nhập kho mới cùng với chi tiết của nó",
     *     operationId="createMaterialReceipt",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test material"),
     *             @OA\Property(property="receive_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="user_id", type="integer", example=1),
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
     *         description="Tạo phiếu nhập kho thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tạo phiếu nhập kho thành công"),
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Test material"),
     *                 @OA\Property(property="receive_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=1),
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
     *         description="Lỗi khi tạo phiếu nhập kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra trong quá trình tạo phiếu nhập kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function store(StoreMaterialReceiptRequest $request)
    {
        try {
            // Tạo phiếu nhập kho và chi tiết
            $materialReceipt = $this->materialReceiptService->createMaterialReceiptWithDetails($request->validated());

            return response()->json([
                'message' => 'Tạo phiếu nhập kho thành công',
                'status' => 201,
                'data' => $materialReceipt,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình tạo phiếu nhập kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }
}
