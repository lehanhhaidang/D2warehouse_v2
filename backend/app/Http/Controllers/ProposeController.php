<?php

namespace App\Http\Controllers;

use App\Events\Propose\ProposeCreated;
use App\Events\Propose\ProposeDeleted;
use App\Events\Propose\ProposeSent;
use App\Events\Propose\ProposeUpdated;
use App\Http\Requests\Propose\StoreProposeRequest;
use App\Models\Propose;
use Illuminate\Http\Request;
use App\Services\ProposeService;

class ProposeController extends Controller
{
    protected $proposeService;

    public function __construct(ProposeService $proposeService)
    {
        $this->proposeService = $proposeService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/proposes",
     *     tags={"Propose"},
     *     summary="Lấy danh sách các đề xuất",
     *     description="Trả về danh sách các đề xuất kèm chi tiết về sản phẩm và nguyên vật liệu.",
     *     operationId="getProposes",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer {access_token}"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đề xuất",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Phiếu đề xuất 2"),
     *                 @OA\Property(property="type", type="string", example="DXXTP"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *                 @OA\Property(property="status", type="string", example="Đã duyệt"),
     *                 @OA\Property(property="description", type="string", example="Đề xuất xuất thành phẩm cho kho thành phẩm 1. Các sản phẩm cần xuất được liệt kê chi tiết trong phiếu."),
     *                 @OA\Property(property="created_by", type="string", example="Lê Hạnh Hải Đăng"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-21T13:44:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="propose_id", type="integer", example=2),
     *                         @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít trắng"),
     *                         @OA\Property(property="material_name", type="string", example=null),
     *                         @OA\Property(property="unit", type="string", example="Chai"),
     *                         @OA\Property(property="quantity", type="integer", example=10)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hiện tại chưa có đề xuất nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có đề xuất nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi lấy dữ liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            $proposes = $this->proposeService->getAllProposeWithDetails();
            return response()->json([
                'data' => $proposes,
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
     *     path="/api/v1/propose/add",
     *     tags={"Propose"},
     *     summary="Tạo mới đề xuất",
     *     description="Tạo một đề xuất mới cùng với chi tiết sản phẩm và nguyên vật liệu.",
     *     operationId="createPropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Phiếu đề xuất 3"),
     *             @OA\Property(property="warehouse_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="Đang chờ duyệt"),
     *             @OA\Property(property="description", type="string", example="Đề xuất nhập nguyên vật liệu cho kho 1"),
     *             @OA\Property(property="type", type="string", example="DXNNVL"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="material_id", type="integer", example=1, nullable=true),
     *                     @OA\Property(property="unit", type="string", example="Chai"),
     *                     @OA\Property(property="quantity", type="integer", example=10),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer {access_token}"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đề xuất được tạo thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=3),
     *             @OA\Property(property="name", type="string", example="Phiếu đề xuất 3"),
     *             @OA\Property(property="warehouse_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="Đang chờ duyệt"),
     *             @OA\Property(property="description", type="string", example="Đề xuất nhập nguyên vật liệu cho kho 1"),
     *             @OA\Property(property="created_by", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-22T10:23:45.000000Z"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="propose_id", type="integer", example=3),
     *                     @OA\Property(property="material_id", type="integer", example=1, nullable=true),
     *                     @OA\Property(property="unit", type="string", example="Chai"),
     *                     @OA\Property(property="quantity", type="integer", example=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi tạo đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi tạo đề xuất"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function store(StoreProposeRequest $request)
    {
        try {
            $propose = $this->proposeService->createProposeWithDetails($request->all());

            event(new ProposeCreated($propose));
            return response()->json([
                'message' => 'Tạo đề xuất thành công',
                'data' => $propose,
                'status' => 201,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi tạo đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ],  $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/propose/{id}",
     *     tags={"Propose"},
     *     summary="Lấy chi tiết đề xuất theo ID",
     *     description="Trả về chi tiết đề xuất kèm thông tin sản phẩm và nguyên vật liệu dựa trên ID đề xuất.",
     *     operationId="getProposeById",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của đề xuất",
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer {access_token}"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chi tiết đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Phiếu đề xuất 2"),
     *             @OA\Property(property="type", type="string", example="DXXTP"),
     *             @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *             @OA\Property(property="status", type="string", example="Đã duyệt"),
     *             @OA\Property(property="description", type="string", example="Đề xuất xuất thành phẩm cho kho thành phẩm 1. Các sản phẩm cần xuất được liệt kê chi tiết trong phiếu."),
     *             @OA\Property(property="created_by", type="string", example="Lê Hạnh Hải Đăng"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-21T13:44:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="propose_id", type="integer", example=2),
     *                     @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít trắng"),
     *                     @OA\Property(property="material_name", type="string", example=null),
     *                     @OA\Property(property="unit", type="string", example="Chai"),
     *                     @OA\Property(property="quantity", type="integer", example=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi lấy dữ liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $propose = $this->proposeService->getProposeWithDetails($id);

            return response()->json($propose, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/propose/update/{id}",
     *     tags={"Propose"},
     *     summary="Cập nhật đề xuất",
     *     description="Cập nhật thông tin của một đề xuất đã tồn tại, bao gồm thông tin chi tiết về sản phẩm và nguyên vật liệu.",
     *     operationId="updatePropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đề xuất cần cập nhật",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=8
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="status", type="string", example="Chờ gửi"),
     *             @OA\Property(property="description", type="string", example="ddddd"),
     *             @OA\Property(property="type", type="string", example="DXNTP"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="unit", type="string", example="chai"),
     *                     @OA\Property(property="quantity", type="integer", example=1000)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật đề xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật đề xuất thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=8),
     *                 @OA\Property(property="name", type="string", example="Test"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="status", type="string", example="Chờ gửi"),
     *                 @OA\Property(property="description", type="string", example="ddddd"),
     *                 @OA\Property(property="type", type="string", example="DXNTP"),
     *                 @OA\Property(property="created_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-22T19:50:48.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-23T12:30:00.000000Z"),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="unit", type="string", example="chai"),
     *                         @OA\Property(property="quantity", type="integer", example=1000)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền chỉnh sửa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật đề xuất"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền chỉnh sửa đề xuất này"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật đề xuất"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi cập nhật đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi cập nhật đề xuất"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function update(StoreProposeRequest $request, $id)
    {
        try {
            $propose = $this->proposeService->updateProposeWithDetails($id, $request->all());

            event(new ProposeUpdated($propose));

            return response()->json([
                'message' => 'Cập nhật đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/propose/delete/{id}",
     *     tags={"Propose"},
     *     summary="Xóa đề xuất",
     *     description="Xóa một đề xuất đã tồn tại, bao gồm tất cả các chi tiết liên quan.",
     *     operationId="deletePropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đề xuất cần xóa",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=8
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa đề xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa đề xuất thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền xóa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa đề xuất"),
     *             @OA\Property(property="error", type="string", example="Bạn không có quyền xóa đề xuất này"),
     *             @OA\Property(property="status", type="integer", example=403)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa đề xuất"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Có lỗi xảy ra khi xóa đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi xóa đề xuất"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {

            $this->proposeService->deleteProposeWithDetails($id);

            event(new ProposeDeleted($id));

            return response()->json([
                'message' => 'Xóa đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/propose/send/{id}",
     *     tags={"Propose"},
     *     summary="Gửi đề xuất",
     *     description="Cập nhật trạng thái của đề xuất để đánh dấu là đã gửi",
     *     operationId="sendPropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đề xuất cần gửi",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gửi đề xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Gửi đề xuất thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi đề xuất"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi xử lý yêu cầu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi gửi đề xuất"),
     *             @OA\Property(property="error", type="string", example="Error details..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function sendPropose($id)
    {
        try {
            $this->proposeService->sendPropose($id);

            event(new ProposeSent($id));

            return response()->json([
                'message' => 'Gửi đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gửi đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/propose/accept/{id}",
     *     tags={"Propose"},
     *     summary="Duyệt đề xuất",
     *     description="Cập nhật trạng thái của đề xuất để đánh dấu là đã duyệt",
     *     operationId="acceptPropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đề xuất cần duyệt",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Duyệt đề xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Duyệt đề xuất thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi duyệt đề xuất"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi xử lý yêu cầu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi duyệt đề xuất"),
     *             @OA\Property(property="error", type="string", example="Error details..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function acceptPropose($id)
    {
        try {
            $this->proposeService->acceptPropse($id);


            return response()->json([
                'message' => 'Duyệt đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi duyệt đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/v1/propose/reject/{id}",
     *     tags={"Propose"},
     *     summary="Từ chối đề xuất",
     *     description="Cập nhật trạng thái của đề xuất để đánh dấu là đã từ chối",
     *     operationId="rejectPropose",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đề xuất cần từ chối",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Từ chối đề xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Từ chối đề xuất thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đề xuất",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối đề xuất"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy đề xuất"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi xử lý yêu cầu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi từ chối đề xuất"),
     *             @OA\Property(property="error", type="string", example="Error details..."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function rejectPropose($id)
    {
        try {
            $this->proposeService->rejectPropose($id);

            return response()->json([
                'message' => 'Từ chối đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi từ chối đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }
}
