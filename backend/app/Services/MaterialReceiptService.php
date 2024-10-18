<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Shelf;
use App\Repositories\Interface\MaterialReceiptRepositoryInterface;
use App\Repositories\MaterialReceiptRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialReceiptService
{
    protected $materialReceiptRepository;

    public function __construct(
        MaterialReceiptRepositoryInterface $materialReceiptRepository
    ) {
        $this->materialReceiptRepository = $materialReceiptRepository;
    }

    public function getAllMaterialReceiptsWithDetails()
    {
        try {
            $materialReceipts = $this->materialReceiptRepository->getAllMaterialReceiptsWithDetails();

            if ($materialReceipts->isEmpty()) {
                throw new \Exception('Hiện tại chưa có phiếu nhập kho nào', 404);
            }

            return $materialReceipts->map(function ($materialReceipt) {
                return [
                    'id' => $materialReceipt->id,
                    'name' => $materialReceipt->name,
                    'warehouse_name' => $materialReceipt->warehouse ? $materialReceipt->warehouse->name : null,
                    'receive_date' => $materialReceipt->receive_date,
                    'status' => $materialReceipt->status,
                    'note' => $materialReceipt->note,
                    'created_by' => $materialReceipt->user ? $materialReceipt->user->name : null,
                    'created_at' => $materialReceipt->created_at,
                    'updated_at' => $materialReceipt->updated_at,
                    'details' => $materialReceipt->details->map(function ($detail) {
                        return [
                            'material_receipt_id' => $detail->material_receipt_id,
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

    public function getMaterialReceiptWithDetails($id)
    {
        try {
            $materialReceipt = $this->materialReceiptRepository->getMaterialReceiptWithDetails($id);

            if (!$materialReceipt) {
                throw new \Exception('Không tìm thấy phiếu nhập kho này', 404);
            }

            return [
                'id' => $materialReceipt->id,
                'name' => $materialReceipt->name,
                'warehouse_name' => $materialReceipt->warehouse ? $materialReceipt->warehouse->name : null,
                'receive_date' => $materialReceipt->receive_date,
                'status' => $materialReceipt->status,
                'note' => $materialReceipt->note,
                'created_by' => $materialReceipt->user ? $materialReceipt->user->name : null,
                'created_at' => $materialReceipt->created_at,
                'updated_at' => $materialReceipt->updated_at,
                'details' => $materialReceipt->details->map(function ($detail) {
                    return [
                        'material_receipt_id' => $detail->material_receipt_id,
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


    // Tách phương thức xử lý từng chi tiết
    protected function processMaterialReceiptDetail($detail, $materialReceiptId)
    {
        // Lấy thông tin của kệ
        $shelf = Shelf::find($detail['shelf_id']);
        if (!$shelf) {
            throw new \Exception('Kệ không tồn tại');
        }

        // Lấy tổng số lượng hiện có trên kệ cho tất cả nguyên vật liệu
        $existingShelfDetails = $this->materialReceiptRepository->getShelfDetails($detail['shelf_id']);
        $currentTotalQuantity = 0;
        foreach ($existingShelfDetails as $existingDetail) {
            $currentTotalQuantity += $existingDetail->quantity; // Cộng dồn số lượng hiện có
        }

        // Lấy số lượng hiện có cho nguyên vật liệu đang nhập
        $existingShelfDetail = $this->materialReceiptRepository->findShelfDetail($detail['shelf_id'], $detail['material_id']);
        $currentQuantity = $existingShelfDetail ? $existingShelfDetail->quantity : 0;

        // Tính tổng số lượng cho nguyên vật liệu hiện tại sau khi thêm mới
        $newQuantity = $currentQuantity + $detail['quantity'];

        // Kiểm tra tổng số lượng nếu nguyên vật liệu mới được thêm vào
        $totalQuantityAfterAdd = $currentTotalQuantity + $detail['quantity'];
        if ($totalQuantityAfterAdd > $shelf->storage_capacity) {
            throw new \Exception('Số lượng lưu trữ vượt quá giới hạn của kệ(' . $shelf->storage_capacity . '), tổng số lượng hiện có: ' . $currentTotalQuantity);
        }

        // Cập nhật hoặc tạo mới chi tiết nguyên vật liệu trên kệ (shelf_details)
        if ($existingShelfDetail) {
            // Nếu nguyên vật liệu đã tồn tại trên kệ, cập nhật số lượng
            $this->materialReceiptRepository->updateShelfDetailQuantity($existingShelfDetail->id, $newQuantity);
        } else {
            // Nếu nguyên vật liệu chưa tồn tại, tạo mới shelf_detail
            $shelfDetail = [
                'shelf_id' => $detail['shelf_id'],
                'material_id' => $detail['material_id'],
                'product_id' => null,
                'quantity' => $detail['quantity'], // Lưu đúng số lượng từ detail
            ];

            $this->materialReceiptRepository->createShelfDetail($shelfDetail);
        }

        // Thêm chi tiết phiếu nhập kho vào bảng Material_receipt_details
        $detail['material_receipt_id'] = $materialReceiptId;
        $this->materialReceiptRepository->createMaterialReceiptDetail($detail);

        // Cập nhật số lượng nguyên vật liệu trong bảng Materials
        $this->materialReceiptRepository->updateMaterialQuantity($detail['material_id'], $detail['quantity']);
    }




    // Phương thức để tạo phiếu nhập kho mới với chi tiết
    public function createMaterialReceiptWithDetails(array $data)
    {
        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            $data['created_by'] = Auth::id();
            // Tạo phiếu nhập kho
            $MaterialReceipt = $this->materialReceiptRepository->createMaterialReceipt($data);

            // Duyệt qua từng detail để xử lý
            foreach ($data['details'] as $detail) {
                $this->processMaterialReceiptDetail($detail, $MaterialReceipt->id);
            }

            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $MaterialReceipt;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
