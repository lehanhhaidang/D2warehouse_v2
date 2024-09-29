<?php

namespace App\Http\Controllers;

use App\Http\Requests\Color\StoreColorRequest;
use App\Repositories\Interface\ColorRepositoryInterface;
use App\Services\ColorService;
use Illuminate\Http\Request;

class ColorController extends Controller
{

    protected $colorRepository;
    protected $colorService;

    public function __construct(
        ColorRepositoryInterface $colorRepository,
        ColorService $colorService
    ) {
        $this->colorRepository = $colorRepository;
        $this->colorService = $colorService;
    }

    public function index()
    {
        try {
            $color = $this->colorRepository->all();

            if (empty($color)) {
                return response()->json([
                    'message' => 'Hiện tại chưa có màu nào được tạo',
                    'status' => '404'
                ], 404);
            }

            return response()->json($color, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy dữ liệu không thành công',
                'status' => '500'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $color = $this->colorRepository->find($id);

            if (empty($color)) {
                return response()->json([
                    'message' => 'Không tìm thấy màu',
                    'status' => '404'
                ], 404);
            }

            return response()->json($color, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy dữ liệu không thành công',
                'status' => '500'
            ], 500);
        }
    }

    public function store(StoreColorRequest $request)
    {
        try {
            $this->colorRepository->create($request->all());
            return response()->json([
                'message' => 'Thêm màu thành công',
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Thêm màu không thành công',
                'status' => '500'
            ], 500);
        }
    }
}
