<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Product;
use App\Repositories\Interface\ProductMaterialFormulaRepositoryInterface;


class ProductMaterialFormulaService
{
    protected $productMaterialFormulaRepository;

    public function __construct(ProductMaterialFormulaRepositoryInterface $productMaterialFormulaRepository)
    {
        $this->productMaterialFormulaRepository = $productMaterialFormulaRepository;
    }

    public function calculateMaterials(array $products)
    {
        $result = [];

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $productQuantity = $product['product_quantity'];

            // Lấy dữ liệu từ repository
            $material = $this->productMaterialFormulaRepository->getProductFormula($productId);

            if ($material) {
                $materialQuantityNeeded = $productQuantity * $material->material_quantity / $material->product_quantity;

                // Thêm mới vào mảng kết quả mà không cần kiểm tra xem vật liệu đã tồn tại chưa
                $materialId = $material->material_id;
                $result[] = [
                    'product_id' => $productId,
                    'product_name' => Product::find($productId)->name,
                    'product_quantity' => $productQuantity,
                    'material_id' => $materialId,
                    'material_name' => Material::find($materialId)->name,
                    'material_quantity_needed' => $materialQuantityNeeded,
                    'unit' => 'KG',
                ];
            }
        }

        // Trả về mảng kết quả
        return $result;
    }
}
