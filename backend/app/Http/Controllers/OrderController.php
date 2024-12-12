<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     tags={"Order"},
     *     summary="Lấy danh sách đơn hàng",
     *     description="Trả về danh sách các đơn hàng cùng với thông tin chi tiết của từng đơn hàng",
     *     operationId="getOrders",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_name", type="string", example="Châu Hoàng Khải Long"),
     *                 @OA\Property(property="customer_email", type="string", example="longchau@yahoo.com"),
     *                 @OA\Property(property="customer_phone", type="string", example="0987654321"),
     *                 @OA\Property(property="customer_address", type="string", example="Hà Nội"),
     *                 @OA\Property(property="order_date", type="string", format="date-time", example="2024-10-28 21:09:59"),
     *                 @OA\Property(property="delivery_date", type="string", format="date-time", example="2024-08-12 17:45:14"),
     *                 @OA\Property(property="status", type="integer", example=2),
     *                 @OA\Property(property="note", type="string", example="Giao hàng sớm"),
     *                 @OA\Property(property="total_price", type="number", format="float", example=6550000),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="order_id", type="integer", example=1),
     *                         @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                         @OA\Property(property="unit", type="string", example="chai"),
     *                         @OA\Property(property="quantity", type="integer", example=10000),
     *                         @OA\Property(property="price", type="number", format="float", example=500),
     *                         @OA\Property(property="total_price", type="number", format="float", example=5000000)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hiện tại chưa có đơn hàng nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy thông tin đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có đơn hàng nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy thông tin đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Error details..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $orders = $this->orderService->getAll();
            return response()->json([
                'data' => $orders,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy thông tin đơn hàng thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/{id}",
     *     tags={"Order"},
     *     summary="Lấy thông tin chi tiết đơn hàng",
     *     description="Trả về thông tin chi tiết của một đơn hàng bao gồm các sản phẩm trong đơn hàng",
     *     operationId="getOrderById",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_name", type="string", example="Châu Hoàng Khải Long"),
     *                 @OA\Property(property="customer_email", type="string", example="longchau@yahoo.com"),
     *                 @OA\Property(property="customer_phone", type="string", example="0987654321"),
     *                 @OA\Property(property="customer_address", type="string", example="Hà Nội"),
     *                 @OA\Property(property="order_date", type="string", format="date-time", example="2024-10-28 21:09:59"),
     *                 @OA\Property(property="delivery_date", type="string", format="date-time", example="2024-08-12 17:45:14"),
     *                 @OA\Property(property="status", type="integer", example=2),
     *                 @OA\Property(property="note", type="string", example="Giao hàng sớm"),
     *                 @OA\Property(property="total_price", type="number", format="float", example=6550000),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="order_id", type="integer", example=1),
     *                         @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                         @OA\Property(property="unit", type="string", example="chai"),
     *                         @OA\Property(property="quantity", type="integer", example=10000),
     *                         @OA\Property(property="price", type="number", format="float", example=500),
     *                         @OA\Property(property="total_price", type="number", format="float", example=5000000)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy thông tin đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đơn hàng"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy thông tin đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return response()->json([
                'data' => $order,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy thông tin đơn hàng thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/order/confirm/{id}",
     *     tags={"Order"},
     *     summary="Xác nhận đơn hàng",
     *     description="Xác nhận trạng thái đơn hàng dựa trên ID",
     *     operationId="confirmOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần xác nhận",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xác nhận đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xác nhận đơn hàng thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xác nhận đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đơn hàng"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi xác nhận đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xác nhận đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function confirmOrder($id)
    {
        try {
            $order = $this->orderService->confirmOrder($id);

            return response()->json([
                'message' => 'Xác nhận đơn hàng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/order/complete/{id}",
     *     tags={"Order"},
     *     summary="Hoàn thành đơn hàng",
     *     description="Hoàn thành trạng thái đơn hàng dựa trên ID",
     *     operationId="completeOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần hoàn thành",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hoàn thành đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hoàn thành đơn hàng thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="hoàn thành đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đơn hàng"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi hoàn thành đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xác nhận đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function startProcessingOrder($id)
    {
        try {
            $order = $this->orderService->startProcessingOrder($id);

            return response()->json([
                'message' => 'Bắt đầu xử lý đơn hàng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ],  500);
        }
    }

    public function completeOrder($id)
    {
        try {
            $order = $this->orderService->completeOrder($id);

            return response()->json([
                'message' => 'Hoàn thành đơn hàng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/order/cancel/{id}",
     *     tags={"Order"},
     *     summary="Hủy đơn hàng",
     *     description="Hủy trạng thái đơn hàng dựa trên ID",
     *     operationId="cancelOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần hủy",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hủy đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hủy đơn hàng thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hủy đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đơn hàng"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi hủy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hủy đơn hàng thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function cancelOrder($id)
    {
        try {
            $order = $this->orderService->cancelOrder($id);

            return response()->json([
                'message' => 'Hủy đơn hàng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }
}
