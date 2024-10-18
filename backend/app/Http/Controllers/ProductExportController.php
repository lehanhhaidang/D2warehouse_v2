<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductExport\StoreProductExportRequest;
use App\Models\ProductExport;
use Illuminate\Http\Request;
use App\Services\ProductExportService;


class ProductExportController extends Controller
{
    protected $productExportService;

    public function __construct(ProductExportService $productExportService)
    {
        $this->productExportService = $productExportService;
    }


    public function index()
    {
        try {

            $productExports = $this->productExportService->getAllProductExportsWithDetails();

            return response()->json(
                $productExports,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(StoreProductExportRequest $request)
    {
        try {
            // Tạo phiếu xuất kho và chi tiết
            $productExport = $this->productExportService->createProductExportWithDetails($request->validated());

            return response()->json([
                'message' => 'Tạo phiếu xuất kho thành công',
                'status' => 201,
                'data' => $productExport,
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
            $productExport = $this->productExportService->getProductExportWithDetails($id);

            return response()->json(
                $productExport,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function destroy(ProductExport $productExport)
    {
        //
    }
}
