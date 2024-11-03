<?php

namespace App\Http\Controllers;

use App\Events\InventoryReport\InventoryReportCreated;
use App\Http\Requests\InventoryReport\InventoryReportRequest;
use App\Models\InventoryReport;
use App\Services\InventoryReportService;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    protected $inventoryReportService;

    public function __construct(InventoryReportService $inventoryReportService)
    {
        $this->inventoryReportService = $inventoryReportService;
    }
    public function index()
    {
        try {
            $inventoryReports = $this->inventoryReportService->getAllInventoryReportWithDetails();
            return response()->json([
                'data' => $inventoryReports,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode() ?: 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryReportRequest $request)
    {
        try {
            $inventoryReport = $this->inventoryReportService->createInventoryReportWithDetails($request->all());

            event(new InventoryReportCreated($inventoryReport));

            return response()->json([
                'message' => 'Tạo phiếu kiểm kê kho thành công',
                'status' => 200,
                'data' => $inventoryReport,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi tạo phiếu kiểm kê kho',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $inventoryReport = $this->inventoryReportService->getInventoryReportWithDetails($id);
            return response()->json([
                'data' => $inventoryReport,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode() ?: 500);
        }
    }


    public function edit(InventoryReport $inventoryReport)
    {
        //
    }


    public function update(Request $request, InventoryReport $inventoryReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryReport $inventoryReport)
    {
        //
    }
}
