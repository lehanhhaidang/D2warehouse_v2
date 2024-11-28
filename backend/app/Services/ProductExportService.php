<?php

namespace App\Services;

use App\Models\Propose;
use App\Models\Shelf;
use App\Repositories\Interface\ProductExportRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductExportService
{

    protected $productExportRepository;

    public function __construct(
        ProductExportRepositoryInterface $productExportRepository
    ) {
        $this->productExportRepository = $productExportRepository;
    }


    public function getAllProductExportsWithDetails()
    {
        try {
            $productExports = $this->productExportRepository->getAllProductExportsWithDetails();

            // if ($productExports->isEmpty()) {
            //     throw new \Exception('Hiện tại chưa có phiếu xuất kho nào', 404);
            // }

            return $productExports->map(function ($productExport) {
                return [
                    'id' => $productExport->id,
                    'name' => $productExport->name,
                    'warehouse_name' => $productExport->warehouse ? $productExport->warehouse->name : null,
                    'export_date' => $productExport->export_date,
                    'status' => $productExport->status,
                    'note' => $productExport->note,
                    'propose_id' => $productExport->propose_id,
                    'propose_name' => $productExport->propose ? $productExport->propose->name : null,
                    'created_by' => $productExport->created_by,
                    'created_by_name' => $productExport->user ? $productExport->user->name : null,
                    'created_at' => $productExport->created_at,
                    'details' => $productExport->details->map(function ($detail) {
                        return [
                            'product_export_id' => $detail->product_export_id,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product->name,
                            'category_id' => $detail->product->category_id,
                            'category_name' => $detail->product->category->name,
                            'shelf_id' => $detail->shelf_id,
                            'shelf_name' => $detail->shelf->name,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function getProductExportWithDetails($id)
    {
        try {
            $productExport = $this->productExportRepository->getProductExportWithDetails($id);

            if (!$productExport) {
                throw new \Exception('Không tìm thấy phiếu xuất kho', 404);
            }

            return [
                'id' => $productExport->id,
                'name' => $productExport->name,
                'warehouse_name' => $productExport->warehouse ? $productExport->warehouse->name : null,
                'export_date' => $productExport->export_date,
                'status' => $productExport->status,
                'note' => $productExport->note,
                'propose_id' => $productExport->propose_id,
                'propose_name' => $productExport->propose ? $productExport->propose->name : null,
                'created_by' => $productExport->created_by,
                'created_by_name' => $productExport->user ? $productExport->user->name : null,
                'created_at' => $productExport->created_at,
                'details' => $productExport->details->map(function ($detail) {
                    return [
                        'product_export_id' => $detail->product_export_id,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name,
                        'category_id' => $detail->product->category_id,
                        'category_name' => $detail->product->category->name,
                        'shelf_id' => $detail->shelf_id,
                        'shelf_name' => $detail->shelf->name,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function processProductExportDetail($detail, $productExportId)
    {
        // Lấy thông tin của kệ
        $shelf = Shelf::find($detail['shelf_id']);
        if (!$shelf) {
            throw new \Exception('Kệ không tồn tại');
        }

        // Lấy chi tiết sản phẩm trên kệ
        $existingShelfDetail = $this->productExportRepository->findShelfDetail($detail['shelf_id'], $detail['product_id']);
        if (!$existingShelfDetail || $existingShelfDetail->quantity < $detail['quantity']) {
            throw new \Exception('Số lượng sản phẩm không đủ trên kệ để xuất');
        } else {
            $shelfDetails = [
                'shelf_id' => $detail['shelf_id'],
                'product_id' => $detail['product_id'],
                'material_id' => null,
                'quantity' => $detail['quantity'],

            ];

            // Tạo chi tiết sản phẩm trên kệ
            // $this->productExportRepository->createShelfDetail($shelfDetails);

            $newQuantity = $existingShelfDetail->quantity - $detail['quantity'];

            if ($newQuantity > 0) {
                $this->productExportRepository->updateShelfDetailQuantity($existingShelfDetail->id, $newQuantity);
            } else {
                $this->productExportRepository->deleteShelfDetail($existingShelfDetail->id);
            }
        }

        // Tính toán số lượng sau khi xuất


        // Thêm chi tiết phiếu xuất kho vào bảng product_export_details
        $detail['product_export_id'] = $productExportId;
        $this->productExportRepository->createProductExportDetail($detail);

        // Cập nhật số lượng sản phẩm trong bảng products
        $this->productExportRepository->updateProductQuantity($detail['product_id'], $detail['quantity']);
    }

    public function createProductExportWithDetails(array $data)
    {
        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            $data['created_by'] = Auth::id();
            $propose_status = Propose::find($data['propose_id'])->status;

            if ($data['created_by'] !== Propose::find($data['propose_id'])->created_by) {
                throw new \Exception('Bạn không có quyền tạo phiếu xuất kho thành phẩm được giao cho người khác', 400);
            }

            if ($propose_status === 0 || $propose_status === 1) {
                throw new \Exception('Đề xuất chưa được duyệt, không thể lập phiếu nhập kho', 400);
            }
            if ($propose_status === 3) {
                throw new \Exception('Đề xuất này đã bị từ chối, không thể tạo phiếu nhập kho', 400);
            }
            // Tạo phiếu xuất kho
            $productExport = $this->productExportRepository->createProductExport($data);

            // Duyệt qua từng detail để xử lý
            foreach ($data['details'] as $detail) {
                $this->processProductExportDetail($detail, $productExport->id);
            }

            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $productExport;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
