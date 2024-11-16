<?php

namespace App\Http\Controllers;

use App\Events\ProductReceipt\ProductReceiptCreated;
use App\Http\Requests\ProductReceipt\StoreProductReceiptRequest;
use App\Models\ProductReceipt;
use Illuminate\Http\Request;

use App\Repositories\Interface\ProductReceiptRepositoryInterface;

use Illuminate\Http\JsonResponse;
use App\Services\ProductReceiptService;

class ProductReceiptController extends Controller
{

    protected $productReceiptRepository;
    protected $productReceiptService;

    public function __construct(
        ProductReceiptRepositoryInterface $productReceiptRepository,
        ProductReceiptService $productReceiptService
    ) {
        $this->productReceiptRepository = $productReceiptRepository;
        $this->productReceiptService = $productReceiptService;
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()

    /**
     * @OA\Get(
     *     path="/api/v1/product-receipts",
     *     tags={"Product Receipt"},
     *     summary="Lấy danh sách phiếu nhập kho thành phẩm",
     *     description="Lấy danh sách tất cả các phiếu nhập kho thành phẩm cùng với thông tin chi tiết của từng phiếu",
     *     operationId="getAllProductReceiptsWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách phiếu nhập kho thành phẩm",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Phiếu nhập kho thành phẩm 1"),
     *                     @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *                     @OA\Property(property="receive_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                     @OA\Property(property="status", type="integer", example=1),
     *                     @OA\Property(property="note", type="string", example=null),
     *                     @OA\Property(property="created_by", type="string", example="Bùi Thục Đoan"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                     @OA\Property(
     *                         property="details",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="product_receipt_id", type="integer", example=1),
     *                             @OA\Property(property="unit", type="string", example="chai"),
     *                             @OA\Property(property="quantity", type="integer", example=100),
     *                             @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                             @OA\Property(property="category_name", type="string", example="Chai nhựa HDPE"),
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
     *         description="Không tìm thấy phiếu nhập kho nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra khi lấy dữ liệu"),
     *             @OA\Property(property="error", type="string", example="Hiện tại chưa có phiếu nhập kho nào"),
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
            $productReceipts = $this->productReceiptService->getAllProductReceiptsWithDetails();

            return response()->json([
                'data' => $productReceipts,
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
     * @OA\Get(
     *     path="/api/v1/product-receipt/{id}",
     *     tags={"Product Receipt"},
     *     summary="Lấy chi tiết phiếu nhập kho",
     *     description="Lấy thông tin chi tiết của một phiếu nhập kho thành phẩm cùng với các chi tiết của nó",
     *     operationId="getProductReceiptWithDetails",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của phiếu nhập kho thành phẩm"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin chi tiết phiếu nhập kho thành phẩm thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Phiếu nhập kho thành phẩm 1"),
     *                 @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *                 @OA\Property(property="receive_date", type="string", format="date-time", example="2024-10-28 18:35:58"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="note", type="string", example=null),
     *                 @OA\Property(property="created_by", type="string", example="Bùi Thục Đoan"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-28T11:35:58.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_receipt_id", type="integer", example=1),
     *                         @OA\Property(property="unit", type="string", example="chai"),
     *                         @OA\Property(property="quantity", type="integer", example=100),
     *                         @OA\Property(property="product_name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                         @OA\Property(property="category_name", type="string", example="Chai nhựa HDPE"),
     *                         @OA\Property(property="shelf_name", type="string", example="Kệ 1")
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
            $productReceipt = $this->productReceiptService->getProductReceiptWithDetails($id);

            return response()->json([
                'data' => $productReceipt,
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
     *     path="/api/v1/product-receipt/add",
     *     tags={"Product Receipt"},
     *     summary="Tạo phiếu nhập kho mới",
     *     description="Tạo một phiếu nhập kho mới cùng với chi tiết của nó",
     *     operationId="createProductReceipt",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="receive_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *             @OA\Property(property="warehouse_id", type="integer", example=2),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="note", type="string", example=null),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="shelf_id", type="integer", example=1),
     *                     @OA\Property(property="color_id", type="integer", example=2),
     *                     @OA\Property(property="unit", type="string", example="chai"),
     *                     @OA\Property(property="quantity", type="integer", example=50)
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
     *                 @OA\Property(property="name", type="string", example="Test"),
     *                 @OA\Property(property="receive_date", type="string", format="date-time", example="2024-09-24 10:22:21"),
     *                 @OA\Property(property="warehouse_id", type="integer", example=2),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="note", type="string", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-24T10:22:21.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="shelf_id", type="integer", example=1),
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


    public function store(StoreProductReceiptRequest $request): JsonResponse
    {
        try {
            // Tạo phiếu nhập kho và chi tiết
            $productReceipt = $this->productReceiptService->createProductReceiptWithDetails($request->validated());

            event(new ProductReceiptCreated($productReceipt));

            return response()->json([
                'message' => 'Tạo phiếu nhập kho thành công',
                'status' => 201,
                'data' => $productReceipt,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình tạo phiếu nhập kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReceipt $productReceipt)
    {
        //
    }
}
