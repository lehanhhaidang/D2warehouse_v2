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
            $productReceipts = $this->productReceiptRepository->getAllProductReceiptsWithDetails();

            if (empty($productReceipts)) {
                return response()->json([
                    'message' => 'Hiện tại chưa có phiếu nhập kho nào',
                    'status' => 404
                ], 404);
            }

            $response = $productReceipts->map(function ($productReceipt) {
                return [
                    'id' => $productReceipt->id,
                    'name' => $productReceipt->name,
                    'warehouse_name' => $productReceipt->warehouse ? $productReceipt->warehouse->name : null,
                    'receive_date' => $productReceipt->receive_date,
                    'status' => $productReceipt->status,
                    'note' => $productReceipt->note,
                    'created_by' => $productReceipt->user ? $productReceipt->user->name : null,
                    'created_at' => $productReceipt->created_at,
                    'updated_at' => $productReceipt->updated_at,
                    'details' => $productReceipt->details->map(function ($detail) {
                        return [
                            'product_receipt_id' => $detail->product_receipt_id,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
                            'product_name' => $detail->product->name,
                            'category_name' => $detail->product->category->name,
                            'shelf_name' => $detail->shelf->name,
                        ];
                    }),
                ];
            });

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            // Lấy phiếu nhập kho cùng với chi tiết bằng repository
            $productReceipt = $this->productReceiptRepository->getProductReceiptsWithDetails($id);

            // Kiểm tra nếu không tìm thấy phiếu nhập kho
            if (!$productReceipt) {
                return response()->json([
                    'message' => 'Phiếu nhập kho không tồn tại',
                    'status' => 404
                ], 404);
            }

            // Tạo response từ một đối tượng duy nhất
            $response = [
                'id' => $productReceipt->id,
                'name' => $productReceipt->name,
                'warehouse_name' => $productReceipt->warehouse ? $productReceipt->warehouse->name : null,
                'receive_date' => $productReceipt->receive_date,
                'status' => $productReceipt->status,
                'note' => $productReceipt->note,
                'user_name' => $productReceipt->user ? $productReceipt->user->name : null,
                'created_at' => $productReceipt->created_at,
                'updated_at' => $productReceipt->updated_at,
                'details' => $productReceipt->details->map(function ($detail) {
                    return [
                        'product_receipt_id' => $detail->product_receipt_id,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'product_name' => $detail->product->name,
                        'category_name' => $detail->product->category->name,
                        'shelf_name' => $detail->shelf->name,
                    ];
                }),
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductReceiptRequest $request): JsonResponse
    {
        // Tạo phiếu nhập kho bằng repository
        try {
            // Kiểm tra category_id của kệ và sản phẩm
            if (!$this->productReceiptService->validateShelfAndProductCategory($request->details)) {
                return response()->json([
                    'message' => 'Kệ và sản phẩm không có cùng loại danh mục.',
                ], 400);
            }

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
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReceipt $productReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReceipt $productReceipt)
    {
        //
    }
}
