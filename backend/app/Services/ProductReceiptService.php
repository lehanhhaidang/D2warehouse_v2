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

            // Thêm chi tiết phiếu nhập kho và lưu vào bảng shelf_details
            foreach ($data['details'] as $detail) {
                $detail['product_receipt_id'] = $productReceipt->id;
                $this->productReceiptRepository->createProductReceiptDetail($detail);

                // Lưu vào bảng shelf_details
                $shelfDetail = [
                    'shelf_id' => $detail['shelf_id'],
                    'product_id' => $detail['product_id'],
                    'material_id' => null,
                    'quantity' => $detail['quantity'],
                ];
                $this->productReceiptRepository->createShelfDetail($shelfDetail);

                // Cập nhật số lượng sản phẩm
                $this->productReceiptRepository->updateProductQuantity($detail['product_id'], $detail['quantity']);
            }

            // Commit transaction khi tất cả các thao tác thành công
            DB::commit();

            return $productReceipt;
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi
            DB::rollBack();
            throw $e; // Ném lỗi ra ngoài để controller xử lý
        }
    }
}
