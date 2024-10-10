<?php

namespace App\Http\Controllers;

use App\Models\MaterialReceipt;
use App\Repositories\Interface\MaterialReceiptRepositoryInterface;
use App\Services\MaterialReceiptService;
use Illuminate\Http\Request;

class MaterialReceiptController extends Controller
{

    protected $materialReceiptRepository;
    protected $materialReceiptService;

    public function __construct(
        MaterialReceiptRepositoryInterface $materialReceiptRepository,
        MaterialReceiptService $materialReceiptService
    ) {
        $this->materialReceiptRepository = $materialReceiptRepository;
        $this->materialReceiptService = $materialReceiptService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $materialReceipts = $this->materialReceiptService->getAllMaterialReceiptsWithDetails();

            return response()->json($materialReceipts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Tạo phiếu nhập kho và chi tiết
            $productReceipt = $this->materialReceiptService->createMaterialReceiptWithDetails($request->validated());

            return response()->json([
                'message' => 'Tạo phiếu nhập kho thành công',
                'status' => 201,
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
}
