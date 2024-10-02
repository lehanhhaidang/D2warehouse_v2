<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShelfRequest;
use App\Repositories\Interface\ShelfRepositoryInterface;
use App\Services\ShelfService;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    protected $shelfRepository;
    protected $shelfService;

    public function __construct(
        ShelfRepositoryInterface $shelfRepository,
        ShelfService $shelfService
    ) {
        $this->shelfRepository = $shelfRepository;
        $this->shelfService = $shelfService;
    }

    public function index()
    {
        try {
            $shelf = $this->shelfService->getAllShelf();
            return response()->json($shelf, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách kệ hàng',
                'error' => $e->getMessage(),
                'status' => 500

            ], 500);
        }
    }

    public function store(StoreShelfRequest $request)
    {
        try {
            $shelf = $this->shelfService->storeShelf($request);

            if (!$shelf) {
                return response()->json([
                    'message' => 'Thêm kệ hàng thất bại'
                ], 500);
            }
            return response()->json([
                'message' => 'Thêm kệ hàng thành công',
                'data' => $shelf
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Thêm kệ hàng thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $shelf = $this->shelfService->findAShelf($id);

            if (!$shelf) {
                return response()->json([
                    'message' => 'Không tìm thấy kệ hàng'
                ], 404);
            }
            return response()->json($shelf, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi lấy thông tin kệ hàng'
            ], 500);
        }
    }

    public function update(StoreShelfRequest $request, $id)
    {

        try {
            $shelf = $this->shelfService->updateShelf($request, $id);
            return response()->json([
                'message' => 'Cập nhật kệ hàng thành công',
                'data' => $shelf
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Cập nhật kệ hàng thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {

        try {

            $this->shelfService->deleteShelf($id);

            return response()->json([
                'message' => 'Xóa kệ hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xóa kệ hàng thất bại',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
