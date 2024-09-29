<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Repositories\Interface\ProductRepositoryInterface;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $productRepository;
    protected $productService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductService $productService
    ) {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Lấy danh sách tất cả thành phẩm",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách thành phẩm",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *                 @OA\Property(property="category_name", type="string", example="Chai nhựa PET"),
     *                 @OA\Property(property="color_name", type="string", example="Xanh"),
     *                 @OA\Property(property="unit", type="string", example="chai"),
     *                 @OA\Property(property="quantity", type="integer", example=100),
     *                 @OA\Property(property="product_img", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-21T15:34:21.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có thành phẩm nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hiện tại chưa có thành phẩm nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy danh sách thành phẩm"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */


    public function index()
    {
        try {
            $products = $this->productRepository->all();

            if (empty($products)) {
                return response()->json([
                    'message' => 'Hiện tại chưa có thành phẩm nào',
                    'status' => 404,
                ], 404);
            }
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách thành phẩm',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/v1/product/add",
     *     summary="Thêm mới thành phẩm",
     *     tags={"Products"},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "unit", "quantity", "category_id", "color_id", "shelf_id", "status"},
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="unit", type="string", example="bao"),
     *                 @OA\Property(property="quantity", type="integer", example=50),
     *                 @OA\Property(property="category_id", type="integer", example=3),
     *                 @OA\Property(property="color_id", type="integer", example=1),
     *                 @OA\Property(property="material_img", type="string", format="binary" , example="image.jpg"),
     *                 @OA\Property(property="status", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thêm thành phẩm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm thành phẩm thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Thêm thành phẩm thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm thành phẩm thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */


    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->storeProduct($request);

            return response()->json([
                'message' => 'Thêm thành phẩm thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm thành phẩm: ' . $e->getMessage());

            return response()->json([
                'message' => 'Thêm thành phẩm thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/product/{id}",
     *     summary="Lấy thông tin thành phẩm theo ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của thành phẩm cần lấy thông tin",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Xô nhựa HDPE 1 lít đỏ"),
     *             @OA\Property(property="category_name", type="string", example="Xô nhựa HDPE"),
     *             @OA\Property(property="color_name", type="string", example="Đỏ"),
     *             @OA\Property(property="shelf_name", type="string", example="Kệ 2"),
     *             @OA\Property(property="warehouse_name", type="string", example="Kho thành phẩm 1"),
     *             @OA\Property(property="unit", type="string", example="chai"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="product_img", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-25T03:42:10.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy thành phẩm"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin thành phẩm"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                return response()->json([
                    'message' => 'Không tim thấy thành phẩm',
                    'status' => 404,

                ], 404);
            }
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin thành phẩm',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * @OA\Patch(
     *     path="/api/v1/product/update/{id}",
     *     summary="Cập nhật thành phẩm",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Chai nhựa HDPE 1 lít xanh"),
     *             @OA\Property(property="category_id", type="integer", example=5),
     *             @OA\Property(property="color_id", type="integer", example=2),
     *             @OA\Property(property="unit", type="string", example="chai"),
     *             @OA\Property(property="quantity", type="integer", example=100),
     *             @OA\Property(property="product_img", type="string", format="binary", example="file"),
     *             @OA\Property(property="status", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành phẩm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật thành phẩm thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy thành phẩm"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật thành phẩm thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật thành phẩm thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function update(StoreProductRequest $request, $id)
    {
        try {
            $this->productService->updateProduct($id, $request);

            return response()->json([
                'message' => 'Cập nhật thành phẩm thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật thành phẩm: ' . $e->getMessage());

            return response()->json([
                'message' => 'Cập nhật thành phẩm thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/product/delete/{id}",
     *     summary="Xóa thành phẩm",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa thành phẩm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa thành phẩm thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy thành phẩm này",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy thành phẩm này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Đã xảy ra lỗi khi xóa thành phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi xóa thành phẩm"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $delete = $this->productRepository->delete($id);

            if (!$delete) {
                return response()->json([
                    'message' => 'Không tìm thấy thành phẩm này',
                    'status' => 404,
                ], 404);
            }

            return response()->json([
                'message' => 'Xóa thành phẩm thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xóa thành phẩm',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
