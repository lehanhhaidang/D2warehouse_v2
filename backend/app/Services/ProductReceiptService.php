<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shelf;
use App\Repositories\Interface\ProductReceiptRepositoryInterface;
use App\Repositories\ProductReceiptRepository;
use Illuminate\Support\Facades\DB;

class ProductReceiptService
{
    protected $productReceiptRepository;

    public function __construct(
        ProductReceiptRepositoryInterface $productReceiptRepository
    ) {
        $this->productReceiptRepository = $productReceiptRepository;
    }

    public function validateShelfAndProductCategory($details)
    {
        foreach ($details as $detail) {
            $product = Product::find($detail['product_id']);
            $shelf = Shelf::find($detail['shelf_id']);

            if ($product->category_id !== $shelf->category_id) {
                return false; // Không trùng category_id
            }
        }
        return true; // Trùng category_id
    }

    public function createProductReceiptWithDetails(array $data)
    {
        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Tạo phiếu nhập kho
            $productReceipt = $this->productReceiptRepository->createProductReceipt($data);

            // Duyệt qua từng detail để thêm chi tiết vào phiếu nhập kho
            foreach ($data['details'] as $detail) {
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
                    throw new \Exception('Số lượng lưu trữ vượt quá giới hạn của kệ(' . $shelf->storage_capacity . '), tổng số lượng hiện có: ' . $currentTotalQuantity);
                }

                // Cập nhật số lượng trong bảng shelf_details
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
                $detail['product_receipt_id'] = $productReceipt->id;
                $this->productReceiptRepository->createProductReceiptDetail($detail);

                // Cập nhật số lượng sản phẩm trong bảng products
                $this->productReceiptRepository->updateProductQuantity($detail['product_id'], $detail['quantity']);
            }

            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $productReceipt;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
