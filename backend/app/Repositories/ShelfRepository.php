<?php

namespace App\Repositories;

use App\Models\Shelf;
use App\Repositories\Interface\ShelfRepositoryInterface;


class ShelfRepository implements ShelfRepositoryInterface
{
    public function all()
    {
        return Shelf::select(
            'shelves.id',
            'shelves.name',
            'warehouses.name as warehouse_name',
            'categories.name as category_name',
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
}
