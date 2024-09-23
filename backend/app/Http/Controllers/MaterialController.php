<?php

namespace App\Http\Controllers;

use App\Http\Requests\Material\StoreMaterialRequest;
use App\Models\Material;
use App\Repositories\Interface\MaterialRepositoryInterface;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    protected $materialRepository;

    public function __construct(MaterialRepositoryInterface $materialRepository)
    {

        $this->materialRepository = $materialRepository;
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/materials",
     *     summary="Lấy danh sách tất cả nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách nguyên vật liệu",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *                 @OA\Property(property="unit", type="string", example="bao"),
     *                 @OA\Property(property="quantity", type="integer", example=50),
     *                 @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-21T15:34:21.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu nào",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu nào"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */

    public function index()
    {
        //
        try {
            $materials = $this->materialRepository->all();
            if (empty($materials)) {
                return response()->json([
                    'message' => 'Không tìm thấy nguyên vật liệu nào',
                    'status' => 404
                ], 404);
            }
            return response()->json($materials);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách nguyên vật liệu',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/materials",
     *     summary="Thêm nguyên vật liệu mới",
     *     tags={"Materials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="unit", type="string", example="bao"),
     *             @OA\Property(property="quantity", type="integer", example=50),
     *             @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thêm nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Thêm nguyên vật liệu thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm nguyên vật liệu thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function store(StoreMaterialRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'material_img' => $request->material_img,
                'status' => $request->status,
            ];

            $this->materialRepository->create($data);

            return response()->json([
                'message' => 'Thêm nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Thêm nguyên vật liệu thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/v1/materials/{id}",
     *     summary="Lấy thông tin nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="unit", type="string", example="bao"),
     *             @OA\Property(property="quantity", type="integer", example=50),
     *             @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-21T15:34:21.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */


    public function show($id)
    {
        try {
            $material = $this->materialRepository->find($id);

            if (!$material) {
                return response()->json([
                    'message' => 'Không tim thấy nguyên vật liệu',
                    'status' => 404,

                ], 404);
            }
            return response()->json($material);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin nguyên vật liệu',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/materials/{id}",
     *     summary="Cập nhật thông tin nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nhựa HDPE"),
     *             @OA\Property(property="unit", type="string", example="bao"),
     *             @OA\Property(property="quantity", type="integer", example=50),
     *             @OA\Property(property="material_img", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Cập nhật nguyên vật liệu thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật nguyên vật liệu thất bại"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function update(StoreMaterialRequest $request, $id)
    {
        try {
            // Kiểm tra tồn tại nguyên vật liệu trước khi cập nhật
            $material = $this->materialRepository->find($id);

            if (!$material) {
                return response()->json([
                    'message' => 'Không tìm thấy nguyên vật liệu',
                    'status' => 404,
                ], 404);
            }

            // Cập nhật nguyên vật liệu
            $this->materialRepository->update(
                $id,
                [
                    'name' => $request->name,
                    'unit' => $request->unit,
                    'quantity' => $request->quantity,
                    'material_img' => $request->material_img,
                    'status' => $request->status,
                ]
            );

            return response()->json([
                'message' => 'Cập nhật nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cập nhật nguyên vật liệu thất bại',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/materials/{id}",
     *     summary="Xóa nguyên vật liệu",
     *     tags={"Materials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa nguyên vật liệu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa nguyên vật liệu thành công"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy nguyên vật liệu này"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Đã xảy ra lỗi khi xóa nguyên vật liệu",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đã xảy ra lỗi khi xóa nguyên vật liệu"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            // Kiểm tra tồn tại nguyên vật liệu trước khi xóa
            $material = $this->materialRepository->find($id);

            if (!$material) {
                return response()->json([
                    'message' => 'Không tìm thấy nguyên vật liệu này',
                    'status' => 404,
                ], 404);
            }

            // Tiến hành xóa nguyên vật liệu
            $this->materialRepository->delete($id);

            return response()->json([
                'message' => 'Xóa nguyên vật liệu thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xóa nguyên vật liệu',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
