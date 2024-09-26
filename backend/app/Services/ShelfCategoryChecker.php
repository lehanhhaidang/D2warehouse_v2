<?php

namespace App\Services;

use App\Models\Shelf;

class ShelfCategoryChecker
{
    // Kiểm tra xem shelf có thuộc category hay không
    public function checkShelfBelongsToCategory($shelf_id, $category_id)
    {
        $shelf = Shelf::find($shelf_id);

        // Kiểm tra xem shelf có tồn tại và có phù hợp với category hay không
        if ($shelf && $shelf->category_id != $category_id) {
            return false;
        }

        return true;
    }

    public function getShelvesByCategory($categoryId)
    {
        // Lấy danh sách tên kệ và tên kho phù hợp với category_id
        return Shelf::with('warehouse') // Giả sử có mối quan hệ 'warehouse' trong model Shelf
            ->where('category_id', $categoryId)
            ->get()
            ->map(function ($shelf) {
                return $shelf->name . ' (' . $shelf->warehouse->name . ')'; // Kết hợp tên kệ và tên kho
            });
    }
}
