<?php

namespace App\Http\Controllers;

use App\Services\ProductMaterialFormulaService;
use Illuminate\Http\Request;

class ProductMaterialFormulaController extends Controller
{
    protected $productMaterialFormulaService;

    public function __construct(ProductMaterialFormulaService $productMaterialFormulaService)
    {
        $this->productMaterialFormulaService = $productMaterialFormulaService;
    }

    public function calculateMaterials(Request $request)
    {
        // Không validate dữ liệu, chỉ chuyển tiếp tới Service
        $products = $request->input('products');

        // Gọi Service để xử lý
        $result = $this->productMaterialFormulaService->calculateMaterials($products);

        return response()->json($result);
    }
}
