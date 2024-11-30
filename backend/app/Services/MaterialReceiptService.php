<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Propose;
use App\Models\Shelf;
use App\Repositories\Interface\MaterialReceiptRepositoryInterface;
use App\Repositories\MaterialReceiptRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialReceiptService
{
    protected $materialReceiptRepository;
    protected $proposeService;

    public function __construct(
        MaterialReceiptRepositoryInterface $materialReceiptRepository,
        ProposeService $proposeService
    ) {
        $this->materialReceiptRepository = $materialReceiptRepository;
        $this->proposeService = $proposeService;
    }

    public function getAllMaterialReceiptsWithDetails()
    {
        try {
            $materialReceipts = $this->materialReceiptRepository->getAllMaterialReceiptsWithDetails();

            // if ($materialReceipts->isEmpty()) {
            //     throw new \Exception('Hiện tại chưa có phiếu nhập kho nào', 404);
            // }

            return $materialReceipts->map(function ($materialReceipt) {
                return [
                    'id' => $materialReceipt->id,
                    'name' => $materialReceipt->name,
                    'warehouse_name' => $materialReceipt->warehouse ? $materialReceipt->warehouse->name : null,
                    'receive_date' => $materialReceipt->receive_date,
                    'status' => $materialReceipt->status,
                    'note' => $materialReceipt->note,
                    'propose_id' => $materialReceipt->propose_id,
                    'propose_name' => $materialReceipt->propose ? $materialReceipt->propose->name : null,
                    'created_by' => $materialReceipt->created_by,
                    'created_by_name' => $materialReceipt->user ? $materialReceipt->user->name : null,
                    'created_at' => $materialReceipt->created_at,
                    'updated_at' => $materialReceipt->updated_at,
                    'details' => $materialReceipt->details->map(function ($detail) {
                        return [
                            'material_receipt_id' => $detail->material_receipt_id,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
                            'material_id' => $detail->material_id,
                            'material_name' => $detail->material->name,
                            'category_id' => $detail->material->category_id,
                            'category_name' => $detail->material->category->name,
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
                'propose_id' => $materialReceipt->propose_id,
                'propose_name' => $materialReceipt->propose ? $materialReceipt->propose->name : null,
                'created_by' => $materialReceipt->created_by,
                'created_by_name' => $materialReceipt->user ? $materialReceipt->user->name : null,
                'created_at' => $materialReceipt->created_at,
                'updated_at' => $materialReceipt->updated_at,
                'details' => $materialReceipt->details->map(function ($detail) {
                    return [
                        'material_receipt_id' => $detail->material_receipt_id,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'material_id' => $detail->material_id,
                        'material_name' => $detail->material->name,
                        'category_id' => $detail->material->category_id,
                        'category_name' => $detail->material->category->name,
                        'shelf_id' => $detail->shelf_id,
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
            $propose_status = Propose::find($data['propose_id'])->status;

            if ($data['created_by'] !== Propose::find($data['propose_id'])->assigned_to) {
                throw new \Exception('Bạn không có quyền tạo phiếu nhập kho nguyên vật liệu được giao cho người khác', 400);
            }
            if ($propose_status === 0 || $propose_status === 1) {
                throw new \Exception('Đề xuất chưa được duyệt, không thể lập phiếu nhập kho', 400);
            }
            if ($propose_status === 3) {
                throw new \Exception('Đề xuất này đã bị từ chối, không thể tạo phiếu nhập kho', 400);
            }
            // Tạo phiếu nhập kho
            $MaterialReceipt = $this->materialReceiptRepository->createMaterialReceipt($data);

            // Duyệt qua từng detail để xử lý
            foreach ($data['details'] as $detail) {
                $this->processMaterialReceiptDetail($detail, $MaterialReceipt->id);
            }

            $this->proposeService->handlePropose($data['propose_id'], 4);
            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $MaterialReceipt;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
