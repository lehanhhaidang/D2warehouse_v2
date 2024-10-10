<?php

namespace App\Repositories\Interface;

interface WarehouseRepositoryInterface
{
    public function all();
    public function find($id);
    public function create($data);
    public function update(array $data, $id);
    public function delete($id);
}
