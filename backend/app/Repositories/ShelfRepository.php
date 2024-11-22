<?php

namespace App\Repositories;

use App\Models\Shelf;
use App\Models\ShelfDetail;
use App\Repositories\Interface\ShelfRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ShelfRepository implements ShelfRepositoryInterface
{
    public function all()
    {
        return Shelf::select(
            'shelves.id',
            'shelves.name',
            'warehouses.name as warehouse_name',
            'categories.name as category_name',
            'shelves.number_of_levels',
            'shelves.storage_capacity',
            'shelves.created_at',
            'shelves.updated_at',
        )
            ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
            ->join('categories', 'shelves.category_id', '=', 'categories.id')
            ->get();
    }

    public function find($id)
    {
        return Shelf::select(
            'shelves.id',
            'shelves.name',
            'warehouses.name as warehouse_name',
            'categories.name as category_name',
            'shelves.number_of_levels',
            'shelves.storage_capacity',
            'shelves.created_at',
            'shelves.updated_at',
        )
            ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
            ->join('categories', 'shelves.category_id', '=', 'categories.id')
            ->where('shelves.id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return Shelf::create($data);
    }

    public function update(array $data, $id)
    {
        $shelf = Shelf::find($id);
        if ($shelf) {
            $shelf->update($data);
            return $shelf;
        }
        return null;
    }

    public function delete($id)
    {
        return Shelf::destroy($id);
    }

    public function filterShelves($warehouseId, $categoryId)
    {
        return Shelf::where('warehouse_id', $warehouseId)
            ->where('category_id', $categoryId)
            ->select('id', 'name') // Chỉ lấy id và name
            ->get();
    }


    public function getShelvesWithProductsByWarehouseId($id)
    {
        return DB::table('shelves')
            ->join('shelf_details', 'shelves.id', '=', 'shelf_details.shelf_id')
            ->join('products', 'products.id', '=', 'shelf_details.product_id')
            ->where('shelves.warehouse_id', $id)
            ->select(
                'shelves.*',
                'products.name as product_name',
                'shelves.name as shelf_name',
                'products.unit',
                'shelf_details.quantity'
            )
            ->get();
    }

    public function getShelvesWithMaterialsByWarehouseId($id)
    {

        return DB::table('shelves')
            ->join('shelf_details', 'shelves.id', '=', 'shelf_details.shelf_id')
            ->join('materials', 'materials.id', '=', 'shelf_details.material_id')
            ->where('shelves.warehouse_id', $id)
            ->select(
                'shelves.*',
                'materials.name as material_name',
                'shelves.name as shelf_name',
                'materials.unit',
                'shelf_details.quantity'
            )
            ->get();
    }
}
