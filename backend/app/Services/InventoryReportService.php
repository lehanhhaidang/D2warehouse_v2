<?php

namespace App\Services;

use App\Models\InventoryReport;
use App\Repositories\InventoryReportRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryReportService
{

    protected $inventoryReportRepository;

    public function __construct(InventoryReportRepository $inventoryReportRepository)
    {
        $this->inventoryReportRepository = $inventoryReportRepository;
    }

    public function getAllInventoryReportWithDetails()
    {
        try {
            $inventoryReports = $this->inventoryReportRepository->getAllInventoryReportWithDetails();

            if ($inventoryReports->isEmpty()) {
                throw new \Exception('Hiện tại chưa có phiếu kiểm kê kho nào', 404);
            }

            return $inventoryReports->map(function ($inventoryReport) {
                return [
                    'id' => $inventoryReport->id,
                    'name' => $inventoryReport->name,
                    'warehouse_name' => $inventoryReport->warehouse ? $inventoryReport->warehouse->name : null,
                    'status' => $inventoryReport->status,
                    'description' => $inventoryReport->description,
                    'created_by' => $inventoryReport->user ? $inventoryReport->user->name : null,
                    'created_at' => $inventoryReport->created_at,
                    'updated_at' => $inventoryReport->updated_at,
                    'details' => $inventoryReport->inventoryReportDetails->map(function ($detail) {
                        return [
                            'inventory_report_id' => $detail->inventory_report_id,
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product->name ?? null,
                            'material_id' => $detail->material_id,
                            'material_name' => $detail->material->name ?? null,
                            'unit' => $detail->product->unit ?? $detail->material->unit ?? null,
                            'shelf_id' => $detail->shelf_id,
                            'shelf_name' => $detail->shelf->name ?? null,
                            'expected_quantity' => $detail->expected_quantity,
                            'actual_quantity' => $detail->actual_quantity,
                            'note' => $detail->note,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getInventoryReportWithDetails($id)
    {
        try {
            $inventoryReport = $this->inventoryReportRepository->getInventoryReportWithDetails($id);

            if (!$inventoryReport) {
                throw new \Exception('Hiện tại chưa có phiếu kiểm kê kho nào', 404);
            }
            return [
                'id' => $inventoryReport->id,
                'name' => $inventoryReport->name,
                'warehouse_name' => $inventoryReport->warehouse ? $inventoryReport->warehouse->name : null,
                'status' => $inventoryReport->status,
                'description' => $inventoryReport->description,
                'created_by' => $inventoryReport->user ? $inventoryReport->user->name : null,
                'created_at' => $inventoryReport->created_at,
                'updated_at' => $inventoryReport->updated_at,
                'details' => $inventoryReport->inventoryReportDetails->map(function ($detail) {
                    return [
                        'inventory_report_id' => $detail->inventory_report_id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name ?? null,
                        'material_id' => $detail->material_id,
                        'material_name' => $detail->material->name ?? null,
                        'unit' => $detail->product->unit ?? $detail->material->unit ?? null,
                        'shelf_id' => $detail->shelf_id,
                        'shelf_name' => $detail->shelf->name ?? null,
                        'expected_quantity' => $detail->expected_quantity,
                        'actual_quantity' => $detail->actual_quantity,
                        'note' => $detail->note,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function createInventoryReport(array $data)
    {
        try {
            $inventoryReportData = [
                'name' => $data['name'],
                'warehouse_id' => $data['warehouse_id'],
                'status' => $data['status'],
                'description' => $data['description'],
                'created_by' => Auth::id(),
                'created_at' => now(),
            ];

            return $this->inventoryReportRepository->createInventoryReport($inventoryReportData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function createInventoryReportDetails(int $inventoryReportId, array $detail)
    {
        try {

            $inventoryReportDetailData = [
                'inventory_report_id' => $inventoryReportId,
                'shelf_id' => $detail['shelf_id'],
                'expected_quantity' => $detail['expected_quantity'],
                'actual_quantity' => $detail['actual_quantity'],
                'note' => $detail['note'],
            ];

            if (isset($detail['product_id'])) {
                $inventoryReportDetailData['product_id'] = $detail['product_id'];
            } elseif (isset($detail['material_id'])) {
                $inventoryReportDetailData['material_id'] = $detail['material_id'];
            }

            return $this->inventoryReportRepository->createInventoryReportDetail($inventoryReportDetailData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function createInventoryReportWithDetails(array $data)
    {
        DB::beginTransaction();
        try {
            $inventoryReport = $this->createInventoryReport($data);

            foreach ($data['details'] as $detail) {
                $this->createInventoryReportDetails($inventoryReport->id, $detail);
            }

            DB::commit();

            return $inventoryReport;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    // public function updateInventoryReport(int $inventoryReportId, array $data)
    // {
    //     $data = [
    //         'actual_quantity' => $data['actual_quantity'],
    //     ];

    //     $this->inventoryReportRepository->updateInventoryReport($inventoryReportId, $data);
    // }

    // public function updateInventoryReportDetails(int $inventoryReportDetailId, array $data)
    // {
    //     $data = [
    //         'actual_quantity' => $data['actual_quantity'],
    //     ];

    //     $this->inventoryReportRepository->updateInventoryReportDetail($inventoryReportDetailId, $data);
    // }


    public function deleteInventoryReport($id)
    {
        try {
            $inventoryReport = InventoryReport::find($id);

            if (!$inventoryReport) {
                throw new \Exception("Không tìm thấy phiếu kiểm kê này", 404);
            }

            if ($inventoryReport->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền xóa phiếu kiểm kê này", 403);
            }

            return $this->inventoryReportRepository->deleteInventoryReport($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function sendInventoryReport($id)
    {
        try {
            $inventoryReport = InventoryReport::find($id);

            if (!$inventoryReport) {
                throw new \Exception('Không tìm thấy phiếu kiểm kê', 404);
            }
            $inventoryReport = $this->inventoryReportRepository->updateInventoryReport($id, ['status' => 1]);
            return $inventoryReport;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function confirmInventoryReport($id)
    {
        try {
            $inventoryReport = InventoryReport::find($id);

            if (!$inventoryReport) {
                throw new \Exception('Không tìm thấy phiếu kiểm kê', 404);
            }
            $inventoryReport = $this->inventoryReportRepository->updateInventoryReport($id, ['status' => 2]);
            return $inventoryReport;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function rejectInventoryReport($id)
    {
        try {
            $inventoryReport = InventoryReport::find($id);

            if (!$inventoryReport) {
                throw new \Exception('Không tìm thấy phiếu kiểm kê', 404);
            }
            $inventoryReport = $this->inventoryReportRepository->updateInventoryReport($id, ['status' => 3]);
            return $inventoryReport;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
