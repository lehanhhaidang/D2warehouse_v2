<?php

namespace App\Services;

use App\Models\Shelf;
use App\Repositories\Interface\MaterialExportRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialExportService
{

    protected $materialExportRepository;

    public function __construct(
        MaterialExportRepositoryInterface $materialExportRepository
    ) {
        $this->materialExportRepository = $materialExportRepository;
    }


    public function getAllMaterialExportsWithDetails()
    {
        try {
            $materialExports = $this->materialExportRepository->getAllMaterialExportsWithDetails();

            if ($materialExports->isEmpty()) {
                throw new \Exception('Hiện tại chưa có phiếu xuất kho nào', 404);
            }

            return $materialExports->map(function ($materialExport) {
                return [
                    'id' => $materialExport->id,
                    'name' => $materialExport->name,
                    'warehouse_name' => $materialExport->warehouse ? $materialExport->warehouse->name : null,
                    'export_date' => $materialExport->export_date,
                    'status' => $materialExport->status,
                    'note' => $materialExport->note,
                    'created_by' => $materialExport->user ? $materialExport->user->name : null,
                    'created_at' => $materialExport->created_at,
                    'details' => $materialExport->details->map(function ($detail) {
                        return [
                            'material_export_id' => $detail->material_export_id,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
                            'material_name' => $detail->material->name,
                            'category_name' => $detail->material->category->name,
                            'shelf_name' => $detail->shelf->name,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function getMaterialExportWithDetails($id)
    {
        try {
            $materialExport = $this->materialExportRepository->getMaterialExportWithDetails($id);

            if (!$materialExport) {
                throw new \Exception('Không tìm thấy phiếu xuất kho', 404);
            }

            return [
                'id' => $materialExport->id,
                'name' => $materialExport->name,
                'warehouse_name' => $materialExport->warehouse ? $materialExport->warehouse->name : null,
                'export_date' => $materialExport->export_date,
                'status' => $materialExport->status,
                'note' => $materialExport->note,
                'created_by' => $materialExport->user ? $materialExport->user->name : null,
                'created_at' => $materialExport->created_at,
                'details' => $materialExport->details->map(function ($detail) {
                    return [
                        'material_export_id' => $detail->material_export_id,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'material_name' => $detail->material->name,
                        'category_name' => $detail->material->category->name,
                        'shelf_name' => $detail->shelf->name,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function processMaterialExportDetail($detail, $materialExportId)
    {
        // Lấy thông tin của kệ
        $shelf = Shelf::find($detail['shelf_id']);
        if (!$shelf) {
            throw new \Exception('Kệ không tồn tại');
        }

        // Lấy chi tiết sản phẩm trên kệ
        $existingShelfDetail = $this->materialExportRepository->findShelfDetail($detail['shelf_id'], $detail['material_id']);
        if (!$existingShelfDetail || $existingShelfDetail->quantity < $detail['quantity']) {
            throw new \Exception('Số lượng nguyên vật liệu không đủ trên kệ để xuất');
        } else {
            $shelfDetails = [
                'shelf_id' => $detail['shelf_id'],
                'material_id' => $detail['material_id'],
                'product_id' => null,
                'quantity' => $detail['quantity'],

            ];

            // Tạo chi tiết sản phẩm trên kệ
            // $this->materialExportRepository->createShelfDetail($shelfDetails);

            $newQuantity = $existingShelfDetail->quantity - $detail['quantity'];

            if ($newQuantity > 0) {
                $this->materialExportRepository->updateShelfDetailQuantity($existingShelfDetail->id, $newQuantity);
            } else {
                $this->materialExportRepository->deleteShelfDetail($existingShelfDetail->id);
            }
        }

        // Tính toán số lượng sau khi xuất
        // $newQuantity = $existingShelfDetail->quantity - $detail['quantity'];

        // Cập nhật chi tiết sản phẩm trên kệ
        $this->materialExportRepository->updateShelfDetailQuantity($existingShelfDetail->id, $newQuantity);

        // Thêm chi tiết phiếu xuất kho vào bảng material_export_details
        $detail['material_export_id'] = $materialExportId;
        $this->materialExportRepository->createMaterialExportDetail($detail);

        // Cập nhật số lượng sản phẩm trong bảng materials
        $this->materialExportRepository->updateMaterialQuantity($detail['material_id'], $detail['quantity']);
    }

    public function creatematerialExportWithDetails(array $data)
    {
        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            $data['created_by'] = Auth::id();
            // Tạo phiếu xuất kho
            $materialExport = $this->materialExportRepository->createMaterialExport($data);

            // Duyệt qua từng detail để xử lý
            foreach ($data['details'] as $detail) {
                $this->processMaterialExportDetail($detail, $materialExport->id);
            }

            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $materialExport;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
