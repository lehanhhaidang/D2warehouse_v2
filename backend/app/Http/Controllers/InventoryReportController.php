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
     * @OA\Post(
     *     path="/api/v1/inventory-report/add",
     *     tags={"Inventory Reports"},
     *     summary="Tạo mới phiếu kiểm kê kho",
     *     description="Tạo mới phiếu kiểm kê kho và thêm chi tiết.",
     *     operationId="createInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test phiếu kiểm kê mới"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="status", type="integer", example=0),
     *             @OA\Property(property="description", type="string", example="ádgasdgfasdfsafs"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_id", type="integer", example=1),
     *                     @OA\Property(property="expected_quantity", type="integer", example=100),
     *                     @OA\Property(property="actual_quantity", type="integer", example=0),
     *                     @OA\Property(property="note", type="string", example="something")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tạo phiếu kiểm kê kho thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tạo phiếu kiểm kê kho thành công"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Test phiếu kiểm kê mới"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="status", type="integer", example=0),
     *                 @OA\Property(property="description", type="string", example="ádgasdgfasdfsafs"),
     *                 @OA\Property(property="details", type="array", @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_id", type="integer", example=1),
     *                     @OA\Property(property="expected_quantity", type="integer", example=100),
     *                     @OA\Property(property="actual_quantity", type="integer", example=0),
     *                     @OA\Property(property="note", type="string", example="something")
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi tạo phiếu kiểm kê kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi tạo phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/v1/inventory-report/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Lấy chi tiết phiếu kiểm kê kho",
     *     description="Trả về thông tin chi tiết của một phiếu kiểm kê kho theo ID.",
     *     operationId="getInventoryReportById",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của phiếu kiểm kê kho cần lấy",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết phiếu kiểm kê kho",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Báo cáo kiểm kê thành phẩm 11/02/2024"),
     *             @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *             @OA\Property(property="status", type="integer", example=0),
     *             @OA\Property(property="description", type="string", example="Báo cáo kiểm kê kho thành phẩm 1 ngày 11/02/2024"),
     *             @OA\Property(property="created_by", type="string", example="Quản trị viên"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-16T21:16:30.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *             @OA\Property(property="details", type="array", @OA\Items(
     *                 @OA\Property(property="inventory_report_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=1),
     *                 @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                 @OA\Property(property="material_id", type="integer", example=null),
     *                 @OA\Property(property="material_name", type="string", example=null),
     *                 @OA\Property(property="unit", type="string", example="chai"),
     *                 @OA\Property(property="shelf_id", type="integer", example=1),
     *                 @OA\Property(property="shelf_name", type="string", example="Kệ 1"),
     *                 @OA\Property(property="expected_quantity", type="integer", example=100),
     *                 @OA\Property(property="actual_quantity", type="integer", example=100),
     *                 @OA\Property(property="note", type="string", example=null)
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Phiếu kiểm kê kho không tồn tại"),
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

    /**
     * @OA\Patch(
     *     path="/api/v1/inventory-report/update/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Cập nhật phiếu kiểm kê kho",
     *     description="Cập nhật thông tin phiếu kiểm kê kho và các chi tiết của phiếu.",
     *     operationId="updateInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của phiếu kiểm kê cần cập nhật",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Test phiếu kiểm kê mới"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="status", type="integer", example=0),
     *             @OA\Property(property="description", type="string", example="Mô tả phiếu kiểm kê"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_id", type="integer", example=1),
     *                     @OA\Property(property="expected_quantity", type="integer", example=100),
     *                     @OA\Property(property="actual_quantity", type="integer", example=0),
     *                     @OA\Property(property="note", type="string", example="Ghi chú chi tiết")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật phiếu kiểm kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật phiếu kiểm kê kho thành công"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object", example={
     *                 "id": 1,
     *                 "name": "Test phiếu kiểm kê mới",
     *                 "warehouse_id": 2,
     *                 "status": 0,
     *                 "description": "Mô tả cập nhật",
     *                 "details": {
     *                     {
     *                         "product_id": 1,
     *                         "shelf_id": 1,
     *                         "expected_quantity": 100,
     *                         "actual_quantity": 0,
     *                         "note": "Ghi chú chi tiết"
     *                     }
     *                 }
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền cập nhật phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền cập nhật phiếu kiểm kê này"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu kiểm kê này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi cập nhật phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function update(InventoryReportRequest $request, $id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->updateInventoryReportWithDetails($id, $request->all());

            return response()->json([
                'message' => 'Cập nhật phiếu kiểm kê kho thành công',
                'status' => 200,
                'data' => $inventoryReport,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật phiếu kiểm kê kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/inventory-report/delete/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Xóa phiếu kiểm kê kho",
     *     description="Xóa một phiếu kiểm kê kho dựa trên ID.",
     *     operationId="deleteInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của phiếu kiểm kê cần xóa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa phiếu kiểm kê kho thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa phiếu kiểm kê kho thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Người dùng không có quyền xóa phiếu kiểm kê này",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền xóa phiếu kiểm kê này"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu kiểm kê này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi xóa phiếu kiểm kê kho",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa phiếu kiểm kê kho"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
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


    /**
     * @OA\Patch(
     *     path="/api/v1/inventory-report/send/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Gửi phiếu kiểm kê kho",
     *     description="Cập nhật trạng thái phiếu kiểm kê kho thành 'đã gửi'. Chỉ người tạo phiếu kiểm kê mới có quyền gửi.",
     *     operationId="sendInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của phiếu kiểm kê cần gửi",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gửi phiếu kiểm kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gửi phiếu kiểm kê thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Trạng thái phiếu kiểm kê không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Trạng thái phiếu kiểm kê không hợp lệ, có vẻ phiếu đã được gửi đi từ trước"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền gửi phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền gửi phiếu kiểm kê này"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu kiểm kê"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi gửi phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

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


    /**
     * @OA\Patch(
     *     path="/api/v1/inventory-report/confirm/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Xác nhận phiếu kiểm kê kho",
     *     description="Xác nhận phiếu kiểm kê kho với quyền hạn cụ thể.",
     *     operationId="confirmInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của phiếu kiểm kê cần xác nhận",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xác nhận phiếu kiểm kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xác nhận phiếu kiểm kê thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Người dùng không có quyền xử lý phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xác nhận phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền xử lý phiếu kiểm kê"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xác nhận phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu kiểm kê"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Trạng thái phiếu kiểm kê không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xác nhận phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Trạng thái phiếu kiểm kê không hợp lệ, có vẻ phiếu đã được xử lý"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi xác nhận phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xác nhận phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

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


    /**
     * @OA\Patch(
     *     path="/api/v1/inventory-report/reject/{id}",
     *     tags={"Inventory Reports"},
     *     summary="Từ chối phiếu kiểm kê kho",
     *     description="Từ chối phiếu kiểm kê kho với quyền hạn cụ thể.",
     *     operationId="rejectInventoryReport",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của phiếu kiểm kê cần từ chối",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Từ chối phiếu kiểm kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Từ chối phiếu kiểm kê thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Người dùng không có quyền xử lý phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền xử lý phiếu kiểm kê"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy phiếu kiểm kê"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Trạng thái phiếu kiểm kê không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Trạng thái phiếu kiểm kê không hợp lệ, có vẻ phiếu đã được xử lý"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi từ chối phiếu kiểm kê",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối phiếu kiểm kê"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

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
