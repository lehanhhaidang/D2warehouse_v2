<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialExport\StoreMaterialExportRequest;
use App\Models\MaterialExport;
use App\Services\MaterialExportService;
use Illuminate\Http\Request;

class MaterialExportController extends Controller
{
    protected $materialExportService;

    public function __construct(MaterialExportService $materialExportService)
    {
        $this->materialExportService = $materialExportService;
    }
    public function index()
    {
        try {

            $materialExports = $this->materialExportService->getAllMaterialExportsWithDetails();

            return response()->json(
                $materialExports,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(StoreMaterialExportRequest $request)
    {
        try {
            // Tạo phiếu xuất kho và chi tiết
            $materialExport = $this->materialExportService->creatematerialExportWithDetails($request->validated());

            return response()->json([
                'message' => 'Tạo phiếu xuất kho thành công',
                'status' => 201,
                'data' => $materialExport,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình tạo phiếu xuất kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $materialExport = $this->materialExportService->getMaterialExportWithDetails($id);

            return response()->json(
                $materialExport,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
