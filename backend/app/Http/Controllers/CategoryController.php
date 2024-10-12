<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use Illuminate\Http\Request;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Models\Category;
use App\Services\CategoryService;
use Carbon\Carbon;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }



    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Lấy danh sách tất cả các danh mục",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục đã được lấy thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=3),
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="type", type="string", example="material"),
     *                 @OA\Property(property="parent_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có danh mục nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có danh mục nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }



    /**
     * @OA\Get(
     *     path="/api/v1/categories/parent",
     *     summary="Lấy danh sách tất cả các danh mục cha",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục đã được lấy thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Material"),
     *                 @OA\Property(property="type", type="string", example="category"),
     *                 @OA\Property(property="parent_id", type="integer", example=null),
     *             )
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có danh mục nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có danh mục nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function parentCategory()
    {
        try {
            $categories = $this->categoryService->getAllParentCategories();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/categories/product",
     *     summary="Lấy danh sách tất cả các danh mục thành phẩm",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục đã được lấy thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Chai nhựa HDPE"),
     *                 @OA\Property(property="type", type="string", example="product"),
     *                 @OA\Property(property="parent_id", type="integer", example=null),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có danh mục nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có danh mục nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function productCategory()
    {
        try {
            $categories = $this->categoryService->getAllProductCategories();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/categories/material",
     *     summary="Lấy danh sách tất cả các danh mục nguyên vật liệu",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục đã được lấy thành công",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="type", type="string", example="material"),
     *                 @OA\Property(property="parent_id", type="integer", example=null),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có danh mục nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Hiện tại không có danh mục nào."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function materialCategory()
    {
        try {
            $categories = $this->categoryService->getAllMaterialCategories();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/category/add",
     *     summary="Tạo mới một danh mục",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Nhựa PP"),
     *             @OA\Property(property="type", type="string", example="material"),
     *             @OA\Property(property="parent_id", type="integer", example=1, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo danh mục thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tạo danh mục thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=7),
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="type", type="string", example="material"),
     *                 @OA\Property(property="parent_id", type="integer", example=1)
     *             ),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Tạo danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tạo danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */



    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request);

            return response()->json([
                'message' => 'Tạo danh mục thành công',
                'data' => $category,
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tạo danh mục thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/category/{id}",
     *     summary="Lấy thông tin chi tiết của một danh mục",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục cần lấy thông tin",
     *         example=3
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh mục thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=3),
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="type", type="string", example="material"),
     *             @OA\Property(property="parent_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy danh mục với ID: 3"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy danh mục thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/v1/category/update/{id}",
     *     summary="Cập nhật thông tin một danh mục",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục cần cập nhật",
     *         example=3
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Nhựa PP"),
     *             @OA\Property(property="type", type="string", example="material"),
     *             @OA\Property(property="parent_id", type="integer", example=1, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật danh mục thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cập nhật danh mục thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=3),
     *                 @OA\Property(property="name", type="string", example="Nhựa PP"),
     *                 @OA\Property(property="type", type="string", example="material"),
     *                 @OA\Property(property="parent_id", type="integer", example=1)
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy danh mục"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($request, $id);

            return response()->json([
                'message' => 'Cập nhật danh mục thành công',
                'data' => $category,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cập nhật danh mục thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }



    /**
     * @OA\Delete(
     *     path="/api/v1/category/delete/{id}",
     *     summary="Xóa một danh mục",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục cần xóa",
     *         example=3
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa danh mục thành công",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Xóa danh mục thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Không tìm thấy danh mục với ID: 3"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Xóa danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa danh mục thất bại"),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'message' => 'Xóa danh mục thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xóa danh mục thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }
}
