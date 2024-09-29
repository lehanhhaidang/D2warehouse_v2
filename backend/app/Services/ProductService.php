<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function storeProduct($request)
    {
        try {
            // Tạo thư mục chứa ảnh sản phẩm nếu chưa tồn tại
            $directory = 'images/products';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Xử lý lưu ảnh
            $imagePath = null;
            if ($request->hasFile('product_img') && $request->file('product_img')->isValid()) {
                $imagePath = $request->file('product_img')->store($directory, 'public');
            }

            // Dữ liệu sản phẩm
            $data = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'color_id' => $request->color_id,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'product_img' => $imagePath,
                'status' => $request->status,
            ];

            // Tạo sản phẩm thông qua repository
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm thành phẩm: ' . $e->getMessage());
            throw new \Exception('Thêm thành phẩm thất bại');
        }
    }


    public function updateProduct($id, $request)
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new \Exception('Không tìm thấy thành phẩm');
            }

            // Xử lý hình ảnh sản phẩm
            $imagePath = $product->product_img;
            if ($request->hasFile('product_img') && $request->file('product_img')->isValid()) {
                // Xóa ảnh cũ nếu có
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                // Lưu ảnh mới
                $imagePath = $request->file('product_img')->store('images/products', 'public');
            }

            // Cập nhật dữ liệu sản phẩm
            $data = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'color_id' => $request->color_id,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'product_img' => $imagePath,
                'status' => $request->status,
            ];

            return $this->productRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật thành phẩm: ' . $e->getMessage());
            throw new \Exception('Cập nhật thành phẩm thất bại');
        }
    }
}