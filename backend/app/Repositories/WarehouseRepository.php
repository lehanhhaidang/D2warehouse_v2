<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interface\WarehouseRepositoryInterface;

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

    public function update($id, $data)
    {
        return Warehouse::find($id)->update($data);
    }

    public function delete($id)
    {
        return Warehouse::destroy($id);
    }
}
