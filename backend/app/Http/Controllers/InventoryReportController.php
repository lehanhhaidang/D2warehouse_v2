<?php

namespace App\Http\Controllers;

use App\Events\InventoryReport\InventoryReportConfirmed;
use App\Events\InventoryReport\InventoryReportCreated;
use App\Events\InventoryReport\InventoryReportDeleted;
use App\Events\InventoryReport\InventoryReportSent;
use App\Http\Requests\InventoryReport\InventoryReportRequest;
use App\Models\InventoryReport;
use App\Services\InventoryReportService;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    protected $inventoryReportService;

    public function __construct(InventoryReportService $inventoryReportService)
    {
        $this->inventoryReportService = $inventoryReportService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/inventory-reports",
     *     tags={"Inventory Reports"},
     *     summary="Lấy danh sách phiếu kiểm kê kho",
     *     description="Trả về danh sách phiếu kiểm kê kho cùng với các chi tiết của từng phiếu.",
     *     operationId="getInventoryReports",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách phiếu kiểm kê kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Báo cáo kiểm kê thành phẩm 11/02/2024"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *                 @OA\Property(property="status", type="integer", example=0),
     *                 @OA\Property(property="description", type="string", example="Báo cáo kiểm kê kho thành phẩm 1 ngày 11/02/2024"),
     *                 @OA\Property(property="created_by", type="string", example="Quản trị viên"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-16T21:16:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(property="details", type="array", @OA\Items(
     *                     @OA\Property(property="inventory_report_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                     @OA\Property(property="material_id", type="integer", example=null),
     *                     @OA\Property(property="material_name", type="string", example=null),
     *                     @OA\Property(property="unit", type="string", example="chai"),
     *                     @OA\Property(property="shelf_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_name", type="string", example="Kệ 1"),
     *                     @OA\Property(property="expected_quantity", type="integer", example=100),
     *                     @OA\Property(property="actual_quantity", type="integer", example=100),
     *                     @OA\Property(property="note", type="string", example=null)
     *                 ))
     *             )),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hiện tại chưa có phiếu kiểm kê kho nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có phiếu kiểm kê kho nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy dữ liệu",
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
            $inventoryReports = $this->inventoryReportService->getAllInventoryReportWithDetails();
            return response()->json([
                'data' => $inventoryReports,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryReportRequest $request)
    {
        try {
            $inventoryReport = $this->inventoryReportService->createInventoryReportWithDetails($request->all());

            event(new InventoryReportCreated($inventoryReport));

            return response()->json([
                'message' => 'Tạo phiếu kiểm kê kho thành công',
                'status' => 200,
                'data' => $inventoryReport,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi tạo phiếu kiểm kê kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->getInventoryReportWithDetails($id);
            return response()->json([
                'data' => $inventoryReport,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }


    public function update(InventoryReportRequest $request) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->inventoryReportService->deleteInventoryReport($id);

            event(new InventoryReportDeleted($id));
            return response()->json([
                'message' => 'Xóa phiếu kiểm kê kho thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa phiếu kiểm kê kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    public function sendInventoryReport($id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->sendInventoryReport($id);

            event(new InventoryReportSent($inventoryReport->id));
            return response()->json([
                'message' => 'Gửi phiếu kiểm kê thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gửi phiếu kiểm kê',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }


    public function confirmInventoryReport($id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->confirmInventoryReport($id);

            event(new InventoryReportConfirmed($inventoryReport->id));
            return response()->json([
                'message' => 'Xác nhận phiếu kiểm kê thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xác nhận phiếu kiểm kê',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    public function rejectInventoryReport($id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->rejectInventoryReport($id);

            return response()->json([
                'message' => 'Từ chối phiếu kiểm kê thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi từ chối phiếu kiểm kê',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }
}
