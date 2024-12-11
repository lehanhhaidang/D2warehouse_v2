<?php

namespace App\Repositories;

use App\Repositories\Interface\ProductMaterialFormulaRepositoryInterface;
use App\Models\ProductMaterialFormula;

class ProductMaterialFormulaRepository implements ProductMaterialFormulaRepositoryInterface
{

    public function getProductFormula($product_id)
    {
        return ProductMaterialFormula::where('product_id', $product_id)->first();
    }
}
