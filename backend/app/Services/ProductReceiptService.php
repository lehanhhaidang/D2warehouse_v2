<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Propose;
use App\Models\Shelf;
use App\Repositories\Interface\ProductReceiptRepositoryInterface;
use App\Repositories\ProductReceiptRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReceiptService
{
    protected $productReceiptRepository;
    protected $proposeService;

    protected $manufacturingPlanService;

    public function __construct(
        ProductReceiptRepositoryInterface $productReceiptRepository,
        ProposeService $proposeService,
        ManufacturingPlanService $manufacturingPlanService

    ) {
        $this->productReceiptRepository = $productReceiptRepository;
        $this->proposeService = $proposeService;
        $this->manufacturingPlanService = $manufacturingPlanService;
    }

    public function getAllProductReceiptsWithDetails()
    {
        try {
            $productReceipts = $this->productReceiptRepository->getAllProductReceiptsWithDetails();

            // if ($productReceipts->isEmpty()) {
            //     throw new \Exception('Hiện tại chưa có phiếu nhập kho nào', 404);
            // }

            return $productReceipts->map(function ($productReceipt) {
                return [
                    'id' => $productReceipt->id,
                    'name' => $productReceipt->name,
                    'warehouse_id' => $productReceipt->warehouse_id,
                    'warehouse_name' => $productReceipt->warehouse ? $productReceipt->warehouse->name : null,
                    'receive_date' => $productReceipt->receive_date,
                    'status' => $productReceipt->status,
                    'note' => $productReceipt->note,
                    'propose_id' => $productReceipt->propose_id,
                    'propose_name' => $productReceipt->propose ? $productReceipt->propose->name : null,
                    'created_by' => $productReceipt->created_by,
                    'created_by_name' => $productReceipt->user ? $productReceipt->user->name : null,
                    'manufacturing_plan_id' => $productReceipt->propose ? $productReceipt->propose->manufacturing_plan_id : null,
                    'manufacturing_plan_name' => $productReceipt->propose->manufacturingPlan ? $productReceipt->propose->manufacturingPlan->name : null,
                    'created_at' => $productReceipt->created_at,
                    'updated_at' => $productReceipt->updated_at,
                    'details' => $productReceipt->details->map(function ($detail) {
                        return [
                            'product_receipt_id' => $detail->product_receipt_id,
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

    public function getProductReceiptWithDetails($id)
    {
        try {
            $productReceipt = $this->productReceiptRepository->getProductReceiptWithDetails($id);

            if (!$productReceipt) {
                throw new \Exception('Không tìm thấy phiếu nhập kho này', 404);
            }

            return [
                'id' => $productReceipt->id,
                'name' => $productReceipt->name,
                'warehouse_id' => $productReceipt->warehouse_id,
                'warehouse_name' => $productReceipt->warehouse ? $productReceipt->warehouse->name : null,
                'receive_date' => $productReceipt->receive_date,
                'status' => $productReceipt->status,
                'note' => $productReceipt->note,
                'propose_id' => $productReceipt->propose_id,
                'propose_name' => $productReceipt->propose ? $productReceipt->propose->name : null,
                'created_by' => $productReceipt->created_by,
                'created_by_name' => $productReceipt->user ? $productReceipt->user->name : null,
                'manufacturing_plan_id' => $productReceipt->propose ? $productReceipt->propose->manufacturing_plan_id : null,
                'manufacturing_plan_name' => $productReceipt->propose->manufacturingPlan ? $productReceipt->propose->manufacturingPlan->name : null,
                'created_at' => $productReceipt->created_at,
                'updated_at' => $productReceipt->updated_at,
                'details' => $productReceipt->details->map(function ($detail) {
                    return [
                        'product_receipt_id' => $detail->product_receipt_id,
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



    // Tách phương thức xử lý từng chi tiết
    protected function processProductReceiptDetail($detail, $productReceiptId)
    {
        // Lấy thông tin của kệ
        $shelf = Shelf::find($detail['shelf_id']);
        if (!$shelf) {
            throw new \Exception('Kệ không tồn tại');
        }

        // Lấy tổng số lượng hiện có trên kệ cho tất cả sản phẩm
        $existingShelfDetails = $this->productReceiptRepository->getShelfDetails($detail['shelf_id']);
        $currentTotalQuantity = 0;
        foreach ($existingShelfDetails as $existingDetail) {
            $currentTotalQuantity += $existingDetail->quantity; // Cộng dồn số lượng hiện có
        }


        // Lấy số lượng hiện có cho sản phẩm đang nhập
        $existingShelfDetail = $this->productReceiptRepository->findShelfDetail($detail['shelf_id'], $detail['product_id']);
        $currentQuantity = $existingShelfDetail ? $existingShelfDetail->quantity : 0;

        // Tính tổng số lượng cho sản phẩm hiện tại sau khi thêm mới
        $newQuantity = $currentQuantity + $detail['quantity'];

        // Kiểm tra tổng số lượng nếu sản phẩm mới được thêm vào
        $totalQuantityAfterAdd = $currentTotalQuantity + $detail['quantity'];
        if ($totalQuantityAfterAdd > $shelf->storage_capacity) {
            throw new \Exception('Số lượng lưu trữ vượt quá giới hạn của kệ (' . $shelf->storage_capacity . '),', 400);
        }

        // Cập nhật hoặc tạo mới chi tiết sản phẩm trên kệ (shelf_details)
        if ($existingShelfDetail) {
            // Nếu sản phẩm đã tồn tại trên kệ, cập nhật số lượng
            $this->productReceiptRepository->updateShelfDetailQuantity($existingShelfDetail->id, $newQuantity);
        } else {
            // Nếu sản phẩm chưa tồn tại, tạo mới shelf_detail
            $shelfDetail = [
                'shelf_id' => $detail['shelf_id'],
                'product_id' => $detail['product_id'],
                'material_id' => null,
                'quantity' => $detail['quantity'], // Lưu đúng số lượng từ detail
            ];

            $this->productReceiptRepository->createShelfDetail($shelfDetail);
        }

        // Thêm chi tiết phiếu nhập kho vào bảng product_receipt_details
        $detail['product_receipt_id'] = $productReceiptId;
        $this->productReceiptRepository->createProductReceiptDetail($detail);

        // Cập nhật số lượng sản phẩm trong bảng products
        $this->productReceiptRepository->updateProductQuantity($detail['product_id'], $detail['quantity']);
    }




    // Phương thức để tạo phiếu nhập kho mới với chi tiết
    public function createProductReceiptWithDetails(array $data)
    {
        // Bắt đầu transaction
        DB::beginTransaction();

        try {

            $data['created_by'] = Auth::id();

            $propose_status = Propose::find($data['propose_id'])->status;

            if ($data['created_by'] !== Propose::find($data['propose_id'])->created_by) {
                throw new \Exception('Bạn không có quyền tạo phiếu nhập kho thành phẩm được giao cho người khác', 400);
            }

            if ($propose_status === 0 || $propose_status === 1) {
                throw new \Exception('Đề xuất chưa được duyệt, không thể lập phiếu nhập kho', 400);
            }
            if ($propose_status === 3) {
                throw new \Exception('Đề xuất này đã bị từ chối, không thể tạo phiếu nhập kho', 400);
            }
            // Tạo phiếu nhập kho

            $productReceipt = $this->productReceiptRepository->createProductReceipt($data);
            foreach ($data['details'] as $detail) {
                $this->processProductReceiptDetail($detail, $productReceipt->id);
            }

            $this->proposeService->handlePropose($data['propose_id'], 4);

            $manufacturingPlanId = Propose::find($data['propose_id'])->manufacturing_plan_id;

            $this->manufacturingPlanService->updateStatusManufacturingPlan($manufacturingPlanId, ['status' => 7]);


            DB::commit();

            return $productReceipt;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
