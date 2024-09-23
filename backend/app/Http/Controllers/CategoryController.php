<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
{

    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Lấy danh sách tất cả danh mục",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách danh mục",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Chai nhựa HDPE")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy danh mục nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy danh sách danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi lấy danh sách danh mục"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */


    public function index()
    {
        try {
            $categories = $this->categoryRepository->all();

            if (empty($categories)) {
                return response()->json([
                    'message' => 'Không tìm thấy danh mục nào',
                    'status' => 404

                ], 404);
            }

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/category/add",
     *     summary="Thêm danh mục mới",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Chai nhựa HDPE")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thêm danh mục thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm danh mục thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Thêm danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm danh mục thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi...")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            $data = [
                'name' => $request->name
            ];

            $this->categoryRepository->create($data);

            return response()->json([
                'message' => 'Thêm danh mục thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Thêm danh mục thất bại',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/category/{id}",
     *     summary="Lấy thông tin danh mục theo ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Chai nhựa HDPE")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy danh mục"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi khi lấy thông tin danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lỗi khi lấy thông tin danh mục"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi...")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Không tìm thấy danh mục',
                    'status' => 404
                ], 404);
            }

            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin danh mục',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/category/update/{id}",
     *     summary="Cập nhật thông tin danh mục",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Xô nhựa HDPE", description="Tên danh mục mới")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật danh mục thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật danh mục thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy danh mục"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật danh mục thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi...")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Không tìm thấy danh mục',
                    'status' => 404
                ], 404);
            }

            $this->categoryRepository->update($id, [
                'name' => $request->name,
            ]);

            return response()->json([
                'message' => 'Cập nhật danh mục thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cập nhật danh mục thất bại',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/category/delete/{id}",
     *     summary="Xóa danh mục",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID của danh mục cần xóa"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa danh mục thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa danh mục thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy danh mục"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Xóa danh mục thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa danh mục thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Chi tiết lỗi...")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Không tìm thấy danh mục',
                    'status' => 404
                ], 404);
            }

            $this->categoryRepository->delete($id);

            return response()->json([
                'message' => 'Xóa danh mục thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xóa danh mục thất bại',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
