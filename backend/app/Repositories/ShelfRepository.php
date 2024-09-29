<?php

namespace App\Repositories;

use App\Models\Shelf;


class ShelfRepository implements ShelfRepositoryInterface
{
    public function all()
    {
        return Shelf::all();
    }

    public function find($id)
    {
        return Shelf::find($id);
    }

    public function create(array $data)
    {
        return Shelf::create($data);
    }

    public function update(array $data, $id)
    {
        return Shelf::find($id)->update($data);
    }

    public function delete($id)
    {
        return Shelf::destroy($id);
    }
}
