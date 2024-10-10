<?php

namespace App\Http\Controllers;

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


    public function index()
    {
        try {
            $productReceipts = $this->productReceiptService->getAllProductReceiptsWithDetails();

            return response()->json($productReceipts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    public function show($id)
    {
        try {
            $productReceipt = $this->productReceiptService->getProductReceiptWithDetails($id);

            return response()->json($productReceipt, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    public function store(StoreProductReceiptRequest $request): JsonResponse
    {
        try {
            // Tạo phiếu nhập kho và chi tiết
            $productReceipt = $this->productReceiptService->createProductReceiptWithDetails($request->validated());

            return response()->json([
                'message' => 'Tạo phiếu nhập kho thành công',
                'data' => $productReceipt,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình tạo phiếu nhập kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
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
