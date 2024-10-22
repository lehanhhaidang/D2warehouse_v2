<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interface\WarehouseRepositoryInterface;
use App\Models\Material;
use App\Models\Product;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    public function all()
    {
        return Warehouse::select(
            'warehouses.id',
            'warehouses.name',
            'warehouses.location',
            'warehouses.acreage',
            'warehouses.number_of_shelves',
            'categories.name as category_name',
            'warehouses.created_at',
            'warehouses.updated_at',
        )
            ->join('categories', 'warehouses.category_id', '=', 'categories.id')
            ->get();
    }


    public function allProductWarehouses()
    {
        return Warehouse::select(
            'warehouses.id',
            'warehouses.name',
            'warehouses.location',
            'warehouses.acreage',
            'warehouses.number_of_shelves',
            'categories.name as category_name',
            'warehouses.created_at',
            'warehouses.updated_at',
        )
            ->join('categories', 'warehouses.category_id', '=', 'categories.id')
            ->where('categories.name', 'Product')
            ->get();
    }

    public function allMaterialWarehouses()
    {
        return Warehouse::select(
            'warehouses.id',
            'warehouses.name',
            'warehouses.location',
            'warehouses.acreage',
            'warehouses.number_of_shelves',
            'categories.name as category_name',
            'warehouses.created_at',
            'warehouses.updated_at',
        )
            ->join('categories', 'warehouses.category_id', '=', 'categories.id')
            ->where('categories.name', 'Material')
            ->get();
    }

    public function find($id)
    {
        return Warehouse::select(
            'warehouses.id',
            'warehouses.name',
            'warehouses.location',
            'warehouses.acreage',
            'warehouses.number_of_shelves',
            'categories.name as category_name',
            'warehouses.created_at',
            'warehouses.updated_at',
        )
            ->join('categories', 'warehouses.category_id', '=', 'categories.id')
            ->find($id);
    }



    public function create($data)
    {
        return Warehouse::create($data);
    }

    public function update(array $data, $id)
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse) {
            $warehouse->update($data);
            return $warehouse;
        }
        return null;
    }

    public function delete($id)
    {
        return Warehouse::destroy($id);
    }
}
