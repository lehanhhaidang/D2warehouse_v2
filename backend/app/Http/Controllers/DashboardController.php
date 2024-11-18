<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\DashboardRepositoryInterface;
use App\Services\DashboardService;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/dashboard",
     *     summary="Lấy số liệu thống kê",
     *     tags={"Dashboard"},
     *     description="Trả về thông tin thống kê bao gồm số lượng các sản phẩm, nguyên vật liệu, đề xuất, phiếu nhập, phiếu xuất và báo cáo kiểm kê.",
     *     operationId="getDashboardData",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="userCount",
     *                     type="integer",
     *                     example=7,
     *                     description="Số lượng người dùng"
     *                 ),
     *                 @OA\Property(
     *                     property="warehouseCount",
     *                     type="integer",
     *                     example=2,
     *                     description="Số lượng kho"
     *                 ),
     *                 @OA\Property(
     *                     property="shelfCount",
     *                     type="integer",
     *                     example=10,
     *                     description="Số lượng kệ"
     *                 ),
     *                 @OA\Property(
     *                     property="shelfCountsByWarehouseId",
     *                     type="object",
     *                     additionalProperties={
     *                         @OA\Property(property="1", type="integer", example=4, description="Số lượng kệ tại kho 1"),
     *                         @OA\Property(property="2", type="integer", example=6, description="Số lượng kệ tại kho 2")
     *                     },
     *                     description="Số lượng kệ phân theo kho"
     *                 ),
     *                 @OA\Property(property="productCount", type="integer", example=120, description="Số lượng thành phẩm"),
     *                 @OA\Property(property="materialCount", type="integer", example=80, description="Số lượng nguyên vật liệu"),
     *                 @OA\Property(property="proposeCount", type="integer", example=10, description="Số lượng đề xuất"),
     *                 @OA\Property(property="importProductProposeCount", type="integer", example=5, description="Số lượng đề xuất nhập thành phẩm"),
     *                 @OA\Property(property="exportProductProposeCount", type="integer", example=3, description="Số lượng đề xuất xuất thành phẩm"),
     *                 @OA\Property(property="importMaterialProposeCount", type="integer", example=4, description="Số lượng đề xuất nhập nguyên vật liệu"),
     *                 @OA\Property(property="exportMaterialProposeCount", type="integer", example=2, description="Số lượng đề xuất xuất nguyên vật liệu"),
     *                 @OA\Property(property="productReceiptCount", type="integer", example=50, description="Số lượng phiếu nhập thành phẩm"),
     *                 @OA\Property(property="productExportCount", type="integer", example=40, description="Số lượng phiếu xuất thành phẩm"),
     *                 @OA\Property(property="materialReceiptCount", type="integer", example=30, description="Số lượng phiếu nhập nguyên vật liệu"),
     *                 @OA\Property(property="materialExportCount", type="integer", example=25, description="Số lượng phiếu xuất nguyên vật liệu"),
     *                 @OA\Property(property="inventoryReportCount", type="integer", example=12, description="Số lượng báo cáo kiểm kê")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200, description="Mã trạng thái HTTP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy dữ liệu."),
     *             @OA\Property(property="error", type="string", example="Không thể lấy dữ liệu, vui lòng thử lại sau."),
     *             @OA\Property(property="status", type="integer", example=500, description="Mã trạng thái HTTP")
     *         )
     *     )
     * )
     */


    public function index()
    {
        try {
            $data = $this->dashboardService->getDashboardData();

            return response()->json([
                'data' => $data,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Lỗi khi lấy dữ liệu.',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
